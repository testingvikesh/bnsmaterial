<?php

namespace Tests\Unit;

use App\Models\ManageSession;
use App\Models\SessionPrompt;
use App\Support\MaterialReverseManagement;
use App\Support\MaterialSessionFormat;
use PHPUnit\Framework\TestCase;

class MaterialSessionFormatTest extends TestCase
{
    public function test_resolves_one_to_25_empire_and_reverse_from_session_name(): void
    {
        $one = new ManageSession(['name' => 'One To 25 Business']);
        $empire = new ManageSession(['name' => 'Business Vision']);
        $reverse = new ManageSession(['name' => 'Reverse Management Session']);
        $tagline = new ManageSession(['name' => 'BNS Tagline Masterclass']);
        $website = new ManageSession(['name' => '32 Gun']);
        $prompt = new SessionPrompt(['title' => 'Plan', 'body' => 'Create the worksheet.']);

        $this->assertSame(MaterialSessionFormat::ONE_TO_25, MaterialSessionFormat::resolve($one, $prompt));
        $this->assertSame(MaterialSessionFormat::EMPIRE, MaterialSessionFormat::resolve($empire, $prompt));
        $this->assertSame(MaterialSessionFormat::REVERSE, MaterialSessionFormat::resolve($reverse, $prompt));
        $this->assertSame(MaterialSessionFormat::TAGLINE, MaterialSessionFormat::resolve($tagline, $prompt));
        $this->assertSame(MaterialSessionFormat::WEBSITE, MaterialSessionFormat::resolve($website, $prompt));
        $sanskar = new ManageSession(['name' => '16 Saskar']);
        $this->assertSame(MaterialSessionFormat::SANSKAR, MaterialSessionFormat::resolve($sanskar, $prompt));
        $company = new ManageSession(['name' => 'PVT LTD Session']);
        $this->assertSame(MaterialSessionFormat::COMPANY, MaterialSessionFormat::resolve($company, $prompt));
        $part2 = new ManageSession(['name' => 'PVT LTD SESSION (PART2)']);
        $this->assertSame(MaterialSessionFormat::COMPANY_PART2, MaterialSessionFormat::resolve($part2, $prompt));
    }

    public function test_pvt_ltd_session_wins_even_if_prompt_mentions_business_empire(): void
    {
        $session = new ManageSession(['name' => 'PVT LTD', 'details' => 'Private Limited Company']);
        $prompt = new SessionPrompt([
            'title' => 'PVT LTD Details',
            'body' => 'From Business House to Business Empire comparison for students.',
        ]);

        $this->assertSame(MaterialSessionFormat::COMPANY, MaterialSessionFormat::resolve($session, $prompt));
    }

    public function test_company_structure_has_fifty_points_and_no_circle_time(): void
    {
        $plan = \App\Support\MaterialCompanyStructure::for([
            'business_name' => 'Cadworld Infoways',
            'member_name' => 'Alice',
            'category' => 'Trading',
        ]);

        $this->assertCount(50, $plan['points']);
        $this->assertCount(5, $plan['entities']);
        $this->assertCount(5, $plan['stages']);
        $this->assertCount(10, $plan['questions']);
        $this->assertSame('Private Limited Company', $plan['entities'][3]['title']);
        $this->assertSame('Cadworld Infoways', $plan['business_name']);
        $json = json_encode($plan);
        $this->assertStringNotContainsString('Circle Time', $json);
        $this->assertStringNotContainsString('Circle Activity', $json);

        $packs = \App\Support\MaterialCompanyI18n::packs('Cadworld Infoways', $plan);
        $this->assertSame(['en', 'gu', 'hi', 'mr'], array_keys($packs));
        $this->assertSame('એકમાલિકી', $packs['gu']['copy']['headers'][0]);
        $this->assertSame('एकल स्वामित्व', $packs['hi']['copy']['headers'][0]);
        $this->assertSame('एकमालकी', $packs['mr']['copy']['headers'][0]);
        $this->assertSame('LLP', $packs['gu']['copy']['headers'][2]);
    }

    public function test_company_structure_part_two_has_points_101_to_200(): void
    {
        $plan = \App\Support\MaterialCompanyStructurePart2::for([
            'business_name' => 'Cadworld Infoways',
        ]);

        $this->assertCount(50, $plan['master']);
        $this->assertSame(101, $plan['master'][0]['no']);
        $this->assertSame(150, $plan['master'][49]['no']);
        $this->assertCount(20, $plan['finance']);
        $this->assertSame(151, $plan['finance'][0]['no']);
        $this->assertCount(10, $plan['legal']);
        $this->assertCount(10, $plan['management']);
        $this->assertCount(10, $plan['growth']);
        $this->assertSame(200, $plan['growth'][9]['no']);
        $this->assertCount(15, $plan['decisions']);
        $this->assertStringContainsString('Capital Market', $plan['formula']);

        $packs = \App\Support\MaterialCompanyI18n::packs('Cadworld Infoways', $plan);
        $this->assertSame('એકમાલિકી', $packs['gu']['copy']['headers'][0]);
        $this->assertSame('પ્રાઇવેટ લિમિટેડ', $packs['gu']['copy']['headers'][3]);
        $this->assertNotEmpty($packs['gu']['copy']['master'][0]['point']);
    }

    public function test_sixteen_sanskar_name_wins_even_if_prompt_mentions_tagline(): void
    {
        $session = new ManageSession(['name' => '16 Sanskar', 'details' => 'Customer Relationship Calendar']);
        $prompt = new SessionPrompt([
            'title' => '16 Sanskar Details',
            'body' => 'Cadworld Infoways Customer 16 Sanskar Relationship Plan with a Hero Tagline mention.',
        ]);

        $this->assertSame(MaterialSessionFormat::SANSKAR, MaterialSessionFormat::resolve($session, $prompt));
    }

    public function test_website_prompt_with_hero_tagline_does_not_become_tagline_session(): void
    {
        $session = new ManageSession(['name' => '32 Gun', 'details' => '32 Gun']);
        $prompt = new SessionPrompt([
            'title' => '32 Gun Details',
            'body' => "Complete Business Website Draft\n### 01. HERO BANNER\n* Headline\n* Subheadline\n* Tagline\n* USP",
        ]);

        $this->assertSame(MaterialSessionFormat::WEBSITE, MaterialSessionFormat::resolve($session, $prompt));
    }

    public function test_jewellery_plan_has_seven_markets_and_auto_revenue(): void
    {
        $plan = MaterialReverseManagement::for([
            'business_name' => 'ABC Jewellery',
            'category' => 'Jewellery',
            'intro' => 'Gold & Diamond Jewellery Retail Business',
            'products' => 'Gold & Diamond Jewellery',
        ]);

        $this->assertSame('jewellery', $plan['family']);
        $this->assertSame('Customers', $plan['unit_label']);
        $this->assertCount(7, $plan['markets']);
        $this->assertSame('Gold Upgrade Offer', $plan['markets'][0]['offer']);
        $this->assertSame(40, $plan['markets'][0]['customers']);
        $this->assertSame(2000000, $plan['markets'][0]['revenue']);
        $this->assertSame(10000000, $plan['desired_turnover']);
        $this->assertSame(9000000, $plan['planned_revenue']);
        $this->assertSame(1000000, $plan['gap']);
        $this->assertSame(90, $plan['achievement']);
        $this->assertArrayHasKey('languages', $plan);
        $this->assertArrayHasKey('en', $plan['languages']);
        $this->assertArrayHasKey('gu', $plan['languages']);
        $this->assertArrayHasKey('hi', $plan['languages']);
        $this->assertArrayHasKey('mr', $plan['languages']);
        $this->assertSame('Reverse Management', $plan['languages']['en']['ui']['pageTitle']);
        $this->assertSame('રિવર્સ મેનેજમેન્ટ', $plan['languages']['gu']['ui']['pageTitle']);
    }

    public function test_real_estate_plan_uses_deals_terminology(): void
    {
        $plan = MaterialReverseManagement::for([
            'business_name' => 'XYZ Properties',
            'category' => 'Real Estate',
            'intro' => 'Residential & Commercial Property Business',
            'products' => 'Residential & Commercial Properties',
        ]);

        $this->assertSame('real_estate', $plan['family']);
        $this->assertSame('Deals', $plan['unit_label']);
        $this->assertSame('Average Deal Value', $plan['price_label']);
        $this->assertSame('Luxury Property', $plan['markets'][2]['offer']);
        $this->assertSame(100, $plan['achievement']);
        $this->assertCount(5, $plan['examples']);
        $this->assertSame('ABC Jewellery', $plan['examples'][0]['business_name']);
        $this->assertSame('XYZ Properties', $plan['examples'][1]['business_name']);
        $this->assertSame('Shree Mithai House', $plan['examples'][4]['business_name']);
    }

    public function test_tagline_masterclass_uses_industry_pack_and_five_examples(): void
    {
        $plan = \App\Support\MaterialTaglineMasterclass::for([
            'business_name' => 'ABC Jewellery',
            'category' => 'Jewellery',
            'intro' => 'Gold & Diamond Jewellery Retail Business',
            'products' => 'Gold & Diamond Jewellery',
        ]);

        $this->assertSame('jewellery', $plan['family']);
        $this->assertCount(15, $plan['rows']);
        $this->assertSame(['Gold & Diamond Jewellery Retail Business'], $plan['intro_points']);
        $this->assertSame(['Gold & Diamond Jewellery'], $plan['product_points']);
        $this->assertSame('Jewellery That Tells Your Story', $plan['rows'][0]['en']);
        $this->assertSame('તમારી કહાની કહેતી જ્વેલરી', $plan['rows'][0]['gu']);
        $this->assertCount(5, $plan['examples']);
        $this->assertSame('real_estate', $plan['examples'][0]['family']);
        $this->assertSame('education', $plan['examples'][1]['family']);
        $this->assertSame('it', $plan['examples'][2]['family']);
        $this->assertSame('sweets', $plan['examples'][3]['family']);
        $this->assertSame('jewellery', $plan['examples'][4]['family']);
        $this->assertNotSame($plan['examples'][0]['theme'], $plan['examples'][1]['theme']);
        $this->assertSame('From Sweet Shop to Sweet Empire', $plan['examples'][3]['rows'][14]['en']);
    }

    public function test_geo_maps_mumbai_to_maharashtra_west_india(): void
    {
        $geo = \App\Support\MaterialReverseDashboard::geo('Mumbai, Maharashtra');

        $this->assertSame('India', $geo['country']);
        $this->assertSame('Maharashtra', $geo['state']);
        $this->assertSame('West India', $geo['region']);
    }

    public function test_website_draft_builds_thirty_two_sections_without_fake_proof(): void
    {
        $plan = \App\Support\MaterialWebsiteDraft::for([
            'business_name' => 'Cadworld Infoways',
            'category' => 'Trading',
            'intro' => 'Wires and cables trading business in Mumbai.',
            'products' => 'Wires and cables',
            'member_name' => 'Mehul',
            'city' => 'Mumbai',
            'phone' => '9876543210',
        ]);

        $this->assertCount(32, $plan['sections']);
        $this->assertSame('hero', $plan['sections'][0]['code']);
        $this->assertSame('contact', $plan['sections'][31]['code']);
        $this->assertSame('Cadworld Infoways', $plan['sections'][0]['blocks'][0]['text']);
        $this->assertSame('Trading in Mumbai', $plan['sections'][0]['blocks'][1]['text']);
        $this->assertSame('Mehul', $plan['profile']['member_name']['text']);
        $this->assertSame('9876543210', $plan['profile']['phone']['text']);
        $this->assertNotEmpty($plan['product_points']);
        $this->assertStringContainsString('Contact us for latest pricing', $plan['sections'][9]['blocks'][0]['text']);
        $this->assertSame('required', $plan['sections'][20]['status']);
        $this->assertCount(5, $plan['actions']);
        $this->assertSame('approve', $plan['actions'][0]['code']);
        $this->assertSame('publish', $plan['actions'][4]['code']);
        $this->assertArrayHasKey('gu', $plan['languages']);
        $this->assertSame('હીરો બેનર', $plan['languages']['gu']['sections']['hero']);
        $this->assertSame('हीरो बैनर', $plan['languages']['hi']['sections']['hero']);
        $this->assertSame('हीरो बॅनर', $plan['languages']['mr']['sections']['hero']);
        $this->assertArrayHasKey('copy', $plan['languages']['gu']);
        $this->assertStringContainsString('ફોટો', (string) ($plan['languages']['gu']['copy']['sections']['hero'][5]['text'] ?? ''));
        $this->assertStringContainsString('फ़ोटो', (string) ($plan['languages']['hi']['copy']['sections']['hero'][5]['text'] ?? ''));
        $this->assertStringContainsString('फोटो', (string) ($plan['languages']['mr']['copy']['sections']['hero'][5]['text'] ?? ''));
        $this->assertSame('હવે પૂછો', $plan['languages']['gu']['copy']['cta']);
    }
}
