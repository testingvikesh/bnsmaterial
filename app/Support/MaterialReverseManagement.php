<?php

namespace App\Support;

class MaterialReverseManagement
{
    /**
     * @return list<array{key: string, name: string, short: string}>
     */
    public static function markets(): array
    {
        return [
            ['key' => 'existing', 'name' => 'Existing Customer Market', 'short' => 'Existing Customer'],
            ['key' => 'new', 'name' => 'New Customer Market', 'short' => 'New Customer'],
            ['key' => 'premium', 'name' => 'Premium Market', 'short' => 'Premium'],
            ['key' => 'budget', 'name' => 'Budget Market', 'short' => 'Budget'],
            ['key' => 'b2b', 'name' => 'Bulk / B2B Market', 'short' => 'Bulk / B2B'],
            ['key' => 'repeat', 'name' => 'Subscription / Repeat Market', 'short' => 'Subscription / Repeat'],
            ['key' => 'digital', 'name' => 'Digital / Online Market', 'short' => 'Digital / Online'],
        ];
    }

    /**
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public static function for(array $facts): array
    {
        $biz = trim((string) ($facts['business_name'] ?? '')) ?: 'this business';
        $cat = trim((string) ($facts['category'] ?? '')) ?: 'Business';
        $intro = trim((string) ($facts['intro'] ?? '')) ?: $cat.' business';
        $product = trim((string) ($facts['products'] ?? '')) ?: $cat;
        $family = self::family($cat, $product, $intro);
        $plan = self::familyPlan($family, $biz, $product);
        $desired = (int) $plan['desired'];
        $rows = [];
        $totalCustomers = 0;
        $planned = 0;

        foreach (self::markets() as $index => $market) {
            $slot = $plan['slots'][$index];
            $price = max(1, (int) $slot['price']);
            $share = (float) $slot['share'];
            $targetRevenue = (int) round($desired * $share);
            $customers = max(1, (int) round($targetRevenue / $price));
            $revenue = $price * $customers;
            $rows[] = [
                'no' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'market' => $market['name'],
                'short' => $market['short'],
                'offer' => $slot['offer'],
                'share' => $share,
                'price' => $price,
                'price_label' => self::rupees($price),
                'customers' => $customers,
                'customers_label' => self::number($customers),
                'revenue' => $revenue,
                'revenue_label' => self::compact($revenue),
            ];
            $totalCustomers += $customers;
            $planned += $revenue;
        }

        $gap = $desired - $planned;
        $achievement = $desired > 0 ? (int) round(($planned / $desired) * 100) : 0;

        return [
            'family' => $family,
            'business_name' => $biz,
            'category' => $cat,
            'intro' => $intro,
            'product' => $product,
            'unit_label' => $family === 'real_estate' ? 'Deals' : 'Customers',
            'price_label' => $family === 'real_estate' ? 'Average Deal Value' : 'Price',
            'desired_turnover' => $desired,
            'desired_label' => self::rupees($desired),
            'planned_revenue' => $planned,
            'planned_label' => self::compact($planned),
            'customers' => $totalCustomers,
            'customers_label' => self::number($totalCustomers),
            'gap' => $gap,
            'gap_label' => self::gapLabel($gap),
            'achievement' => $achievement,
            'markets' => $rows,
            'examples' => self::examples(),
            'languages' => MaterialReverseI18n::packs($biz),
        ];
    }

    /**
     * Complete sample views shown for every member.
     *
     * @return list<array<string, mixed>>
     */
    public static function examples(): array
    {
        return [
            [
                'code' => 'view-01',
                'title' => 'VIEW 01 — Jewellery Business',
                'business_name' => 'ABC Jewellery',
                'category' => 'Jewellery',
                'intro' => 'Gold & Diamond Jewellery Retail Business',
                'product' => 'Gold & Diamond Jewellery',
                'price_label' => 'Price',
                'unit_label' => 'Customers',
                'desired_label' => self::rupees(10000000),
                'planned_label' => self::compact(9000000),
                'customers_label' => self::number(380),
                'gap_label' => '₹10 Lakh',
                'achievement' => 90,
                'markets' => self::exampleRows([
                    ['Existing Customer', 'Gold Upgrade Offer', 50000, 40, 2000000],
                    ['New Customer', 'First Purchase Collection', 25000, 40, 1000000],
                    ['Premium', 'Luxury Diamond Collection', 200000, 15, 3000000],
                    ['Budget', 'Starter Jewellery Collection', 10000, 50, 500000],
                    ['Bulk / B2B', 'Corporate Gift Jewellery', 100000, 10, 1000000],
                    ['Subscription / Repeat', 'Jewellery Purchase Plan', 5000, 200, 1000000],
                    ['Digital / Online', 'Virtual Jewellery Shopping', 20000, 25, 500000],
                ]),
            ],
            [
                'code' => 'view-02',
                'title' => 'VIEW 02 — Real Estate Business',
                'business_name' => 'XYZ Properties',
                'category' => 'Real Estate',
                'intro' => 'Residential & Commercial Property Business',
                'product' => 'Residential & Commercial Properties',
                'price_label' => 'Average Deal Value',
                'unit_label' => 'Deals',
                'desired_label' => self::rupees(50000000),
                'planned_label' => self::compact(50000000),
                'customers_label' => self::number(92),
                'gap_label' => '₹0',
                'achievement' => 100,
                'note' => 'Real Estate uses Deals / Average Deal Value instead of Customers / Price.',
                'markets' => self::exampleRows([
                    ['Existing Customer', 'Upgrade / Referral Property', 2500000, 4, 10000000],
                    ['New Customer', 'First-Time Buyer Property', 3000000, 3, 9000000],
                    ['Premium', 'Luxury Property', 10000000, 2, 20000000],
                    ['Budget', 'Affordable Property', 1500000, 2, 3000000],
                    ['Bulk / B2B', 'Investor Property Deal', 5000000, 1, 5000000],
                    ['Subscription / Repeat', 'Property Management', 25000, 40, 1000000],
                    ['Digital / Online', 'Virtual Property Consultation', 50000, 40, 2000000],
                ]),
            ],
            [
                'code' => 'view-03',
                'title' => 'VIEW 03 — Manufacturing Business',
                'business_name' => 'ABC Manufacturing',
                'category' => 'Manufacturing',
                'intro' => 'Industrial Component Manufacturer',
                'product' => 'Industrial Components',
                'price_label' => 'Price',
                'unit_label' => 'Customers',
                'desired_label' => self::rupees(100000000),
                'planned_label' => self::compact(100000000),
                'customers_label' => self::number(123),
                'gap_label' => '₹0',
                'achievement' => 100,
                'markets' => self::exampleRows([
                    ['Existing Customer', 'Annual Supply Upgrade', 1000000, 10, 10000000],
                    ['New Customer', 'New Buyer Package', 500000, 10, 5000000],
                    ['Premium', 'Custom Premium Components', 2500000, 10, 25000000],
                    ['Budget', 'Standard Product Package', 200000, 25, 5000000],
                    ['Bulk / B2B', 'Bulk Manufacturing Contract', 5000000, 8, 40000000],
                    ['Subscription / Repeat', 'Annual Supply Contract', 1000000, 10, 10000000],
                    ['Digital / Online', 'Online B2B Ordering', 100000, 50, 5000000],
                ]),
            ],
            [
                'code' => 'view-04',
                'title' => 'VIEW 04 — Lamination Business',
                'business_name' => 'Fast Lamination Centre',
                'category' => 'Printing / Lamination',
                'intro' => 'Document Printing & Lamination Service',
                'product' => 'Printing & Lamination',
                'price_label' => 'Price',
                'unit_label' => 'Customers',
                'desired_label' => self::rupees(3000000),
                'planned_label' => self::compact(3000000),
                'customers_label' => self::number(5740),
                'gap_label' => '₹0',
                'achievement' => 100,
                'markets' => self::exampleRows([
                    ['Existing Customer', 'Premium Document Package', 500, 2000, 1000000],
                    ['New Customer', 'Student Document Package', 300, 1000, 300000],
                    ['Premium', 'Certificate & Portfolio Package', 2000, 500, 1000000],
                    ['Budget', 'Basic Lamination Package', 100, 2000, 200000],
                    ['Bulk / B2B', 'School / Office Package', 25000, 10, 250000],
                    ['Subscription / Repeat', 'Monthly Business Plan', 5000, 30, 150000],
                    ['Digital / Online', 'Online Document Service', 500, 200, 100000],
                ]),
            ],
            [
                'code' => 'view-05',
                'title' => 'VIEW 05 — Sweets / Mithai Business',
                'business_name' => 'Shree Mithai House',
                'category' => 'Food / Sweets',
                'intro' => 'Traditional & Premium Indian Sweets Business',
                'product' => 'Sweets & Gift Hampers',
                'price_label' => 'Price',
                'unit_label' => 'Customers',
                'desired_label' => self::rupees(10000000),
                'planned_label' => '₹1 Crore',
                'customers_label' => self::number(5000),
                'gap_label' => '₹0',
                'achievement' => 100,
                'markets' => self::exampleRows([
                    ['Existing Customer', 'Festival Family Sweet Box', 2000, 1500, 3000000],
                    ['New Customer', 'First Order Sweet Box', 1000, 1000, 1000000],
                    ['Premium', 'Premium Celebration Hamper', 5000, 600, 3000000],
                    ['Budget', 'Mini Sweet Pack', 500, 1000, 500000],
                    ['Bulk / B2B', 'Corporate Festival Gifting', 10000, 150, 1500000],
                    ['Subscription / Repeat', 'Monthly Sweet Subscription', 1000, 500, 500000],
                    ['Digital / Online', 'Online Sweet Delivery', 2000, 250, 500000],
                ]),
            ],
        ];
    }

    /**
     * @param  list<array{0: string, 1: string, 2: int, 3: int, 4: int}>  $rows
     * @return list<array<string, string|int>>
     */
    private static function exampleRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $index => $row) {
            $out[] = [
                'no' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'short' => $row[0],
                'offer' => $row[1],
                'price_label' => self::compact((int) $row[2]) === self::rupees((int) $row[2])
                    ? self::rupees((int) $row[2])
                    : self::compact((int) $row[2]),
                'customers_label' => self::number((int) $row[3]),
                'revenue_label' => self::compact((int) $row[4]),
            ];
        }

        return $out;
    }

    public static function family(string $category, string $products = '', string $intro = ''): string
    {
        $hay = strtolower($category.' '.$products.' '.$intro);

        if (self::has($hay, ['jewel', 'gold', 'diamond', 'ornament'])) {
            return 'jewellery';
        }
        if (self::has($hay, ['real estate', 'propert', 'realtor', 'housing', 'plot'])) {
            return 'real_estate';
        }
        if (self::has($hay, ['manufactur', 'industrial', 'factory', 'component', 'fabricat'])) {
            return 'manufacturing';
        }
        if (self::has($hay, ['laminat', 'printing', 'xerox', 'photocopy', 'copier'])) {
            return 'lamination';
        }
        if (self::has($hay, ['sweet', 'mithai', 'namkeen', 'bakery', 'tiffin', 'farsan', 'food'])) {
            return 'sweets';
        }

        return 'generic';
    }

    public static function rupees(int $amount): string
    {
        $sign = $amount < 0 ? '-' : '';
        $digits = (string) abs($amount);
        if (strlen($digits) <= 3) {
            return '₹'.$sign.$digits;
        }

        $last3 = substr($digits, -3);
        $rest = substr($digits, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) ?: $rest;

        return '₹'.$sign.$rest.','.$last3;
    }

    public static function compact(int $amount): string
    {
        $sign = $amount < 0 ? '-' : '';
        $n = abs($amount);
        if ($n >= 10000000) {
            $cr = $n / 10000000;
            $label = fmod($cr, 1.0) === 0.0 ? (string) (int) $cr : rtrim(rtrim(number_format($cr, 2, '.', ''), '0'), '.');

            return '₹'.$sign.$label.' Cr';
        }
        if ($n >= 100000) {
            $lakh = $n / 100000;
            $label = fmod($lakh, 1.0) === 0.0 ? (string) (int) $lakh : rtrim(rtrim(number_format($lakh, 2, '.', ''), '0'), '.');

            return '₹'.$sign.$label.' L';
        }

        return self::rupees($amount);
    }

    public static function number(int $n): string
    {
        return number_format($n);
    }

    private static function gapLabel(int $gap): string
    {
        if ($gap === 0) {
            return '₹0';
        }

        $abs = abs($gap);
        if ($abs >= 100000 && $abs % 100000 === 0) {
            $lakh = $abs / 100000;
            $word = $lakh === 1.0 ? 'Lakh' : 'Lakh';

            return ($gap < 0 ? '-' : '').'₹'.(int) $lakh.' '.$word;
        }

        return self::compact($gap);
    }

    /**
     * @return array{desired: int, slots: list<array{offer: string, price: int, share: float}>}
     */
    private static function familyPlan(string $family, string $biz, string $product): array
    {
        $noun = self::shortNoun($product);

        return match ($family) {
            'jewellery' => [
                'desired' => 10000000,
                'slots' => [
                    ['offer' => 'Gold Upgrade Offer', 'price' => 50000, 'share' => 0.20],
                    ['offer' => 'First Purchase Collection', 'price' => 25000, 'share' => 0.10],
                    ['offer' => 'Luxury Diamond Collection', 'price' => 200000, 'share' => 0.30],
                    ['offer' => 'Starter Jewellery Collection', 'price' => 10000, 'share' => 0.05],
                    ['offer' => 'Corporate Gift Jewellery', 'price' => 100000, 'share' => 0.10],
                    ['offer' => 'Jewellery Purchase Plan', 'price' => 5000, 'share' => 0.10],
                    ['offer' => 'Virtual Jewellery Shopping', 'price' => 20000, 'share' => 0.05],
                ],
            ],
            'real_estate' => [
                'desired' => 50000000,
                'slots' => [
                    ['offer' => 'Upgrade / Referral Property', 'price' => 2500000, 'share' => 0.20],
                    ['offer' => 'First-Time Buyer Property', 'price' => 3000000, 'share' => 0.18],
                    ['offer' => 'Luxury Property', 'price' => 10000000, 'share' => 0.40],
                    ['offer' => 'Affordable Property', 'price' => 1500000, 'share' => 0.06],
                    ['offer' => 'Investor Property Deal', 'price' => 5000000, 'share' => 0.10],
                    ['offer' => 'Property Management', 'price' => 25000, 'share' => 0.02],
                    ['offer' => 'Virtual Property Consultation', 'price' => 50000, 'share' => 0.04],
                ],
            ],
            'manufacturing' => [
                'desired' => 100000000,
                'slots' => [
                    ['offer' => 'Annual Supply Upgrade', 'price' => 1000000, 'share' => 0.10],
                    ['offer' => 'New Buyer Package', 'price' => 500000, 'share' => 0.05],
                    ['offer' => 'Custom Premium Components', 'price' => 2500000, 'share' => 0.25],
                    ['offer' => 'Standard Product Package', 'price' => 200000, 'share' => 0.05],
                    ['offer' => 'Bulk Manufacturing Contract', 'price' => 5000000, 'share' => 0.40],
                    ['offer' => 'Annual Supply Contract', 'price' => 1000000, 'share' => 0.10],
                    ['offer' => 'Online B2B Ordering', 'price' => 100000, 'share' => 0.05],
                ],
            ],
            'lamination' => [
                'desired' => 3000000,
                'slots' => [
                    ['offer' => 'Premium Document Package', 'price' => 500, 'share' => 0.333],
                    ['offer' => 'Student Document Package', 'price' => 300, 'share' => 0.10],
                    ['offer' => 'Certificate & Portfolio Package', 'price' => 2000, 'share' => 0.333],
                    ['offer' => 'Basic Lamination Package', 'price' => 100, 'share' => 0.067],
                    ['offer' => 'School / Office Package', 'price' => 25000, 'share' => 0.083],
                    ['offer' => 'Monthly Business Plan', 'price' => 5000, 'share' => 0.05],
                    ['offer' => 'Online Document Service', 'price' => 500, 'share' => 0.033],
                ],
            ],
            'sweets' => [
                'desired' => 10000000,
                'slots' => [
                    ['offer' => 'Festival Family Sweet Box', 'price' => 2000, 'share' => 0.30],
                    ['offer' => 'First Order Sweet Box', 'price' => 1000, 'share' => 0.10],
                    ['offer' => 'Premium Celebration Hamper', 'price' => 5000, 'share' => 0.30],
                    ['offer' => 'Mini Sweet Pack', 'price' => 500, 'share' => 0.05],
                    ['offer' => 'Corporate Festival Gifting', 'price' => 10000, 'share' => 0.15],
                    ['offer' => 'Monthly Sweet Subscription', 'price' => 1000, 'share' => 0.05],
                    ['offer' => 'Online Sweet Delivery', 'price' => 2000, 'share' => 0.05],
                ],
            ],
            default => [
                'desired' => 5000000,
                'slots' => [
                    ['offer' => $noun.' Upgrade Offer for existing '.$biz.' customers', 'price' => 25000, 'share' => 0.22],
                    ['offer' => 'First Purchase '.$noun.' Pack', 'price' => 12000, 'share' => 0.12],
                    ['offer' => 'Premium '.$noun.' Collection', 'price' => 75000, 'share' => 0.28],
                    ['offer' => 'Starter '.$noun.' Kit', 'price' => 5000, 'share' => 0.08],
                    ['offer' => 'Bulk / Dealer '.$noun.' Contract', 'price' => 100000, 'share' => 0.15],
                    ['offer' => $noun.' Repeat / AMC Plan', 'price' => 3000, 'share' => 0.08],
                    ['offer' => 'Online '.$noun.' Order Desk', 'price' => 8000, 'share' => 0.07],
                ],
            ],
        };
    }

    private static function shortNoun(string $product): string
    {
        $product = trim($product);
        if ($product === '') {
            return 'Offer';
        }

        $first = trim((string) strtok($product, "/,;|&"));

        return $first !== '' ? $first : 'Offer';
    }

    /**
     * @param  list<string>  $needles
     */
    private static function has(string $hay, array $needles): bool
    {
        foreach ($needles as $needle) {
            if ($needle !== '' && str_contains($hay, $needle)) {
                return true;
            }
        }

        return false;
    }
}
