<?php

namespace App\Support;

use App\Models\ManageSession;
use App\Models\SessionPrompt;

class MaterialWorkspace
{
    public static function defaultSessionId(?int $requested = null): ?int
    {
        $requested = (int) ($requested ?? 0);
        $ids = ManageSession::query()->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->values();

        if ($requested > 0 && $ids->contains($requested)) {
            return $requested;
        }

        $withActivePrompt = (int) ManageSession::query()
            ->whereHas('prompts', fn ($query) => $query->where('is_active', true))
            ->orderBy('id')
            ->value('id');

        if ($withActivePrompt > 0) {
            return $withActivePrompt;
        }

        $first = $ids->first();

        return $first ? (int) $first : null;
    }

    public static function promptFor(?ManageSession $session): ?SessionPrompt
    {
        if (! $session) {
            return null;
        }

        return SessionPrompt::query()
            ->where('manage_session_id', $session->id)
            ->orderByDesc('is_active')
            ->orderBy('id')
            ->first();
    }
}
