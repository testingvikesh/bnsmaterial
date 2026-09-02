<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageSession;
use App\Models\SessionPrompt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PromptController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));
        $sessionId = $request->integer('session_id') ?: null;

        $prompts = SessionPrompt::query()
            ->with('session')
            ->when($sessionId, fn ($query) => $query->where('manage_session_id', $sessionId))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('title', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%")
                        ->orWhereHas('session', function ($sessionQuery) use ($q) {
                            $sessionQuery->where('name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $sessions = ManageSession::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.prompts.index', compact('prompts', 'sessions', 'sessionId'));
    }

    public function create(Request $request): View
    {
        $sessions = ManageSession::query()->orderBy('name')->get(['id', 'name']);
        $selectedSessionId = $request->integer('session_id') ?: null;

        return view('admin.prompts.form', [
            'promptItem' => null,
            'sessions' => $sessions,
            'selectedSessionId' => $selectedSessionId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $prompt = SessionPrompt::create($data);

        if ($prompt->is_active) {
            $prompt->makeSoleActive();
        }

        return redirect()
            ->route('admin.prompts.index', ['session_id' => $prompt->manage_session_id])
            ->with('success', 'Prompt saved for this session.');
    }

    public function show(SessionPrompt $prompt): View
    {
        $prompt->load('session');

        return view('admin.prompts.show', ['promptItem' => $prompt]);
    }

    public function edit(SessionPrompt $prompt): View
    {
        $sessions = ManageSession::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.prompts.form', [
            'promptItem' => $prompt,
            'sessions' => $sessions,
            'selectedSessionId' => $prompt->manage_session_id,
        ]);
    }

    public function update(Request $request, SessionPrompt $prompt): RedirectResponse
    {
        $data = $this->validated($request);
        $prompt->update($data);

        if ($prompt->is_active) {
            $prompt->makeSoleActive();
        }

        return redirect()
            ->route('admin.prompts.index', ['session_id' => $prompt->manage_session_id])
            ->with('success', 'Prompt updated successfully.');
    }

    public function destroy(SessionPrompt $prompt): RedirectResponse
    {
        $sessionId = $prompt->manage_session_id;
        $prompt->delete();

        return redirect()
            ->route('admin.prompts.index', ['session_id' => $sessionId])
            ->with('success', 'Prompt deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'manage_session_id' => ['required', 'integer', Rule::exists('manage_sessions', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
