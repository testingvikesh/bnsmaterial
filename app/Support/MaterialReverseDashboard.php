<?php

namespace App\Support;

use App\Models\ManageSession;
use App\Models\MaterialFile;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class MaterialReverseDashboard
{
    /**
     * @return array<string, mixed>
     */
    public static function for(ManageSession $session, ?SessionPrompt $prompt, ?Request $request = null): array
    {
        $request ??= request();
        $rows = self::rows($session, $prompt);
        $filters = self::filters($request, $rows);
        $visible = self::applyFilters($rows, $filters);

        $completed = $visible->where('completed', true);
        $pending = $visible->where('completed', false);
        $india = $visible->where('country', 'India');
        $other = $visible->where('country', '!=', 'India');

        $kpis = [
            'institutes' => self::kpi(1, 1, 0),
            'coaches' => self::kpi(1, 1, 0),
            'members' => self::kpi($visible->count(), $india->count(), $other->count()),
            'sessions' => self::kpi(1, 1, 0),
            'completed' => self::kpi($completed->count(), $india->where('completed', true)->count(), $other->where('completed', true)->count()),
            'businesses' => self::kpi($completed->count(), $india->where('completed', true)->count(), $other->where('completed', true)->count()),
            'markets' => self::kpi($completed->count() * 7, $india->where('completed', true)->count() * 7, $other->where('completed', true)->count() * 7),
            'offers' => self::kpi($completed->sum('offers'), $india->where('completed', true)->sum('offers'), $other->where('completed', true)->sum('offers')),
            'customers' => self::kpi($completed->sum('customers'), $india->where('completed', true)->sum('customers'), $other->where('completed', true)->sum('customers')),
            'target' => self::moneyKpi($completed->sum('desired'), $india->where('completed', true)->sum('desired'), $other->where('completed', true)->sum('desired')),
            'planned' => self::moneyKpi($completed->sum('planned'), $india->where('completed', true)->sum('planned'), $other->where('completed', true)->sum('planned')),
            'gap' => self::moneyKpi($completed->sum('gap'), $india->where('completed', true)->sum('gap'), $other->where('completed', true)->sum('gap')),
            'achievement' => self::pctKpi(
                self::avgAchievement($completed),
                self::avgAchievement($india->where('completed', true)),
                self::avgAchievement($other->where('completed', true))
            ),
        ];

        return [
            'filters' => $filters,
            'filterOptions' => self::filterOptions($rows),
            'kpis' => $kpis,
            'members' => $visible->values()->all(),
            'completedCount' => $completed->count(),
            'pendingCount' => $pending->count(),
            'markets' => self::marketAnalysis($completed),
            'offers' => self::offerAnalytics($completed),
            'institutes' => self::instituteTable($completed),
            'coaches' => self::coachTable($completed),
            'sessionOutcomes' => self::sessionOutcomes($session, $completed),
            'categories' => self::groupMoney($completed, 'category'),
            'states' => self::groupMoney($completed, 'state'),
            'countries' => self::groupMoney($completed, 'country'),
            'regions' => self::regionBars($completed),
            'categoryBars' => self::categoryBars($completed),
            'monthly' => self::monthly($completed),
            'achievementBands' => self::achievementBands($completed),
            'top20' => $completed->sortByDesc('achievement')->take(20)->values()->all(),
            'alerts' => self::alerts($visible, $pending),
            'productivity' => self::productivity($completed, $visible, $pending),
            'innovation' => self::innovation(),
            'home' => [
                'institutes' => 1,
                'countries' => max(1, $visible->pluck('country')->filter()->unique()->count()),
                'states' => max(1, $visible->pluck('state')->filter()->unique()->count()),
                'coaches' => 1,
                'members' => $visible->count(),
                'businesses' => $completed->count(),
                'sessions' => 1,
                'desired' => MaterialReverseManagement::compact((int) $completed->sum('desired')),
                'planned' => MaterialReverseManagement::compact((int) $completed->sum('planned')),
                'gap' => MaterialReverseManagement::compact((int) $completed->sum('gap')),
                'achievement' => self::avgAchievement($completed).'%',
                'markets' => $completed->count() * 7,
                'offers' => (int) $completed->sum('offers'),
                'customers' => MaterialReverseManagement::number((int) $completed->sum('customers')),
            ],
            'sessionReport' => self::sessionReport($session, $visible, $completed, $pending),
            'detail' => self::detail($visible, $request),
            'year' => '2026–27',
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function rows(ManageSession $session, ?SessionPrompt $prompt): Collection
    {
        $files = collect();
        if ($prompt) {
            $files = MaterialFile::query()
                ->where('manage_session_id', $session->id)
                ->where('session_prompt_id', $prompt->id)
                ->get()
                ->keyBy('user_id');
        }

        return User::query()
            ->join('member_profiles', 'member_profiles.user_id', '=', 'users.id')
            ->select([
                'users.id',
                'users.name',
                'member_profiles.member_id',
                'member_profiles.business_name',
                'member_profiles.business_category',
                'member_profiles.business_location',
                'member_profiles.business_address',
            ])
            ->orderBy('users.name')
            ->get()
            ->map(function ($user) use ($files, $session) {
                $file = $files->get($user->id);
                $plan = self::planFromFile($file);
                $completed = is_array($plan);
                $location = trim((string) ($user->business_location ?: $user->business_address));
                $geo = self::geo($location);
                $desired = (int) ($plan['desired_turnover'] ?? 0);
                $planned = (int) ($plan['planned_revenue'] ?? 0);
                $gap = (int) ($plan['gap'] ?? ($desired - $planned));
                $achievement = (int) ($plan['achievement'] ?? 0);
                $customers = (int) ($plan['customers'] ?? 0);
                $offers = $completed ? count($plan['markets'] ?? []) : 0;
                $score = $completed ? self::innovationScore($plan) : 0;

                return [
                    'user_id' => (int) $user->id,
                    'member' => (string) $user->name,
                    'member_code' => (string) ($user->member_id ?: ('MEM-'.$user->id)),
                    'business' => (string) ($user->business_name ?: '—'),
                    'category' => self::categoryLabel((string) ($user->business_category ?: ($plan['category'] ?? 'Other'))),
                    'session' => (string) $session->name,
                    'desired' => $desired,
                    'desired_label' => $completed ? MaterialReverseManagement::compact($desired) : '—',
                    'offers' => $offers,
                    'customers' => $customers,
                    'customers_label' => $completed ? MaterialReverseManagement::number($customers) : '—',
                    'planned' => $planned,
                    'planned_label' => $completed ? MaterialReverseManagement::compact($planned) : '—',
                    'gap' => $gap,
                    'gap_label' => $completed ? MaterialReverseManagement::compact($gap) : '—',
                    'achievement' => $achievement,
                    'achievement_label' => $completed ? $achievement.'%' : '—',
                    'completed' => $completed,
                    'status' => $completed ? 'completed' : 'pending',
                    'file_id' => $file?->id,
                    'view_url' => $file ? route('admin.material.show', $file) : null,
                    'generated_at' => $file?->generated_at,
                    'location' => $location !== '' ? $location : '—',
                    'country' => $geo['country'],
                    'state' => $geo['state'],
                    'region' => $geo['region'],
                    'institute' => 'BNS Institute',
                    'coach' => 'Business Coach',
                    'markets' => is_array($plan) ? ($plan['markets'] ?? []) : [],
                    'score' => $score,
                    'rating' => self::rating($score),
                ];
            });
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @param  array<string, string>  $filters
     * @return Collection<int, array<string, mixed>>
     */
    public static function applyFilters(Collection $rows, array $filters): Collection
    {
        return $rows
            ->when($filters['country'] !== '', fn ($q) => $q->where('country', $filters['country']))
            ->when($filters['state'] !== '', fn ($q) => $q->where('state', $filters['state']))
            ->when($filters['category'] !== '', fn ($q) => $q->where('category', $filters['category']))
            ->when($filters['member'] !== '', function ($q) use ($filters) {
                $needle = strtolower($filters['member']);

                return $q->filter(function ($row) use ($needle) {
                    return str_contains(strtolower($row['member'].' '.$row['business']), $needle);
                });
            })
            ->when($filters['status'] === 'completed', fn ($q) => $q->where('completed', true))
            ->when($filters['status'] === 'pending', fn ($q) => $q->where('completed', false))
            ->when($filters['achievement'] !== '', function ($q) use ($filters) {
                return $q->filter(fn ($row) => self::inBand((int) $row['achievement'], $filters['achievement']) && $row['completed']);
            })
            ->values();
    }

    /**
     * @return array<string, string>
     */
    private static function filters(Request $request, Collection $rows): array
    {
        return [
            'region' => trim((string) $request->get('region', 'global')),
            'country' => trim((string) $request->get('country', '')),
            'state' => trim((string) $request->get('state', '')),
            'institute' => trim((string) $request->get('institute', '')),
            'coach' => trim((string) $request->get('coach', '')),
            'category' => trim((string) $request->get('category', '')),
            'member' => trim((string) $request->get('member', '')),
            'year' => trim((string) $request->get('year', '2026-27')),
            'status' => trim((string) $request->get('status', '')),
            'achievement' => trim((string) $request->get('achievement', '')),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return array<string, list<string>>
     */
    private static function filterOptions(Collection $rows): array
    {
        return [
            'countries' => $rows->pluck('country')->filter()->unique()->sort()->values()->all(),
            'states' => $rows->pluck('state')->filter()->unique()->sort()->values()->all(),
            'categories' => $rows->pluck('category')->filter()->unique()->sort()->values()->all(),
            'members' => $rows->map(fn ($row) => $row['member'])->unique()->sort()->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function planFromFile(?MaterialFile $file): ?array
    {
        if (! $file instanceof MaterialFile) {
            return null;
        }

        $payload = $file->cachedPayload();
        if (! is_array($payload)) {
            $jsonPath = $file->jsonPath();
            if ($jsonPath !== '' && Storage::disk('local')->exists($jsonPath)) {
                $decoded = json_decode((string) Storage::disk('local')->get($jsonPath), true);
                $payload = is_array($decoded) ? $decoded : null;
            }
        }

        $plan = is_array($payload['reverse'] ?? null) ? $payload['reverse'] : null;

        return is_array($plan) ? $plan : null;
    }

    /**
     * @return array{country: string, state: string, region: string}
     */
    public static function geo(string $location): array
    {
        $hay = strtolower($location);
        $countries = [
            'UAE' => ['uae', 'dubai', 'abu dhabi', 'sharjah'],
            'USA' => ['usa', 'united states', 'new york', 'california'],
            'UK' => ['uk', 'united kingdom', 'london', 'england'],
            'Australia' => ['australia', 'sydney', 'melbourne'],
        ];
        foreach ($countries as $country => $needles) {
            foreach ($needles as $needle) {
                if ($needle !== '' && str_contains($hay, $needle)) {
                    return ['country' => $country, 'state' => '—', 'region' => 'International'];
                }
            }
        }

        $states = [
            'Gujarat' => ['gujarat', 'ahmedabad', 'surat', 'vadodara', 'rajkot'],
            'Maharashtra' => ['maharashtra', 'mumbai', 'pune', 'nagpur'],
            'Rajasthan' => ['rajasthan', 'jaipur'],
            'Delhi' => ['delhi', 'new delhi'],
            'Karnataka' => ['karnataka', 'bengaluru', 'bangalore'],
            'Tamil Nadu' => ['tamil nadu', 'chennai'],
            'Uttar Pradesh' => ['uttar pradesh', 'lucknow', 'noida'],
        ];
        foreach ($states as $state => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($hay, $needle)) {
                    return ['country' => 'India', 'state' => $state, 'region' => self::indiaRegion($state)];
                }
            }
        }

        return ['country' => 'India', 'state' => 'Other States', 'region' => 'West India'];
    }

    private static function indiaRegion(string $state): string
    {
        return match ($state) {
            'Gujarat', 'Maharashtra', 'Rajasthan' => 'West India',
            'Delhi', 'Uttar Pradesh' => 'North India',
            'Karnataka', 'Tamil Nadu' => 'South India',
            default => 'East India',
        };
    }

    private static function categoryLabel(string $category): string
    {
        $hay = strtolower($category);
        if (str_contains($hay, 'jewel')) {
            return 'Jewellery';
        }
        if (str_contains($hay, 'real estate') || str_contains($hay, 'propert')) {
            return 'Real Estate';
        }
        if (str_contains($hay, 'manufactur')) {
            return 'Manufacturing';
        }
        if (str_contains($hay, 'sweet') || str_contains($hay, 'food') || str_contains($hay, 'mithai')) {
            return 'Food / Sweets';
        }
        if (str_contains($hay, 'laminat') || str_contains($hay, 'print') || str_contains($hay, 'service')) {
            return 'Services';
        }
        if (str_contains($hay, 'retail')) {
            return 'Retail';
        }
        if (str_contains($hay, 'educat')) {
            return 'Education';
        }
        if (str_contains($hay, 'tech')) {
            return 'Technology';
        }

        return $category !== '' ? $category : 'Other';
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function marketAnalysis(Collection $rows): array
    {
        $names = [
            'Existing Customer',
            'New Customer',
            'Premium',
            'Budget',
            'Bulk / B2B',
            'Subscription / Repeat',
            'Digital / Online',
        ];
        $out = [];
        $plannedTotal = max(1, (int) $rows->sum('planned'));
        foreach ($names as $index => $name) {
            $customers = 0;
            $revenue = 0;
            $businesses = 0;
            foreach ($rows as $row) {
                $market = $row['markets'][$index] ?? null;
                if (! is_array($market)) {
                    continue;
                }
                $businesses++;
                $customers += (int) ($market['customers'] ?? 0);
                $revenue += (int) ($market['revenue'] ?? 0);
            }
            $out[] = [
                'name' => $name,
                'businesses' => $businesses,
                'offers' => $businesses,
                'customers' => $customers,
                'customers_label' => MaterialReverseManagement::number($customers),
                'revenue' => $revenue,
                'revenue_label' => MaterialReverseManagement::compact($revenue),
                'share' => round(($revenue / $plannedTotal) * 100).'%',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function offerAnalytics(Collection $rows): array
    {
        $types = [
            'Existing Customer Offer',
            'New Customer Offer',
            'Premium Offer',
            'Budget Offer',
            'B2B / Bulk Offer',
            'Repeat / Subscription',
            'Digital / Online',
        ];
        $count = max(1, $rows->count());
        $out = [];
        foreach ($types as $index => $type) {
            $prices = [];
            $customers = 0;
            $revenue = 0;
            $businesses = 0;
            foreach ($rows as $row) {
                $market = $row['markets'][$index] ?? null;
                if (! is_array($market)) {
                    continue;
                }
                $businesses++;
                $price = (int) ($market['price'] ?? 0);
                if ($price > 0) {
                    $prices[] = $price;
                }
                $customers += (int) ($market['customers'] ?? 0);
                $revenue += (int) ($market['revenue'] ?? 0);
            }
            $avg = $prices === [] ? 0 : (int) round(array_sum($prices) / count($prices));
            $out[] = [
                'no' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'type' => $type,
                'businesses' => $businesses,
                'avg_price' => MaterialReverseManagement::compact($avg),
                'customers' => MaterialReverseManagement::number($customers),
                'revenue' => MaterialReverseManagement::compact($revenue),
                'success' => round(($businesses / $count) * 100).'%',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function groupMoney(Collection $rows, string $key): array
    {
        return $rows->groupBy($key)->map(function (Collection $group, $name) {
            $desired = (int) $group->sum('desired');
            $planned = (int) $group->sum('planned');

            return [
                'name' => $name ?: 'Other',
                'businesses' => $group->count(),
                'members' => $group->count(),
                'institutes' => 1,
                'coaches' => 1,
                'sessions' => $group->count(),
                'target' => MaterialReverseManagement::compact($desired),
                'planned' => MaterialReverseManagement::compact($planned),
                'gap' => MaterialReverseManagement::compact((int) $group->sum('gap')),
                'achievement' => self::avgAchievement($group).'%',
            ];
        })->sortByDesc('businesses')->values()->all();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function instituteTable(Collection $rows): array
    {
        $grouped = $rows->groupBy('institute');
        $rank = 1;
        $out = [];
        foreach ($grouped as $name => $group) {
            $out[] = [
                'rank' => str_pad((string) $rank++, 2, '0', STR_PAD_LEFT),
                'name' => $name,
                'country' => (string) ($group->first()['country'] ?? 'India'),
                'members' => $group->count(),
                'sessions' => $group->count(),
                'target' => MaterialReverseManagement::compact((int) $group->sum('desired')),
                'planned' => MaterialReverseManagement::compact((int) $group->sum('planned')),
                'achievement' => self::avgAchievement($group).'%',
            ];
        }

        return $out ?: [[
            'rank' => '01',
            'name' => 'BNS Institute',
            'country' => 'India',
            'members' => 0,
            'sessions' => 0,
            'target' => '₹0',
            'planned' => '₹0',
            'achievement' => '0%',
        ]];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function coachTable(Collection $rows): array
    {
        return [[
            'name' => 'Business Coach',
            'institute' => 'BNS Institute',
            'members' => $rows->count(),
            'sessions' => $rows->count(),
            'businesses' => $rows->count(),
            'target' => MaterialReverseManagement::compact((int) $rows->sum('desired')),
            'planned' => MaterialReverseManagement::compact((int) $rows->sum('planned')),
            'achievement' => self::avgAchievement($rows).'%',
        ]];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function sessionOutcomes(ManageSession $session, Collection $rows): array
    {
        $completed = $rows->where('completed', true);

        return [[
            'code' => 'RM-'.str_pad((string) $session->id, 3, '0', STR_PAD_LEFT),
            'institute' => 'BNS Institute',
            'coach' => 'Business Coach',
            'members' => $rows->count(),
            'completed' => $completed->count(),
            'target' => MaterialReverseManagement::compact((int) $completed->sum('desired')),
            'planned' => MaterialReverseManagement::compact((int) $completed->sum('planned')),
            'gap' => MaterialReverseManagement::compact((int) $completed->sum('gap')),
            'achievement' => self::avgAchievement($completed).'%',
        ]];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array{label: string, target: int, planned: int, target_h: int, planned_h: int}>
     */
    private static function regionBars(Collection $rows): array
    {
        $order = ['West India', 'North India', 'South India', 'East India', 'International'];
        $max = 1;
        $map = [];
        foreach ($order as $label) {
            $group = $rows->where('region', $label);
            $target = (int) $group->sum('desired');
            $planned = (int) $group->sum('planned');
            $max = max($max, $target, $planned);
            $map[] = ['label' => $label, 'target' => $target, 'planned' => $planned];
        }

        return array_map(function ($row) use ($max) {
            $row['target_h'] = (int) round(($row['target'] / $max) * 100);
            $row['planned_h'] = (int) round(($row['planned'] / $max) * 100);
            $row['target_label'] = MaterialReverseManagement::compact($row['target']);
            $row['planned_label'] = MaterialReverseManagement::compact($row['planned']);

            return $row;
        }, $map);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function categoryBars(Collection $rows): array
    {
        $order = ['Jewellery', 'Real Estate', 'Manufacturing', 'Services', 'Food / Sweets'];
        $max = 1;
        $bars = [];
        foreach ($order as $label) {
            $group = $rows->filter(fn ($row) => $row['category'] === $label
                || ($label === 'Services' && str_contains(strtolower($row['category']), 'laminat')));
            $target = (int) $group->sum('desired');
            $planned = (int) $group->sum('planned');
            $max = max($max, $target, $planned);
            $bars[] = ['label' => $label === 'Services' ? 'Lamination' : $label, 'target' => $target, 'planned' => $planned];
        }

        return array_map(function ($row) use ($max) {
            $row['target_h'] = (int) round(($row['target'] / $max) * 100);
            $row['planned_h'] = (int) round(($row['planned'] / $max) * 100);
            $row['target_label'] = MaterialReverseManagement::compact($row['target']);
            $row['planned_label'] = MaterialReverseManagement::compact($row['planned']);

            return $row;
        }, $bars);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function monthly(Collection $rows): array
    {
        $labels = ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'];
        $totals = array_fill(0, 12, 0);
        foreach ($rows as $row) {
            $date = $row['generated_at'] ?? null;
            if (! $date) {
                continue;
            }
            $month = (int) $date->format('n');
            $index = $month >= 4 ? $month - 4 : $month + 8;
            $totals[$index] += (int) $row['planned'];
        }
        $max = max(1, max($totals));

        return array_map(function ($label, $i) use ($totals, $max) {
            return [
                'label' => $label,
                'value' => $totals[$i],
                'label_money' => MaterialReverseManagement::compact($totals[$i]),
                'h' => (int) round(($totals[$i] / $max) * 100),
            ];
        }, $labels, array_keys($labels));
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private static function achievementBands(Collection $rows): array
    {
        $total = max(1, $rows->count());
        $bands = [
            ['key' => 'below50', 'label' => 'Below 50%', 'icon' => '🔴', 'min' => 0, 'max' => 49],
            ['key' => '50-79', 'label' => '50–79%', 'icon' => '🟠', 'min' => 50, 'max' => 79],
            ['key' => '80-99', 'label' => '80–99%', 'icon' => '🟡', 'min' => 80, 'max' => 99],
            ['key' => '100', 'label' => '100%', 'icon' => '🟢', 'min' => 100, 'max' => 100],
            ['key' => 'above', 'label' => 'Above 100%', 'icon' => '⭐', 'min' => 101, 'max' => 10000],
        ];

        return array_map(function ($band) use ($rows, $total) {
            $group = $rows->filter(fn ($row) => $row['achievement'] >= $band['min'] && $row['achievement'] <= $band['max']);

            return [
                'label' => $band['icon'].' '.$band['label'],
                'members' => $group->count(),
                'share' => round(($group->count() / $total) * 100).'%',
                'revenue' => MaterialReverseManagement::compact((int) $group->sum('planned')),
            ];
        }, $bands);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $visible
     * @param  Collection<int, array<string, mixed>>  $pending
     * @return list<array{alert: string, count: int, action: string}>
     */
    private static function alerts(Collection $visible, Collection $pending): array
    {
        $completed = $visible->where('completed', true);
        $below = $completed->filter(fn ($row) => $row['achievement'] < 100);
        $star = $completed->filter(fn ($row) => $row['achievement'] >= 100);

        return [
            ['alert' => 'Desired Turnover entered but 7 offers incomplete', 'count' => 0, 'action' => 'view'],
            ['alert' => 'Offer Price missing', 'count' => 0, 'action' => 'view'],
            ['alert' => 'Customer calculation incomplete', 'count' => 0, 'action' => 'view'],
            ['alert' => 'Revenue below target', 'count' => $below->count(), 'action' => 'below'],
            ['alert' => 'Session pending', 'count' => $pending->count(), 'action' => 'pending'],
            ['alert' => 'Coach review pending', 'count' => $pending->count(), 'action' => 'pending'],
            ['alert' => 'Member plan not submitted', 'count' => $pending->count(), 'action' => 'pending'],
            ['alert' => 'Target achieved 100%+', 'count' => $star->count(), 'action' => 'star'],
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $completed
     * @param  Collection<int, array<string, mixed>>  $visible
     * @param  Collection<int, array<string, mixed>>  $pending
     * @return array<string, array<string, string|int>>
     */
    private static function productivity(Collection $completed, Collection $visible, Collection $pending): array
    {
        $now = now();
        $week = $completed->filter(fn ($row) => $row['generated_at'] && $row['generated_at']->gte($now->copy()->startOfWeek()));
        $month = $completed->filter(fn ($row) => $row['generated_at'] && $row['generated_at']->gte($now->copy()->startOfMonth()));
        $today = $completed->filter(fn ($row) => $row['generated_at'] && $row['generated_at']->isToday());

        $pack = function (Collection $set, Collection $allVisible) {
            return [
                'planned' => $set->count(),
                'completed' => $set->count(),
                'members' => $set->count(),
                'businesses' => $set->count(),
                'offers' => (int) $set->sum('offers'),
                'target' => MaterialReverseManagement::compact((int) $set->sum('desired')),
                'revenue' => MaterialReverseManagement::compact((int) $set->sum('planned')),
                'pending' => $allVisible->where('completed', false)->count(),
            ];
        };

        return [
            'today' => $pack($today, $visible),
            'week' => $pack($week, $visible),
            'month' => $pack($month, $visible),
            'year' => $pack($completed, $visible),
        ];
    }

    /**
     * @return list<array{parameter: string, weight: int}>
     */
    private static function innovation(): array
    {
        return [
            ['parameter' => '7 Markets Completed', 'weight' => 15],
            ['parameter' => '7 Offers Completed', 'weight' => 15],
            ['parameter' => 'New Customer Opportunity', 'weight' => 10],
            ['parameter' => 'Premium Opportunity', 'weight' => 10],
            ['parameter' => 'B2B Opportunity', 'weight' => 10],
            ['parameter' => 'Repeat Revenue Opportunity', 'weight' => 10],
            ['parameter' => 'Digital Opportunity', 'weight' => 10],
            ['parameter' => 'Revenue Target Coverage', 'weight' => 10],
            ['parameter' => 'Practicality of Offers', 'weight' => 5],
            ['parameter' => 'Scalability', 'weight' => 5],
        ];
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    public static function innovationScore(array $plan): int
    {
        $markets = $plan['markets'] ?? [];
        $score = 0;
        if (count($markets) >= 7) {
            $score += 15;
        }
        $priced = collect($markets)->filter(fn ($row) => (int) ($row['price'] ?? 0) > 0)->count();
        if ($priced >= 7) {
            $score += 15;
        }
        $score += 10 + 10 + 10 + 10 + 10;
        $desired = max(1, (int) ($plan['desired_turnover'] ?? 0));
        $planned = (int) ($plan['planned_revenue'] ?? 0);
        $score += (int) round(min(1, $planned / $desired) * 10);
        $score += 5 + 5;

        return min(100, $score);
    }

    private static function rating(int $score): string
    {
        if ($score >= 90) {
            return '⭐ Excellent';
        }
        if ($score >= 75) {
            return '🟢 Strong';
        }
        if ($score >= 60) {
            return '🟡 Needs Improvement';
        }

        return '🔴 Intervention Required';
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $visible
     * @param  Collection<int, array<string, mixed>>  $completed
     * @param  Collection<int, array<string, mixed>>  $pending
     * @return array<string, mixed>
     */
    private static function sessionReport(ManageSession $session, Collection $visible, Collection $completed, Collection $pending): array
    {
        $top = $completed->sortByDesc('achievement')->first();
        $low = $completed->sortBy('achievement')->first();
        $popular = collect(self::marketAnalysis($completed))->sortByDesc('businesses')->first();

        return [
            'institute' => 'BNS Institute',
            'coach' => 'Business Coach',
            'date' => now()->format('d M Y'),
            'members' => $visible->count(),
            'present' => $completed->count(),
            'completed' => $completed->count(),
            'incomplete' => $pending->count(),
            'desired' => MaterialReverseManagement::compact((int) $completed->sum('desired')),
            'planned' => MaterialReverseManagement::compact((int) $completed->sum('planned')),
            'gap' => MaterialReverseManagement::compact((int) $completed->sum('gap')),
            'achievement' => self::avgAchievement($completed).'%',
            'top' => $top['member'] ?? '—',
            'low' => $low['member'] ?? '—',
            'market' => $popular['name'] ?? '—',
            'offer' => $popular['name'] ?? '—',
            'session' => $session->name,
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $visible
     * @return array<string, mixed>|null
     */
    private static function detail(Collection $visible, Request $request): ?array
    {
        $id = $request->integer('member_id');
        $row = $id > 0 ? $visible->firstWhere('user_id', $id) : $visible->where('completed', true)->first();

        return is_array($row) ? $row : $visible->first();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     */
    private static function avgAchievement(Collection $rows): int
    {
        if ($rows->isEmpty()) {
            return 0;
        }

        return (int) round($rows->avg('achievement'));
    }

    /**
     * @return array{global: int, india: int, other: int, global_label: string, india_label: string, other_label: string}
     */
    private static function kpi(int $global, int $india, int $other): array
    {
        return [
            'global' => $global,
            'india' => $india,
            'other' => $other,
            'global_label' => MaterialReverseManagement::number($global),
            'india_label' => MaterialReverseManagement::number($india),
            'other_label' => MaterialReverseManagement::number($other),
        ];
    }

    /**
     * @return array{global: int, india: int, other: int, global_label: string, india_label: string, other_label: string}
     */
    private static function moneyKpi(int $global, int $india, int $other): array
    {
        return [
            'global' => $global,
            'india' => $india,
            'other' => $other,
            'global_label' => MaterialReverseManagement::compact($global),
            'india_label' => MaterialReverseManagement::compact($india),
            'other_label' => MaterialReverseManagement::compact($other),
        ];
    }

    /**
     * @return array{global: int, india: int, other: int, global_label: string, india_label: string, other_label: string}
     */
    private static function pctKpi(int $global, int $india, int $other): array
    {
        return [
            'global' => $global,
            'india' => $india,
            'other' => $other,
            'global_label' => $global.'%',
            'india_label' => $india.'%',
            'other_label' => $other.'%',
        ];
    }

    private static function inBand(int $value, string $band): bool
    {
        return match ($band) {
            '0-50' => $value < 50,
            '50-80' => $value >= 50 && $value < 80,
            '80-100' => $value >= 80 && $value < 100,
            '100+' => $value >= 100,
            default => true,
        };
    }
}
