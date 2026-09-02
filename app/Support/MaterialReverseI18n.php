<?php

namespace App\Support;

class MaterialReverseI18n
{
    /**
     * @return array<string, array{ui: array<string, string>, markets: list<string>, examples: list<array<string, mixed>>}>
     */
    public static function packs(string $businessName): array
    {
        return [
            'en' => self::en($businessName),
            'gu' => self::gu($businessName),
            'hi' => self::hi($businessName),
            'mr' => self::mr($businessName),
        ];
    }

    /**
     * @return array{ui: array<string, string>, markets: list<string>, examples: list<array<string, mixed>>}
     */
    private static function en(string $businessName): array
    {
        return [
            'ui' => [
                'pageSubtitle' => 'Desired Turnover → 7 Markets → 7 Offers → Customers → Revenue',
                'kicker' => 'Business Navachar School · BNS ERP',
                'pageTitle' => 'Reverse Management',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'turnoverTitle' => '02 — Desired Annual Turnover',
                'turnoverNote' => 'Member input only. ERP uses this target for reverse calculation.',
                'turnoverLabel' => 'My Desired Annual Turnover',
                'plannerTitle' => '03 — 7 Market Offer Planner',
                'plannerNote' => 'Market and suggested offer are auto-generated. Member fills offer price only. Target customers/deals and revenue are auto.',
                'thNo' => 'No.',
                'thMarket' => 'Market',
                'thOffer' => 'ERP Suggested Offer',
                'thPrice' => 'Member Price',
                'thCustomers' => 'Target Customers / Deals',
                'thRevenue' => 'Expected Revenue',
                'total' => 'TOTAL',
                'btnSave' => 'Save',
                'btnEdit' => 'Edit',
                'btnGenerate' => 'Generate Plan',
                'btnView' => 'View Reverse Management',
                'btnBack' => 'Edit Input',
                'btnPrint' => 'Print / Download',
                'nav01' => 'VIEW 01 Jewellery',
                'nav02' => 'VIEW 02 Real Estate',
                'nav03' => 'VIEW 03 Manufacturing',
                'nav04' => 'VIEW 04 Lamination',
                'nav05' => 'VIEW 05 Sweets',
                'businessSummary' => 'Business Summary',
                'sevenMarkets' => '7 Market Opportunities',
                'sevenOffers' => '7 Offer Plan',
                'customerTarget' => 'Customer / Deal Target',
                'revenuePlan' => 'Revenue Plan',
                'desiredTurnover' => 'Desired Turnover',
                'plannedRevenue' => 'Planned Revenue',
                'totalCustomers' => 'Total Customers / Deals',
                'achievement' => 'Target Achievement',
                'marketOpps' => 'Market Opportunities',
                'totalOffers' => 'Total Offers',
                'revenueGap' => 'Revenue Gap',
                'member' => 'Member',
                'exampleKicker' => 'Complete example · shown for every member',
                'formGapEmpty' => 'Fill turnover and 7 prices, then click Generate Plan.',
                'revenueGapLabel' => 'Revenue Gap',
                'achievementLabel' => 'Achievement',
                'realEstateNote' => 'Real Estate uses Deals / Average Deal Value instead of Customers / Price.',
                'customersUnit' => 'Customers',
                'dealsUnit' => 'Deals',
                'priceUnit' => 'Price',
                'dealValueUnit' => 'Average Deal Value',
                'footer' => $businessName.' — Reverse Management · Member fills Target and Price · ERP auto-calculates Market, Customers and Revenue',
            ],
            'markets' => ['Existing Customer', 'New Customer', 'Premium', 'Budget', 'Bulk / B2B', 'Subscription / Repeat', 'Digital / Online'],
            'examples' => self::exampleCopy('en'),
        ];
    }

    /**
     * @return array{ui: array<string, string>, markets: list<string>, examples: list<array<string, mixed>>}
     */
    private static function gu(string $businessName): array
    {
        return [
            'ui' => [
                'pageSubtitle' => 'ડિઝાયર્ડ ટર્નઓવર → 7 માર્કેટ → 7 ઓફર → કસ્ટમર → રેવન્યુ',
                'kicker' => 'બિઝનેસ નવાચાર સ્કૂલ · BNS ERP',
                'pageTitle' => 'રિવર્સ મેનેજમેન્ટ',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'turnoverTitle' => '02 — ઇચ્છિત વાર્ષિક ટર્નઓવર',
                'turnoverNote' => 'મેમ્બર માત્ર આ ટાર્ગેટ ભરે. ERP રિવર્સ કેલ્ક્યુલેશન કરે.',
                'turnoverLabel' => 'મારું ઇચ્છિત વાર્ષિક ટર્નઓવર',
                'plannerTitle' => '03 — 7 માર્કેટ ઓફર પ્લાનર',
                'plannerNote' => 'માર્કેટ અને સજેસ્ટેડ ઓફર ઓટો. મેમ્બર માત્ર ભાવ ભરે. કસ્ટમર અને રેવન્યુ ઓટો.',
                'thNo' => 'નં.',
                'thMarket' => 'માર્કેટ',
                'thOffer' => 'ERP સજેસ્ટેડ ઓફર',
                'thPrice' => 'મેમ્બર ભાવ',
                'thCustomers' => 'ટાર્ગેટ કસ્ટમર / ડીલ',
                'thRevenue' => 'અપેક્ષિત રેવન્યુ',
                'total' => 'કુલ',
                'btnSave' => 'સેવ',
                'btnEdit' => 'એડિટ',
                'btnGenerate' => 'જનરેટ પ્લાન',
                'btnView' => 'વ્યૂ રિવર્સ મેનેજમેન્ટ',
                'btnBack' => 'ઇનપુટ એડિટ',
                'btnPrint' => 'પ્રિન્ટ / ડાઉનલોડ',
                'nav01' => 'VIEW 01 જ્વેલરી',
                'nav02' => 'VIEW 02 રિયલ એસ્ટેટ',
                'nav03' => 'VIEW 03 મેન્યુફેક્ચરિંગ',
                'nav04' => 'VIEW 04 લેમિનેશન',
                'nav05' => 'VIEW 05 મિઠાઈ',
                'businessSummary' => 'બિઝનેસ સમરી',
                'sevenMarkets' => '7 માર્કેટ તકો',
                'sevenOffers' => '7 ઓફર પ્લાન',
                'customerTarget' => 'કસ્ટમર / ડીલ ટાર્ગેટ',
                'revenuePlan' => 'રેવન્યુ પ્લાન',
                'desiredTurnover' => 'ઇચ્છિત ટર્નઓવર',
                'plannedRevenue' => 'પ્લાન્ડ રેવન્યુ',
                'totalCustomers' => 'કુલ કસ્ટમર / ડીલ',
                'achievement' => 'ટાર્ગેટ એચીવમેન્ટ',
                'marketOpps' => 'માર્કેટ તકો',
                'totalOffers' => 'કુલ ઓફર',
                'revenueGap' => 'રેવન્યુ ગેપ',
                'member' => 'મેમ્બર',
                'exampleKicker' => 'સંપૂર્ણ ઉદાહરણ · દરેક મેમ્બર માટે',
                'formGapEmpty' => 'ટર્નઓવર અને 7 ભાવ ભરો, પછી જનરેટ પ્લાન દબાવો.',
                'revenueGapLabel' => 'રેવન્યુ ગેપ',
                'achievementLabel' => 'એચીવમેન્ટ',
                'realEstateNote' => 'રિયલ એસ્ટેટમાં કસ્ટમર / ભાવ ની જગ્યાએ ડીલ / એવરેજ ડીલ વેલ્યુ વપરાય છે.',
                'customersUnit' => 'કસ્ટમર',
                'dealsUnit' => 'ડીલ',
                'priceUnit' => 'ભાવ',
                'dealValueUnit' => 'એવરેજ ડીલ વેલ્યુ',
                'footer' => $businessName.' — રિવર્સ મેનેજમેન્ટ · મેમ્બર ટાર્ગેટ અને ભાવ નક્કી કરે · ERP માર્કેટ, કસ્ટમર અને રેવન્યુ ઓટો કાઢે',
            ],
            'markets' => ['હાલના કસ્ટમર', 'નવા કસ્ટમર', 'પ્રીમિયમ', 'બજેટ', 'બલ્ક / B2B', 'સબ્સ્ક્રિપ્શન / રિપીટ', 'ડિજિટલ / ઓનલાઈન'],
            'examples' => self::exampleCopy('gu'),
        ];
    }

    /**
     * @return array{ui: array<string, string>, markets: list<string>, examples: list<array<string, mixed>>}
     */
    private static function hi(string $businessName): array
    {
        return [
            'ui' => [
                'pageSubtitle' => 'डिज़ायर्ड टर्नओवर → 7 मार्केट → 7 ऑफर → कस्टमर → रेवेन्यू',
                'kicker' => 'बिज़नेस नवाचार स्कूल · BNS ERP',
                'pageTitle' => 'रिवर्स मैनेजमेंट',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'turnoverTitle' => '02 — इच्छित वार्षिक टर्नओवर',
                'turnoverNote' => 'मेंबर केवल यह टारगेट भरता है। ERP रिवर्स कैलकुलेशन करता है।',
                'turnoverLabel' => 'मेरा इच्छित वार्षिक टर्नओवर',
                'plannerTitle' => '03 — 7 मार्केट ऑफर प्लानर',
                'plannerNote' => 'मार्केट और सुझाया ऑफर ऑटो। मेंबर केवल कीमत भरता है। कस्टमर और रेवेन्यू ऑटो।',
                'thNo' => 'नं.',
                'thMarket' => 'मार्केट',
                'thOffer' => 'ERP सुझाया ऑफर',
                'thPrice' => 'मेंबर कीमत',
                'thCustomers' => 'टारगेट कस्टमर / डील',
                'thRevenue' => 'अपेक्षित रेवेन्यू',
                'total' => 'कुल',
                'btnSave' => 'सेव',
                'btnEdit' => 'एडिट',
                'btnGenerate' => 'जनरेट प्लान',
                'btnView' => 'व्यू रिवर्स मैनेजमेंट',
                'btnBack' => 'इनपुट एडिट',
                'btnPrint' => 'प्रिंट / डाउनलोड',
                'nav01' => 'VIEW 01 ज्वेलरी',
                'nav02' => 'VIEW 02 रियल एस्टेट',
                'nav03' => 'VIEW 03 मैन्युफैक्चरिंग',
                'nav04' => 'VIEW 04 लैमिनेशन',
                'nav05' => 'VIEW 05 मिठाई',
                'businessSummary' => 'बिज़नेस समरी',
                'sevenMarkets' => '7 मार्केट अवसर',
                'sevenOffers' => '7 ऑफर प्लान',
                'customerTarget' => 'कस्टमर / डील टारगेट',
                'revenuePlan' => 'रेवेन्यू प्लान',
                'desiredTurnover' => 'इच्छित टर्नओवर',
                'plannedRevenue' => 'प्लान्ड रेवेन्यू',
                'totalCustomers' => 'कुल कस्टमर / डील',
                'achievement' => 'टारगेट अचीवमेंट',
                'marketOpps' => 'मार्केट अवसर',
                'totalOffers' => 'कुल ऑफर',
                'revenueGap' => 'रेवेन्यू गैप',
                'member' => 'मेंबर',
                'exampleKicker' => 'पूर्ण उदाहरण · हर मेंबर के लिए',
                'formGapEmpty' => 'टर्नओवर और 7 कीमतें भरें, फिर जनरेट प्लान दबाएँ।',
                'revenueGapLabel' => 'रेवेन्यू गैप',
                'achievementLabel' => 'अचीवमेंट',
                'realEstateNote' => 'रियल एस्टेट में कस्टमर / कीमत की जगह डील / एवरेज डील वैल्यू इस्तेमाल होता है।',
                'customersUnit' => 'कस्टमर',
                'dealsUnit' => 'डील',
                'priceUnit' => 'कीमत',
                'dealValueUnit' => 'एवरेज डील वैल्यू',
                'footer' => $businessName.' — रिवर्स मैनेजमेंट · मेंबर टारगेट और कीमत तय करे · ERP मार्केट, कस्टमर और रेवेन्यू ऑटो निकाले',
            ],
            'markets' => ['मौजूदा कस्टमर', 'नए कस्टमर', 'प्रीमियम', 'बजट', 'बल्क / B2B', 'सब्सक्रिप्शन / रिपीट', 'डिजिटल / ऑनलाइन'],
            'examples' => self::exampleCopy('hi'),
        ];
    }

    /**
     * @return array{ui: array<string, string>, markets: list<string>, examples: list<array<string, mixed>>}
     */
    private static function mr(string $businessName): array
    {
        return [
            'ui' => [
                'pageSubtitle' => 'डिझायर्ड टर्नओव्हर → 7 मार्केट → 7 ऑफर → कस्टमर → रेव्हेन्यू',
                'kicker' => 'बिझनेस नवाचार स्कूल · BNS ERP',
                'pageTitle' => 'रिव्हर्स मॅनेजमेंट',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'turnoverTitle' => '02 — इच्छित वार्षिक टर्नओव्हर',
                'turnoverNote' => 'मेंबर फक्त हा टार्गेट भरतो. ERP रिव्हर्स कॅल्क्युलेशन करते.',
                'turnoverLabel' => 'माझे इच्छित वार्षिक टर्नओव्हर',
                'plannerTitle' => '03 — 7 मार्केट ऑफर प्लॅनर',
                'plannerNote' => 'मार्केट आणि सुचवलेली ऑफर ऑटो. मेंबर फक्त किंमत भरतो. कस्टमर आणि रेव्हेन्यू ऑटो.',
                'thNo' => 'क्र.',
                'thMarket' => 'मार्केट',
                'thOffer' => 'ERP सुचवलेली ऑफर',
                'thPrice' => 'मेंबर किंमत',
                'thCustomers' => 'टार्गेट कस्टमर / डील',
                'thRevenue' => 'अपेक्षित रेव्हेन्यू',
                'total' => 'एकूण',
                'btnSave' => 'सेव्ह',
                'btnEdit' => 'एडिट',
                'btnGenerate' => 'जनरेट प्लॅन',
                'btnView' => 'व्ह्यू रिव्हर्स मॅनेजमेंट',
                'btnBack' => 'इनपुट एडिट',
                'btnPrint' => 'प्रिंट / डाउनलोड',
                'nav01' => 'VIEW 01 ज्वेलरी',
                'nav02' => 'VIEW 02 रिअल इस्टेट',
                'nav03' => 'VIEW 03 मॅन्युफॅक्चरिंग',
                'nav04' => 'VIEW 04 लॅमिनेशन',
                'nav05' => 'VIEW 05 मिठाई',
                'businessSummary' => 'बिझनेस समरी',
                'sevenMarkets' => '7 मार्केट संधी',
                'sevenOffers' => '7 ऑफर प्लॅन',
                'customerTarget' => 'कस्टमर / डील टार्गेट',
                'revenuePlan' => 'रेव्हेन्यू प्लॅन',
                'desiredTurnover' => 'इच्छित टर्नओव्हर',
                'plannedRevenue' => 'प्लॅन्ड रेव्हेन्यू',
                'totalCustomers' => 'एकूण कस्टमर / डील',
                'achievement' => 'टार्गेट अचीव्हमेंट',
                'marketOpps' => 'मार्केट संधी',
                'totalOffers' => 'एकूण ऑफर',
                'revenueGap' => 'रेव्हेन्यू गॅप',
                'member' => 'मेंबर',
                'exampleKicker' => 'पूर्ण उदाहरण · प्रत्येक मेंबरसाठी',
                'formGapEmpty' => 'टर्नओव्हर आणि 7 किंमती भरा, नंतर जनरेट प्लॅन दाबा.',
                'revenueGapLabel' => 'रेव्हेन्यू गॅप',
                'achievementLabel' => 'अचीव्हमेंट',
                'realEstateNote' => 'रिअल इस्टेटमध्ये कस्टमर / किंमत ऐवजी डील / अॅव्हरेज डील व्हॅल्यू वापरली जाते.',
                'customersUnit' => 'कस्टमर',
                'dealsUnit' => 'डील',
                'priceUnit' => 'किंमत',
                'dealValueUnit' => 'अॅव्हरेज डील व्हॅल्यू',
                'footer' => $businessName.' — रिव्हर्स मॅनेजमेंट · मेंबर टार्गेट आणि किंमत ठरवतो · ERP मार्केट, कस्टमर आणि रेव्हेन्यू ऑटो काढते',
            ],
            'markets' => ['सध्याचे कस्टमर', 'नवीन कस्टमर', 'प्रीमियम', 'बजेट', 'बल्क / B2B', 'सबस्क्रिप्शन / रिपीट', 'डिजिटल / ऑनलाइन'],
            'examples' => self::exampleCopy('mr'),
        ];
    }

    /**
     * @return list<array{title: string, offers: list<string>}>
     */
    private static function exampleCopy(string $lang): array
    {
        $packs = [
            'en' => [
                ['title' => 'VIEW 01 — Jewellery Business', 'offers' => ['Gold Upgrade Offer', 'First Purchase Collection', 'Luxury Diamond Collection', 'Starter Jewellery Collection', 'Corporate Gift Jewellery', 'Jewellery Purchase Plan', 'Virtual Jewellery Shopping']],
                ['title' => 'VIEW 02 — Real Estate Business', 'offers' => ['Upgrade / Referral Property', 'First-Time Buyer Property', 'Luxury Property', 'Affordable Property', 'Investor Property Deal', 'Property Management', 'Virtual Property Consultation']],
                ['title' => 'VIEW 03 — Manufacturing Business', 'offers' => ['Annual Supply Upgrade', 'New Buyer Package', 'Custom Premium Components', 'Standard Product Package', 'Bulk Manufacturing Contract', 'Annual Supply Contract', 'Online B2B Ordering']],
                ['title' => 'VIEW 04 — Lamination Business', 'offers' => ['Premium Document Package', 'Student Document Package', 'Certificate & Portfolio Package', 'Basic Lamination Package', 'School / Office Package', 'Monthly Business Plan', 'Online Document Service']],
                ['title' => 'VIEW 05 — Sweets / Mithai Business', 'offers' => ['Festival Family Sweet Box', 'First Order Sweet Box', 'Premium Celebration Hamper', 'Mini Sweet Pack', 'Corporate Festival Gifting', 'Monthly Sweet Subscription', 'Online Sweet Delivery']],
            ],
            'gu' => [
                ['title' => 'VIEW 01 — જ્વેલરી બિઝનેસ', 'offers' => ['ગોલ્ડ અપગ્રેડ ઓફર', 'ફર્સ્ટ પર્ચેઝ કલેક્શન', 'લક્ઝરી ડાયમંડ કલેક્શન', 'સ્ટાર્ટર જ્વેલરી કલેક્શન', 'કોર્પોરેટ ગિફ્ટ જ્વેલરી', 'જ્વેલરી પર્ચેઝ પ્લાન', 'વર્ચ્યુઅલ જ્વેલરી શોપિંગ']],
                ['title' => 'VIEW 02 — રિયલ એસ્ટેટ બિઝનેસ', 'offers' => ['અપગ્રેડ / રેફરલ પ્રોપર્ટી', 'ફર્સ્ટ-ટાઈમ બાયર પ્રોપર્ટી', 'લક્ઝરી પ્રોપર્ટી', 'એફોર્ડેબલ પ્રોપર્ટી', 'ઇન્વેસ્ટર પ્રોપર્ટી ડીલ', 'પ્રોપર્ટી મેનેજમેન્ટ', 'વર્ચ્યુઅલ પ્રોપર્ટી કન્સલ્ટેશન']],
                ['title' => 'VIEW 03 — મેન્યુફેક્ચરિંગ બિઝનેસ', 'offers' => ['એન્યુઅલ સપ્લાય અપગ્રેડ', 'ન્યૂ બાયર પેકેજ', 'કસ્ટમ પ્રીમિયમ કમ્પોનન્ટ્સ', 'સ્ટાન્ડર્ડ પ્રોડક્ટ પેકેજ', 'બલ્ક મેન્યુફેક્ચરિંગ કોન્ટ્રાક્ટ', 'એન્યુઅલ સપ્લાય કોન્ટ્રાક્ટ', 'ઓનલાઈન B2B ઓર્ડરિંગ']],
                ['title' => 'VIEW 04 — લેમિનેશન બિઝનેસ', 'offers' => ['પ્રીમિયમ ડોક્યુમેન્ટ પેકેજ', 'સ્ટુડન્ટ ડોક્યુમેન્ટ પેકેજ', 'સર્ટિફિકેટ અને પોર્ટફોલિયો પેકેજ', 'બેસિક લેમિનેશન પેકેજ', 'સ્કૂલ / ઓફિસ પેકેજ', 'માસિક બિઝનેસ પ્લાન', 'ઓનલાઈન ડોક્યુમેન્ટ સર્વિસ']],
                ['title' => 'VIEW 05 — મિઠાઈ બિઝનેસ', 'offers' => ['તહેવાર ફેમિલી સ્વીટ બોક્સ', 'ફર્સ્ટ ઓર્ડર સ્વીટ બોક્સ', 'પ્રીમિયમ સેલિબ્રેશન હેમ્પર', 'મિની સ્વીટ પેક', 'કોર્પોરેટ ફેસ્ટિવલ ગિફ્ટિંગ', 'માસિક સ્વીટ સબ્સ્ક્રિપ્શન', 'ઓનલાઈન સ્વીટ ડિલિવરી']],
            ],
            'hi' => [
                ['title' => 'VIEW 01 — ज्वेलरी बिज़नेस', 'offers' => ['गोल्ड अपग्रेड ऑफर', 'फर्स्ट परचेज कलेक्शन', 'लक्ज़री डायमंड कलेक्शन', 'स्टार्टर ज्वेलरी कलेक्शन', 'कॉर्पोरेट गिफ्ट ज्वेलरी', 'ज्वेलरी परचेज प्लान', 'वर्चुअल ज्वेलरी शॉपिंग']],
                ['title' => 'VIEW 02 — रियल एस्टेट बिज़नेस', 'offers' => ['अपग्रेड / रेफरल प्रॉपर्टी', 'फर्स्ट-टाइम बायर प्रॉपर्टी', 'लक्ज़री प्रॉपर्टी', 'अफोर्डेबल प्रॉपर्टी', 'इनवेस्टर प्रॉपर्टी डील', 'प्रॉपर्टी मैनेजमेंट', 'वर्चुअल प्रॉपर्टी कंसल्टेशन']],
                ['title' => 'VIEW 03 — मैन्युफैक्चरिंग बिज़नेस', 'offers' => ['एनुअल सप्लाई अपग्रेड', 'न्यू बायर पैकेज', 'कस्टम प्रीमियम कंपोनेंट्स', 'स्टैंडर्ड प्रोडक्ट पैकेज', 'बल्क मैन्युफैक्चरिंग कॉन्ट्रैक्ट', 'एनुअल सप्लाई कॉन्ट्रैक्ट', 'ऑनलाइन B2B ऑर्डरिंग']],
                ['title' => 'VIEW 04 — लैमिनेशन बिज़नेस', 'offers' => ['प्रीमियम डॉक्यूमेंट पैकेज', 'स्टूडेंट डॉक्यूमेंट पैकेज', 'सर्टिफिकेट और पोर्टफोलियो पैकेज', 'बेसिक लैमिनेशन पैकेज', 'स्कूल / ऑफिस पैकेज', 'मासिक बिज़नेस प्लान', 'ऑनलाइन डॉक्यूमेंट सर्विस']],
                ['title' => 'VIEW 05 — मिठाई बिज़नेस', 'offers' => ['त्योहार फैमिली स्वीट बॉक्स', 'फर्स्ट ऑर्डर स्वीट बॉक्स', 'प्रीमियम सेलिब्रेशन हैम्पर', 'मिनी स्वीट पैक', 'कॉर्पोरेट फेस्टिवल गिफ्टिंग', 'मासिक स्वीट सब्सक्रिप्शन', 'ऑनलाइन स्वीट डिलीवरी']],
            ],
            'mr' => [
                ['title' => 'VIEW 01 — ज्वेलरी बिझनेस', 'offers' => ['गोल्ड अपग्रेड ऑफर', 'फर्स्ट परचेस कलेक्शन', 'लक्झरी डायमंड कलेक्शन', 'स्टार्टर ज्वेलरी कलेक्शन', 'कॉर्पोरेट गिफ्ट ज्वेलरी', 'ज्वेलरी परचेस प्लॅन', 'व्हर्च्युअल ज्वेलरी शॉपिंग']],
                ['title' => 'VIEW 02 — रिअल इस्टेट बिझनेस', 'offers' => ['अपग्रेड / रेफरल प्रॉपर्टी', 'फर्स्ट-टाइम बायर प्रॉपर्टी', 'लक्झरी प्रॉपर्टी', 'अफोर्डेबल प्रॉपर्टी', 'इन्व्हेस्टर प्रॉपर्टी डील', 'प्रॉपर्टी मॅनेजमेंट', 'व्हर्च्युअल प्रॉपर्टी कन्सल्टेशन']],
                ['title' => 'VIEW 03 — मॅन्युफॅक्चरिंग बिझनेस', 'offers' => ['अॅन्युअल सप्लाय अपग्रेड', 'न्यू बायर पॅकेज', 'कस्टम प्रीमियम कंपोनंट्स', 'स्टँडर्ड प्रॉडक्ट पॅकेज', 'बल्क मॅन्युफॅक्चरिंग कॉन्ट्रॅक्ट', 'अॅन्युअल सप्लाय कॉन्ट्रॅक्ट', 'ऑनलाइन B2B ऑर्डरिंग']],
                ['title' => 'VIEW 04 — लॅमिनेशन बिझनेस', 'offers' => ['प्रीमियम डॉक्युमेंट पॅकेज', 'स्टुडंट डॉक्युमेंट पॅकेज', 'सर्टिफिकेट आणि पोर्टफोलिओ पॅकेज', 'बेसिक लॅमिनेशन पॅकेज', 'स्कूल / ऑफिस पॅकेज', 'मासिक बिझनेस प्लॅन', 'ऑनलाइन डॉक्युमेंट सर्व्हिस']],
                ['title' => 'VIEW 05 — मिठाई बिझनेस', 'offers' => ['सण कुटुंब स्वीट बॉक्स', 'फर्स्ट ऑर्डर स्वीट बॉक्स', 'प्रीमियम सेलिब्रेशन हॅम्पर', 'मिनी स्वीट पॅक', 'कॉर्पोरेट फेस्टिव्हल गिफ्टिंग', 'मासिक स्वीट सबस्क्रिप्शन', 'ऑनलाइन स्वीट डिलिव्हरी']],
            ],
        ];

        return $packs[$lang] ?? $packs['en'];
    }
}
