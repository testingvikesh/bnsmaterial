<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));

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
                'member_profiles.business_location',
                'member_profiles.business_address',
                'member_profiles.business_description',
                'member_profiles.main_products_services',
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.phone', 'like', "%{$q}%")
                        ->orWhere('member_profiles.whatsapp', 'like', "%{$q}%");
                });
            })
            ->orderBy('users.name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    public function show(User $user): View
    {
        abort_unless($user->memberProfile()->exists(), 404);

        $user->load('memberProfile');

        $sessions = ManageSession::query()
            ->with('activePrompt')
            ->withCount('prompts')
            ->orderBy('id')
            ->get();

        return view('admin.members.show', [
            'member' => $user,
            'sessions' => $sessions,
        ]);
    }
}
