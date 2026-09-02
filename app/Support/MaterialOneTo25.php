<?php

namespace App\Support;

class MaterialOneTo25
{
    /**
     * @param  array<string, string>  $ctx
     * @return array<int, array{name: string, concept: string, product_ideas: list<string>}>
     */
    public static function for(array $ctx, string $memberName = ''): array
    {
        $biz = trim((string) ($ctx['biz'] ?? 'Business')) ?: 'Business';
        $cat = trim((string) ($ctx['cat'] ?? 'Business')) ?: 'Business';
        $core = trim((string) ($ctx['products'] ?? $ctx['desc'] ?? $cat)) ?: $cat;
        $coreShort = mb_strtolower(strtok($core, "/,.;") ?: $cat);
        $noun = self::title(explode(' ', $coreShort)[0] ?? $cat);
        $first = self::firstName($memberName !== '' ? $memberName : $biz);

        $rows = [
            1 => [$noun.' Academy', 'training and skill-building around '.$coreShort],
            2 => [$noun.' Studio', 'premium custom work around '.$coreShort],
            3 => [$noun.' Desk', 'a dedicated service desk for '.$coreShort],
            4 => [$noun.' Hub', 'a customer hub built from '.$coreShort],
            5 => [$noun.' B2B Solutions', 'B2B solutions using '.$coreShort],
            6 => [$noun.' Restart Line', 'a restart / refresh range of '.$coreShort],
            7 => [$noun.' Connect', 'community and referral work around '.$coreShort],
            8 => [$noun.' Mentorship Club', 'monthly mentorship around '.$coreShort],
            9 => [$noun.' Membership Club', 'membership built from '.$coreShort],
            10 => [$noun.' Communication Academy', 'how to present and sell '.$coreShort],
            11 => [$noun.' Leadership Studio', 'owner and team leadership for the '.$coreShort.' business'],
            12 => [$noun.' Skills Academy', 'practical skills grown from '.$coreShort],
            13 => [$noun.' Care Desk', 'after-care and add-on services around '.$coreShort],
            14 => [$noun.' Signature Line', 'a signature range of '.$coreShort],
            15 => [$noun.' Presence Studio', 'brand presence and experience for '.$coreShort],
            16 => [$cat.' Skills Academy', 'practical '.$cat.' skills from the present business'],
            17 => ['Young '.$noun.' Academy', 'next-generation '.$coreShort.' training'],
            18 => [$noun.' Goal Lab', 'goal and action-plan work for '.$coreShort.' customers'],
            19 => [$noun.' Growth Coaching', 'growth coaching using '.$coreShort],
            20 => [$noun.' Digital Studio', 'digital catalogue and online selling of '.$coreShort],
            21 => [$noun.' Balance Desk', 'operations support for '.$coreShort.' owners'],
            22 => [$noun.' Future Line', 'a future-ready range from '.$coreShort],
            23 => [$noun.' Transformation Studio', 'structured transformation programs around '.$coreShort],
            24 => ['Family '.$noun.' Centre', 'family and community '.$coreShort.' centre'],
            25 => [$first.' '.$noun.' Hub', 'a complete '.$coreShort.' ecosystem under the '.$first.' brand'],
        ];

        $out = [];
        foreach ($rows as $n => [$name, $angle]) {
            $out[$n] = [
                'name' => $name,
                'concept' => self::conceptFor($name, $biz, $cat, $core, $angle),
                'product_ideas' => self::offers($name, $coreShort),
            ];
        }

        return $out;
    }

    public static function conceptFor(string $name, string $biz, string $cat, string $core, string $angle = ''): string
    {
        $biz = trim($biz) !== '' ? $biz : 'the present business';
        $cat = trim($cat) !== '' ? $cat : 'this category';
        $core = trim($core) !== '' ? $core : $cat;
        $angle = trim($angle);

        $first = $name.' is a new English-market business grown from '.$biz.' in the '.$cat.' category.';
        $second = $angle !== ''
            ? 'It stays in the same industry and uses existing customers, skills and assets for '.$angle.'.'
            : 'It stays in the same industry and uses existing customers, products, skills and assets around '.$core.'.';
        $third = 'Offers are specific, sellable items for the Indian market, not a different industry.';
        $fourth = 'This is an opportunity assessment from the present business, not a revenue guarantee.';

        return $first.' '.$second.' '.$third.' '.$fourth;
    }

    /**
     * @return list<string>
     */
    public static function offers(string $name, string $core = ''): array
    {
        $core = trim($core) !== '' ? $core : 'the present offering';

        return [
            $name.' flagship '.$core.' package',
            $name.' starter '.$core.' kit',
            $name.' premium '.$core.' package',
            $name.' online '.$core.' programme',
            $name.' weekend workshop',
            $name.' 1:1 consulting session',
            $name.' monthly membership',
            $name.' family add-on pack',
            $name.' B2B bulk package',
            $name.' community / alumni offer',
        ];
    }

    public static function offerLine(string $name, string $core, int $n): string
    {
        $offers = self::offers($name, mb_strtolower(strtok($core, "/,.;") ?: $core));

        return $offers[($n - 1) % 10] ?? ($name.' offer '.$n);
    }

    private static function firstName(string $name): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name) ?? '');
        if ($name === '') {
            return 'Member';
        }
        $skip = ['mr', 'mrs', 'ms', 'miss', 'dr', 'prof', 'smt', 'shri', 'ku'];
        foreach (explode(' ', $name) as $part) {
            $clean = rtrim($part, '.');
            if ($clean !== '' && ! in_array(mb_strtolower($clean), $skip, true)) {
                return $clean;
            }
        }

        return 'Member';
    }

    private static function title(string $word): string
    {
        $word = trim($word, " \t\n\r-/");

        return $word === '' ? 'Growth' : mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
    }
}
