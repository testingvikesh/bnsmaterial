<?php

namespace App\Support;

class MaterialTaglineMasterclass
{
    /**
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public static function for(array $facts): array
    {
        $biz = trim((string) ($facts['business_name'] ?? '')) ?: 'this business';
        $cat = trim((string) ($facts['category'] ?? '')) ?: 'Business';
        $intro = trim((string) ($facts['intro'] ?? '')) ?: $cat.' business';
        $product = trim((string) ($facts['products'] ?? '')) ?: $cat;
        $family = self::family($cat, $product, $intro);
        $examples = self::examples();
        $matched = collect($examples)->firstWhere('family', $family);
        $rows = is_array($matched)
            ? $matched['rows']
            : self::genericRows($biz, $product);

        return [
            'family' => $family,
            'business_name' => $biz,
            'category' => $cat,
            'intro' => $intro,
            'product' => $product,
            'member_name' => (string) ($facts['member_name'] ?? ''),
            'rows' => $rows,
            'examples' => $examples,
            'languages' => self::ui($biz),
        ];
    }

    public static function family(string $category, string $products = '', string $intro = ''): string
    {
        $hay = strtolower($category.' '.$products.' '.$intro);

        if (self::has($hay, ['jewel', 'gold', 'diamond', 'ornament'])) {
            return 'jewellery';
        }
        if (self::has($hay, ['real estate', 'propert', 'realtor', 'housing', 'plot'])) {
            return 'real_estate';
        }
        if (self::has($hay, ['school', 'college', 'educat', 'tuition', 'coaching', 'student', 'skill education'])) {
            return 'education';
        }
        if (self::has($hay, ['software', 'information tech', 'digital solution', 'saas', 'it company', 'tech company'])
            || preg_match('/(?:^|[\s\/])it(?:[\s\/]|$)/', $hay)) {
            return 'it';
        }
        if (self::has($hay, ['sweet', 'mithai', 'namkeen', 'bakery', 'farsan'])) {
            return 'sweets';
        }

        return 'generic';
    }

    /**
     * Five classroom examples shown for every member, each with a distinct background.
     *
     * @return list<array<string, mixed>>
     */
    public static function examples(): array
    {
        return [
            [
                'family' => 'real_estate',
                'code' => 'view-01',
                'no' => '01',
                'emoji' => '🏠',
                'title' => 'REAL ESTATE',
                'business' => 'Property Developer / Real Estate Consultant',
                'service' => 'Residential & Commercial Properties',
                'theme' => '#fff3e0',
                'accent' => '#e67e22',
                'rows' => self::pack([
                    ['Brand Identity', 'Building Better Tomorrows', 'વધુ સારા આવતીકાલનું નિર્માણ', 'बेहतर कल का निर्माण', 'बेहतर उद्याची निर्मिती'],
                    ['Customer Benefit', 'Find Your Right Space', 'તમારી યોગ્ય જગ્યા શોધો', 'अपनी सही जगह पाएं', 'तुमची योग्य जागा शोधा'],
                    ['Quality', 'Quality You Can Build On', 'ગુણવત્તા જેના પર ભરોસો કરી શકાય', 'भरोसेमंद गुणवत्ता', 'विश्वासार्ह गुणवत्ता'],
                    ['Trust', 'Your Property, Our Promise', 'તમારી મિલકત, અમારું વચન', 'आपकी संपत्ति, हमारा वादा', 'तुमची मालमत्ता, आमचे वचन'],
                    ['Innovation', 'Reimagining Real Estate', 'રિયલ એસ્ટેટમાં નવાચાર', 'रियल एस्टेट में नवाचार', 'रिअल इस्टेटमध्ये नवाचार'],
                    ['Value', 'More Value, Better Living', 'વધુ મૂલ્ય, વધુ સારું જીવન', 'अधिक मूल्य, बेहतर जीवन', 'अधिक मूल्य, उत्तम जीवन'],
                    ['Premium', 'Live Beyond Ordinary', 'સામાન્યથી આગળનું જીવન', 'सामान्य से बेहतर जीवन', 'सामान्यतेपलीकडचे जीवन'],
                    ['Experience', 'Experience Your Future Home', 'તમારા ભવિષ્યના ઘરનો અનુભવ', 'अपने भविष्य के घर का अनुभव', 'तुमच्या भविष्यातील घराचा अनुभव'],
                    ['Problem Solving', 'Making Property Simple', 'મિલકતને સરળ બનાવીએ', 'प्रॉपर्टी को आसान बनाएं', 'प्रॉपर्टी सोपी करूया'],
                    ['Emotional', 'Where Dreams Find an Address', 'જ્યાં સપનાઓને સરનામું મળે', 'जहां सपनों को पता मिलता है', 'जिथे स्वप्नांना पत्ता मिळतो'],
                    ['Local', 'Your City, Your Future', 'તમારું શહેર, તમારું ભવિષ્ય', 'आपका शहर, आपका भविष्य', 'तुमचे शहर, तुमचे भविष्य'],
                    ['National', 'Building India’s Future', 'ભારતનું ભવિષ્ય બનાવીએ', 'भारत का भविष्य बनाएं', 'भारताचे भविष्य घडवूया'],
                    ['Digital', 'Smart Property, Smarter Decisions', 'સ્માર્ટ પ્રોપર્ટી, સ્માર્ટ નિર્ણય', 'स्मार्ट प्रॉपर्टी, स्मार्ट निर्णय', 'स्मार्ट प्रॉपर्टी, स्मार्ट निर्णय'],
                    ['Global', 'Indian Spaces, Global Standards', 'ભારતીય જગ્યાઓ, વૈશ્વિક ધોરણો', 'भारतीय स्पेस, वैश्विक मानक', 'भारतीय जागा, जागतिक मानके'],
                    ['Business Empire', 'From Property to Possibility', 'પ્રોપર્ટીથી નવી સંભાવનાઓ સુધી', 'प्रॉपर्टी से नई संभावनाओं तक', 'प्रॉपर्टीपासून नव्या संधींपर्यंत'],
                ]),
            ],
            [
                'family' => 'education',
                'code' => 'view-02',
                'no' => '02',
                'emoji' => '🎓',
                'title' => 'EDUCATION BUSINESS',
                'business' => 'Education Institution',
                'service' => 'School / College / Skill Education',
                'theme' => '#e8f4ff',
                'accent' => '#2563eb',
                'rows' => self::pack([
                    ['Brand Identity', 'Education That Creates Leaders', 'નેતાઓ બનાવતું શિક્ષણ', 'नेतृत्व बनाने वाली शिक्षा', 'नेतृत्व घडवणारे शिक्षण'],
                    ['Customer Benefit', 'Learn Today, Lead Tomorrow', 'આજે શીખો, આવતીકાલે નેતૃત્વ કરો', 'आज सीखें, कल नेतृत्व करें', 'आज शिका, उद्या नेतृत्व करा'],
                    ['Quality', 'Excellence in Every Learner', 'દરેક શીખનારામાં શ્રેષ્ઠતા', 'हर विद्यार्थी में उत्कृष्टता', 'प्रत्येक विद्यार्थ्यात उत्कृष्टता'],
                    ['Trust', 'Your Child, Our Responsibility', 'તમારું બાળક, અમારી જવાબદારી', 'आपका बच्चा, हमारी जिम्मेदारी', 'तुमचे मूल, आमची जबाबदारी'],
                    ['Innovation', 'Reimagining Education', 'શિક્ષણમાં નવાચાર', 'शिक्षा में नवाचार', 'शिक्षणात नवाचार'],
                    ['Value', 'More Learning, More Possibilities', 'વધુ શિક્ષણ, વધુ સંભાવનાઓ', 'अधिक सीखना, अधिक संभावनाएं', 'अधिक शिक्षण, अधिक संधी'],
                    ['Premium', 'Where Excellence Becomes a Habit', 'જ્યાં શ્રેષ્ઠતા આદત બને', 'जहां उत्कृष्टता आदत बनती है', 'जिथे उत्कृष्टता सवय बनते'],
                    ['Experience', 'Learning Beyond the Classroom', 'વર્ગખંડથી આગળનું શિક્ષણ', 'कक्षा से परे सीखना', 'वर्गखोल्याच्या पलीकडचे शिक्षण'],
                    ['Problem Solving', 'Turning Challenges into Learning', 'પડકારોને શિક્ષણમાં ફેરવીએ', 'चुनौतियों को सीखने में बदलें', 'आव्हानांचे शिक्षणात रूपांतर'],
                    ['Emotional', 'Every Child Has a Future', 'દરેક બાળકનું ભવિષ્ય છે', 'हर बच्चे का भविष्य है', 'प्रत्येक मुलाचे भविष्य आहे'],
                    ['Local', 'Growing With Our Community', 'આપણા સમાજ સાથે વિકાસ', 'अपने समुदाय के साथ विकास', 'आपल्या समाजासोबत विकास'],
                    ['National', 'Educating New India', 'નવા ભારતનું શિક્ષણ', 'नए भारत को शिक्षित करना', 'नवभारत घडवणारे शिक्षण'],
                    ['Digital', 'Smart Learning, Smart Future', 'સ્માર્ટ લર્નિંગ, સ્માર્ટ ફ્યુચર', 'स्मार्ट लर्निंग, स्मार्ट भविष्य', 'स्मार्ट लर्निंग, स्मार्ट भविष्य'],
                    ['Global', 'Learning Without Borders', 'સીમાઓ વગરનું શિક્ષણ', 'सीमाओं से परे शिक्षा', 'सीमांच्या पलीकडचे शिक्षण'],
                    ['Business Empire', 'From School to Global Learning Network', 'સ્કૂલથી ગ્લોબલ લર્નિંગ નેટવર્ક સુધી', 'स्कूल से वैश्विक शिक्षा नेटवर्क तक', 'शाळेपासून जागतिक शिक्षण नेटवर्कपर्यंत'],
                ]),
            ],
            [
                'family' => 'it',
                'code' => 'view-03',
                'no' => '03',
                'emoji' => '💻',
                'title' => 'IT INDUSTRY',
                'business' => 'IT / Software Company',
                'service' => 'Digital Solutions',
                'theme' => '#eef2f7',
                'accent' => '#0a1d37',
                'rows' => self::pack([
                    ['Brand Identity', 'Technology That Moves Business', 'બિઝનેસને આગળ વધારતી ટેક્નોલોજી', 'बिज़नेस को आगे बढ़ाने वाली तकनीक', 'व्यवसायाला पुढे नेणारे तंत्रज्ञान'],
                    ['Customer Benefit', 'Simplify Your Digital World', 'તમારી ડિજિટલ દુનિયા સરળ બનાવો', 'अपनी डिजिटल दुनिया सरल बनाएं', 'तुमची डिजिटल दुनिया सोपी करा'],
                    ['Quality', 'Built for Performance', 'પરફોર્મન્સ માટે બનાવેલું', 'प्रदर्शन के लिए निर्मित', 'उत्तम कामगिरीसाठी निर्मित'],
                    ['Trust', 'Technology You Can Trust', 'વિશ્વાસપાત્ર ટેક્નોલોજી', 'भरोसेमंद तकनीक', 'विश्वासार्ह तंत्रज्ञान'],
                    ['Innovation', 'Innovate Without Limits', 'મર્યાદા વિના નવાચાર', 'बिना सीमाओं के नवाचार', 'मर्यादांशिवाय नवाचार'],
                    ['Value', 'More Technology, More Value', 'વધુ ટેક્નોલોજી, વધુ મૂલ્ય', 'अधिक तकनीक, अधिक मूल्य', 'अधिक तंत्रज्ञान, अधिक मूल्य'],
                    ['Premium', 'Enterprise Technology, Elevated', 'ઉચ્ચ સ્તરની એન્ટરપ્રાઇઝ ટેક્નોલોજી', 'उन्नत एंटरप्राइज तकनीक', 'प्रगत एंटरप्राइज तंत्रज्ञान'],
                    ['Experience', 'Technology Made Simple', 'સરળ બનાવેલી ટેક્નોલોજી', 'सरल बनाई गई तकनीक', 'सोपे केलेले तंत्रज्ञान'],
                    ['Problem Solving', 'Turning Problems into Solutions', 'સમસ્યાથી સોલ્યુશન સુધી', 'समस्या से समाधान तक', 'समस्येतून समाधानाकडे'],
                    ['Emotional', 'Your Vision, Our Technology', 'તમારું Vision, અમારી ટેક્નોલોજી', 'आपका विज़न, हमारी तकनीक', 'तुमचे Vision, आमचे तंत्रज्ञान'],
                    ['Local', 'Technology for Every Business', 'દરેક બિઝનેસ માટે ટેક્નોલોજી', 'हर व्यवसाय के लिए तकनीक', 'प्रत्येक व्यवसायासाठी तंत्रज्ञान'],
                    ['National', 'Powering Digital India', 'ડિજિટલ ભારતને શક્તિ આપીએ', 'डिजिटल भारत को शक्ति', 'डिजिटल भारताला शक्ती'],
                    ['Digital', 'Digital First, Future Ready', 'ડિજિટલ ફર્સ્ટ, ફ્યુચર રેડી', 'डिजिटल फर्स्ट, फ्यूचर रेडी', 'डिजिटल फर्स्ट, फ्युचर रेडी'],
                    ['Global', 'Build Local, Scale Global', 'સ્થાનિક બનાવો, વૈશ્વિક બનાવો', 'स्थानीय बनाएं, वैश्विक बढ़ाएं', 'स्थानिक बनवा, जागतिक करा'],
                    ['Business Empire', 'From IT Company to Technology Ecosystem', 'IT કંપનીથી ટેક્નોલોજી ઇકોસિસ્ટમ સુધી', 'IT कंपनी से टेक्नोलॉजी इकोसिस्टम तक', 'IT कंपनीपासून टेक्नॉलॉजी इकोसिस्टमपर्यंत'],
                ]),
            ],
            [
                'family' => 'sweets',
                'code' => 'view-04',
                'no' => '04',
                'emoji' => '🍬',
                'title' => 'SWEET SHOP',
                'business' => 'Sweet Shop',
                'service' => 'Indian Sweets & Gift Hampers',
                'theme' => '#ffe8f0',
                'accent' => '#db2777',
                'rows' => self::pack([
                    ['Brand Identity', 'Sweetness That Brings People Together', 'લોકોને જોડતી મીઠાશ', 'लोगों को जोड़ने वाली मिठास', 'माणसांना जोडणारी गोडी'],
                    ['Customer Benefit', 'Make Every Celebration Sweeter', 'દરેક ઉજવણીને વધુ મીઠી બનાવો', 'हर खुशी को और मीठा बनाएं', 'प्रत्येक आनंदोत्सव अधिक गोड करा'],
                    ['Quality', 'Pure Taste, Every Time', 'દરેક વખતે શુદ્ધ સ્વાદ', 'हर बार शुद्ध स्वाद', 'प्रत्येक वेळी शुद्ध चव'],
                    ['Trust', 'Tradition You Can Taste', 'સ્વાદમાં વિશ્વાસની પરંપરા', 'स्वाद में विश्वास की परंपरा', 'चवीत विश्वासाची परंपरा'],
                    ['Innovation', 'Reimagining Indian Sweets', 'ભારતીય મીઠાઈમાં નવાચાર', 'भारतीय मिठाइयों में नवाचार', 'भारतीय मिठाईत नवाचार'],
                    ['Value', 'More Taste, More Happiness', 'વધુ સ્વાદ, વધુ ખુશી', 'अधिक स्वाद, अधिक खुशी', 'अधिक चव, अधिक आनंद'],
                    ['Premium', 'Luxury in Every Bite', 'દરેક બાઇટમાં લક્ઝરી', 'हर बाइट में लग्ज़री', 'प्रत्येक घासात लक्झरी'],
                    ['Experience', 'Taste the Celebration', 'ઉજવણીનો સ્વાદ માણો', 'जश्न का स्वाद चखें', 'उत्सवाची चव अनुभवा'],
                    ['Problem Solving', 'The Perfect Sweet for Every Occasion', 'દરેક પ્રસંગ માટે પરફેક્ટ મીઠાઈ', 'हर अवसर के लिए सही मिठाई', 'प्रत्येक प्रसंगासाठी योग्य मिठाई'],
                    ['Emotional', 'A Taste of Happy Memories', 'ખુશ યાદોની મીઠાશ', 'खुश यादों का स्वाद', 'आनंदी आठवणींची गोडी'],
                    ['Local', 'Our City’s Sweet Tradition', 'આપણા શહેરની મીઠી પરંપરા', 'हमारे शहर की मीठी परंपरा', 'आपल्या शहराची गोड परंपरा'],
                    ['National', 'The Taste of India', 'ભારતનો સ્વાદ', 'भारत का स्वाद', 'भारताची चव'],
                    ['Digital', 'Sweets at Your Doorstep', 'મીઠાઈ તમારા દરવાજે', 'मिठाई आपके दरवाज़े पर', 'मिठाई तुमच्या दारात'],
                    ['Global', 'Indian Sweetness, Global Taste', 'ભારતીય મીઠાશ, વૈશ્વિક સ્વાદ', 'भारतीय मिठास, वैश्विक स्वाद', 'भारतीय गोडी, जागतिक चव'],
                    ['Business Empire', 'From Sweet Shop to Sweet Empire', 'મીઠાઈની દુકાનથી મીઠાઈ સામ્રાજ્ય સુધી', 'मिठाई की दुकान से मिठाई साम्राज्य तक', 'मिठाईच्या दुकानापासून मिठाई साम्राज्यापर्यंत'],
                ]),
            ],
            [
                'family' => 'jewellery',
                'code' => 'view-05',
                'no' => '05',
                'emoji' => '💎',
                'title' => 'JEWELLERY SHOP',
                'business' => 'Jewellery Store',
                'service' => 'Gold & Diamond Jewellery',
                'theme' => '#fff6d8',
                'accent' => '#ca8a04',
                'rows' => self::pack([
                    ['Brand Identity', 'Jewellery That Tells Your Story', 'તમારી કહાની કહેતી જ્વેલરી', 'आपकी कहानी कहने वाले आभूषण', 'तुमची कहाणी सांगणारे दागिने'],
                    ['Customer Benefit', 'Find the Perfect Expression', 'તમારી ઓળખનું પરફેક્ટ એક્સપ્રેશન', 'अपनी पहचान की सही अभिव्यक्ति', 'तुमच्या व्यक्तिमत्त्वाची योग्य अभिव्यक्ती'],
                    ['Quality', 'Crafted for Generations', 'પેઢીઓ માટે તૈયાર કરેલી', 'पीढ़ियों के लिए तैयार', 'पिढ्यान्‌पिढ्यांसाठी घडवलेले'],
                    ['Trust', 'Precious Moments, Trusted Craft', 'અમૂલ્ય ક્ષણો, વિશ્વાસપાત્ર કારીગરી', 'अनमोल पल, भरोसेमंद कारीगरी', 'अमूल्य क्षण, विश्वासार्ह कारागिरी'],
                    ['Innovation', 'Reimagining Jewellery', 'જ્વેલરીમાં નવાચાર', 'आभूषणों में नवाचार', 'दागिन्यांमध्ये नवाचार'],
                    ['Value', 'More Beauty, More Meaning', 'વધુ સુંદરતા, વધુ અર્થ', 'अधिक सुंदरता, अधिक अर्थ', 'अधिक सौंदर्य, अधिक अर्थ'],
                    ['Premium', 'Designed Beyond Ordinary', 'સામાન્યથી આગળની ડિઝાઇન', 'सामान्य से परे डिज़ाइन', 'सामान्यतेपलीकडील डिझाइन'],
                    ['Experience', 'Discover Your Signature Style', 'તમારી Signature Style શોધો', 'अपनी सिग्नेचर स्टाइल खोजें', 'तुमची सिग्नेचर स्टाइल शोधा'],
                    ['Problem Solving', 'The Right Jewellery for Every Moment', 'દરેક ક્ષણ માટે યોગ્ય જ્વેલરી', 'हर पल के लिए सही आभूषण', 'प्रत्येक क्षणासाठी योग्य दागिना'],
                    ['Emotional', 'Celebrate Moments That Matter', 'ખાસ ક્ષણોની ઉજવણી કરો', 'खास पलों का जश्न मनाएं', 'खास क्षणांचा उत्सव साजरा करा'],
                    ['Local', 'Crafted for Our Community', 'આપણા સમાજ માટે રચાયેલી', 'हमारे समुदाय के लिए निर्मित', 'आपल्या समाजासाठी घडवलेले'],
                    ['National', 'India’s Heritage, Reimagined', 'ભારતની વિરાસતનું નવસર્જન', 'भारत की विरासत का नया रूप', 'भारताच्या वारशाचे नवसर्जन'],
                    ['Digital', 'Your Jewellery, One Click Away', 'તમારી જ્વેલરી, એક ક્લિક દૂર', 'आपकी ज्वेलरी, एक क्लिक दूर', 'तुमचे दागिने, एका क्लिकवर'],
                    ['Global', 'Indian Craft, Global Elegance', 'ભારતીય કારીગરી, વૈશ્વિક એલિગન્સ', 'भारतीय कारीगरी, वैश्विक सुंदरता', 'भारतीय कारागिरी, जागतिक अभिजातता'],
                    ['Business Empire', 'From Jewellery Store to Global Jewellery House', 'જ્વેલરી સ્ટોરથી ગ્લોબલ જ્વેલરી હાઉસ સુધી', 'ज्वेलरी स्टोर से ग्लोबल ज्वेलरी हाउस तक', 'ज्वेलरी स्टोअरपासून ग्लोबल ज्वेलरी हाऊसपर्यंत'],
                ]),
            ],
        ];
    }

    /**
     * @param  list<array{0: string, 1: string, 2: string, 3: string, 4: string}>  $rows
     * @return list<array<string, string>>
     */
    private static function pack(array $rows): array
    {
        $out = [];
        foreach ($rows as $index => $row) {
            $out[] = [
                'no' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'category' => $row[0],
                'en' => $row[1],
                'gu' => $row[2],
                'hi' => $row[3],
                'mr' => $row[4],
            ];
        }

        return $out;
    }

    /**
     * @return list<array<string, string>>
     */
    public static function genericRows(string $biz, string $product): array
    {
        $noun = self::shortNoun($product);

        return self::pack([
            ['Brand Identity', $noun.' That Builds Trust', $noun.' જે વિશ્વાસ બનાવે', $noun.' जो विश्वास बनाता है', $noun.' जे विश्वास निर्माण करते'],
            ['Customer Benefit', 'Get More From '.$noun, $noun.'માંથી વધુ મેળવો', $noun.' से अधिक पाएं', $noun.'मधून अधिक मिळवा'],
            ['Quality', 'Quality You Can Count On', 'ગુણવત્તા જેના પર ભરોસો રાખી શકાય', 'भरोसेमंद गुणवत्ता', 'विश्वासार्ह गुणवत्ता'],
            ['Trust', 'Your '.$noun.', Our Promise', 'તમારું '.$noun.', અમારું વચન', 'आपका '.$noun.', हमारा वादा', 'तुमचे '.$noun.', आमचे वचन'],
            ['Innovation', 'Reimagining '.$noun, $noun.'માં નવાચાર', $noun.' में नवाचार', $noun.'मध्ये नवाचार'],
            ['Value', 'More Value, Better Results', 'વધુ મૂલ્ય, વધુ સારું પરિણામ', 'अधिक मूल्य, बेहतर परिणाम', 'अधिक मूल्य, उत्तम निकाल'],
            ['Premium', 'Beyond Ordinary '.$noun, 'સામાન્યથી આગળનું '.$noun, 'सामान्य से परे '.$noun, 'सामान्यतेपलीकडील '.$noun],
            ['Experience', 'Experience '.$biz, $biz.'નો અનુભવ', $biz.' का अनुभव', $biz.' चा अनुभव'],
            ['Problem Solving', 'Making '.$noun.' Simple', $noun.'ને સરળ બનાવીએ', $noun.' को आसान बनाएं', $noun.' सोपी करूया'],
            ['Emotional', 'Where '.$noun.' Meets Trust', 'જ્યાં '.$noun.' વિશ્વાસ મળે', 'जहां '.$noun.' से विश्वास मिलता है', 'जिथे '.$noun.' विश्वास देते'],
            ['Local', 'For Our Community', 'આપણા સમાજ માટે', 'हमारे समुदाय के लिए', 'आपल्या समाजासाठी'],
            ['National', 'Building India’s '.$noun, 'ભારતનું '.$noun.' બનાવીએ', 'भारत का '.$noun.' बनाएं', 'भारताचे '.$noun.' घडवूया'],
            ['Digital', $noun.', One Click Away', $noun.', એક ક્લિક દૂર', $noun.', एक क्लिक दूर', $noun.', एका क्लिकवर'],
            ['Global', 'Indian Craft, Global Standard', 'ભારતીય કારીગરી, વૈશ્વિક ધોરણ', 'भारतीय कारीगरी, वैश्विक मानक', 'भारतीय कारागिरी, जागतिक मानक'],
            ['Business Empire', 'From '.$biz.' to Business Empire', $biz.'થી બિઝનેસ એમ્પાયર સુધી', $biz.' से बिज़नेस एम्पायर तक', $biz.' पासून बिझनेस एम्पायरपर्यंत'],
        ]);
    }

    /**
     * @return array<string, array{ui: array<string, string>}>
     */
    public static function ui(string $businessName): array
    {
        return [
            'en' => ['ui' => [
                'kicker' => 'BNS Tagline Masterclass',
                'pageSubtitle' => 'What does the business do? → What do people remember?',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'yourTitle' => 'Your 15 Business Taglines',
                'yourNote' => 'Generated from this member’s business. Identity + Customer Value + Trust + Innovation + Experience + Future Vision.',
                'examplesTitle' => 'Classroom examples — shown for every member',
                'thNo' => 'No.',
                'thCategory' => 'Category',
                'thEn' => 'English',
                'thGu' => 'ગુજરાતી',
                'thHi' => 'हिन्दी',
                'thMr' => 'मराठी',
                'bizLabel' => 'Business:',
                'serviceLabel' => 'Main Service:',
                'takeawayTitle' => 'BNS Coach — Classroom Takeaway',
                'takeaway' => 'A tagline is not only a beautiful sentence. It must show Identity + Customer Value + Trust + Innovation + Experience + Future Vision.',
                'formulaTitle' => 'BNS Tagline Formula',
                'formula' => 'WHO WE ARE + WHAT VALUE WE CREATE + WHAT MAKES US DIFFERENT + WHERE WE WANT TO GO → POWERFUL BUSINESS TAGLINE',
                'nav01' => 'VIEW 01 Real Estate',
                'nav02' => 'VIEW 02 Education',
                'nav03' => 'VIEW 03 IT',
                'nav04' => 'VIEW 04 Sweets',
                'nav05' => 'VIEW 05 Jewellery',
                'footer' => $businessName.' — BNS Tagline Masterclass · 15 taglines · 4 languages · 5 classroom examples',
            ]],
            'gu' => ['ui' => [
                'kicker' => 'BNS ટેગલાઈન માસ્ટરક્લાસ',
                'pageSubtitle' => 'બિઝનેસ શું કરે છે? → લોકો શું યાદ રાખે?',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'yourTitle' => 'તમારી 15 બિઝનેસ ટેગલાઈન',
                'yourNote' => 'આ મેમ્બરના બિઝનેસ પરથી. Identity + Customer Value + Trust + Innovation + Experience + Future Vision.',
                'examplesTitle' => 'ક્લાસરૂમ ઉદાહરણ — દરેક મેમ્બર માટે',
                'thNo' => 'નં.',
                'thCategory' => 'કેટેગરી',
                'thEn' => 'English',
                'thGu' => 'ગુજરાતી',
                'thHi' => 'हिन्दी',
                'thMr' => 'मराठी',
                'bizLabel' => 'બિઝનેસ:',
                'serviceLabel' => 'મુખ્ય સર્વિસ:',
                'takeawayTitle' => 'BNS કોચ — ક્લાસરૂમ ટેકઅવે',
                'takeaway' => 'ટેગલાઈન માત્ર સુંદર વાક્ય નથી. તેમાં Identity + Customer Value + Trust + Innovation + Experience + Future Vision દેખાવા જોઈએ.',
                'formulaTitle' => 'BNS ટેગલાઈન ફોર્મ્યુલા',
                'formula' => 'WHO WE ARE + WHAT VALUE WE CREATE + WHAT MAKES US DIFFERENT + WHERE WE WANT TO GO → POWERFUL BUSINESS TAGLINE',
                'nav01' => 'VIEW 01 રિયલ એસ્ટેટ',
                'nav02' => 'VIEW 02 એજ્યુકેશન',
                'nav03' => 'VIEW 03 IT',
                'nav04' => 'VIEW 04 મિઠાઈ',
                'nav05' => 'VIEW 05 જ્વેલરી',
                'footer' => $businessName.' — BNS ટેગલાઈન માસ્ટરક્લાસ · 15 ટેગલાઈન · 4 ભાષા · 5 ક્લાસરૂમ ઉદાહરણ',
            ]],
            'hi' => ['ui' => [
                'kicker' => 'BNS टैगलाइन मास्टरक्लास',
                'pageSubtitle' => 'बिज़नेस क्या करता है? → लोग क्या याद रखते हैं?',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'yourTitle' => 'आपकी 15 बिज़नेस टैगलाइन',
                'yourNote' => 'इस मेंबर के बिज़नेस से. Identity + Customer Value + Trust + Innovation + Experience + Future Vision.',
                'examplesTitle' => 'क्लासरूम उदाहरण — हर मेंबर के लिए',
                'thNo' => 'नं.',
                'thCategory' => 'केटेगरी',
                'thEn' => 'English',
                'thGu' => 'ગુજરાતી',
                'thHi' => 'हिन्दी',
                'thMr' => 'मराठी',
                'bizLabel' => 'बिज़नेस:',
                'serviceLabel' => 'मुख्य सर्विस:',
                'takeawayTitle' => 'BNS कोच — क्लासरूम टेकअवे',
                'takeaway' => 'टैगलाइन केवल सुंदर वाक्य नहीं है। उसमें Identity + Customer Value + Trust + Innovation + Experience + Future Vision दिखना चाहिए।',
                'formulaTitle' => 'BNS टैगलाइन फॉर्मूला',
                'formula' => 'WHO WE ARE + WHAT VALUE WE CREATE + WHAT MAKES US DIFFERENT + WHERE WE WANT TO GO → POWERFUL BUSINESS TAGLINE',
                'nav01' => 'VIEW 01 रियल एस्टेट',
                'nav02' => 'VIEW 02 एजुकेशन',
                'nav03' => 'VIEW 03 IT',
                'nav04' => 'VIEW 04 मिठाई',
                'nav05' => 'VIEW 05 ज्वेलरी',
                'footer' => $businessName.' — BNS टैगलाइन मास्टरक्लास · 15 टैगलाइन · 4 भाषा · 5 क्लासरूम उदाहरण',
            ]],
            'mr' => ['ui' => [
                'kicker' => 'BNS टॅगलाइन मास्टरक्लास',
                'pageSubtitle' => 'बिझनेस काय करतो? → लोक काय लक्षात ठेवतात?',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'yourTitle' => 'तुमच्या 15 बिझनेस टॅगलाइन',
                'yourNote' => 'या मेंबरच्या बिझनेसवरून. Identity + Customer Value + Trust + Innovation + Experience + Future Vision.',
                'examplesTitle' => 'क्लासरूम उदाहरण — प्रत्येक मेंबरसाठी',
                'thNo' => 'क्र.',
                'thCategory' => 'केटेगरी',
                'thEn' => 'English',
                'thGu' => 'ગુજરાતી',
                'thHi' => 'हिन्दी',
                'thMr' => 'मराठी',
                'bizLabel' => 'बिझनेस:',
                'serviceLabel' => 'मुख्य सर्व्हिस:',
                'takeawayTitle' => 'BNS कोच — क्लासरूम टेकअवे',
                'takeaway' => 'टॅगलाइन केवळ सुंदर वाक्य नाही. त्यात Identity + Customer Value + Trust + Innovation + Experience + Future Vision दिसले पाहिजे.',
                'formulaTitle' => 'BNS टॅगलाइन फॉर्म्युला',
                'formula' => 'WHO WE ARE + WHAT VALUE WE CREATE + WHAT MAKES US DIFFERENT + WHERE WE WANT TO GO → POWERFUL BUSINESS TAGLINE',
                'nav01' => 'VIEW 01 रिअल इस्टेट',
                'nav02' => 'VIEW 02 एज्युकेशन',
                'nav03' => 'VIEW 03 IT',
                'nav04' => 'VIEW 04 मिठाई',
                'nav05' => 'VIEW 05 ज्वेलरी',
                'footer' => $businessName.' — BNS टॅगलाइन मास्टरक्लास · 15 टॅगलाइन · 4 भाषा · 5 क्लासरूम उदाहरण',
            ]],
        ];
    }

    private static function shortNoun(string $product): string
    {
        $part = trim(strtok(str_replace(['/', ',', ';'], ' ', $product), ' ') ?: $product);

        return $part !== '' ? $part : 'Business';
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
