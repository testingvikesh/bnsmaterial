<?php

namespace App\Support;

class MaterialWebsiteDraft
{
    public const VERIFIED = 'verified';

    public const INFERRED = 'inferred';

    public const SUGGESTED = 'suggested';

    public const REQUIRED = 'required';

    /**
     * @return array<string, string>
     */
    public static function titles(): array
    {
        return [
            'hero' => 'Hero Banner',
            'introduction' => 'Business Introduction',
            'purpose' => 'Purpose / Vision / Mission',
            'problem' => 'Customer Problem',
            'solution' => 'Our Solution',
            'why' => 'Why Choose Us',
            'products' => 'Products',
            'services' => 'Services',
            'signature' => 'Signature Product / Service',
            'pricing' => 'Pricing / Packages',
            'process' => 'How It Works',
            'navachar' => 'Business Navachar',
            'quality' => 'Quality System',
            'founder' => 'Founder',
            'team' => 'Team',
            'story' => 'Our Story',
            'portfolio' => 'Portfolio',
            'results' => 'Results & Achievements',
            'certifications' => 'Certifications & Awards',
            'clients' => 'Clients & Partners',
            'testimonials' => 'Testimonials',
            'reviews' => 'Reviews',
            'gallery' => 'Gallery',
            'videos' => 'Videos',
            'knowledge' => 'Knowledge Centre',
            'faq' => 'FAQ',
            'offers' => 'Offers / Promotions',
            'membership' => 'Membership / Loyalty',
            'experience' => 'Customer Experience',
            'cta' => 'CTA / Enquiry',
            'digital' => 'Digital Connect',
            'contact' => 'Contact Us',
        ];
    }

    /**
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public static function for(array $facts): array
    {
        $biz = self::val($facts, 'business_name') ?: 'this business';
        $cat = self::val($facts, 'category') ?: 'Business';
        $product = self::val($facts, 'products') ?: $cat;
        $intro = self::val($facts, 'intro');
        $services = self::val($facts, 'services') ?: $product;
        $city = self::val($facts, 'city') ?: self::val($facts, 'location');
        $address = self::val($facts, 'address');
        $phone = self::val($facts, 'phone');
        $member = self::val($facts, 'member_name');
        $family = MaterialTaglineMasterclass::family($cat, $product, $intro);
        $cta = self::ctaFor($family);
        $context = compact('biz', 'cat', 'product', 'intro', 'services', 'city', 'address', 'phone', 'member', 'family', 'cta') + ['facts' => $facts];

        $sections = [];
        foreach (array_keys(self::titles()) as $code) {
            $sections[] = self::section($code, $context);
        }

        $report = self::report($sections, $context);
        $seo = self::seo($context);
        $journey = self::journey($context, $sections);

        return [
            'business_name' => $biz,
            'member_name' => $member,
            'profile' => self::profile($context),
            'sections' => $sections,
            'seo' => $seo,
            'journey' => $journey,
            'report' => $report,
            'actions' => [
                ['code' => 'approve', 'label' => 'Approve', 'icon' => '✓'],
                ['code' => 'edit', 'label' => 'Edit', 'icon' => '✏️'],
                ['code' => 'regenerate', 'label' => 'Regenerate', 'icon' => '🔄'],
                ['code' => 'add', 'label' => 'Add Information', 'icon' => '📌'],
                ['code' => 'publish', 'label' => 'Publish', 'icon' => '🚀'],
            ],
        ];
    }

    /**
     * Overlay OpenAI draft onto the local 32-section plan without inventing locked facts.
     *
     * @param  array<string, mixed>  $local
     * @param  array<string, mixed>  $api
     * @return array<string, mixed>
     */
    public static function merge(array $local, array $api): array
    {
        $locked = ['pricing', 'founder', 'team', 'story', 'portfolio', 'results', 'certifications', 'clients', 'testimonials', 'reviews', 'offers'];
        $localSections = is_array($local['sections'] ?? null) ? $local['sections'] : [];
        $apiSections = is_array($api['sections'] ?? null) ? $api['sections'] : [];
        $apiByCode = [];
        foreach ($apiSections as $section) {
            if (! is_array($section)) {
                continue;
            }
            $code = (string) ($section['code'] ?? '');
            if ($code !== '') {
                $apiByCode[$code] = $section;
            }
        }

        $merged = [];
        foreach ($localSections as $index => $section) {
            $code = (string) ($section['code'] ?? '');
            $fromApi = $apiByCode[$code] ?? (is_array($apiSections[$index] ?? null) ? $apiSections[$index] : []);
            if ($fromApi === [] || in_array($code, $locked, true)) {
                $merged[] = $section;
                continue;
            }
            $merged[] = self::mergeSection($section, $fromApi);
        }

        $local['sections'] = $merged;
        if (is_array($api['seo'] ?? null) && $api['seo'] !== []) {
            $local['seo'] = array_merge($local['seo'] ?? [], $api['seo']);
        }
        $local['report'] = self::report($merged, [
            'biz' => $local['business_name'] ?? '',
            'facts' => [],
        ]);

        return $local;
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private static function profile(array $context): array
    {
        $facts = $context['facts'];

        return [
            'business_name' => self::field($context['biz'], self::VERIFIED),
            'business_category' => self::field($context['cat'], self::has($context['cat']) ? self::VERIFIED : self::REQUIRED),
            'sub_category' => self::field(self::subCategory($context), self::INFERRED),
            'business_type' => self::field(self::val($facts, 'business_type') ?: self::typeGuess($context), self::val($facts, 'business_type') ? self::VERIFIED : self::INFERRED),
            'industry' => self::field($context['cat'], self::INFERRED),
            'target_customer' => self::field(self::targetCustomer($context), self::INFERRED),
            'location' => self::field($context['city'] ?: 'Location information required', $context['city'] !== '' ? self::VERIFIED : self::REQUIRED),
            'business_description' => self::field($context['intro'] ?: $context['biz'].' offers '.$context['product'].' for customers who want a trusted '.$context['cat'].' partner.', $context['intro'] !== '' ? self::VERIFIED : self::INFERRED),
            'main_product' => self::field($context['product'], self::has($context['product']) ? self::VERIFIED : self::REQUIRED),
            'main_services' => self::field($context['services'], self::has($context['services']) ? self::VERIFIED : self::INFERRED),
            'related_products' => self::field(self::relatedOffer($context, 'product'), self::SUGGESTED),
            'related_services' => self::field(self::relatedOffer($context, 'service'), self::SUGGESTED),
            'business_positioning' => self::field($context['biz'].' as a customer-first '.$context['cat'].' brand for '.$context['product'].'.', self::INFERRED),
            'customer_need' => self::field(self::needLine($context), self::INFERRED),
            'market_context' => self::field(($context['city'] !== '' ? $context['city'] : 'Local').' '.$context['cat'].' customers looking for reliable '.$context['product'].'.', self::INFERRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private static function section(string $code, array $context): array
    {
        $titles = self::titles();
        $index = array_search($code, array_keys($titles), true);
        $blocks = match ($code) {
            'hero' => self::hero($context),
            'introduction' => self::introduction($context),
            'purpose' => self::purpose($context),
            'problem' => self::problem($context),
            'solution' => self::solution($context),
            'why' => self::why($context),
            'products' => self::products($context),
            'services' => self::services($context),
            'signature' => [
                self::block('Flagship offering', $context['product'], self::has($context['product']) ? self::VERIFIED : self::REQUIRED),
                self::block('How to present it', $context['biz'].' presents '.$context['product'].' as the signature reason customers choose this brand.', self::INFERRED),
            ],
            'pricing' => [
                self::block('Pricing', 'Contact us for latest pricing / Get a Quote', self::SUGGESTED),
                self::block('Note', 'Public prices were not provided. No price has been invented.', self::REQUIRED),
            ],
            'process' => self::process($context),
            'navachar' => self::navachar($context),
            'quality' => [
                self::block('Quality promise', $context['biz'].' keeps '.$context['product'].' simple, reliable and customer-friendly.', self::INFERRED),
                self::block('Warranty / certification', 'Verified warranty, ISO or safety certification is required before this claim can be published.', self::REQUIRED),
            ],
            'founder' => [
                self::block('Founder', $context['member'] !== '' ? $context['member'] : 'Founder Information Required', $context['member'] !== '' ? self::VERIFIED : self::REQUIRED),
                self::block('Background / experience / vision', 'Founder Information Required. Do not invent experience, education or awards.', self::REQUIRED),
            ],
            'team' => [
                self::block('Team structure', 'Show the customer-facing team once names and roles are confirmed.', self::SUGGESTED),
                self::block('People', 'Individual names have not been invented.', self::REQUIRED),
            ],
            'story' => self::story($context),
            'portfolio' => [
                self::block('Portfolio', 'Add real projects, collections or case studies. None have been invented.', self::REQUIRED),
            ],
            'results' => [
                self::block('Results', 'No revenue, customer-count or achievement numbers have been invented.', self::REQUIRED),
            ],
            'certifications' => [
                self::block('Certifications & awards', 'Show only publicly verified certificates or awards.', self::REQUIRED),
            ],
            'clients' => [
                self::block('Clients & partners', 'List only clients or partners who can be publicly named.', self::REQUIRED),
            ],
            'testimonials' => [
                self::block('Testimonials', 'Fake testimonials must never be generated. Add real customer quotes when available.', self::REQUIRED),
            ],
            'reviews' => [
                self::block('Reviews', 'Public review summary can be added when Google / platform reviews are confirmed.', self::REQUIRED),
            ],
            'gallery' => [
                self::block('Image categories', implode(' • ', self::galleryTopics($context)), self::SUGGESTED),
            ],
            'videos' => [
                self::block('Video topics', implode(' • ', self::videoTopics($context)), self::SUGGESTED),
                self::block('Script direction', 'Keep videos short: who you are, what you offer, how to enquire.', self::SUGGESTED),
            ],
            'knowledge' => self::knowledge($context),
            'faq' => self::faq($context),
            'offers' => [
                self::block('Offers', 'No public offer was provided. Fake discounts have not been created.', self::REQUIRED),
            ],
            'membership' => [
                self::block('Loyalty idea', 'A simple repeat-customer club for '.$context['product'].' can be added if it fits the business model.', self::SUGGESTED),
            ],
            'experience' => [
                self::block('Enquiry', 'Customer contacts '.$context['biz'].' by phone, WhatsApp or form.', self::INFERRED),
                self::block('Purchase / booking', 'Confirm requirement, share options, close the '.$context['product'].' order.', self::INFERRED),
                self::block('Delivery / support', 'Deliver the offer, follow up, and collect feedback.', self::INFERRED),
                self::block('Repeat customer', 'Stay in touch with useful updates instead of only selling.', self::SUGGESTED),
            ],
            'cta' => [
                self::block('Primary CTA', (string) $context['cta'], self::INFERRED),
                self::block('Supporting CTAs', 'WhatsApp Now • Call Now • Enquire Now', self::SUGGESTED),
            ],
            'digital' => self::digital($context),
            'contact' => self::contact($context),
            default => [self::block('Draft', $context['biz'].' '.$titles[$code], self::INFERRED)],
        };

        return [
            'no' => str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT),
            'code' => $code,
            'title' => $titles[$code],
            'status' => self::sectionStatus($blocks),
            'blocks' => $blocks,
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function hero(array $context): array
    {
        $place = $context['city'] !== '' ? ' in '.$context['city'] : '';

        return [
            self::block('Headline', $context['biz'], self::VERIFIED),
            self::block('Subheadline', $context['cat'].' for '.$context['product'].$place, self::INFERRED),
            self::block('Tagline', self::taglineFor($context), self::INFERRED),
            self::block('USP', self::uspFor($context), self::INFERRED),
            self::block('CTA', $context['cta'], self::INFERRED),
            self::block('Hero image concept', 'A clean photograph of '.$context['product'].' with the '.$context['biz'].' name and one clear '.$context['cta'].' button.', self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function introduction(array $context): array
    {
        $points = MaterialCopyPoints::from($context['intro']);
        $overview = $points !== [] ? implode(' ', array_slice($points, 0, 2)) : $context['biz'].' is a '.$context['cat'].' business offering '.$context['product'].'.';

        return [
            self::block('Who we are', $context['biz'].($context['city'] !== '' ? ', '.$context['city'] : '').' — '.$context['cat'], $context['intro'] !== '' ? self::VERIFIED : self::INFERRED),
            self::block('What we do', $context['product'], self::has($context['product']) ? self::VERIFIED : self::REQUIRED),
            self::block('Business overview', $overview, $context['intro'] !== '' ? self::VERIFIED : self::INFERRED),
            self::block('Key highlights', $context['product'].' • Customer-first process • Clear next step to '.$context['cta'], self::INFERRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function purpose(array $context): array
    {
        return [
            self::block('Purpose', 'Help customers choose '.$context['product'].' with clarity and trust.', self::INFERRED),
            self::block('Vision', 'Become the remembered '.$context['cat'].' name'.($context['city'] !== '' ? ' in '.$context['city'] : '').'.', self::INFERRED),
            self::block('Mission', 'Deliver '.$context['product'].' that is simple to understand, easy to buy and reliable after purchase.', self::INFERRED),
            self::block('Values', 'Clarity, craftsmanship of service, honesty of claims, and long-term customer care.', self::INFERRED),
            self::block('Future direction', 'Strengthen digital enquiry, repeat customers and one signature '.$context['product'].' offer.', self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function problem(array $context): array
    {
        $lines = match ($context['family']) {
            'jewellery' => ['Unclear design direction', 'Jewellery that follows trends instead of meaning', 'Difficulty briefing a maker', 'Worry about finish, fit and after-care'],
            'real_estate' => ['Confusing options', 'Trust gap in paperwork', 'Slow response', 'Unclear next step'],
            'education' => ['Unclear outcomes', 'Too many similar courses', 'Weak counselling', 'No simple enrolment path'],
            'it' => ['Scattered digital tools', 'Slow delivery', 'Unclear scope', 'Weak after-support'],
            'sweets' => ['Inconsistent freshness', 'Last-minute gifting stress', 'Unclear custom-order process'],
            default => ['Too many similar options', 'Unclear pricing process', 'Slow replies', 'Low trust before the first order'],
        };

        return [
            self::block('Problems', implode(' • ', $lines), self::INFERRED),
            self::block('Needs', 'A clear offer, honest process and one easy way to enquire.', self::INFERRED),
            self::block('Expectations', 'Customers expect '.$context['biz'].' to explain '.$context['product'].' simply and respond quickly.', self::INFERRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function solution(array $context): array
    {
        return [
            self::block('Solution', $context['biz'].' uses '.$context['product'].' to remove guesswork: understand the need, show a clear option, and complete the enquiry with a human conversation.', self::INFERRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function why(array $context): array
    {
        return [
            self::block('Why choose us', self::uspFor($context), self::INFERRED),
            self::block('Proof still needed', 'Add verified work samples, reviews or process photos before calling this a proven USP.', self::REQUIRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function products(array $context): array
    {
        $points = MaterialCopyPoints::from($context['product']);
        $list = $points !== [] ? $points : [$context['product']];

        return [
            self::block('Product category', $context['cat'], self::VERIFIED),
            self::block('Main products', implode("\n", $list), self::has($context['product']) ? self::VERIFIED : self::REQUIRED),
            self::block('Benefits', 'Customers get a clear '.$context['product'].' option, guided selection and a simple next step.', self::INFERRED),
            self::block('Use cases', self::useCases($context), self::INFERRED),
            self::block('Related products', self::relatedOffer($context, 'product'), self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function services(array $context): array
    {
        $points = MaterialCopyPoints::from($context['services']);

        return [
            self::block('Services', $points !== [] ? implode("\n", $points) : $context['services'], self::has($context['services']) ? self::VERIFIED : self::INFERRED),
            self::block('Related services', self::relatedOffer($context, 'service'), self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function process(array $context): array
    {
        $steps = match ($context['family']) {
            'jewellery' => ['Share the story, occasion or brief', 'Review design direction', 'Confirm material, finish and timeline', 'Craft, trial and deliver'],
            'real_estate' => ['Share requirement', 'Shortlist options', 'Visit / review papers', 'Close with a clear next step'],
            default => ['Tell us the requirement', 'See a clear option', 'Confirm details and quote', 'Deliver and follow up'],
        };

        return [
            self::block('Step 1', $steps[0], self::INFERRED),
            self::block('Step 2', $steps[1], self::INFERRED),
            self::block('Step 3', $steps[2], self::INFERRED),
            self::block('Step 4', $steps[3], self::INFERRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function navachar(array $context): array
    {
        return [
            self::block('Product innovation', 'Potential Navachar Opportunity: a named signature '.$context['product'].' collection or package.', self::SUGGESTED),
            self::block('Service innovation', 'Potential Navachar Opportunity: consultative briefing before the sale.', self::SUGGESTED),
            self::block('Technology / digitalisation', 'Potential Navachar Opportunity: WhatsApp catalogue + website enquiry form.', self::SUGGESTED),
            self::block('Customer experience', 'Potential Navachar Opportunity: one tracked follow-up after every enquiry.', self::SUGGESTED),
            self::block('Process / business model', 'Potential Navachar Opportunity: appointment-led selling instead of only walk-in.', self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function story(array $context): array
    {
        if ($context['intro'] !== '') {
            $points = MaterialCopyPoints::from($context['intro']);

            return [
                self::block('Story from business introduction', $points !== [] ? implode("\n", $points) : $context['intro'], self::VERIFIED),
                self::block('Timeline / founding year', 'Business Story Information Required. Dates and history have not been invented.', self::REQUIRED),
            ];
        }

        return [
            self::block('Our story', 'Business Story Information Required', self::REQUIRED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function knowledge(array $context): array
    {
        $offer = $context['product'];

        return [
            self::block('10 blog topics', implode("\n", [
                'How to choose '.$offer,
                'Common mistakes when buying '.$offer,
                'What to ask before you confirm',
                'Care and after-use tips',
                $context['cat'].' buying guide',
                'How a first consultation works',
                'How to brief '.$context['biz'],
                'Local guide'.($context['city'] !== '' ? ' for '.$context['city'] : ''),
                'When to upgrade or repeat',
                'Questions customers ask most',
            ]), self::SUGGESTED),
            self::block('10 customer education topics', implode("\n", [
                'Quality checklist',
                'Timeline expectations',
                'What affects price',
                'How custom work is done',
                'How to share references',
                'After-care basics',
                'When a quote is needed',
                'How to compare options fairly',
                'What happens after you enquire',
                'How to stay in touch',
            ]), self::SUGGESTED),
            self::block('10 FAQ topics', implode("\n", [
                'What does '.$context['biz'].' offer?',
                'Who is it for?',
                'How do I start?',
                'Do you work on appointment?',
                'How long does it take?',
                'How do I get a quote?',
                'What information should I share?',
                'Do you deliver / visit?',
                'How do I contact you?',
                'What happens after the first meeting?',
            ]), self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function faq(array $context): array
    {
        return [
            self::block('What do you offer?', $context['biz'].' offers '.$context['product'].'.', self::INFERRED),
            self::block('Who is it for?', self::targetCustomer($context), self::INFERRED),
            self::block('How do I start?', 'Use '.$context['cta'].' or WhatsApp with your requirement.', self::INFERRED),
            self::block('How much does it cost?', 'Contact us for latest pricing / Get a Quote. No price has been invented.', self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function digital(array $context): array
    {
        $facts = $context['facts'];
        $rows = [
            self::block('Phone / WhatsApp', $context['phone'] !== '' ? $context['phone'] : 'Contact number required', $context['phone'] !== '' ? self::VERIFIED : self::REQUIRED),
            self::block('Website', self::val($facts, 'website') !== '' ? self::val($facts, 'website') : 'Website URL required', self::val($facts, 'website') !== '' ? self::VERIFIED : self::REQUIRED),
        ];
        foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'google_business' => 'Google Business'] as $key => $label) {
            $value = self::val($facts, $key);
            $rows[] = self::block($label, $value !== '' ? $value : $label.' not provided', $value !== '' ? self::VERIFIED : self::REQUIRED);
        }

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<array<string, string>>
     */
    private static function contact(array $context): array
    {
        return [
            self::block('Address', $context['address'] !== '' ? $context['address'] : 'Address required', $context['address'] !== '' ? self::VERIFIED : self::REQUIRED),
            self::block('Phone', $context['phone'] !== '' ? $context['phone'] : 'Phone required', $context['phone'] !== '' ? self::VERIFIED : self::REQUIRED),
            self::block('Email', 'Email required', self::REQUIRED),
            self::block('Business hours', 'Business hours required', self::REQUIRED),
            self::block('Map location', $context['city'] !== '' ? $context['city'] : 'Map location required', $context['city'] !== '' ? self::INFERRED : self::REQUIRED),
            self::block('Contact form', 'Name, phone, requirement, preferred time — submit to '.$context['cta'].'.', self::SUGGESTED),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, string>
     */
    private static function seo(array $context): array
    {
        $place = $context['city'] !== '' ? ' '.$context['city'] : '';
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $context['biz']) ?? 'business', '-'));

        return [
            'primary_keyword' => $context['product'].$place,
            'secondary_keywords' => $context['cat'].', '.$context['product'].', '.$context['biz'],
            'local_keywords' => $context['cat'].$place,
            'seo_title' => $context['biz'].' | '.$context['product'].$place,
            'meta_description' => $context['biz'].' offers '.$context['product'].($place !== '' ? ' in'.$place : '').'. Enquire for a clear next step.',
            'h1' => $context['biz'],
            'h2' => $context['product'].' for customers who want a trusted '.$context['cat'].' partner',
            'faq_keywords' => $context['product'].' price, '.$context['product'].' process, '.$context['biz'].' contact',
            'image_alt' => $context['biz'].' '.$context['product'].' '.$context['cat'],
            'url_slug' => $slug,
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @param  list<array<string, mixed>>  $sections
     * @return list<array<string, string>>
     */
    private static function journey(array $context, array $sections): array
    {
        $byCode = [];
        foreach ($sections as $section) {
            $byCode[$section['code']] = $section['status'];
        }

        $checks = [
            ['Who are you?', 'introduction'],
            ['What do you offer?', 'products'],
            ['Who is it for?', 'problem'],
            ['What problem do you solve?', 'solution'],
            ['Why should the customer consider you?', 'why'],
            ['What is your USP?', 'hero'],
            ['What product/service is available?', 'signature'],
            ['How much does it cost?', 'pricing'],
            ['How does it work?', 'process'],
            ['Can I trust you?', 'quality'],
            ['What proof do you have?', 'testimonials'],
            ['How can I contact / buy / book?', 'contact'],
        ];

        $out = [];
        foreach ($checks as [$question, $code]) {
            $status = $byCode[$code] ?? self::REQUIRED;
            $out[] = [
                'question' => $question,
                'status' => $status,
                'answer' => $status === self::REQUIRED ? 'Pending — add verified information' : 'Covered in the draft',
            ];
        }

        return $out;
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private static function report(array $sections, array $context): array
    {
        $ready = 0;
        foreach ($sections as $section) {
            if (($section['status'] ?? '') !== self::REQUIRED) {
                $ready++;
            }
        }
        $total = max(1, count($sections));
        $percent = (int) round(($ready / $total) * 100);

        return [
            'business_profile' => self::has((string) ($context['biz'] ?? '')) ? '100%' : 'Pending',
            'content' => $ready.'/'.$total.' Sections',
            'usp' => 'Ready',
            'product' => self::has((string) ($context['facts']['products'] ?? $context['product'] ?? '')) ? 'Ready' : 'Pending',
            'trust' => 'Pending',
            'seo' => 'Ready',
            'contact' => self::has((string) ($context['facts']['phone'] ?? $context['phone'] ?? '')) ? 'Ready' : 'Pending',
            'overall' => $percent.'%',
            'ready_sections' => $ready,
            'total_sections' => $total,
        ];
    }

    /**
     * @param  array<string, mixed>  $local
     * @param  array<string, mixed>  $api
     * @return array<string, mixed>
     */
    private static function mergeSection(array $local, array $api): array
    {
        $localBlocks = is_array($local['blocks'] ?? null) ? $local['blocks'] : [];
        $apiBlocks = is_array($api['blocks'] ?? null) ? $api['blocks'] : [];
        $blocks = [];
        foreach ($localBlocks as $index => $block) {
            $fromApi = is_array($apiBlocks[$index] ?? null) ? $apiBlocks[$index] : [];
            $text = trim((string) ($fromApi['text'] ?? $block['text'] ?? ''));
            $blocks[] = [
                'label' => (string) ($fromApi['label'] ?? $block['label'] ?? ''),
                'text' => $text !== '' ? $text : (string) ($block['text'] ?? ''),
                'status' => (string) ($fromApi['status'] ?? $block['status'] ?? self::INFERRED),
            ];
        }
        if ($blocks === [] && $apiBlocks !== []) {
            foreach ($apiBlocks as $block) {
                if (is_array($block)) {
                    $blocks[] = [
                        'label' => (string) ($block['label'] ?? ''),
                        'text' => (string) ($block['text'] ?? ''),
                        'status' => (string) ($block['status'] ?? self::INFERRED),
                    ];
                }
            }
        }

        $local['blocks'] = $blocks;
        $local['status'] = self::sectionStatus($blocks);
        if (isset($api['title']) && trim((string) $api['title']) !== '') {
            $local['title'] = (string) $api['title'];
        }

        return $local;
    }

    /**
     * @param  list<array<string, string>>  $blocks
     */
    private static function sectionStatus(array $blocks): string
    {
        $statuses = array_column($blocks, 'status');
        if (in_array(self::REQUIRED, $statuses, true) && ! in_array(self::VERIFIED, $statuses, true) && ! in_array(self::INFERRED, $statuses, true)) {
            return self::REQUIRED;
        }
        if (in_array(self::VERIFIED, $statuses, true)) {
            return self::VERIFIED;
        }
        if (in_array(self::INFERRED, $statuses, true)) {
            return self::INFERRED;
        }
        if (in_array(self::SUGGESTED, $statuses, true)) {
            return self::SUGGESTED;
        }

        return self::REQUIRED;
    }

    /**
     * @return array<string, string>
     */
    private static function block(string $label, string $text, string $status): array
    {
        return [
            'label' => $label,
            'text' => $text,
            'status' => $status,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function field(string $text, string $status): array
    {
        return self::block('', $text, $status);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function taglineFor(array $context): string
    {
        return match ($context['family']) {
            'jewellery' => 'Jewellery that tells your story',
            'real_estate' => 'Find the space that fits your next chapter',
            'education' => 'Learn today, lead tomorrow',
            'it' => 'Technology that moves the business',
            'sweets' => 'Sweetness made for every celebration',
            default => $context['product'].' you can trust',
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function uspFor(array $context): string
    {
        if ($context['intro'] !== '') {
            $points = MaterialCopyPoints::from($context['intro']);
            foreach ($points as $point) {
                if (preg_match('/\b(specialis|story|bespoke|design|trust|quality|custom)\w*/i', $point)) {
                    return $point;
                }
            }
        }

        return $context['biz'].' makes '.$context['product'].' easy to understand and easy to start.';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function ctaFor(string $family): string
    {
        return match ($family) {
            'jewellery' => 'Enquire Now',
            'real_estate' => 'Schedule Consultation',
            'education' => 'Enquire Now',
            'it' => 'Get Quote',
            'sweets' => 'Order Now',
            default => 'Enquire Now',
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function targetCustomer(array $context): string
    {
        return match ($context['family']) {
            'jewellery' => 'People who want personal, design-led jewellery and brands that need jewellery design support',
            'real_estate' => 'Families and businesses looking for the right space',
            'education' => 'Students and parents who want clear learning outcomes',
            'it' => 'Businesses that need practical digital systems',
            default => 'Customers who need '.$context['product'].' with a trusted local partner',
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function needLine(array $context): string
    {
        return 'A clear, trustworthy way to choose '.$context['product'].' without confusion.';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function useCases(array $context): string
    {
        return match ($context['family']) {
            'jewellery' => 'Personal gifts, occasions, everyday wear, and brand collections',
            'real_estate' => 'Home search, investment, commercial occupancy',
            default => 'First-time buyers, repeat customers and people comparing '.$context['product'].' options',
        };
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<string>
     */
    private static function galleryTopics(array $context): array
    {
        return [
            $context['product'].' close-ups',
            'Workshop / process',
            'Customer meeting',
            ($context['city'] !== '' ? $context['city'].' ' : '').'location',
            'Before / after or finished work',
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return list<string>
     */
    private static function videoTopics(array $context): array
    {
        return [
            '60-second brand intro',
            'How '.$context['product'].' is selected',
            'A real process walk-through',
            'How to enquire on WhatsApp',
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function relatedOffer(array $context, string $kind): string
    {
        $base = $context['product'];

        return $kind === 'service'
            ? 'Consultation, customisation, after-care and repeat-order support around '.$base
            : 'Related '.$context['cat'].' options that sit next to '.$base;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function subCategory(array $context): string
    {
        return match ($context['family']) {
            'jewellery' => 'Fine jewellery / design studio',
            'real_estate' => 'Property advisory',
            'education' => 'Learning services',
            'it' => 'Digital solutions',
            'sweets' => 'Indian sweets & gifting',
            default => $context['cat'],
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function typeGuess(array $context): string
    {
        return match ($context['family']) {
            'jewellery', 'sweets' => 'Product + service studio',
            'it', 'education' => 'Service business',
            default => 'Local business',
        };
    }

    /**
     * @param  array<string, string>  $facts
     */
    private static function val(array $facts, string $key): string
    {
        $value = trim((string) ($facts[$key] ?? ''));
        if ($value === '' || $value === '—') {
            return '';
        }

        return $value;
    }

    private static function has(string $value): bool
    {
        $value = trim($value);

        return $value !== '' && $value !== '—' && ! str_contains(strtolower($value), 'required');
    }
}
