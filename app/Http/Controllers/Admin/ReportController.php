<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageSession;
use App\Models\MaterialEvent;
use App\Models\MaterialFile;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));
        $sessionId = $request->integer('session_id');
        $group = strtolower(trim((string) $request->get('group')));
        if (! in_array($group, ['member', 'session'], true)) {
            $group = 'member';
        }

        $status = strtolower(trim((string) $request->get('status')));
        if (! in_array($status, ['pending', 'complete'], true)) {
            $status = 'pending';
        }

        $sessions = ManageSession::query()->orderBy('id')->get(['id', 'name']);
        $filtered = $q !== '' || $sessionId > 0;

        $overviewFiles = MaterialFile::query()
            ->withCount([
                'events as views_count' => fn ($query) => $query->where('type', MaterialEvent::VIEW),
                'events as reads_count' => fn ($query) => $query->where('type', MaterialEvent::READ),
            ])
            ->get(['id', 'user_id', 'manage_session_id']);

        $stats = [
            'members' => $overviewFiles->pluck('user_id')->unique()->count(),
            'sessions' => $overviewFiles->pluck('manage_session_id')->unique()->count(),
            'views' => (int) $overviewFiles->sum('views_count'),
            'reads' => (int) $overviewFiles->sum('reads_count'),
        ];

        $sessionNames = $sessions->pluck('name', 'id');
        $chart = $this->chartFromFiles($overviewFiles, $sessionNames);
        [$pendingCount, $completeCount] = $this->memberStatusCounts($overviewFiles);

        $memberGroups = collect();
        $sessionGroups = collect();

        if ($filtered) {
            $files = MaterialFile::query()
                ->with(['user.memberProfile', 'session'])
                ->withCount([
                    'events as views_count' => fn ($query) => $query->where('type', MaterialEvent::VIEW),
                    'events as reads_count' => fn ($query) => $query->where('type', MaterialEvent::READ),
                ])
                ->withMax('events', 'created_at')
                ->when($sessionId > 0, fn ($query) => $query->where('manage_session_id', $sessionId))
                ->when($q !== '', function ($query) use ($q) {
                    $query->whereHas('user', function ($user) use ($q) {
                        $user->where('name', 'like', "%{$q}%")
                            ->orWhere('phone', 'like', "%{$q}%")
                            ->orWhereHas('memberProfile', function ($profile) use ($q) {
                                $profile->where('business_name', 'like', "%{$q}%")
                                    ->orWhere('whatsapp', 'like', "%{$q}%");
                            });
                    });
                })
                ->get();

            $rows = $files->map(function (MaterialFile $file) {
                $member = $file->user;
                $profile = $member?->memberProfile;
                $views = (int) ($file->views_count ?? 0);
                $reads = (int) ($file->reads_count ?? 0);

                return [
                    'file_id' => $file->id,
                    'member_id' => (int) $file->user_id,
                    'member' => (string) ($member?->name ?: 'Member'),
                    'business' => (string) ($profile?->business_name ?: '—'),
                    'session_id' => (int) $file->manage_session_id,
                    'session' => (string) ($file->session?->name ?: 'Session'),
                    'views' => $views,
                    'reads' => $reads,
                    'complete' => $views > 0,
                    'last_viewed' => $file->events_max_created_at,
                    'show_url' => route('admin.material.show', $file),
                ];
            });

            $allMemberGroups = $rows
                ->groupBy('member_id')
                ->map(function ($items) {
                    $first = $items->first();
                    $views = $items->sum('views');

                    return [
                        'member' => $first['member'],
                        'business' => $first['business'],
                        'views' => $views,
                        'reads' => $items->sum('reads'),
                        'complete' => $views > 0,
                        'sessions' => $items->sortBy('session')->values(),
                    ];
                })
                ->sortBy('member')
                ->values();

            $allSessionGroups = $rows
                ->groupBy('session_id')
                ->map(function ($items) {
                    $first = $items->first();

                    return [
                        'session' => $first['session'],
                        'views' => $items->sum('views'),
                        'reads' => $items->sum('reads'),
                        'pending' => $items->where('complete', false)->count(),
                        'complete' => $items->where('complete', true)->count(),
                        'members' => $items->sortBy('member')->values(),
                    ];
                })
                ->sortBy('session')
                ->values();

            $pendingCount = $allMemberGroups->where('complete', false)->count();
            $completeCount = $allMemberGroups->where('complete', true)->count();

            $chart = [
                'title' => 'Session wise pending and complete',
                'labels' => $allSessionGroups->pluck('session')->values()->all(),
                'pending' => $allSessionGroups->pluck('pending')->values()->all(),
                'complete' => $allSessionGroups->pluck('complete')->values()->all(),
            ];

            $wantComplete = $status === 'complete';
            $memberGroups = $allMemberGroups
                ->where('complete', $wantComplete)
                ->values();
            $sessionGroups = $allSessionGroups
                ->map(function ($block) use ($wantComplete) {
                    $members = $block['members']->where('complete', $wantComplete)->values();

                    return [
                        'session' => $block['session'],
                        'views' => $members->sum('views'),
                        'reads' => $members->sum('reads'),
                        'pending' => $block['pending'],
                        'complete' => $block['complete'],
                        'members' => $members,
                    ];
                })
                ->filter(fn ($block) => $block['members']->isNotEmpty())
                ->values();
        }

        return view('admin.reports.index', compact(
            'q',
            'sessionId',
            'group',
            'status',
            'filtered',
            'sessions',
            'stats',
            'pendingCount',
            'completeCount',
            'memberGroups',
            'sessionGroups',
            'chart'
        ));
    }

    /**
     * @param  Collection<int, MaterialFile>  $files
     * @param  Collection<int, string>  $sessionNames
     * @return array{title: string, labels: list<string>, pending: list<int>, complete: list<int>}
     */
    private function chartFromFiles(Collection $files, Collection $sessionNames): array
    {
        $chart = [
            'title' => 'Session wise pending and complete',
            'labels' => [],
            'pending' => [],
            'complete' => [],
        ];

        foreach ($files->groupBy('manage_session_id')->sortKeys() as $id => $items) {
            $chart['labels'][] = (string) ($sessionNames[$id] ?? 'Session');
            $chart['pending'][] = $items->filter(fn (MaterialFile $file) => (int) ($file->views_count ?? 0) === 0)->count();
            $chart['complete'][] = $items->filter(fn (MaterialFile $file) => (int) ($file->views_count ?? 0) > 0)->count();
        }

        return $chart;
    }

    /**
     * @param  Collection<int, MaterialFile>  $files
     * @return array{0: int, 1: int}
     */
    private function memberStatusCounts(Collection $files): array
    {
        $pending = 0;
        $complete = 0;

        foreach ($files->groupBy('user_id') as $items) {
            if ((int) $items->sum('views_count') > 0) {
                $complete++;
            } else {
                $pending++;
            }
        }

        return [$pending, $complete];
    }
}
