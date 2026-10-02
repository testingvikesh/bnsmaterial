<?php

namespace App\Support;

class MaterialCompanyI18n
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
                $packs[$lang]['copy'] = self::copy($plan, $lang);
            }
        }

        $drop = ((int) ($plan['part'] ?? 1) === 2)
            ? ['kicker', 'topMeta', 'title', 'subtitle', 'navCompare', 'navEntities', 'navExpenses', 'navOwners', 'navFunding', 'navStages', 'navQuestions', 'compareTitle', 'entitiesTitle', 'labelMeaning', 'labelExample', 'labelFeatures', 'labelExamples', 'labelDocuments', 'labelSuitable', 'expensesTitle', 'ownersTitle', 'fundingTitle', 'stagesTitle', 'questionsTitle', 'thStage', 'thStructure', 'stageNoteLabel', 'formulaKicker', 'footer']
            : ['kicker2', 'topMeta2', 'title2', 'subtitle2', 'navMaster', 'navFinance', 'navLegal', 'navManagement', 'navGrowth', 'navDecisions', 'masterTitle', 'financeTitle', 'legalTitle', 'managementTitle', 'growthTitle', 'decisionsTitle', 'formulaKicker2', 'footer2', 'thConsider'];
        foreach ($packs as $lang => $pack) {
            foreach ($drop as $key) {
                unset($packs[$lang]['ui'][$key]);
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
                'kicker' => 'BNS PVT LTD Session',
                'topMeta' => 'Students & Business Owners',
                'title' => 'Proprietorship vs Partnership vs LLP vs Private Limited vs Public Limited',
                'subtitle' => 'Complete Comparison for Students & Business Owners',
                'navCompare' => '50-Point Comparison',
                'navEntities' => '5 Structures',
                'navExpenses' => 'Business Expenses',
                'navOwners' => 'Owner Comparison',
                'navFunding' => 'Funding',
                'navStages' => 'Empire Journey',
                'navQuestions' => '10 Questions',
                'compareTitle' => '50-Point Complete Comparison',
                'entitiesTitle' => 'The 5 Business Structures',
                'thNo' => '#',
                'thPoint' => 'Point',
                'labelMeaning' => 'Meaning',
                'labelExample' => 'Example',
                'labelFeatures' => 'Key Features',
                'labelExamples' => 'Example Businesses',
                'labelDocuments' => 'Important Documents',
                'labelSuitable' => 'Suitable For',
                'expensesTitle' => 'Business Expense Comparison',
                'ownersTitle' => 'Owner / Partner / Shareholder Comparison',
                'fundingTitle' => 'Funding Comparison',
                'stagesTitle' => 'From Business House to Business Empire',
                'questionsTitle' => '10 Questions Before Selecting a Business Structure',
                'thStage' => 'Stage',
                'thSituation' => 'Business Situation',
                'thStructure' => 'Possible Structure',
                'thConsider' => 'Common Structures to Consider',
                'stageNoteLabel' => 'Important Learning:',
                'formulaKicker' => 'BNS Key Learning',
                'footer' => $businessName.' — PVT LTD Session',
                'studentsOwners' => 'Students & Business Owners',
                'chipYes' => 'Yes',
                'chipNo' => 'No',
                'hideDetails' => 'Hide details',
                'showDetails' => 'Show details',
                'kicker2' => 'BNS PVT LTD Session · Part 2',
                'topMeta2' => 'Points 101–200',
                'title2' => 'Business Structure Master Comparison',
                'subtitle2' => 'PVT LTD Session (Part 2) · Points 101–200 for Students & Business Owners',
                'navMaster' => '101–150 Structure',
                'navFinance' => '151–170 Finance',
                'navLegal' => '171–180 Legal',
                'navManagement' => '181–190 Management',
                'navGrowth' => '191–200 Growth',
                'navDecisions' => 'Student Decisions',
                'masterTitle' => 'Business Structure Master Comparison',
                'financeTitle' => 'Finance & Accounting Comparison',
                'legalTitle' => 'Legal & Document Comparison',
                'managementTitle' => 'Management Comparison',
                'growthTitle' => 'Growth & Scaling Comparison',
                'decisionsTitle' => 'Decision-Making Comparison for Students',
                'formulaKicker2' => 'Key Formula for BNS Students',
                'footer2' => $businessName.' — PVT LTD Session (Part 2)',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function gu(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => 'BNS PVT LTD સેશન',
                'topMeta' => 'સ્ટુડન્ટ્સ અને બિઝનેસ ઓનર્સ',
                'title' => 'એકમાલિકી vs ભાગીદારી vs LLP vs પ્રાઇવેટ લિમિટેડ vs પબ્લિક લિમિટેડ',
                'subtitle' => 'સ્ટુડન્ટ્સ અને બિઝનેસ ઓનર્સ માટે કમ્પ્લીટ કમ્પેરિઝન',
                'navCompare' => '50-પોઈન્ટ કમ્પેરિઝન',
                'navEntities' => '5 સ્ટ્રક્ચર્સ',
                'navExpenses' => 'બિઝનેસ ખર્ચ',
                'navOwners' => 'ઓનર કમ્પેરિઝન',
                'navFunding' => 'ફંડિંગ',
                'navStages' => 'એમ્પાયર જર્ની',
                'navQuestions' => '10 પ્રશ્નો',
                'compareTitle' => '50-પોઈન્ટ કમ્પ્લીટ કમ્પેરિઝન',
                'entitiesTitle' => '5 બિઝનેસ સ્ટ્રક્ચર્સ',
                'thNo' => 'ક્રમ',
                'thPoint' => 'પોઈન્ટ',
                'labelMeaning' => 'અર્થ',
                'labelExample' => 'ઉદાહરણ',
                'labelFeatures' => 'મુખ્ય ફીચર્સ',
                'labelExamples' => 'ઉદાહરણ બિઝનેસ',
                'labelDocuments' => 'મહત્વના દસ્તાવેજો',
                'labelSuitable' => 'કોના માટે યોગ્ય',
                'expensesTitle' => 'બિઝનેસ ખર્ચ કમ્પેરિઝન',
                'ownersTitle' => 'ઓનર / પાર્ટનર / શેરહોલ્ડર કમ્પેરિઝન',
                'fundingTitle' => 'ફંડિંગ કમ્પેરિઝન',
                'stagesTitle' => 'બિઝનેસ હાઉસથી બિઝનેસ એમ્પાયર',
                'questionsTitle' => 'બિઝનેસ સ્ટ્રક્ચર પસંદ કરતા પહેલા 10 પ્રશ્નો',
                'thStage' => 'સ્ટેજ',
                'thSituation' => 'બિઝનેસ પરિસ્થિતિ',
                'thStructure' => 'શક્ય સ્ટ્રક્ચર',
                'thConsider' => 'વિચારવા યોગ્ય સ્ટ્રક્ચર્સ',
                'stageNoteLabel' => 'મહત્વની લર્નિંગ:',
                'formulaKicker' => 'BNS કી લર્નિંગ',
                'footer' => $businessName.' — PVT LTD સેશન',
                'studentsOwners' => 'સ્ટુડન્ટ્સ અને બિઝનેસ ઓનર્સ',
                'chipYes' => 'હા',
                'chipNo' => 'ના',
                'hideDetails' => 'ડિટેઈલ્સ છુપાવો',
                'showDetails' => 'ડિટેઈલ્સ બતાવો',
                'kicker2' => 'BNS PVT LTD સેશન · ભાગ 2',
                'topMeta2' => 'પોઈન્ટ 101–200',
                'title2' => 'બિઝનેસ સ્ટ્રક્ચર માસ્ટર કમ્પેરિઝન',
                'subtitle2' => 'PVT LTD સેશન (ભાગ 2) · સ્ટુડન્ટ્સ અને બિઝનેસ ઓનર્સ માટે પોઈન્ટ 101–200',
                'navMaster' => '101–150 સ્ટ્રક્ચર',
                'navFinance' => '151–170 ફાઈનાન્સ',
                'navLegal' => '171–180 લીગલ',
                'navManagement' => '181–190 મેનેજમેન્ટ',
                'navGrowth' => '191–200 ગ્રોથ',
                'navDecisions' => 'સ્ટુડન્ટ ડિસિઝન',
                'masterTitle' => 'બિઝનેસ સ્ટ્રક્ચર માસ્ટર કમ્પેરિઝન',
                'financeTitle' => 'ફાઈનાન્સ અને અકાઉન્ટિંગ કમ્પેરિઝન',
                'legalTitle' => 'લીગલ અને દસ્તાવેજ કમ્પેરિઝન',
                'managementTitle' => 'મેનેજમેન્ટ કમ્પેરિઝન',
                'growthTitle' => 'ગ્રોથ અને સ્કેલિંગ કમ્પેરિઝન',
                'decisionsTitle' => 'સ્ટુડન્ટ્સ માટે ડિસિઝન-મેકિંગ કમ્પેરિઝન',
                'formulaKicker2' => 'BNS સ્ટુડન્ટ્સ માટે કી ફોર્મ્યુલા',
                'footer2' => $businessName.' — PVT LTD સેશન (ભાગ 2)',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function hi(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => 'BNS PVT LTD सेशन',
                'topMeta' => 'स्टूडेंट्स और बिज़नेस ओनर्स',
                'title' => 'एकल स्वामित्व vs साझेदारी vs LLP vs प्राइवेट लिमिटेड vs पब्लिक लिमिटेड',
                'subtitle' => 'स्टूडेंट्स और बिज़नेस ओनर्स के लिए कम्प्लीट कंपैरिजन',
                'navCompare' => '50-पॉइंट कंपैरिजन',
                'navEntities' => '5 स्ट्रक्चर्स',
                'navExpenses' => 'बिज़नेस खर्च',
                'navOwners' => 'ओनर कंपैरिजन',
                'navFunding' => 'फंडिंग',
                'navStages' => 'एम्पायर जर्नी',
                'navQuestions' => '10 प्रश्न',
                'compareTitle' => '50-पॉइंट कम्प्लीट कंपैरिजन',
                'entitiesTitle' => '5 बिज़नेस स्ट्रक्चर्स',
                'thNo' => 'क्रम',
                'thPoint' => 'पॉइंट',
                'labelMeaning' => 'अर्थ',
                'labelExample' => 'उदाहरण',
                'labelFeatures' => 'मुख्य फीचर्स',
                'labelExamples' => 'उदाहरण बिज़नेस',
                'labelDocuments' => 'महत्वपूर्ण दस्तावेज़',
                'labelSuitable' => 'किसके लिए उपयुक्त',
                'expensesTitle' => 'बिज़नेस खर्च कंपैरिजन',
                'ownersTitle' => 'ओनर / पार्टनर / शेयरहोल्डर कंपैरिजन',
                'fundingTitle' => 'फंडिंग कंपैरिजन',
                'stagesTitle' => 'बिज़नेस हाउस से बिज़नेस एम्पायर',
                'questionsTitle' => 'बिज़नेस स्ट्रक्चर चुनने से पहले 10 प्रश्न',
                'thStage' => 'स्टेज',
                'thSituation' => 'बिज़नेस स्थिति',
                'thStructure' => 'संभव स्ट्रक्चर',
                'thConsider' => 'विचार करने योग्य स्ट्रक्चर्स',
                'stageNoteLabel' => 'महत्वपूर्ण लर्निंग:',
                'formulaKicker' => 'BNS की लर्निंग',
                'footer' => $businessName.' — PVT LTD सेशन',
                'studentsOwners' => 'स्टूडेंट्स और बिज़नेस ओनर्स',
                'chipYes' => 'हाँ',
                'chipNo' => 'नहीं',
                'hideDetails' => 'डिटेल्स छिपाएँ',
                'showDetails' => 'डिटेल्स दिखाएँ',
                'kicker2' => 'BNS PVT LTD सेशन · भाग 2',
                'topMeta2' => 'पॉइंट 101–200',
                'title2' => 'बिज़नेस स्ट्रक्चर मास्टर कंपैरिजन',
                'subtitle2' => 'PVT LTD सेशन (भाग 2) · स्टूडेंट्स और बिज़नेस ओनर्स के लिए पॉइंट 101–200',
                'navMaster' => '101–150 स्ट्रक्चर',
                'navFinance' => '151–170 फाइनेंस',
                'navLegal' => '171–180 लीगल',
                'navManagement' => '181–190 मैनेजमेंट',
                'navGrowth' => '191–200 ग्रोथ',
                'navDecisions' => 'स्टूडेंट डिसीजन',
                'masterTitle' => 'बिज़नेस स्ट्रक्चर मास्टर कंपैरिजन',
                'financeTitle' => 'फाइनेंस और अकाउंटिंग कंपैरिजन',
                'legalTitle' => 'लीगल और दस्तावेज़ कंपैरिजन',
                'managementTitle' => 'मैनेजमेंट कंपैरिजन',
                'growthTitle' => 'ग्रोथ और स्केलिंग कंपैरिजन',
                'decisionsTitle' => 'स्टूडेंट्स के लिए डिसीजन-मेकिंग कंपैरिजन',
                'formulaKicker2' => 'BNS स्टूडेंट्स के लिए की फॉर्मूला',
                'footer2' => $businessName.' — PVT LTD सेशन (भाग 2)',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function mr(string $businessName): array
    {
        return [
            'ui' => [
                'kicker' => 'BNS PVT LTD सेशन',
                'topMeta' => 'स्टुडंट्स आणि बिझनेस ओनर्स',
                'title' => 'एकमालकी vs भागीदारी vs LLP vs प्रायव्हेट लिमिटेड vs पब्लिक लिमिटेड',
                'subtitle' => 'स्टुडंट्स आणि बिझनेस ओनर्ससाठी कंप्लीट कंपॅरिसन',
                'navCompare' => '50-पॉइंट कंपॅरिसन',
                'navEntities' => '5 स्ट्रक्चर्स',
                'navExpenses' => 'बिझनेस खर्च',
                'navOwners' => 'ओनर कंपॅरिसन',
                'navFunding' => 'फंडिंग',
                'navStages' => 'एम्पायर जर्नी',
                'navQuestions' => '10 प्रश्न',
                'compareTitle' => '50-पॉइंट कंप्लीट कंपॅरिसन',
                'entitiesTitle' => '5 बिझनेस स्ट्रक्चर्स',
                'thNo' => 'क्रमांक',
                'thPoint' => 'पॉइंट',
                'labelMeaning' => 'अर्थ',
                'labelExample' => 'उदाहरण',
                'labelFeatures' => 'मुख्य फीचर्स',
                'labelExamples' => 'उदाहरण बिझनेस',
                'labelDocuments' => 'महत्त्वाची कागदपत्रे',
                'labelSuitable' => 'कोणासाठी योग्य',
                'expensesTitle' => 'बिझनेस खर्च कंपॅरिसन',
                'ownersTitle' => 'ओनर / पार्टनर / शेअरहोल्डर कंपॅरिसन',
                'fundingTitle' => 'फंडिंग कंपॅरिसन',
                'stagesTitle' => 'बिझनेस हाउस ते बिझनेस एम्पायर',
                'questionsTitle' => 'बिझनेस स्ट्रक्चर निवडण्यापूर्वी 10 प्रश्न',
                'thStage' => 'स्टेज',
                'thSituation' => 'बिझनेस परिस्थिती',
                'thStructure' => 'शक्य स्ट्रक्चर',
                'thConsider' => 'विचारात घ्यावयाची स्ट्रक्चर्स',
                'stageNoteLabel' => 'महत्त्वाची लर्निंग:',
                'formulaKicker' => 'BNS की लर्निंग',
                'footer' => $businessName.' — PVT LTD सेशन',
                'studentsOwners' => 'स्टुडंट्स आणि बिझनेस ओनर्स',
                'chipYes' => 'हो',
                'chipNo' => 'नाही',
                'hideDetails' => 'डिटेल्स लपवा',
                'showDetails' => 'डिटेल्स दाखवा',
                'kicker2' => 'BNS PVT LTD सेशन · भाग 2',
                'topMeta2' => 'पॉइंट 101–200',
                'title2' => 'बिझनेस स्ट्रक्चर मास्टर कंपॅरिसन',
                'subtitle2' => 'PVT LTD सेशन (भाग 2) · स्टुडंट्स आणि बिझनेस ओनर्ससाठी पॉइंट 101–200',
                'navMaster' => '101–150 स्ट्रक्चर',
                'navFinance' => '151–170 फायनान्स',
                'navLegal' => '171–180 लीगल',
                'navManagement' => '181–190 मॅनेजमेंट',
                'navGrowth' => '191–200 ग्रोथ',
                'navDecisions' => 'स्टुडंट डिसीजन',
                'masterTitle' => 'बिझनेस स्ट्रक्चर मास्टर कंपॅरिसन',
                'financeTitle' => 'फायनान्स आणि अकाउंटिंग कंपॅरिसन',
                'legalTitle' => 'लीगल आणि कागदपत्र कंपॅरिसन',
                'managementTitle' => 'मॅनेजमेंट कंपॅरिसन',
                'growthTitle' => 'ग्रोथ आणि स्केलिंग कंपॅरिसन',
                'decisionsTitle' => 'स्टुडंट्ससाठी डिसीजन-मेकिंग कंपॅरिसन',
                'formulaKicker2' => 'BNS स्टुडंट्ससाठी की फॉर्म्युला',
                'footer2' => $businessName.' — PVT LTD सेशन (भाग 2)',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $plan
     * @return array<string, mixed>
     */
    private static function copy(array $plan, string $lang): array
    {
        $keep = MaterialIndicScript::keepNames(
            (string) ($plan['business_name'] ?? ''),
            (string) ($plan['member_name'] ?? '')
        );
        $tree = $plan;
        unset($tree['languages'], $tree['title'], $tree['kicker'], $tree['subtitle']);
        $copy = self::walk($lang, $tree, $keep);
        $copy['headers'] = self::translateList($lang, is_array($plan['headers'] ?? null) ? $plan['headers'] : []);
        foreach (['expenses', 'owners', 'funding'] as $block) {
            if (isset($plan[$block]['headers']) && is_array($plan[$block]['headers'])) {
                $copy[$block]['headers'] = self::translateList($lang, $plan[$block]['headers']);
            }
        }

        return $copy;
    }

    /**
     * @param  list<string>  $keep
     */
    private static function walk(string $lang, mixed $value, array $keep): mixed
    {
        if (is_string($value)) {
            $trim = trim($value);
            $low = strtolower($trim);
            if (in_array($low, ['yes', 'no', 'y', 'n', 'true', 'false'], true) || $trim === '—' || $trim === '-') {
                return $trim;
            }

            return MaterialIndicScript::phrase($lang, $value, $keep);
        }
        if (! is_array($value)) {
            return $value;
        }
        foreach ($value as $key => $item) {
            if (is_string($key) && in_array($key, ['code', 'languages', 'part', 'business_name', 'member_name'], true)) {
                continue;
            }
            $value[$key] = self::walk($lang, $item, $keep);
        }

        return $value;
    }

    /**
     * @param  list<string>  $headers
     * @return list<string>
     */
    private static function translateList(string $lang, array $headers): array
    {
        $map = [
            'proprietorship' => ['gu' => 'એકમાલિકી', 'hi' => 'एकल स्वामित्व', 'mr' => 'एकमालकी'],
            'partnership' => ['gu' => 'ભાગીદારી', 'hi' => 'साझेदारी', 'mr' => 'भागीदारी'],
            'llp' => ['gu' => 'LLP', 'hi' => 'LLP', 'mr' => 'LLP'],
            'private limited' => ['gu' => 'પ્રાઇવેટ લિમિટેડ', 'hi' => 'प्राइवेट लिमिटेड', 'mr' => 'प्रायव्हेट लिमिटेड'],
            'public limited' => ['gu' => 'પબ્લિક લિમિટેડ', 'hi' => 'पब्लिक लिमिटेड', 'mr' => 'पब्लिक लिमिटेड'],
            'pvt ltd' => ['gu' => 'પ્રાઇવેટ લિમિટેડ', 'hi' => 'प्राइवेट लिमिटेड', 'mr' => 'प्रायव्हेट लिमिटेड'],
            'public ltd' => ['gu' => 'પબ્લિક લિમિટેડ', 'hi' => 'पब्लिक लिमिटेड', 'mr' => 'पब्लिक लिमिटेड'],
            'proprietor' => ['gu' => 'પ્રોપ્રાયટર', 'hi' => 'प्रोपराइटर', 'mr' => 'मालक'],
            'partner' => ['gu' => 'પાર્ટનર', 'hi' => 'पार्टनर', 'mr' => 'भागीदार'],
            'llp partner' => ['gu' => 'LLP પાર્ટનર', 'hi' => 'LLP पार्टनर', 'mr' => 'LLP भागीदार'],
            'pvt ltd shareholder' => ['gu' => 'પ્રાઇવેટ લિમિટેડ શેરહોલ્ડર', 'hi' => 'प्राइवेट लिमिटेड शेयरहोल्डर', 'mr' => 'प्रायव्हेट लिमिटेड शेअरहोल्डर'],
            'public ltd shareholder' => ['gu' => 'પબ્લિક લિમિટેડ શેરહોલ્ડર', 'hi' => 'पब्लिक लिमिटेड शेयरहोल्डर', 'mr' => 'पब्लिक लिमिटेड शेअरहोल्डर'],
            'expense' => ['gu' => 'ખર્ચ', 'hi' => 'खर्च', 'mr' => 'खर्च'],
            'point' => ['gu' => 'પોઈન્ટ', 'hi' => 'पॉइंट', 'mr' => 'पॉइंट'],
            'funding source' => ['gu' => 'ફંડિંગ સોર્સ', 'hi' => 'फंडिंग सोर्स', 'mr' => 'फंडिंग सोर्स'],
        ];

        $out = [];
        foreach ($headers as $header) {
            $key = strtolower(trim((string) $header));
            if ($lang !== 'en' && isset($map[$key][$lang])) {
                $out[] = $map[$key][$lang];
                continue;
            }
            $out[] = MaterialIndicScript::phrase($lang, (string) $header);
        }

        return $out;
    }
}
