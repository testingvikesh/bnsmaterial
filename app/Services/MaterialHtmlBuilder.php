<?php

namespace App\Services;

use App\Models\ManageSession;
use App\Models\MemberProfile;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\Support\Carbon;

class MaterialHtmlBuilder
{
    /**
     * @param  array<string, mixed>|null  $result
     */
    public function build(
        ManageSession $session,
        SessionPrompt $prompt,
        User $user,
        ?array $result = null,
        ?Carbon $generatedAt = null
    ): string {
        $user->loadMissing('memberProfile');
        $profile = $user->memberProfile;
        $generatedAt ??= now();
        $result ??= [];

        $facts = $this->facts($user, $profile, $session);
        $snapshot = is_array($result['snapshot'] ?? null) ? $result['snapshot'] : null;
        if (is_array($snapshot) && filled($snapshot['sessionDetails'] ?? null)) {
            $facts['session_details'] = (string) $snapshot['sessionDetails'];
        }
        $filledPrompt = is_array($snapshot)
            ? $prompt->filled($snapshot)
            : $this->fill((string) $prompt->body, $facts);

        $view = match ((string) ($result['format'] ?? '')) {
            'empire' => 'admin.material.document-empire',
            'reverse' => 'admin.material.document-reverse',
            'tagline' => 'admin.material.document-tagline',
            default => 'admin.material.document',
        };

        return view($view, [
            'session' => $session,
            'prompt' => $prompt,
            'facts' => $facts,
            'filledPrompt' => $filledPrompt,
            'generatedAt' => $generatedAt,
            'ideas' => $result['ideas'] ?? [],
            'visions' => $result['visions'] ?? [],
            'journey' => $result['journey'] ?? [],
            'timeline' => $result['timeline'] ?? [],
            'final' => $result['final'] ?? [],
            'languages' => $result['languages'] ?? [],
            'source' => $result['source'] ?? 'local',
            'reverse' => $result['reverse'] ?? [],
            'tagline' => $result['tagline'] ?? [],
        ])->render();
    }

    /**
     * @return array<string, string>
     */
    public function facts(User $user, ?MemberProfile $profile, ManageSession $session): array
    {
        $phone = trim((string) $user->phone);
        if ($phone === '') {
            $phone = trim((string) ($profile?->whatsapp ?? ''));
        }

        return [
            'member_name' => (string) $user->name,
            'phone' => $phone !== '' ? $phone : '—',
            'member_id' => (string) ($profile?->member_id ?: '—'),
            'business_name' => (string) ($profile?->business_name ?: '—'),
            'category' => (string) ($profile?->business_category ?: '—'),
            'location' => (string) ($profile?->business_location ?: '—'),
            'address' => (string) ($profile?->business_address ?: '—'),
            'intro' => (string) ($profile?->business_description ?: '—'),
            'products' => (string) ($profile?->main_products_services ?: '—'),
            'session_name' => (string) $session->name,
            'session_details' => (string) ($session->details ?: '—'),
        ];
    }

    /**
     * @param  array<string, string>  $facts
     */
    public function fill(string $body, array $facts): string
    {
        $map = [
            '{{memberName}}' => $facts['member_name'],
            '{{businessName}}' => $facts['business_name'],
            '{{businessCategory}}' => $facts['category'],
            '{{businessIntroduction}}' => $facts['intro'],
            '{{mainProduct}}' => $facts['products'],
            '{{businessLocation}}' => $facts['location'],
            '{{businessAddress}}' => $facts['address'],
            '{{whatsapp}}' => $facts['phone'],
            '{{sessionName}}' => $facts['session_name'],
            '{{sessionDetails}}' => $facts['session_details'],
        ];

        return str_replace(array_keys($map), array_values($map), $body);
    }
}
