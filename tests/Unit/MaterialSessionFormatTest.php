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
        $prompt = new SessionPrompt(['title' => 'Plan', 'body' => 'Create the worksheet.']);

        $this->assertSame(MaterialSessionFormat::ONE_TO_25, MaterialSessionFormat::resolve($one, $prompt));
        $this->assertSame(MaterialSessionFormat::EMPIRE, MaterialSessionFormat::resolve($empire, $prompt));
        $this->assertSame(MaterialSessionFormat::REVERSE, MaterialSessionFormat::resolve($reverse, $prompt));
        $this->assertSame(MaterialSessionFormat::TAGLINE, MaterialSessionFormat::resolve($tagline, $prompt));
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
}
