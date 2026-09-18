<?php

namespace App\Support;

class MaterialSanskarCalendar
{
    public static function shortLine(string $text, int $max = 72): string
    {
        $points = MaterialCopyPoints::from($text);
        foreach ($points as $line) {
            $line = trim((string) $line);
            if ($line === '' || preg_match('/^(for\s+|services offered by)/iu', $line)) {
                continue;
            }
            if (preg_match('/^([^:–-]{3,60}?)\s*[:–-]/u', $line, $match)) {
                $line = trim($match[1]);
            }
            if (mb_strlen($line) > $max) {
                $line = rtrim(mb_substr($line, 0, $max - 1)).'…';
            }
            if ($line !== '') {
                return $line;
            }
        }

        $fallback = trim((string) ($points[0] ?? $text));

        return $fallback !== '' ? $fallback : 'this offer';
    }

    /**
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public static function for(array $facts): array
    {
        $biz = trim((string) ($facts['business_name'] ?? '')) ?: 'this business';
        $cat = trim((string) ($facts['category'] ?? '')) ?: 'Business';
        $intro = trim((string) ($facts['intro'] ?? ''));
        $product = trim((string) ($facts['products'] ?? '')) ?: $cat;
        $city = trim((string) ($facts['city'] ?? $facts['location'] ?? ''));
        $family = self::family($cat, $product, $intro);
        $pack = self::pack($family, $biz, $cat, $intro, $product, $city);
        $activities = $pack['activities'];
        $calendar = [];
        foreach ($activities as $activity) {
            $calendar[] = [
                'no' => $activity['no'],
                'sanskar' => $activity['sanskar'],
                'activity' => $activity['title'],
            ];
        }

        if ($intro === '') {
            $intro = $pack['intro'];
        }

        $out = [
            'family' => $family,
            'business_name' => $biz,
            'member_name' => (string) ($facts['member_name'] ?? ''),
            'category' => $cat,
            'business_type' => $pack['business_type'],
            'model' => $pack['model'],
            'intro' => $intro,
            'intro_points' => MaterialCopyPoints::from($intro),
            'product' => $product,
            'product_points' => MaterialCopyPoints::from($product),
            'target_customers' => $pack['customers'],
            'annual_goal' => 'Create 16 meaningful experiences with every customer through the year and build a lifetime relationship.',
            'calendar' => $calendar,
            'activities' => $activities,
            'invitation' => $pack['invitation'],
            'formula' => '16 Activities → 16 Experiences → 16 Memories → Lifetime Customer',
            'closing' => $pack['closing'],
        ];

        $out['languages'] = MaterialSanskarI18n::packs($biz, $out);

        return $out;
    }

    public static function family(string $category, string $products = '', string $intro = ''): string
    {
        $hay = strtolower($category.' '.$products.' '.$intro);

        if (self::has($hay, ['kabel', 'cable', 'wire', 'switchgear', 'electrical', 'electrician', 'panel builder', 'mcb', 'mccb', 'acb', 'rr kabel', 'abb '])) {
            return 'electrical';
        }

        return MaterialTaglineMasterclass::family($category, $products, $intro);
    }

    /**
     * @return list<array{month: string, sanskar: string, title: string}>
     */
    public static function skeleton(): array
    {
        return [
            ['month' => 'January', 'sanskar' => 'Parichay', 'title' => 'Customer Connect Meet'],
            ['month' => 'January', 'sanskar' => 'Swagat', 'title' => 'Welcome Kit Distribution'],
            ['month' => 'February', 'sanskar' => 'Margdarshan', 'title' => 'Product Knowledge Workshop'],
            ['month' => 'March', 'sanskar' => 'Anubhav', 'title' => 'One-Minute Games'],
            ['month' => 'April', 'sanskar' => 'Samjan', 'title' => 'Open House'],
            ['month' => 'May', 'sanskar' => 'Parivar', 'title' => 'Family Evening Picnic'],
            ['month' => 'June', 'sanskar' => 'Samman', 'title' => 'Partner Appreciation Day'],
            ['month' => 'July', 'sanskar' => 'Vishwas', 'title' => 'Quality & Trust Workshop'],
            ['month' => 'August', 'sanskar' => 'Sahbhagita', 'title' => 'Sports & Outdoor Day'],
            ['month' => 'September', 'sanskar' => 'Seva', 'title' => 'Customer Service Day'],
            ['month' => 'October', 'sanskar' => 'Sambandh', 'title' => 'Cultural Night'],
            ['month' => 'November', 'sanskar' => 'Puraskar', 'title' => 'Customer Awards'],
            ['month' => 'December', 'sanskar' => 'Utsav', 'title' => 'Annual Customer Celebration'],
            ['month' => 'December', 'sanskar' => 'Abhar', 'title' => 'Thank You Evening'],
            ['month' => 'December', 'sanskar' => 'Smruti', 'title' => 'Memory Wall & Photo Day'],
            ['month' => 'December', 'sanskar' => 'Virasat', 'title' => 'Signature Customer Summit'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function pack(string $family, string $biz, string $cat, string $intro, string $product, string $city): array
    {
        $offer = self::shortLine($product) ?: $cat;
        $industry = match ($family) {
            'electrical' => self::electricalFlavour($biz, $cat, $intro, $product, $offer),
            'jewellery' => self::jewelleryFlavour($biz, $cat, $intro, $product, $offer),
            'real_estate' => self::realEstateFlavour($biz, $cat, $intro, $product, $offer),
            'education' => self::educationFlavour($biz, $cat, $intro, $product, $offer),
            'it' => self::itFlavour($biz, $cat, $intro, $product, $offer),
            'sweets' => self::sweetsFlavour($biz, $cat, $intro, $product, $offer),
            default => self::genericFlavour($biz, $cat, $intro, $product, $offer),
        };

        $activities = [];
        foreach (self::skeleton() as $index => $row) {
            $detail = $industry['details'][$index] ?? [];
            $activities[] = [
                'no' => $index + 1,
                'month' => $row['month'],
                'sanskar' => $row['sanskar'],
                'title' => (string) ($detail['title'] ?? $row['title']),
                'objective' => (string) ($detail['objective'] ?? ''),
                'blocks' => is_array($detail['blocks'] ?? null) ? $detail['blocks'] : [],
                'memory' => (string) ($detail['memory'] ?? ''),
                'budget' => (string) ($detail['budget'] ?? ''),
                'certificate' => (bool) ($detail['certificate'] ?? false),
            ];
        }

        $audience = implode(', ', $industry['customers']);
        $place = $city !== '' ? $city : 'your city';

        return [
            'business_type' => $industry['business_type'],
            'model' => $industry['model'],
            'intro' => $industry['intro'],
            'customers' => $industry['customers'],
            'activities' => $activities,
            'invitation' => [
                'greeting' => 'પ્રિય ગ્રાહકશ્રી,',
                'welcome' => $biz.' તરફથી આપનું હાર્દિક સ્વાગત છે.',
                'invite' => 'આપ અમારી સાથે વર્ષ દરમિયાન યોજાનાર વિશેષ Customer Relationship કાર્યક્રમમાં આમંત્રિત છો.',
                'place' => '📍 સ્થળ:',
                'date' => '📅 તારીખ:',
                'time' => '🕒 સમય:',
                'benefit' => 'આ કાર્યક્રમમાં નવા '.$offer.' solutions, expert guidance, fun activities અને networkingનો લાભ મળશે.',
                'close' => 'આપની હાજરી અમારે માટે ખૂબ જ મૂલ્યવાન છે.',
                'signoff' => '— '.$biz.' Team',
            ],
            'closing' => 'For '.$biz.', this framework is not only events. It is an ERP-driven Customer Relationship Strategy to build long-term trust and repeat business with '.$audience.'.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function electricalFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        $introLine = $intro !== ''
            ? $intro
            : 'We are distributors of '.$product.'.';
        $workshop = self::has(strtolower($product.' '.$intro), ['abb'])
            ? 'ABB Switchgear Workshop'
            : $offer.' Workshop';

        return [
            'business_type' => $cat !== '' && $cat !== 'Business' ? $cat : 'Electrical Trading',
            'model' => 'B2B + B2C',
            'intro' => $introLine,
            'customers' => [
                'Electrical Contractors',
                'Panel Builders',
                'Electricians',
                'Dealers',
                'Industrial Clients',
                'Builders',
                'OEM Customers',
            ],
            'details' => [
                [
                    'title' => 'Customer Connect Meet',
                    'objective' => 'Build introduction with new and existing customers.',
                    'blocks' => [
                        ['label' => 'Programme Flow', 'items' => ['Registration', 'Tea & Networking', 'Company Introduction', $offer.' Product Overview', 'Group Photo']],
                    ],
                    'memory' => 'Personalized Welcome Photo',
                    'budget' => '₹8,000–15,000',
                ],
                [
                    'title' => 'Welcome Kit Distribution',
                    'objective' => 'Give every new customer a strong first impression.',
                    'blocks' => [
                        ['label' => 'Kit includes', 'items' => ['Branded Diary', 'Pen', 'Product Catalogue', 'Thank You Card']],
                    ],
                    'memory' => 'First Impression Kit',
                ],
                [
                    'title' => $workshop,
                    'objective' => 'Build trust with practical product knowledge.',
                    'blocks' => [
                        ['label' => 'Topics', 'items' => self::electricalTopics($product)],
                        ['label' => 'Audience', 'items' => ['Electricians', 'Panel Builders', 'Contractors']],
                    ],
                    'certificate' => true,
                ],
                [
                    'title' => 'One-Minute Games Day',
                    'objective' => 'Create a light, memorable experience with customers.',
                    'blocks' => [
                        ['label' => 'Fun activities', 'items' => ['Wire Roll Challenge', 'Nut & Bolt Race', 'Cable Identification Quiz']],
                    ],
                    'memory' => 'Branded Gifts',
                ],
                [
                    'title' => 'Open House',
                    'objective' => 'Increase trust by showing the warehouse and process.',
                    'blocks' => [
                        ['label' => 'Show customers', 'items' => ['Warehouse', 'Stock System', 'Dispatch Process', 'Quality Process']],
                    ],
                    'memory' => 'Process Walkthrough',
                ],
                [
                    'title' => 'Family Evening Picnic',
                    'objective' => 'Strengthen the relationship with the customer family.',
                    'blocks' => [
                        ['label' => 'Include', 'items' => ['Spouse', 'Children']],
                        ['label' => 'Activities', 'items' => ['Musical Chairs', 'Family Games', 'Dinner']],
                    ],
                    'memory' => 'Family Photo Frame',
                ],
                [
                    'title' => 'Contractor Appreciation Day',
                    'objective' => 'Honour loyal contractors and dealers.',
                    'blocks' => [
                        ['label' => 'Awards', 'items' => ['Loyal Customer', 'Fast Payment', 'Best Partner']],
                        ['label' => 'Gift', 'items' => ['Trophy', 'Certificate']],
                    ],
                ],
                [
                    'title' => 'Safety & Quality Workshop',
                    'objective' => 'Build understanding of safety and quality.',
                    'blocks' => [
                        ['label' => 'Topics', 'items' => ['Electrical Safety', 'PPE', 'Cable Selection', 'Load Calculation']],
                    ],
                    'memory' => 'Partner with brand experts',
                ],
                [
                    'title' => 'Sports & Outdoor Day',
                    'objective' => 'Build team spirit and informal bonding.',
                    'blocks' => [
                        ['label' => 'Games', 'items' => ['Cricket', 'Volleyball', 'Tug of War']],
                    ],
                ],
                [
                    'title' => 'Customer Service Day',
                    'objective' => 'Give warranty, technical support and product guidance in one place.',
                    'blocks' => [
                        ['label' => 'Special Desk', 'items' => ['Warranty Help', 'Technical Support', 'Product Guidance']],
                    ],
                ],
                [
                    'title' => 'Cultural Night',
                    'objective' => 'Build a cultural relationship with customers.',
                    'blocks' => [
                        ['label' => 'Programme', 'items' => ['Live Music', 'Garba', 'Talent Show']],
                    ],
                ],
                [
                    'title' => 'Customer Awards Night',
                    'objective' => 'Publicly recognise the best partners.',
                    'blocks' => [
                        ['label' => 'Categories', 'items' => ['Best Dealer', 'Best Contractor', 'Growth Partner']],
                    ],
                ],
                [
                    'title' => 'Annual Customer Celebration',
                    'objective' => 'Host the year grand customer event and new product launch.',
                    'blocks' => [
                        ['label' => 'Grand Event', 'items' => ['Dinner', 'Entertainment', 'New Product Launch']],
                    ],
                ],
                [
                    'title' => 'Thank You Evening',
                    'objective' => 'Personally thank close customers.',
                    'blocks' => [
                        ['label' => 'Gift', 'items' => ['Personalized Thank You Letter']],
                    ],
                    'memory' => 'Small intimate gathering',
                ],
                [
                    'title' => 'Memory Wall & Photo Day',
                    'objective' => 'Join the year memories into a strong memory wall.',
                    'blocks' => [
                        ['label' => 'Create', 'items' => ['Photo Booth', 'Customer Story Video', 'Memory Wall']],
                    ],
                ],
                [
                    'title' => 'Signature Customer Summit',
                    'objective' => 'Make this the largest annual industry event.',
                    'blocks' => [
                        ['label' => 'Include', 'items' => ['Brand Partners', 'Industry Experts', 'Product Showcase', 'Future Roadmap']],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private static function electricalTopics(string $product): array
    {
        $hay = strtolower($product);
        $topics = [];
        if (self::has($hay, ['mcb'])) {
            $topics[] = 'MCB';
        }
        if (self::has($hay, ['mccb'])) {
            $topics[] = 'MCCB';
        }
        if (self::has($hay, ['acb'])) {
            $topics[] = 'ACB';
        }
        if (self::has($hay, ['cable', 'kabel', 'wire'])) {
            $topics[] = 'Wires & Cables';
        }
        if (self::has($hay, ['switch'])) {
            $topics[] = 'Switchgears';
        }
        $topics = array_values(array_unique(array_merge($topics, ['Safety', 'Installation'])));

        return $topics !== [] ? $topics : ['Product Range', 'Safety', 'Installation'];
    }

    /**
     * @return array<string, mixed>
     */
    private static function jewelleryFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        return self::customiseFlavour([
            'business_type' => $cat !== '' ? $cat : 'Jewellery',
            'model' => 'B2C + B2B',
            'intro' => $intro !== '' ? $intro : $biz.' creates '.$product.' for personal clients and jewellery brands.',
            'customers' => ['Personal Clients', 'Jewellery Brands', 'Occasion Buyers', 'Repeat Families', 'Corporate Gifting'],
            'workshop' => 'Design-Led Collection Workshop',
            'topics' => ['Bespoke Design', 'Collections', $offer, 'Craftsmanship'],
            'audience' => ['Personal Clients', 'Brand Partners', 'Occasion Buyers'],
            'games' => ['Stone Identification Quiz', 'Design Sketch Relay', 'Occasion Match Game'],
            'open_house' => ['Showroom', 'Design Studio', 'Making Process', 'Quality Check'],
            'awards_day' => 'Client Appreciation Day',
            'award_names' => ['Loyal Client', 'Family Legacy', 'Brand Partner'],
            'quality' => ['Hallmark / Quality', 'Making Standards', 'Aftercare', 'Trust Ritual'],
            'service' => ['Resize & Repair Help', 'Design Guidance', 'Occasion Planning'],
            'award_night' => ['Best Family Client', 'Best Brand Partner', 'Growth Partner'],
            'summit' => ['Master Craftsmen', 'Brand Partners', 'Collection Showcase', 'Next Year Roadmap'],
        ], $biz, $offer);
    }

    /**
     * @return array<string, mixed>
     */
    private static function realEstateFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        return self::customiseFlavour([
            'business_type' => $cat !== '' ? $cat : 'Real Estate',
            'model' => 'B2C + B2B',
            'intro' => $intro !== '' ? $intro : $biz.' helps customers find the right '.$product.'.',
            'customers' => ['Home Buyers', 'Investors', 'Families', 'Business Occupiers', 'Channel Partners'],
            'workshop' => 'Property Guidance Workshop',
            'topics' => ['Site Selection', 'Legal Checklist', 'Investment Planning', $offer],
            'audience' => ['Home Buyers', 'Investors', 'Channel Partners'],
            'games' => ['Plan Reading Quiz', 'Budget Builder', 'Location Match'],
            'open_house' => ['Site Visit', 'Sample Flat / Office', 'Documentation Desk', 'Loan Desk'],
            'awards_day' => 'Channel Partner Appreciation Day',
            'award_names' => ['Top Closer', 'Trusted Partner', 'Fast Conversion'],
            'quality' => ['Title Clarity', 'Construction Quality', 'Customer Handover', 'After-Sales'],
            'service' => ['Site Help', 'Loan Guidance', 'Documentation Support'],
            'award_night' => ['Best Channel Partner', 'Best Investor Client', 'Growth Partner'],
            'summit' => ['Developers', 'Channel Partners', 'Project Showcase', 'Future Inventory'],
        ], $biz, $offer);
    }

    /**
     * @return array<string, mixed>
     */
    private static function educationFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        return self::customiseFlavour([
            'business_type' => $cat !== '' ? $cat : 'Education',
            'model' => 'B2C',
            'intro' => $intro !== '' ? $intro : $biz.' delivers '.$product.' for students and parents.',
            'customers' => ['Students', 'Parents', 'Working Professionals', 'Schools', 'Corporate Learners'],
            'workshop' => 'Learning Outcomes Workshop',
            'topics' => ['Curriculum', 'Career Path', 'Parent Guidance', $offer],
            'audience' => ['Students', 'Parents', 'Working Professionals'],
            'games' => ['Quiz Bowl', 'Skill Sprint', 'Team Puzzle'],
            'open_house' => ['Campus / Classroom', 'Lab / Studio', 'Counsellor Desk', 'Demo Class'],
            'awards_day' => 'Parent & Student Appreciation Day',
            'award_names' => ['Star Learner', 'Supportive Parent', 'Best Batch'],
            'quality' => ['Teaching Quality', 'Safety', 'Progress Review', 'Placement Support'],
            'service' => ['Admission Help', 'Progress Review', 'Career Guidance'],
            'award_night' => ['Best Student', 'Best Parent Partner', 'Growth Batch'],
            'summit' => ['Faculty', 'Alumni', 'Programme Showcase', 'Next Year Roadmap'],
        ], $biz, $offer);
    }

    /**
     * @return array<string, mixed>
     */
    private static function itFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        return self::customiseFlavour([
            'business_type' => $cat !== '' ? $cat : 'Information Technology',
            'model' => 'B2B',
            'intro' => $intro !== '' ? $intro : $biz.' builds practical '.$product.' for growing businesses.',
            'customers' => ['SME Owners', 'Operations Teams', 'IT Managers', 'Dealers', 'Enterprise Clients'],
            'workshop' => 'Digital Systems Workshop',
            'topics' => ['Process Mapping', 'Software Demo', 'Data Safety', $offer],
            'audience' => ['Business Owners', 'IT Managers', 'Operations Teams'],
            'games' => ['Process Puzzle', 'Speed Demo', 'Bug Hunt Quiz'],
            'open_house' => ['Office', 'Support Desk', 'Demo Lab', 'Delivery Process'],
            'awards_day' => 'Client Appreciation Day',
            'award_names' => ['Loyal Client', 'Fast Adopter', 'Best Partner'],
            'quality' => ['Uptime', 'Support SLA', 'Data Security', 'Implementation Quality'],
            'service' => ['Ticket Help', 'Training', 'Product Guidance'],
            'award_night' => ['Best Client', 'Best Implementation Partner', 'Growth Partner'],
            'summit' => ['Product Partners', 'Industry Experts', 'Roadmap Showcase', 'New Modules'],
        ], $biz, $offer);
    }

    /**
     * @return array<string, mixed>
     */
    private static function sweetsFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        return self::customiseFlavour([
            'business_type' => $cat !== '' ? $cat : 'Sweets & Farsan',
            'model' => 'B2C + B2B',
            'intro' => $intro !== '' ? $intro : $biz.' makes '.$product.' for families, gifting and celebrations.',
            'customers' => ['Family Customers', 'Gifting Buyers', 'Event Planners', 'Dealers', 'Corporate Clients'],
            'workshop' => 'Taste & Occasion Workshop',
            'topics' => ['Festival Range', 'Gifting Packs', 'Freshness', $offer],
            'audience' => ['Family Customers', 'Event Planners', 'Dealers'],
            'games' => ['Taste Identification', 'Packing Race', 'Festival Quiz'],
            'open_house' => ['Kitchen / Workshop', 'Packing Line', 'Hygiene Process', 'Dispatch'],
            'awards_day' => 'Dealer & Family Appreciation Day',
            'award_names' => ['Loyal Family', 'Festival Partner', 'Best Dealer'],
            'quality' => ['Hygiene', 'Freshness', 'Taste Standard', 'Packing Quality'],
            'service' => ['Bulk Order Help', 'Festival Booking', 'Gifting Guidance'],
            'award_night' => ['Best Dealer', 'Best Family Client', 'Growth Partner'],
            'summit' => ['Brand Partners', 'Dealers', 'New Range Showcase', 'Festival Roadmap'],
        ], $biz, $offer);
    }

    /**
     * @return array<string, mixed>
     */
    private static function genericFlavour(string $biz, string $cat, string $intro, string $product, string $offer): array
    {
        return self::customiseFlavour([
            'business_type' => $cat !== '' ? $cat : 'Business',
            'model' => 'B2B + B2C',
            'intro' => $intro !== '' ? $intro : $biz.' serves customers with '.$product.'.',
            'customers' => ['Retail Customers', 'Dealers', 'Repeat Clients', 'Institutional Buyers', 'Local Partners'],
            'workshop' => $offer.' Knowledge Workshop',
            'topics' => [$offer, 'Quality', 'Usage', 'After-Sales'],
            'audience' => ['Customers', 'Dealers', 'Partners'],
            'games' => ['Product Identification Quiz', 'Speed Pitch', 'Team Relay'],
            'open_house' => ['Office / Store', 'Stock System', 'Dispatch Process', 'Quality Process'],
            'awards_day' => 'Partner Appreciation Day',
            'award_names' => ['Loyal Customer', 'Fast Payment', 'Best Partner'],
            'quality' => ['Product Quality', 'Service Standard', 'Safety', 'Trust Ritual'],
            'service' => ['Warranty Help', 'Product Guidance', 'Repeat Order Support'],
            'award_night' => ['Best Dealer', 'Best Customer', 'Growth Partner'],
            'summit' => ['Brand Partners', 'Industry Experts', 'Product Showcase', 'Future Roadmap'],
        ], $biz, $offer);
    }

    /**
     * @param  array<string, mixed>  $flavour
     * @return array<string, mixed>
     */
    private static function customiseFlavour(array $flavour, string $biz, string $offer): array
    {
        return [
            'business_type' => $flavour['business_type'],
            'model' => $flavour['model'],
            'intro' => $flavour['intro'],
            'customers' => $flavour['customers'],
            'details' => [
                [
                    'title' => 'Customer Connect Meet',
                    'objective' => 'Build introduction with new and existing customers.',
                    'blocks' => [
                        ['label' => 'Programme Flow', 'items' => ['Registration', 'Tea & Networking', 'Company Introduction', $offer.' Overview', 'Group Photo']],
                    ],
                    'memory' => 'Personalized Welcome Photo',
                    'budget' => '₹8,000–15,000',
                ],
                [
                    'title' => 'Welcome Kit Distribution',
                    'objective' => 'Give every new customer a strong first impression.',
                    'blocks' => [
                        ['label' => 'Kit includes', 'items' => ['Branded Diary', 'Pen', 'Product Catalogue', 'Thank You Card']],
                    ],
                    'memory' => 'First Impression Kit',
                ],
                [
                    'title' => $flavour['workshop'],
                    'objective' => 'Build trust with practical product knowledge.',
                    'blocks' => [
                        ['label' => 'Topics', 'items' => $flavour['topics']],
                        ['label' => 'Audience', 'items' => $flavour['audience']],
                    ],
                    'certificate' => true,
                ],
                [
                    'title' => 'One-Minute Games Day',
                    'objective' => 'Create a light, memorable experience with customers.',
                    'blocks' => [
                        ['label' => 'Fun activities', 'items' => $flavour['games']],
                    ],
                    'memory' => 'Branded Gifts',
                ],
                [
                    'title' => 'Open House',
                    'objective' => 'Increase trust by showing how the work is done.',
                    'blocks' => [
                        ['label' => 'Show customers', 'items' => $flavour['open_house']],
                    ],
                    'memory' => 'Process Walkthrough',
                ],
                [
                    'title' => 'Family Evening Picnic',
                    'objective' => 'Strengthen the relationship with the customer family.',
                    'blocks' => [
                        ['label' => 'Include', 'items' => ['Spouse', 'Children']],
                        ['label' => 'Activities', 'items' => ['Musical Chairs', 'Family Games', 'Dinner']],
                    ],
                    'memory' => 'Family Photo Frame',
                ],
                [
                    'title' => $flavour['awards_day'],
                    'objective' => 'Honour loyal customers and partners.',
                    'blocks' => [
                        ['label' => 'Awards', 'items' => $flavour['award_names']],
                        ['label' => 'Gift', 'items' => ['Trophy', 'Certificate']],
                    ],
                ],
                [
                    'title' => 'Quality & Trust Workshop',
                    'objective' => 'Build understanding of quality and trust.',
                    'blocks' => [
                        ['label' => 'Topics', 'items' => $flavour['quality']],
                    ],
                ],
                [
                    'title' => 'Sports & Outdoor Day',
                    'objective' => 'Build team spirit and informal bonding.',
                    'blocks' => [
                        ['label' => 'Games', 'items' => ['Cricket', 'Volleyball', 'Tug of War']],
                    ],
                ],
                [
                    'title' => 'Customer Service Day',
                    'objective' => 'Give help, guidance and service in one place.',
                    'blocks' => [
                        ['label' => 'Special Desk', 'items' => $flavour['service']],
                    ],
                ],
                [
                    'title' => 'Cultural Night',
                    'objective' => 'Build a cultural relationship with customers.',
                    'blocks' => [
                        ['label' => 'Programme', 'items' => ['Live Music', 'Garba', 'Talent Show']],
                    ],
                ],
                [
                    'title' => 'Customer Awards Night',
                    'objective' => 'Publicly recognise the best partners.',
                    'blocks' => [
                        ['label' => 'Categories', 'items' => $flavour['award_night']],
                    ],
                ],
                [
                    'title' => 'Annual Customer Celebration',
                    'objective' => 'Host the year grand customer event and new offer launch.',
                    'blocks' => [
                        ['label' => 'Grand Event', 'items' => ['Dinner', 'Entertainment', 'New Product Launch']],
                    ],
                ],
                [
                    'title' => 'Thank You Evening',
                    'objective' => 'Personally thank close customers.',
                    'blocks' => [
                        ['label' => 'Gift', 'items' => ['Personalized Thank You Letter']],
                    ],
                    'memory' => 'Small intimate gathering',
                ],
                [
                    'title' => 'Memory Wall & Photo Day',
                    'objective' => 'Join the year memories into a strong memory wall.',
                    'blocks' => [
                        ['label' => 'Create', 'items' => ['Photo Booth', 'Customer Story Video', 'Memory Wall']],
                    ],
                ],
                [
                    'title' => 'Signature Customer Summit',
                    'objective' => 'Make this the largest annual relationship event.',
                    'blocks' => [
                        ['label' => 'Include', 'items' => $flavour['summit']],
                    ],
                ],
            ],
        ];
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
