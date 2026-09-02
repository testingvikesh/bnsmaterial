<?php

namespace App\Http\Controllers;

use App\Models\ManageSession;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $memberQuery = User::query()->whereHas('memberProfile');

        $stats = [
            'users' => User::count(),
            'members' => (clone $memberQuery)->count(),
            'sessions' => ManageSession::count(),
            'prompts' => SessionPrompt::count(),
        ];

        $recentUsers = User::query()->latest()->limit(8)->get();
        $recentSessions = ManageSession::query()->latest()->limit(8)->get();
        $recentMembers = User::query()
            ->whereHas('memberProfile')
            ->with('memberProfile')
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', compact('stats', 'recentUsers', 'recentSessions', 'recentMembers'));
    }
}
