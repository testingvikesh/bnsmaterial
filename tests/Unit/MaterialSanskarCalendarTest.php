<?php

namespace Tests\Unit;

use App\Support\MaterialSanskarCalendar;
use PHPUnit\Framework\TestCase;

class MaterialSanskarCalendarTest extends TestCase
{
    public function test_electrical_member_gets_cadworld_style_sixteen_sanskar_plan(): void
    {
        $plan = MaterialSanskarCalendar::for([
            'business_name' => 'Cadworld Infoways',
            'category' => 'Electrical Trading',
            'intro' => 'We are distributors of RR KABEL Wires & Cables, ABB Switchgears, and complete control panel accessories.',
            'products' => 'RR KABEL Wires & Cables, ABB Switchgears, and complete control panel accessories',
            'member_name' => 'Mehul',
            'city' => 'Mumbai',
        ]);

        $this->assertSame('electrical', $plan['family']);
        $this->assertCount(16, $plan['activities']);
        $this->assertCount(16, $plan['calendar']);
        $this->assertSame(1, $plan['calendar'][0]['no']);
        $this->assertSame(16, $plan['calendar'][15]['no']);
        $this->assertArrayNotHasKey('month', $plan['calendar'][0]);
        $this->assertSame('Parichay', $plan['calendar'][0]['sanskar']);
        $this->assertSame('Virasat', $plan['calendar'][15]['sanskar']);
        $this->assertSame('Customer Connect Meet', $plan['activities'][0]['title']);
        $this->assertSame('ABB Switchgear Workshop', $plan['activities'][2]['title']);
        $this->assertSame('Signature Customer Summit', $plan['activities'][15]['title']);
        $this->assertContains('Electrical Contractors', $plan['target_customers']);
        $this->assertContains('Panel Builders', $plan['target_customers']);
        $this->assertStringContainsString('Cadworld Infoways', $plan['invitation']['welcome']);
        $this->assertStringContainsString('Cadworld Infoways તરફથી', $plan['invitation']['welcome']);
        $this->assertStringContainsString('Cadworld Infoways Team', $plan['invitation']['signoff']);
        $this->assertStringContainsString('Electrical Contractors', $plan['closing']);
        $this->assertArrayHasKey('gu', $plan['languages']);
        $this->assertSame('પરિચય', $plan['languages']['gu']['sanskars']['Parichay']);
        $this->assertSame('परिचय', $plan['languages']['hi']['sanskars']['Parichay']);
        $this->assertSame('परिचय', $plan['languages']['mr']['sanskars']['Parichay']);
    }

    public function test_jewellery_member_does_not_get_electrical_workshop(): void
    {
        $plan = MaterialSanskarCalendar::for([
            'business_name' => 'ABC Jewellery',
            'category' => 'Jewellery',
            'intro' => 'Gold & Diamond Jewellery Retail Business',
            'products' => 'Gold & Diamond Jewellery',
        ]);

        $this->assertSame('jewellery', $plan['family']);
        $this->assertCount(16, $plan['activities']);
        $this->assertSame('Design-Led Collection Workshop', $plan['activities'][2]['title']);
        $this->assertNotSame('ABB Switchgear Workshop', $plan['activities'][2]['title']);
        $this->assertContains('Personal Clients', $plan['target_customers']);
        $this->assertNotContains('Panel Builders', $plan['target_customers']);
        $this->assertStringContainsString('ABC Jewellery', $plan['invitation']['welcome']);
    }
}
