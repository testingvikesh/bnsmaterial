<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q'));

        $sessions = ManageSession::query()
            ->withCount('prompts')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('details', 'like', "%{$q}%");
                });
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.sessions.index', compact('sessions'));
    }

    public function create(): View
    {
        return view('admin.sessions.form', ['sessionItem' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        ManageSession::create($this->validated($request));

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session created successfully.');
    }

    public function show(ManageSession $session): View
    {
        $session->load(['prompts' => fn ($query) => $query->orderBy('id')]);

        return view('admin.sessions.show', ['sessionItem' => $session]);
    }

    public function edit(ManageSession $session): View
    {
        return view('admin.sessions.form', ['sessionItem' => $session]);
    }

    public function update(Request $request, ManageSession $session): RedirectResponse
    {
        $session->update($this->validated($request));

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session updated successfully.');
    }

    public function destroy(ManageSession $session): RedirectResponse
    {
        $session->delete();

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Session deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
        ]);
    }
}
