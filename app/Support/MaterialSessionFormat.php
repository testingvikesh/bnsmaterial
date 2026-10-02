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

    public const SANSKAR = 'sanskar';

    public const COMPANY = 'company';

    public const COMPANY_PART2 = 'company_part2';

    public static function resolve(ManageSession $session, ?SessionPrompt $prompt = null): string
    {
        $nameHay = strtolower(trim(
            $session->name.' '.
            ($session->details ?? '').' '.
            ($prompt?->title ?? '')
        ));
        $hay = trim($nameHay.' '.strtolower((string) ($prompt?->body ?? '')));

        $isCompany = self::contains($nameHay, [
            'pvt ltd',
            'pvt. ltd',
            'pvt.ltd',
            'pvt-ltd',
            'pvtltd',
            'private limited',
            'public limited',
            'proprietorship vs',
            'company structure',
            'business structure',
            'company type',
        ]) || self::contains($hay, [
            'proprietorship vs partnership vs llp',
            'pvt ltd session',
            'private limited company session',
            'complete comparison for students',
            'business structure master comparison',
        ]);

        if ($isCompany && self::isCompanyPartTwo($nameHay.' '.$hay)) {
            return self::COMPANY_PART2;
        }

        if ($isCompany) {
            return self::COMPANY;
        }

        if (self::contains($nameHay, [
            '16 sanskar',
            '16 saskar',
            '16 sanskaar',
            'sixteen sanskar',
            'sixteen saskar',
            '16saskar',
            '16sanskar',
        ]) || self::contains($hay, [
            '16 sanskar',
            '16 saskar',
            'customer 16 sanskar',
            'customer relationship calendar',
            'sanskar relationship',
            'saskar relationship',
        ])) {
            return self::SANSKAR;
        }

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

    private static function isCompanyPartTwo(string $hay): bool
    {
        return self::contains($hay, [
            'part 2',
            'part2',
            'part-2',
            '(part2)',
            '(part 2)',
            'session 2',
            'part two',
        ]);
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
