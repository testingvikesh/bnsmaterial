<?php

namespace App\Services;

use App\Models\ManageSession;
use App\Models\MemberProfile;
use App\Models\SessionPrompt;
use App\Models\User;
use App\Support\MaterialSanskarI18n;
use App\Support\MaterialWebsiteI18n;
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

        $format = (string) ($result['format'] ?? '');
        $view = match ($format) {
            'empire' => 'admin.material.document-empire',
            'reverse' => 'admin.material.document-reverse',
            'tagline' => 'admin.material.document-tagline',
            'website' => 'admin.material.document-website',
            'sanskar' => 'admin.material.document-sanskar',
            default => 'admin.material.document',
        };

        $languages = $result['languages'] ?? [];
        $biz = (string) ($facts['business_name'] ?? '');
        if ($format === 'sanskar' && is_array($result['sanskar'] ?? null) && $result['sanskar'] !== []) {
            $plan = $result['sanskar'];
            $languages = MaterialSanskarI18n::packs((string) ($plan['business_name'] ?? $biz), $plan);
        } elseif ($format === 'website' && is_array($result['website'] ?? null) && $result['website'] !== []) {
            $plan = $result['website'];
            $languages = MaterialWebsiteI18n::packs((string) ($plan['business_name'] ?? $biz), $plan);
        }

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
            'languages' => $languages,
            'source' => $result['source'] ?? 'local',
            'reverse' => $result['reverse'] ?? [],
            'tagline' => $result['tagline'] ?? [],
            'website' => $result['website'] ?? [],
            'sanskar' => $result['sanskar'] ?? [],
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
            'services' => (string) ($profile?->main_services ?: '—'),
            'business_type' => (string) ($profile?->business_type ?: '—'),
            'city' => (string) ($profile?->city ?: $profile?->business_location ?: '—'),
            'website' => (string) ($profile?->website_url ?: '—'),
            'instagram' => (string) ($profile?->instagram ?: '—'),
            'facebook' => (string) ($profile?->facebook ?: '—'),
            'linkedin' => (string) ($profile?->linkedin ?: '—'),
            'youtube' => (string) ($profile?->youtube ?: '—'),
            'google_business' => (string) ($profile?->google_business ?: '—'),
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
