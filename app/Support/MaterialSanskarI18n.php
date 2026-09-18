<?php

namespace App\Support;

class MaterialSanskarI18n
{
    /**
     * @param  array<string, mixed>  $plan
     * @return array<string, array<string, mixed>>
     */
    public static function packs(string $businessName, array $plan = []): array
    {
        $packs = [
            'en' => self::en($businessName),
            'gu' => self::gu($businessName),
            'hi' => self::hi($businessName),
            'mr' => self::mr($businessName),
        ];

        if ($plan !== []) {
            foreach ($packs as $lang => $pack) {
                $packs[$lang]['copy'] = MaterialSanskarCopy::forLanguage($plan, $lang);
            }
        }

        $packs['mr'] = MaterialIndicScript::marathiPack($packs['mr']);

        return $packs;
    }

    /**
     * @return array<string, mixed>
     */
    private static function en(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => '16 Sanskar Calendar',
                'heroKicker' => 'Customer 16 Sanskar Relationship Plan',
                'topMeta' => 'Customer Relationship Plan',
                'heroLine' => '16 meaningful experiences a year.',
                'navIntro' => 'Introduction',
                'navCalendar' => 'Yearly Calendar',
                'navActivities' => '16 Activities',
                'navInvite' => 'Invitation',
                'snapshotTitle' => 'Business Snapshot',
                'labelBusinessName' => 'Business Name',
                'labelBusinessType' => 'Business Type',
                'labelIntroduction' => 'Business Introduction',
                'labelProducts' => 'Main Products / Services',
                'labelCustomers' => 'Target Customers',
                'labelGoal' => 'Annual Goal',
                'calendarTitle' => 'Yearly 16 Sanskar Calendar',
                'thNo' => 'Sr No',
                'thSanskar' => 'Sanskar',
                'thActivity' => 'Activity',
                'activitiesTitle' => 'Complete Activity Details',
                'inviteTitle' => 'Customer Invitation',
                'memory' => 'Memory',
                'budget' => 'Budget',
                'certificate' => 'Certificate',
                'formulaKicker' => 'Final Relationship Formula',
                'formulaText' => '16 Activities → 16 Experiences → 16 Memories → Lifetime Customer',
                'footer' => $businessName.' — 16 Sanskar Customer Relationship Plan',
            ],
            'sanskars' => self::sanskars('en'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function gu(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => '16 સંસ્કાર કેલેન્ડર',
                'heroKicker' => 'કસ્ટમર 16 સંસ્કાર રિલેશનશિપ પ્લાન',
                'topMeta' => 'કસ્ટમર રિલેશનશિપ પ્લાન',
                'heroLine' => 'વર્ષમાં 16 અર્થપૂર્ણ અનુભવ.',
                'navIntro' => 'ઇન્ટ્રોડક્શન',
                'navCalendar' => 'એન્યુઅલ કેલેન્ડર',
                'navActivities' => '16 એક્ટિવિટી',
                'navInvite' => 'આમંત્રણ',
                'snapshotTitle' => 'બિઝનેસ સ્નેપશોટ',
                'labelBusinessName' => 'બિઝનેસ નામ',
                'labelBusinessType' => 'બિઝનેસ ટાઈપ',
                'labelIntroduction' => 'બિઝનેસ ઇન્ટ્રોડક્શન',
                'labelProducts' => 'મેઈન પ્રોડક્ટ્સ / સર્વિસીસ',
                'labelCustomers' => 'ટાર્ગેટ કસ્ટમર્સ',
                'labelGoal' => 'એન્યુઅલ ગોલ',
                'calendarTitle' => 'એન્યુઅલ 16 સંસ્કાર કેલેન્ડર',
                'thNo' => 'ક્રમ',
                'thSanskar' => 'સંસ્કાર',
                'thActivity' => 'એક્ટિવિટી',
                'activitiesTitle' => 'પૂરી એક્ટિવિટી ડિટેઈલ્સ',
                'inviteTitle' => 'કસ્ટમર આમંત્રણ',
                'memory' => 'મેમરી',
                'budget' => 'બજેટ',
                'certificate' => 'સર્ટિફિકેટ',
                'formulaKicker' => 'ફાઈનલ રિલેશનશિપ ફોર્મ્યુલા',
                'formulaText' => '16 એક્ટિવિટી → 16 અનુભવ → 16 યાદો → લાઈફટાઈમ કસ્ટમર',
                'footer' => $businessName.' — 16 સંસ્કાર કસ્ટમર રિલેશનશિપ પ્લાન',
            ],
            'sanskars' => self::sanskars('gu'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function hi(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => '16 संस्कार कैलेंडर',
                'heroKicker' => 'कस्टमर 16 संस्कार रिलेशनशिप प्लान',
                'topMeta' => 'कस्टमर रिलेशनशिप प्लान',
                'heroLine' => 'साल में 16 अर्थपूर्ण अनुभव.',
                'navIntro' => 'इंट्रोडक्शन',
                'navCalendar' => 'एनुअल कैलेंडर',
                'navActivities' => '16 एक्टिविटी',
                'navInvite' => 'निमंत्रण',
                'snapshotTitle' => 'बिज़नेस स्नैपशॉट',
                'labelBusinessName' => 'बिज़नेस नाम',
                'labelBusinessType' => 'बिज़नेस टाइप',
                'labelIntroduction' => 'बिज़नेस इंट्रोडक्शन',
                'labelProducts' => 'मेन प्रॉडक्ट्स / सर्विसेज',
                'labelCustomers' => 'टारगेट कस्टमर्स',
                'labelGoal' => 'एनुअल गोल',
                'calendarTitle' => 'एनुअल 16 संस्कार कैलेंडर',
                'thNo' => 'क्रमांक',
                'thSanskar' => 'संस्कार',
                'thActivity' => 'एक्टिविटी',
                'activitiesTitle' => 'पूरी एक्टिविटी डिटेल्स',
                'inviteTitle' => 'कस्टमर निमंत्रण',
                'memory' => 'मेमोरी',
                'budget' => 'बजट',
                'certificate' => 'सर्टिफिकेट',
                'formulaKicker' => 'फाइनल रिलेशनशिप फॉर्मूला',
                'formulaText' => '16 एक्टिविटी → 16 अनुभव → 16 यादें → लाइफटाइम कस्टमर',
                'footer' => $businessName.' — 16 संस्कार कस्टमर रिलेशनशिप प्लान',
            ],
            'sanskars' => self::sanskars('hi'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function mr(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => '16 संस्कार कॅलेंडर',
                'heroKicker' => 'कस्टमर 16 संस्कार रिलेशनशिप प्लान',
                'topMeta' => 'कस्टमर रिलेशनशिप प्लान',
                'heroLine' => 'वर्षात 16 अर्थपूर्ण अनुभव.',
                'navIntro' => 'इंट्रोडक्शन',
                'navCalendar' => 'अॅन्युअल कॅलेंडर',
                'navActivities' => '16 अॅक्टिव्हिटी',
                'navInvite' => 'आमंत्रण',
                'snapshotTitle' => 'बिझनेस स्नॅपशॉट',
                'labelBusinessName' => 'बिझनेस नाव',
                'labelBusinessType' => 'बिझनेस टाइप',
                'labelIntroduction' => 'बिझनेस इंट्रोडक्शन',
                'labelProducts' => 'मेन प्रॉडक्ट्स / सर्व्हिसेस',
                'labelCustomers' => 'टार्गेट कस्टमर',
                'labelGoal' => 'अॅन्युअल गोल',
                'calendarTitle' => 'अॅन्युअल 16 संस्कार कॅलेंडर',
                'thNo' => 'अ.क्र.',
                'thSanskar' => 'संस्कार',
                'thActivity' => 'अॅक्टिव्हिटी',
                'activitiesTitle' => 'पूर्ण अॅक्टिव्हिटी डिटेल्स',
                'inviteTitle' => 'कस्टमर आमंत्रण',
                'memory' => 'मेमरी',
                'budget' => 'बजेट',
                'certificate' => 'सर्टिफिकेट',
                'formulaKicker' => 'फायनल रिलेशनशिप फॉर्म्युला',
                'formulaText' => '16 अॅक्टिव्हिटी → 16 अनुभव → 16 आठवणी → लाईफटाइम कस्टमर',
                'footer' => $businessName.' — 16 संस्कार कस्टमर रिलेशनशिप प्लान',
            ],
            'sanskars' => self::sanskars('mr'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function sanskars(string $lang): array
    {
        $en = [
            'Parichay' => 'Parichay',
            'Swagat' => 'Swagat',
            'Margdarshan' => 'Margdarshan',
            'Anubhav' => 'Anubhav',
            'Samjan' => 'Samjan',
            'Parivar' => 'Parivar',
            'Samman' => 'Samman',
            'Vishwas' => 'Vishwas',
            'Sahbhagita' => 'Sahbhagita',
            'Seva' => 'Seva',
            'Sambandh' => 'Sambandh',
            'Puraskar' => 'Puraskar',
            'Utsav' => 'Utsav',
            'Abhar' => 'Abhar',
            'Smruti' => 'Smruti',
            'Virasat' => 'Virasat',
        ];

        return match ($lang) {
            'gu' => [
                'Parichay' => 'પરિચય',
                'Swagat' => 'સ્વાગત',
                'Margdarshan' => 'માર્ગદર્શન',
                'Anubhav' => 'અનુભવ',
                'Samjan' => 'સમજણ',
                'Parivar' => 'પરિવાર',
                'Samman' => 'સન્માન',
                'Vishwas' => 'વિશ્વાસ',
                'Sahbhagita' => 'સહભાગિતા',
                'Seva' => 'સેવા',
                'Sambandh' => 'સંબંધ',
                'Puraskar' => 'પુરસ્કાર',
                'Utsav' => 'ઉત્સવ',
                'Abhar' => 'આભાર',
                'Smruti' => 'સ્મૃતિ',
                'Virasat' => 'વિરાસત',
            ],
            'hi' => [
                'Parichay' => 'परिचय',
                'Swagat' => 'स्वागत',
                'Margdarshan' => 'मार्गदर्शन',
                'Anubhav' => 'अनुभव',
                'Samjan' => 'समझ',
                'Parivar' => 'परिवार',
                'Samman' => 'सम्मान',
                'Vishwas' => 'विश्वास',
                'Sahbhagita' => 'सहभागिता',
                'Seva' => 'सेवा',
                'Sambandh' => 'संबंध',
                'Puraskar' => 'पुरस्कार',
                'Utsav' => 'उत्सव',
                'Abhar' => 'आभार',
                'Smruti' => 'स्मृति',
                'Virasat' => 'विरासत',
            ],
            'mr' => [
                'Parichay' => 'परिचय',
                'Swagat' => 'स्वागत',
                'Margdarshan' => 'मार्गदर्शन',
                'Anubhav' => 'अनुभव',
                'Samjan' => 'समज',
                'Parivar' => 'परिवार',
                'Samman' => 'सन्मान',
                'Vishwas' => 'विश्वास',
                'Sahbhagita' => 'सहभागिता',
                'Seva' => 'सेवा',
                'Sambandh' => 'संबंध',
                'Puraskar' => 'पुरस्कार',
                'Utsav' => 'उत्सव',
                'Abhar' => 'आभार',
                'Smruti' => 'स्मृती',
                'Virasat' => 'वारसा',
            ],
            default => $en,
        };
    }
}
