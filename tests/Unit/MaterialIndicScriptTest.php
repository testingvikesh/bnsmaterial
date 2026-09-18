<?php

namespace Tests\Unit;

use App\Support\MaterialIndicScript;
use App\Support\MaterialSanskarCalendar;
use App\Support\MaterialWebsiteDraft;
use PHPUnit\Framework\TestCase;

class MaterialIndicScriptTest extends TestCase
{
    public function test_accessories_uses_proper_indic_script(): void
    {
        $this->assertSame('એક્સેસરીઝ', MaterialIndicScript::phrase('gu', 'accessories'));
        $this->assertSame('एक्सेसरीज़', MaterialIndicScript::phrase('hi', 'accessories'));
        $this->assertSame('ॲक्सेसरीज', MaterialIndicScript::phrase('mr', 'accessories'));
        $this->assertStringContainsString('ॲक्सेसरीज', MaterialIndicScript::phrase('mr', 'complete control panel accessories'));
        $this->assertStringContainsString('एक्सेसरीज़', MaterialIndicScript::phrase('hi', 'complete control panel accessories'));
        $this->assertStringContainsString('એક્સેસરીઝ', MaterialIndicScript::phrase('gu', 'complete control panel accessories'));
    }

    public function test_website_and_sanskar_copy_script_product_words(): void
    {
        $website = MaterialWebsiteDraft::for([
            'business_name' => 'Cadworld Infoways',
            'category' => 'Trading',
            'products' => 'Wires and cables',
            'city' => 'Mumbai',
        ]);
        $this->assertStringContainsString('કેબલ્સ', (string) ($website['languages']['gu']['copy']['sections']['products'][1]['text'] ?? $website['languages']['gu']['copy']['sections']['hero'][3]['text'] ?? ''));

        $sanskar = MaterialSanskarCalendar::for([
            'business_name' => 'Cadworld Infoways',
            'category' => 'Electrical Trading',
            'intro' => 'We are distributors of RR KABEL Wires & Cables, ABB Switchgears, and complete control panel accessories.',
            'products' => 'RR KABEL Wires & Cables, ABB Switchgears, and complete control panel accessories',
        ]);
        $product = implode(' ', $sanskar['languages']['mr']['copy']['product_points'] ?? []);
        $this->assertStringContainsString('ॲक्सेसरीज', $product);
        $this->assertStringContainsString('एक्सेसरीज़', implode(' ', $sanskar['languages']['hi']['copy']['product_points'] ?? []));

        $jewellery = MaterialSanskarCalendar::for([
            'business_name' => 'Aditi Bhandari Design',
            'category' => 'Jewellery',
            'products' => 'Gold & Diamond Jewellery',
        ]);
        $this->assertSame('डिझाइन-लेड कलेक्शन वर्कशॉप', $jewellery['languages']['mr']['copy']['activities'][2]['title'] ?? null);
        $this->assertSame('क्लायंट ॲप्रिसिएशन डे', $jewellery['languages']['mr']['copy']['activities'][6]['title'] ?? null);
        $this->assertSame('ॲक्टिव्हिटी', MaterialIndicScript::phrase('mr', 'activity'));
        $this->assertSame('डिझाइन-लेड कलेक्शन वर्कशॉप', MaterialIndicScript::phrase('mr', 'Design-Led Collection Workshop'));
        $this->assertSame('रिपीट फॅमिलीज', MaterialIndicScript::phrase('mr', 'Repeat Families'));
        $this->assertSame('ॲन्युअल', MaterialIndicScript::phrase('mr', 'annual'));
        $this->assertSame('ॲन्युअल', MaterialIndicScript::phrase('mr', 'अॅन्युअल'));
        $this->assertSame('ॲन्युअल गोल', MaterialIndicScript::phrase('mr', 'Annual Goal'));
        $this->assertSame('रजिस्ट्रेशन', MaterialIndicScript::phrase('mr', 'Registration'));
        $this->assertSame('सर्टिफिकेट', MaterialIndicScript::phrase('mr', 'Certificate'));
        $this->assertSame('डिनर', MaterialIndicScript::phrase('mr', 'Dinner'));
        $this->assertSame('ॲन्युअल गोल', $jewellery['languages']['mr']['ui']['labelGoal'] ?? null);
        $this->assertSame('हा सर्वात मोठा ॲन्युअल रिलेशनशिप इव्हेंट बनवा.', $jewellery['languages']['mr']['copy']['activities'][15]['objective'] ?? null);
        $this->assertSame('ॲन्युअल कस्टमर सेलिब्रेशन', $jewellery['languages']['mr']['copy']['activities'][12]['title'] ?? null);
        foreach ($jewellery['languages']['mr']['copy']['customers'] ?? [] as $customer) {
            $this->assertDoesNotMatchRegularExpression('/[A-Za-z]/', (string) $customer);
        }
        foreach ($jewellery['languages']['mr']['copy']['activities'] ?? [] as $activity) {
            $this->assertDoesNotMatchRegularExpression('/[A-Za-z]/', (string) ($activity['title'] ?? ''));
        }
    }

    public function test_unknown_words_stay_english_in_sanskar_and_website(): void
    {
        $this->assertSame('Xylophone', MaterialIndicScript::phrase('mr', 'Xylophone'));
        $this->assertSame('annual Xylophone workshop', MaterialIndicScript::phrase('mr', 'annual Xylophone workshop'));
        $this->assertSame('रिटेल कस्टमर', MaterialIndicScript::phrase('mr', 'Retail Customers'));
        $this->assertSame('फायनान्स', MaterialIndicScript::phrase('mr', 'Finance'));

        $finance = MaterialSanskarCalendar::for([
            'business_name' => 'Ca Ashish Sethi',
            'category' => 'Finance',
            'products' => 'Business Finance',
        ]);
        $this->assertSame('फायनान्स', $finance['languages']['mr']['copy']['business_type'] ?? null);
        foreach ($finance['languages']['mr']['copy']['customers'] ?? [] as $customer) {
            $this->assertDoesNotMatchRegularExpression('/[A-Za-z]/', (string) $customer);
        }

        $keep = MaterialIndicScript::keepNames('Aditi Bhandari Design');
        $this->assertSame('Aditi Bhandari Design', MaterialIndicScript::phrase('mr', 'Aditi Bhandari Design', $keep));
        $this->assertSame('Aditi Bhandari Design क्रिएट्स ज्वेलरी', MaterialIndicScript::phrase('mr', 'Aditi Bhandari Design creates jewellery', $keep));

        $website = MaterialWebsiteDraft::for([
            'business_name' => 'Cadworld Infoways',
            'category' => 'Trading',
            'products' => 'Wires and cables and Xylophone',
            'city' => 'Mumbai',
        ]);
        $hay = json_encode($website['languages']['mr']['copy'] ?? [], JSON_UNESCAPED_UNICODE);
        $this->assertStringContainsString('Xylophone', (string) $hay);
        $this->assertStringContainsString('केबल्स', (string) $hay);
        $this->assertSame('Cadworld Infoways', $website['languages']['mr']['copy']['headline'] ?? null);
    }
}
