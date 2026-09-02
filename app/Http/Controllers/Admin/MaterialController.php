<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageSession;
use App\Models\MaterialFile;
use App\Models\SessionPrompt;
use App\Models\User;
use App\Services\MaterialHtmlBuilder;
use App\Services\MaterialIdeaGenerator;
use App\Support\MaterialSessionFormat;
use App\Support\MaterialWorkspace;
use App\Support\MaterialReverseDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));
        $status = strtolower(trim((string) $request->get('status')));
        if (! in_array($status, ['generated', 'pending'], true)) {
            $status = '';
        }

        $sessions = ManageSession::query()->orderBy('id')->get(['id', 'name']);
        $requestedId = $request->integer('session_id');
        $session = $requestedId > 0
            ? ManageSession::query()->find($requestedId)
            : null;
        $sessionId = $session?->id;
        $prompt = MaterialWorkspace::promptFor($session);

        $members = new LengthAwarePaginator([], 0, 25, 1, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
        $files = collect();
        $generatedCount = 0;
        $generatedUserIds = collect();

        $memberTotal = User::query()
            ->join('member_profiles', 'member_profiles.user_id', '=', 'users.id')
            ->count();

        if ($session) {
            if ($prompt) {
                $files = MaterialFile::query()
                    ->where('manage_session_id', $session->id)
                    ->where('session_prompt_id', $prompt->id)
                    ->get()
                    ->keyBy('user_id');
                $generatedUserIds = $files->keys();
                $generatedCount = $files->count();
            }

            $members = User::query()
                ->join('member_profiles', 'member_profiles.user_id', '=', 'users.id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.phone',
                    'users.is_guest',
                    'member_profiles.whatsapp',
                    'member_profiles.business_name',
                    'member_profiles.business_category',
                    'member_profiles.main_products_services',
                ])
                ->when($q !== '', function ($query) use ($q) {
                    $query->where(function ($inner) use ($q) {
                        $inner->where('users.name', 'like', "%{$q}%")
                            ->orWhere('users.phone', 'like', "%{$q}%")
                            ->orWhere('member_profiles.whatsapp', 'like', "%{$q}%")
                            ->orWhere('member_profiles.business_name', 'like', "%{$q}%");
                    });
                })
                ->when($status === 'generated', function ($query) use ($generatedUserIds) {
                    $query->whereIn('users.id', $generatedUserIds->all() ?: [0]);
                })
                ->when($status === 'pending', function ($query) use ($generatedUserIds) {
                    if ($generatedUserIds->isNotEmpty()) {
                        $query->whereNotIn('users.id', $generatedUserIds->all());
                    }
                })
                ->orderBy('users.name')
                ->paginate(25)
                ->withQueryString();
        }

        $pendingCount = max(0, $memberTotal - $generatedCount);

        $openaiReady = app(MaterialIdeaGenerator::class)->apiEnabled();
        $openaiModel = (string) config('services.openai.model', 'gpt-4o-mini');
        $isReverse = $session
            ? MaterialSessionFormat::resolve($session, $prompt) === MaterialSessionFormat::REVERSE
            : false;

        return view('admin.material.index', compact(
            'sessions',
            'sessionId',
            'session',
            'prompt',
            'members',
            'files',
            'memberTotal',
            'generatedCount',
            'pendingCount',
            'status',
            'openaiReady',
            'openaiModel',
            'isReverse'
        ));
    }

    public function generate(Request $request, MaterialIdeaGenerator $generator, MaterialHtmlBuilder $builder): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'manage_session_id' => ['required', 'exists:manage_sessions,id'],
            'user_id' => ['required_without:user_ids', 'nullable', 'exists:users,id'],
            'user_ids' => ['required_without:user_id', 'nullable', 'array', 'min:1', 'max:25'],
            'user_ids.*' => ['integer', 'distinct', 'exists:users,id'],
            'force' => ['sometimes', 'boolean'],
        ]);

        $json = $request->expectsJson();
        if ($json) {
            $request->session()->save();
            session_write_close();
        }

        $session = ManageSession::query()->with('activePrompt')->findOrFail($data['manage_session_id']);
        $prompt = $session->activePrompt;
        if (! $prompt) {
            $message = 'This session has no active prompt. Activate a prompt first.';

            return $json
                ? response()->json(['ok' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        $userIds = collect($data['user_ids'] ?? [])
            ->when(isset($data['user_id']), fn ($ids) => $ids->push($data['user_id']))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $users = User::query()
            ->with('memberProfile')
            ->whereIn('id', $userIds)
            ->get()
            ->filter(fn (User $user) => $user->memberProfile !== null)
            ->sortBy(fn (User $user) => array_search($user->id, $userIds->all(), true));

        if ($users->isEmpty()) {
            $message = 'Select at least one member to generate material.';

            return $json
                ? response()->json(['ok' => false, 'message' => $message], 422)
                : back()->with('error', $message);
        }

        ignore_user_abort(true);
        @set_time_limit(min(360 * max(1, $users->count()), 7200));

        $force = $request->boolean('force');
        $generated = [];
        $failed = [];
        $source = 'local';
        $lastFile = null;
        $lastDuration = 0;
        $lastCached = false;

        foreach ($users as $user) {
            try {
                $saved = $this->storeMaterial($generator, $builder, $session, $prompt, $user, $force);
                $generated[] = $user->name;
                $source = (string) ($saved['result']['source'] ?? $source);
                $lastFile = $saved['file'];
                $lastDuration = $saved['duration_ms'];
                $lastCached = (bool) ($saved['cached'] ?? false);
            } catch (\Throwable $e) {
                Log::warning('Bulk material generate failed.', [
                    'user_id' => $user->id,
                    'session_id' => $session->id,
                    'error' => $e->getMessage(),
                ]);
                $failed[] = $user->name;
                if ($json && $users->count() === 1) {
                    return response()->json([
                        'ok' => false,
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'message' => 'Material could not be generated for '.$user->name.'.',
                    ], 500);
                }
            }
        }

        $index = array_filter([
            'session_id' => $session->id,
            'q' => $request->get('q'),
            'status' => $request->get('status'),
        ], fn ($value) => $value !== null && $value !== '');

        if ($json) {
            if ($generated === [] || ! $lastFile instanceof MaterialFile) {
                return response()->json(['ok' => false, 'message' => 'Material could not be generated for the selected members.'], 500);
            }

            $lastFile->loadMissing(['user', 'session']);

            return response()->json([
                'ok' => true,
                'user_id' => $lastFile->user_id,
                'name' => $lastFile->user?->name,
                'cached' => $lastCached,
                'duration_ms' => $lastDuration,
                'duration_label' => $lastFile->fresh()?->durationLabel() ?: $lastFile->durationLabel(),
                'source' => $source,
                'file_id' => $lastFile->id,
                'show_url' => route('admin.material.show', $lastFile),
                'download_url' => route('admin.material.download', $lastFile),
            ]);
        }

        if ($generated === []) {
            return redirect()
                ->route('admin.material.index', $index)
                ->with('error', 'Material could not be generated for the selected members.');
        }

        if (count($generated) === 1 && $failed === []) {
            $verb = $lastCached ? 'Reused cached HTML material for ' : 'HTML material generated for ';

            return redirect()
                ->route('admin.material.index', $index)
                ->with('success', $verb.$generated[0].' in '.$lastFile->durationLabel().' ('.$source.').');
        }

        $message = 'HTML material generated for '.count($generated).' member'.(count($generated) === 1 ? '' : 's').'.';
        if ($failed !== []) {
            $message .= ' Skipped: '.implode(', ', $failed).'.';
        }

        return redirect()
            ->route('admin.material.index', $index)
            ->with('success', $message);
    }

    public function dashboard(Request $request): View|RedirectResponse|StreamedResponse
    {
        $session = ManageSession::query()->find($request->integer('session_id'));
        if (! $session) {
            return redirect()
                ->route('admin.material.index')
                ->with('error', 'Select a Reverse Management session first.');
        }

        $prompt = MaterialWorkspace::promptFor($session);
        if (MaterialSessionFormat::resolve($session, $prompt) !== MaterialSessionFormat::REVERSE) {
            return redirect()
                ->route('admin.material.index', ['session_id' => $session->id])
                ->with('error', 'Dashboard is available for Reverse Management Session.');
        }

        $report = MaterialReverseDashboard::for($session, $prompt, $request);

        if ($request->get('export') === 'csv') {
            return $this->exportMembersCsv($session, $report['members'] ?? []);
        }

        return view('admin.material.reverse-dashboard', compact('session', 'prompt', 'report'));
    }

    /**
     * @param  list<array<string, mixed>>  $members
     */
    private function exportMembersCsv(ManageSession $session, array $members): StreamedResponse
    {
        $filename = str($session->name)->slug('_').'_reverse_dashboard.csv';

        return response()->streamDownload(function () use ($members) {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['Member', 'Business', 'Category', 'Session', 'Desired Turnover', '7 Offers', 'Customer Target', 'Planned Revenue', 'Gap', 'Achievement', 'Status']);
            foreach ($members as $row) {
                fputcsv($out, [
                    $row['member'] ?? '',
                    $row['business'] ?? '',
                    $row['category'] ?? '',
                    $row['session'] ?? '',
                    $row['desired_label'] ?? '',
                    $row['offers'] ?? '',
                    $row['customers_label'] ?? '',
                    $row['planned_label'] ?? '',
                    $row['gap_label'] ?? '',
                    $row['achievement_label'] ?? '',
                    $row['status'] ?? '',
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function show(MaterialFile $materialFile): Response
    {
        return response($this->htmlFor($materialFile), 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    public function download(MaterialFile $materialFile): StreamedResponse
    {
        $html = $this->htmlFor($materialFile);
        $materialFile->loadMissing(['user', 'session']);

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $materialFile->downloadName(), [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    private function htmlFor(MaterialFile $materialFile): string
    {
        $materialFile->loadMissing(['user.memberProfile', 'session', 'prompt']);
        $jsonPath = preg_replace('/\.html$/', '.json', (string) $materialFile->file_path);
        $session = $materialFile->session;
        $prompt = $materialFile->prompt;
        $user = $materialFile->user;

        if ($session && $prompt && $user && is_string($jsonPath) && Storage::disk('local')->exists($jsonPath)) {
            $result = json_decode((string) Storage::disk('local')->get($jsonPath), true);
            if (is_array($result)) {
                return app(MaterialHtmlBuilder::class)->build(
                    $session,
                    $prompt,
                    $user,
                    $result,
                    $materialFile->generated_at
                );
            }
        }

        abort_unless(Storage::disk('local')->exists($materialFile->file_path), 404);

        return (string) Storage::disk('local')->get($materialFile->file_path);
    }

    /**
     * @return array{result: array<string, mixed>, file: MaterialFile, duration_ms: int, cached: bool}
     */
    private function storeMaterial(
        MaterialIdeaGenerator $generator,
        MaterialHtmlBuilder $builder,
        ManageSession $session,
        SessionPrompt $prompt,
        User $user,
        bool $force = false
    ): array {
        $started = microtime(true);

        $existing = MaterialFile::query()
            ->where('user_id', $user->id)
            ->where('manage_session_id', $session->id)
            ->where('session_prompt_id', $prompt->id)
            ->first();

        if (! $force && $existing instanceof MaterialFile && $existing->hasUsableCache()) {
            $result = $existing->cachedPayload() ?? [];
            $result['source'] = 'cache';

            return [
                'result' => $result,
                'file' => $existing,
                'duration_ms' => (int) max(1, round((microtime(true) - $started) * 1000)),
                'cached' => true,
            ];
        }

        $result = $generator->generate($session, $prompt, $user);
        $html = $builder->build($session, $prompt, $user, $result);
        $path = sprintf(
            'materials/session_%d/prompt_%d/user_%d.html',
            $session->id,
            $prompt->id,
            $user->id
        );

        Storage::disk('local')->put($path, $html);
        Storage::disk('local')->put(
            preg_replace('/\.html$/', '.json', $path),
            json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $durationMs = (int) max(1, round((microtime(true) - $started) * 1000));

        $file = MaterialFile::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'manage_session_id' => $session->id,
                'session_prompt_id' => $prompt->id,
            ],
            [
                'file_path' => $path,
                'generated_at' => now(),
                'duration_ms' => $durationMs,
            ]
        );

        return [
            'result' => $result,
            'file' => $file,
            'duration_ms' => $durationMs,
            'cached' => false,
        ];
    }
}
