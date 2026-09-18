<?php

namespace App\Support;

use App\Models\ManageSession;
use App\Models\SessionPrompt;

class MaterialSessionFormat
{
    public const ONE_TO_25 = 'one_to_25';

    public const EMPIRE = 'empire';

    public const REVERSE = 'reverse';

    public const TAGLINE = 'tagline';

    public const WEBSITE = 'website';

    public static function resolve(ManageSession $session, ?SessionPrompt $prompt = null): string
    {
        $nameHay = strtolower(trim(
            $session->name.' '.
            ($session->details ?? '').' '.
            ($prompt?->title ?? '')
        ));
        $hay = trim($nameHay.' '.strtolower((string) ($prompt?->body ?? '')));

        if (self::contains($hay, [
            '32 gun',
            '32-gun',
            '32gun',
            '32 section',
            '32-section',
            '32 website',
            'universal 32',
            'complete business website',
            'website draft',
            'website master prompt',
            'zero-question ai business website',
            'business website master',
        ])) {
            return self::WEBSITE;
        }

        if (self::contains($nameHay, [
            'tagline',
            'tag line',
            'tagline masterclass',
            'bns tagline',
            'business tagline',
        ])) {
            return self::TAGLINE;
        }

        if (self::contains($hay, [
            'reverse management',
            'reverse-management',
            '7 market',
            '7 markets',
            'desired annual turnover',
            'desired turnover',
        ])) {
            return self::REVERSE;
        }

        if (self::contains($hay, [
            '30-point',
            '30 point',
            '30 vision',
            'vision-001',
            'business empire',
            'business house to business empire',
            'business vision',
        ])) {
            return self::EMPIRE;
        }

        if (self::contains($hay, [
            'one to 25',
            '1 to 25',
            '1-to-25',
            'one-to-25',
            '25 business',
        ])) {
            return self::ONE_TO_25;
        }

        return self::ONE_TO_25;
    }

    /**
     * @param  list<string>  $needles
     */
    private static function contains(string $hay, array $needles): bool
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && str_contains($hay, $needle)) {
                return true;
            }
        }

        return false;
    }
}
