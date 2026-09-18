<?php

namespace App\Support;

class MaterialWebsiteCopy
{
    /**
     * @param  array<string, mixed>  $draft
     * @return array<string, mixed>
     */
    public static function forLanguage(array $draft, string $lang): array
    {
        $ctx = self::context($draft);
        $ctx['lang'] = $lang;

        return MaterialIndicScript::apply($lang, [
            'kicker' => self::word($lang, $ctx['cat']),
            'headline' => $ctx['biz'],
            'subheadline' => trim(self::word($lang, $ctx['cat']).self::place($lang, $ctx['city'])),
            'tagline' => self::tagline($lang, $ctx),
            'usp' => self::usp($lang, $ctx),
            'cta' => self::cta($lang, $ctx['family']),
            'profile' => self::profileCopy($draft, $lang, $ctx),
            'sections' => self::sectionCopy($draft, $lang, $ctx),
            'seo' => self::seoCopy($lang, $ctx, is_array($draft['seo'] ?? null) ? $draft['seo'] : []),
            'journey' => self::journeyCopy($lang, is_array($draft['journey'] ?? null) ? $draft['journey'] : []),
            'report' => self::reportCopy($lang, is_array($draft['report'] ?? null) ? $draft['report'] : []),
        ], $ctx['keep']);
    }

    /**
     * @param  array<string, mixed>  $draft
     * @return array<string, string>
     */
    private static function context(array $draft): array
    {
        $profile = is_array($draft['profile'] ?? null) ? $draft['profile'] : [];
        $biz = (string) ($draft['business_name'] ?? '');
        $cat = (string) ((is_array($profile['business_category'] ?? null) ? ($profile['business_category']['text'] ?? '') : '') ?: 'Business');
        $product = (string) (is_array($profile['main_product'] ?? null) ? ($profile['main_product']['text'] ?? '') : '');
        $offer = MaterialWebsiteDraft::shortLine($product !== '' ? $product : $cat);
        $cityRaw = (string) ((is_array($profile['city'] ?? null) ? ($profile['city']['text'] ?? '') : '')
            ?: (is_array($profile['location'] ?? null) ? ($profile['location']['text'] ?? '') : ''));
        $city = str_contains(strtolower($cityRaw), 'required') ? '' : $cityRaw;
        $memberRaw = (string) ((is_array($profile['member_name'] ?? null) ? ($profile['member_name']['text'] ?? '') : '') ?: ($draft['member_name'] ?? ''));
        $member = $memberRaw;
        $intro = (string) (is_array($profile['business_description'] ?? null) ? ($profile['business_description']['text'] ?? '') : '');
        $family = MaterialTaglineMasterclass::family($cat, $offer, $intro);
        $keep = MaterialIndicScript::keepNames($biz, $member);

        return compact('biz', 'cat', 'offer', 'city', 'member', 'family', 'keep');
    }

    /**
     * @param  array<string, mixed>  $draft
     * @param  array<string, string>  $ctx
     * @return array<string, array<string, mixed>>
     */
    private static function profileCopy(array $draft, string $lang, array $ctx): array
    {
        $out = [];
        foreach (is_array($draft['profile'] ?? null) ? $draft['profile'] : [] as $key => $item) {
            $item = is_array($item) ? $item : ['text' => (string) $item, 'status' => 'inferred'];
            $out[$key] = self::fieldCopy($lang, 'profile.'.$key, (string) ($item['text'] ?? ''), (string) ($item['status'] ?? ''), $ctx);
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $draft
     * @param  array<string, string>  $ctx
     * @return array<string, list<array<string, mixed>>>
     */
    private static function sectionCopy(array $draft, string $lang, array $ctx): array
    {
        $out = [];
        foreach (is_array($draft['sections'] ?? null) ? $draft['sections'] : [] as $section) {
            if (! is_array($section)) {
                continue;
            }
            $code = (string) ($section['code'] ?? '');
            $blocks = [];
            foreach (is_array($section['blocks'] ?? null) ? $section['blocks'] : [] as $block) {
                if (! is_array($block)) {
                    continue;
                }
                $label = (string) ($block['label'] ?? '');
                $status = (string) ($block['status'] ?? '');
                $text = self::blockText($lang, $code, $label, (string) ($block['text'] ?? ''), $status, $ctx);
                $points = MaterialCopyPoints::from($text);
                $blocks[] = [
                    'label' => MaterialIndicScript::phrase($lang, $label, $ctx['keep'] ?? []),
                    'text' => $text,
                    'points' => $points,
                    'status' => $status,
                ];
            }
            $out[$code] = $blocks;
        }

        return $out;
    }

    /**
     * @param  array<string, string>  $ctx
     */
    private static function blockText(string $lang, string $code, string $label, string $original, string $status, array $ctx): string
    {
        if ($lang === 'en') {
            return $original;
        }

        if (self::keepOriginal($label, $original, $status)) {
            return MaterialIndicScript::phrase($lang, $original, $ctx['keep'] ?? []);
        }

        $key = $code.'.'.self::slug($label);
        $template = self::templates($lang)[$key] ?? self::templates($lang)[$label] ?? null;
        if (is_string($template) && $template !== '') {
            return MaterialIndicScript::phrase($lang, self::fill($template, $lang, $ctx), $ctx['keep'] ?? []);
        }

        if ($code === 'process') {
            $steps = self::processSteps($lang, $ctx['family']);
            $index = (int) substr($label, -1) - 1;

            return MaterialIndicScript::phrase($lang, $steps[$index] ?? $original, $ctx['keep'] ?? []);
        }

        if ($code === 'problem' && $label === 'Problems') {
            return implode(' • ', self::problems($lang, $ctx['family']));
        }

        if ($code === 'knowledge') {
            return self::knowledge($lang, $label, $ctx);
        }

        if ($code === 'gallery' && $label === 'Image categories') {
            return implode(' • ', self::gallery($lang, $ctx));
        }

        if ($code === 'videos' && $label === 'Video topics') {
            return implode(' • ', self::videos($lang, $ctx));
        }

        return MaterialIndicScript::phrase($lang, $original, $ctx['keep'] ?? []);
    }

    /**
     * @param  array<string, string>  $ctx
     * @return array<string, mixed>
     */
    private static function fieldCopy(string $lang, string $key, string $original, string $status, array $ctx): array
    {
        $text = $original;
        if ($lang !== 'en') {
            if (! self::keepOriginal($key, $original, $status)) {
                $template = self::templates($lang)[$key] ?? null;
                if (is_string($template) && $template !== '') {
                    $text = self::fill($template, $lang, $ctx);
                }
            }
            $text = MaterialIndicScript::phrase($lang, $text, $ctx['keep'] ?? []);
        }
        $points = MaterialCopyPoints::from($text);

        return ['text' => $text, 'points' => $points, 'status' => $status];
    }

    private static function keepOriginal(string $label, string $original, string $status): bool
    {
        $label = strtolower($label);
        if (preg_match('/^https?:|@|\.com|\.in|instagram|facebook|linkedin|youtube/i', $original)) {
            return true;
        }
        if (preg_match('/^\+?\d[\d\s\-()]{6,}$/', $original)) {
            return true;
        }
        $locked = ['headline', 'phone', 'address', 'website', 'instagram', 'facebook', 'linkedin', 'youtube', 'google_business', 'member_name', 'member_id', 'business_name', 'profile.phone', 'profile.address', 'profile.website', 'profile.instagram', 'profile.facebook', 'profile.linkedin', 'profile.youtube', 'profile.google_business', 'profile.member_name', 'profile.member_id', 'profile.business_name', 'profile.city', 'profile.location'];

        return in_array($label, $locked, true);
    }

    /**
     * @param  array<string, string>  $ctx
     * @param  array<string, string>  $seo
     * @return array<string, string>
     */
    private static function seoCopy(string $lang, array $ctx, array $seo): array
    {
        if ($lang === 'en') {
            return $seo;
        }
        $place = self::place($lang, $ctx['city']);
        $cat = self::word($lang, $ctx['cat']);

        return [
            'primary_keyword' => $ctx['offer'].$place,
            'secondary_keywords' => $cat.', '.$ctx['offer'].', '.$ctx['biz'],
            'local_keywords' => $cat.$place,
            'seo_title' => $ctx['biz'].' | '.$ctx['offer'].$place,
            'meta_description' => self::fill(self::templates($lang)['seo.meta'] ?? '{biz} {offer}.', $lang, $ctx),
            'h1' => $ctx['biz'],
            'h2' => self::fill(self::templates($lang)['seo.h2'] ?? '{offer}', $lang, $ctx),
            'faq_keywords' => $ctx['offer'].', '.$ctx['biz'],
            'image_alt' => $ctx['biz'].' '.$ctx['offer'].' '.$cat,
            'url_slug' => (string) ($seo['url_slug'] ?? ''),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $journey
     * @return list<array<string, string>>
     */
    private static function journeyCopy(string $lang, array $journey): array
    {
        $questions = self::journeyQuestions($lang);
        $out = [];
        foreach ($journey as $index => $item) {
            $item = is_array($item) ? $item : [];
            $status = (string) ($item['status'] ?? 'required');
            $out[] = [
                'question' => $questions[$index] ?? (string) ($item['question'] ?? ''),
                'answer' => $status === 'required'
                    ? (self::templates($lang)['journey.pending'] ?? 'Pending')
                    : (self::templates($lang)['journey.covered'] ?? 'Covered'),
                'status' => $status,
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $report
     * @return array<string, string>
     */
    private static function reportCopy(string $lang, array $report): array
    {
        if ($lang === 'en') {
            return array_map(fn ($value) => is_scalar($value) ? (string) $value : '', $report);
        }
        $ready = self::word($lang, 'Ready');
        $pending = self::word($lang, 'Pending');
        $out = [];
        foreach ($report as $key => $value) {
            if (! is_scalar($value)) {
                continue;
            }
            $text = (string) $value;
            $text = str_replace(['Ready', 'Pending', 'Sections'], [$ready, $pending, self::word($lang, 'Sections')], $text);
            $out[$key] = $text;
        }

        return $out;
    }

    /**
     * @param  array<string, string>  $ctx
     */
    private static function fill(string $template, string $lang, array $ctx): string
    {
        return strtr($template, [
            '{biz}' => MaterialIndicScript::phrase($lang, $ctx['biz'], $ctx['keep'] ?? []),
            '{offer}' => MaterialIndicScript::phrase($lang, $ctx['offer'], $ctx['keep'] ?? []),
            '{cat}' => MaterialIndicScript::phrase($lang, self::word($lang, $ctx['cat']), $ctx['keep'] ?? []),
            '{city}' => MaterialIndicScript::phrase($lang, self::word($lang, $ctx['city']), $ctx['keep'] ?? []),
            '{place}' => self::place($lang, $ctx['city']),
            '{cta}' => self::cta($lang, $ctx['family']),
            '{member}' => $ctx['member'] !== '' && ! str_contains(strtolower($ctx['member']), 'required') ? $ctx['member'] : self::word($lang, 'Founder'),
        ]);
    }

    private static function place(string $lang, string $city): string
    {
        $city = trim($city);
        if ($city === '' || str_contains(strtolower($city), 'required')) {
            return '';
        }
        $name = self::word($lang, $city);

        return match ($lang) {
            'gu' => ' '.$name.'માં',
            'hi' => ' '.$name.' में',
            'mr' => ' '.$name.'त',
            default => ' in '.$name,
        };
    }

    private static function word(string $lang, string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }
        $map = [
            'gu' => [
                'Food' => 'ફૂડ',
                'Trading' => 'ટ્રેડિંગ',
                'Jewellery' => 'જ્વેલરી',
                'Education' => 'એજ્યુકેશન',
                'Business' => 'બિઝનેસ',
                'Mumbai' => 'મુંબઈ',
                'Ready' => 'રેડી',
                'Pending' => 'પેન્ડિંગ',
                'Sections' => 'સેક્શન',
                'Founder' => 'ફાઉન્ડર',
                'Local' => 'લોકલ',
            ],
            'hi' => [
                'Food' => 'फ़ूड',
                'Trading' => 'ट्रेडिंग',
                'Jewellery' => 'ज्वेलरी',
                'Education' => 'एजुकेशन',
                'Business' => 'बिज़नेस',
                'Mumbai' => 'मुंबई',
                'Ready' => 'रेडी',
                'Pending' => 'पेंडिंग',
                'Sections' => 'सेक्शन',
                'Founder' => 'फाउंडर',
                'Local' => 'लोकल',
            ],
            'mr' => [
                'Food' => 'फूड',
                'Trading' => 'ट्रेडिंग',
                'Jewellery' => 'ज्वेलरी',
                'Education' => 'एज्युकेशन',
                'Business' => 'बिझनेस',
                'Mumbai' => 'मुंबई',
                'Ready' => 'रेडी',
                'Pending' => 'पेंडिंग',
                'Sections' => 'सेक्शन',
                'Founder' => 'फाउंडर',
                'Local' => 'लोकल',
            ],
        ];

        return $map[$lang][$text] ?? $text;
    }

    /**
     * @param  array<string, string>  $ctx
     */
    private static function tagline(string $lang, array $ctx): string
    {
        $family = $ctx['family'];
        $lines = [
            'gu' => [
                'jewellery' => 'તમારી કહાની કહેતી જ્વેલરી',
                'real_estate' => 'તમારા આગલા પ્રકરણ માટે યોગ્ય જગ્યા',
                'education' => 'આજે શીખો, કાલે આગળ વધો',
                'it' => 'બિઝનેસને આગળ ધપાવતી ટેક્નોલોજી',
                'sweets' => 'દરેક ઉજવણી માટે મિઠાસ',
                'default' => '{offer} જેના પર વિશ્વાસ કરી શકાય',
            ],
            'hi' => [
                'jewellery' => 'आपकी कहानी कहती ज्वेलरी',
                'real_estate' => 'आपके अगले अध्याय के लिए सही जगह',
                'education' => 'आज सीखें, कल आगे बढ़ें',
                'it' => 'बिज़नेस को आगे बढ़ाने वाली टेक्नोलॉजी',
                'sweets' => 'हर उत्सव के लिए मिठास',
                'default' => '{offer} जिस पर भरोसा किया जा सके',
            ],
            'mr' => [
                'jewellery' => 'तुमची गोष्ट सांगणारी ज्वेलरी',
                'real_estate' => 'तुमच्या पुढच्या प्रकरणासाठी योग्य जागा',
                'education' => 'आज शिका, उद्या पुढे जा',
                'it' => 'बिझनेस पुढे नेणारी टेक्नॉलॉजी',
                'sweets' => 'प्रत्येक उत्सवासाठी गोडी',
                'default' => '{offer} ज्यावर विश्वास ठेवता येईल',
            ],
        ];
        $template = $lines[$lang][$family] ?? $lines[$lang]['default'] ?? '{offer} you can trust';

        return self::fill($template, $lang, $ctx);
    }

    /**
     * @param  array<string, string>  $ctx
     */
    private static function usp(string $lang, array $ctx): string
    {
        $template = self::templates($lang)['hero.usp'] ?? '{biz} {offer}';

        return self::fill($template, $lang, $ctx);
    }

    private static function cta(string $lang, string $family): string
    {
        $map = [
            'gu' => [
                'real_estate' => 'કન્સલ્ટેશન બુક કરો',
                'it' => 'ક્વોટ મેળવો',
                'sweets' => 'ઓર્ડર કરો',
                'default' => 'હવે પૂછો',
            ],
            'hi' => [
                'real_estate' => 'कंसल्टेशन बुक करें',
                'it' => 'कोट पाएं',
                'sweets' => 'ऑर्डर करें',
                'default' => 'अभी पूछें',
            ],
            'mr' => [
                'real_estate' => 'कन्सल्टेशन बुक करा',
                'it' => 'कोट मिळवा',
                'sweets' => 'ऑर्डर करा',
                'default' => 'आता विचारा',
            ],
        ];

        return $map[$lang][$family] ?? $map[$lang]['default'] ?? 'Enquire Now';
    }

    /**
     * @return list<string>
     */
    private static function problems(string $lang, string $family): array
    {
        $packs = [
            'gu' => [
                'default' => ['ઘણા સરખા વિકલ્પો', 'અસ્પષ્ટ કિંમત પ્રક્રિયા', 'ધીમો જવાબ', 'પહેલા ઓર્ડર પહેલાં ઓછો વિશ્વાસ'],
            ],
            'hi' => [
                'default' => ['बहुत से एक जैसे विकल्प', 'अस्पष्ट कीमत प्रक्रिया', 'धीमा जवाब', 'पहले ऑर्डर से पहले कम भरोसा'],
            ],
            'mr' => [
                'default' => ['खूप सारखे पर्याय', 'अस्पष्ट किंमत प्रक्रिया', 'मंद उत्तर', 'पहिल्या ऑर्डरपूर्वी कमी विश्वास'],
            ],
        ];

        return $packs[$lang][$family] ?? $packs[$lang]['default'] ?? [];
    }

    /**
     * @return list<string>
     */
    private static function processSteps(string $lang, string $family): array
    {
        $packs = [
            'gu' => ['જરૂરિયાત જણાવો', 'સ્પષ્ટ વિકલ્પ જુઓ', 'વિગત અને ક્વોટ કન્ફર્મ કરો', 'ડિલિવરી અને ફોલો-અપ'],
            'hi' => ['ज़रूरत बताएं', 'स्पष्ट विकल्प देखें', 'डिटेल और कोट कन्फ़र्म करें', 'डिलीवरी और फॉलो-अप'],
            'mr' => ['गरज सांगा', 'स्पष्ट पर्याय पहा', 'तपशील आणि कोट कन्फर्म करा', 'डिलिव्हरी आणि फॉलो-अप'],
        ];

        return $packs[$lang] ?? ['Tell us the requirement', 'See a clear option', 'Confirm details and quote', 'Deliver and follow up'];
    }

    /**
     * @param  array<string, string>  $ctx
     */
    private static function knowledge(string $lang, string $label, array $ctx): string
    {
        $offer = $ctx['offer'];
        $biz = $ctx['biz'];
        $cat = self::word($lang, $ctx['cat']);
        $city = self::word($lang, $ctx['city']);
        $lists = [
            'gu' => [
                '10 blog topics' => [
                    $offer.' કેવી રીતે પસંદ કરવું',
                    $offer.' ખરીદતી વખતે સામાન્ય ભૂલો',
                    'કન્ફર્મ કરતા પહેલાં શું પૂછવું',
                    'કેર અને આફ્ટર-યુઝ ટિપ્સ',
                    $cat.' બાયિંગ ગાઈડ',
                    'પહેલી કન્સલ્ટેશન કેવી રીતે થાય',
                    $biz.'ને બ્રીફ કેવી રીતે આપવો',
                    ($city !== '' ? $city.' માટે લોકલ ગાઈડ' : 'લોકલ ગાઈડ'),
                    'ક્યારે અપગ્રેડ અથવા રિપીટ કરવું',
                    'ગ્રાહકો સૌથી વધુ શું પૂછે છે',
                ],
                '10 customer education topics' => ['ક્વોલિટી ચેકલિસ્ટ', 'ટાઈમલાઈન અપેક્ષા', 'કિંમતને શું અસર કરે', 'કસ્ટમ વર્ક કેવી રીતે થાય', 'રેફરન્સ કેવી રીતે શેર કરવા', 'આફ્ટર-કેર બેઝિક્સ', 'ક્વોટ ક્યારે જોઈએ', 'વિકલ્પો ન્યાયથી કેવી રીતે સરખાવવા', 'એન્ક્વાયરી પછી શું થાય', 'સંપર્કમાં કેવી રીતે રહેવું'],
                '10 FAQ topics' => [$biz.' શું આપે છે?', 'આ કોના માટે છે?', 'શરૂઆત કેવી રીતે કરવી?', 'શું એપોઈન્ટમેન્ટ પર કામ થાય?', 'કેટલો સમય લાગે?', 'ક્વોટ કેવી રીતે મળે?', 'કઈ માહિતી શેર કરવી?', 'શું ડિલિવરી / વિઝિટ થાય?', 'સંપર્ક કેવી રીતે કરવો?', 'પહેલી મુલાકાત પછી શું થાય?'],
            ],
            'hi' => [
                '10 blog topics' => [
                    $offer.' कैसे चुनें',
                    $offer.' खरीदते समय आम गलतियाँ',
                    'कन्फ़र्म करने से पहले क्या पूछें',
                    'केयर और आफ्टर-यूज़ टिप्स',
                    $cat.' बाइंग गाइड',
                    'पहली कंसल्टेशन कैसे होती है',
                    $biz.' को ब्रीफ कैसे दें',
                    ($city !== '' ? $city.' के लिए लोकल गाइड' : 'लोकल गाइड'),
                    'अपग्रेड या रिपीट कब करें',
                    'ग्राहक सबसे ज़्यादा क्या पूछते हैं',
                ],
                '10 customer education topics' => ['क्वालिटी चेकलिस्ट', 'टाइमलाइन अपेक्षा', 'कीमत को क्या प्रभावित करता है', 'कस्टम वर्क कैसे होता है', 'रेफ़रेंस कैसे शेयर करें', 'आफ्टर-केयर बेसिक्स', 'कोट कब चाहिए', 'विकल्पों की सही तुलना', 'इंक्वायरी के बाद क्या होता है', 'संपर्क में कैसे रहें'],
                '10 FAQ topics' => [$biz.' क्या देता है?', 'यह किसके लिए है?', 'शुरू कैसे करें?', 'क्या अपॉइंटमेंट पर काम होता है?', 'कितना समय लगता है?', 'कोट कैसे मिले?', 'क्या जानकारी शेयर करें?', 'क्या डिलीवरी / विज़िट होती है?', 'संपर्क कैसे करें?', 'पहली मुलाकात के बाद क्या होता है?'],
            ],
            'mr' => [
                '10 blog topics' => [
                    $offer.' कसा निवडावा',
                    $offer.' खरेदी करताना सामान्य चुका',
                    'कन्फर्म करण्याआधी काय विचारावे',
                    'केअर आणि आफ्टर-यूज टिप्स',
                    $cat.' बाइंग गाइड',
                    'पहिली कन्सल्टेशन कशी होते',
                    $biz.'ला ब्रीफ कसे द्यावे',
                    ($city !== '' ? $city.' साठी लोकल गाइड' : 'लोकल गाइड'),
                    'अपग्रेड किंवा रिपीट केव्हा करावे',
                    'ग्राहक सर्वाधिक काय विचारतात',
                ],
                '10 customer education topics' => ['क्वालिटी चेकलिस्ट', 'टाइमलाइन अपेक्षा', 'किंमतीवर काय परिणाम होतो', 'कस्टम वर्क कसे होते', 'रेफरन्स कसे शेअर करावे', 'आफ्टर-केअर बेसिक्स', 'कोट केव्हा हवा', 'पर्याय योग्य रीतीने कसे तुलना करावी', 'इन्क्वायरीनंतर काय होते', 'संपर्कात कसे रहावे'],
                '10 FAQ topics' => [$biz.' काय देते?', 'हे कोणासाठी आहे?', 'सुरुवात कशी करावी?', 'अपॉइंटमेंटवर काम होते का?', 'किती वेळ लागतो?', 'कोट कसा मिळेल?', 'कोणती माहिती शेअर करावी?', 'डिलिव्हरी / भेट होते का?', 'संपर्क कसा करावा?', 'पहिल्या भेटीनंतर काय होते?'],
            ],
        ];
        $items = $lists[$lang][$label] ?? [];

        return $items !== [] ? implode("\n", $items) : '';
    }

    /**
     * @param  array<string, string>  $ctx
     * @return list<string>
     */
    private static function gallery(string $lang, array $ctx): array
    {
        $city = self::word($lang, $ctx['city']);

        return match ($lang) {
            'gu' => [$ctx['offer'].' ક્લોઝ-અપ', 'વર્કશોપ / પ્રક્રિયા', 'કસ્ટમર મીટિંગ', ($city !== '' ? $city.' ' : '').'લોકેશન', 'ફિનિશ્ડ વર્ક'],
            'hi' => [$ctx['offer'].' क्लोज़-अप', 'वर्कशॉप / प्रक्रिया', 'कस्टमर मीटिंग', ($city !== '' ? $city.' ' : '').'लोकेशन', 'फिनिश्ड वर्क'],
            'mr' => [$ctx['offer'].' क्लोज-अप', 'वर्कशॉप / प्रक्रिया', 'कस्टमर मीटिंग', ($city !== '' ? $city.' ' : '').'लोकेशन', 'फिनिश्ड वर्क'],
            default => [],
        };
    }

    /**
     * @param  array<string, string>  $ctx
     * @return list<string>
     */
    private static function videos(string $lang, array $ctx): array
    {
        return match ($lang) {
            'gu' => ['60-સેકન્ડ બ્રાન્ડ ઇન્ટ્રો', $ctx['offer'].' કેવી રીતે પસંદ થાય', 'રિયલ પ્રોસેસ વોક-થ્રૂ', 'વોટ્સએપ પર એન્ક્વાયરી કેવી રીતે કરવી'],
            'hi' => ['60-सेकंड ब्रांड इंट्रो', $ctx['offer'].' कैसे चुना जाता है', 'रियल प्रोसेस वॉक-थ्रू', 'व्हाट्सएप पर इंक्वायरी कैसे करें'],
            'mr' => ['60-सेकंद ब्रँड इंट्रो', $ctx['offer'].' कसा निवडला जातो', 'रिअल प्रोसेस वॉक-थ्रू', 'व्हॉट्सअॅपवर इन्क्वायरी कशी करावी'],
            default => [],
        };
    }

    /**
     * @return list<string>
     */
    private static function journeyQuestions(string $lang): array
    {
        return match ($lang) {
            'gu' => ['તમે કોણ છો?', 'તમે શું આપો છો?', 'આ કોના માટે છે?', 'કઈ સમસ્યા હલ થાય છે?', 'ગ્રાહક તમને કેમ વિચારે?', 'તમારું USP શું છે?', 'કઈ પ્રોડક્ટ/સર્વિસ મળે છે?', 'કિંમત કેટલી?', 'કેવી રીતે કામ થાય?', 'શું વિશ્વાસ કરી શકાય?', 'શું પુરાવો છે?', 'સંપર્ક / ખરીદી કેવી રીતે?'],
            'hi' => ['आप कौन हैं?', 'आप क्या देते हैं?', 'यह किसके लिए है?', 'कौन सी समस्या हल होती है?', 'ग्राहक आपको क्यों सोचे?', 'आपका USP क्या है?', 'कौन सा प्रॉडक्ट/सर्विस मिलता है?', 'कीमत कितनी है?', 'कैसे काम करता है?', 'क्या भरोसा किया जा सकता है?', 'क्या प्रमाण है?', 'संपर्क / खरीद कैसे?'],
            'mr' => ['तुम्ही कोण आहात?', 'तुम्ही काय देता?', 'हे कोणासाठी आहे?', 'कोणती समस्या सुटते?', 'ग्राहक तुम्हाला का विचारावे?', 'तुमचे USP काय आहे?', 'कोणते प्रॉडक्ट/सर्व्हिस मिळते?', 'किंमत किती?', 'कसे काम करते?', 'विश्वास ठेवता येईल का?', 'काय पुरावा आहे?', 'संपर्क / खरेदी कशी?'],
            default => [],
        };
    }

    private static function slug(string $label): string
    {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', $label) ?? $label, '_'));

        return $slug;
    }

    /**
     * @return array<string, string>
     */
    private static function templates(string $lang): array
    {
        return match ($lang) {
            'gu' => [
                'hero.tagline' => '{offer} જેના પર વિશ્વાસ કરી શકાય',
                'hero.usp' => '{biz} {offer}ને સમજવાનું અને શરૂ કરવાનું સરળ બનાવે છે.',
                'hero.cta' => '{cta}',
                'hero.hero_image_concept' => '{offer}નો સ્વચ્છ ફોટો, {biz}નું નામ અને એક સ્પષ્ટ {cta} બટન.',
                'hero.subheadline' => '{cat}{place}',
                'introduction.who_we_are' => '{biz}{place} — {cat}',
                'introduction.business_overview' => '{biz} {cat} બિઝનેસ છે અને {offer} આપે છે.',
                'introduction.key_highlights' => '{offer} • કસ્ટમર-ફર્સ્ટ પ્રક્રિયા • {cta} માટે સ્પષ્ટ આગળનું પગલું',
                'purpose.purpose' => 'ગ્રાહકોને {offer} સ્પષ્ટતા અને વિશ્વાસ સાથે પસંદ કરવામાં મદદ.',
                'purpose.vision' => '{cat}માં યાદ રહે તેવું નામ બનવું{place}.',
                'purpose.mission' => 'એવું {offer} આપવું જે સમજવું સરળ, ખરીદવું સહેલું અને ખરીદી પછી વિશ્વસનીય હોય.',
                'purpose.values' => 'સ્પષ્ટતા, સેવાની કાળજી, પ્રમાણિક દાવા અને લાંબા ગાળાની કસ્ટમર કેર.',
                'purpose.future_direction' => 'ડિજિટલ એન્ક્વાયરી, રિપીટ કસ્ટમર અને એક સિગ્નેચર {offer} ઓફર મજબૂત કરવી.',
                'problem.needs' => 'સ્પષ્ટ ઓફર, પ્રમાણિક પ્રક્રિયા અને એન્ક્વાયરીનો એક સહેલો રસ્તો.',
                'problem.expectations' => 'ગ્રાહકો અપેક્ષા રાખે છે કે {biz} {offer} સરળ રીતે સમજાવે અને ઝડપથી જવાબ આપે.',
                'solution.solution' => '{biz} {offer}થી અટકળ દૂર કરે છે: જરૂર સમજો, સ્પષ્ટ વિકલ્પ બતાવો, અને વાતચીતથી એન્ક્વાયરી પૂરી કરો.',
                'why.why_choose_us' => '{biz} {offer}ને સમજવાનું અને શરૂ કરવાનું સરળ બનાવે છે.',
                'why.proof_still_needed' => 'પુરાવા તરીકે વેરિફાઈડ વર્ક, રિવ્યૂ અથવા પ્રોસેસ ફોટો ઉમેરો.',
                'products.product_category' => '{cat}',
                'products.benefits' => 'ગ્રાહકને સ્પષ્ટ {offer} વિકલ્પ, માર્ગદર્શન અને સહેલું આગળનું પગલું મળે છે.',
                'products.use_cases' => 'નવા ખરીદનાર, રિપીટ કસ્ટમર અને {offer} વિકલ્પો સરખાવતા લોકો.',
                'products.related_products' => '{offer}ની સાથે બેસે તેવા સંબંધિત {cat} વિકલ્પો.',
                'services.related_services' => '{offer} માટે કન્સલ્ટેશન, કસ્ટમાઈઝેશન, આફ્ટર-કેર અને રિપીટ-ઓર્ડર સપોર્ટ.',
                'signature.flagship_offering' => '{offer}',
                'signature.how_to_present_it' => '{biz} {offer}ને એ કારણ તરીકે રજૂ કરે છે કે ગ્રાહકો આ બ્રાન્ડ પસંદ કરે.',
                'pricing.pricing' => 'તાજી કિંમત માટે સંપર્ક કરો / ક્વોટ મેળવો',
                'pricing.note' => 'પબ્લિક કિંમત આપવામાં આવી નથી. કોઈ કિંમત બનાવવામાં આવી નથી.',
                'navachar.product_innovation' => 'નવાચાર તક: {offer}ની નામવાળી સિગ્નેચર કલેક્શન અથવા પેકેજ.',
                'navachar.service_innovation' => 'નવાચાર તક: વેચાણ પહેલાં સલાહભર્યું બ્રીફિંગ.',
                'navachar.technology_digitalisation' => 'નવાચાર તક: વોટ્સએપ કેટલોગ + વેબસાઈટ એન્ક્વાયરી ફોર્મ.',
                'navachar.customer_experience' => 'નવાચાર તક: દરેક એન્ક્વાયરી પછી એક ટ્રેક ફોલો-અપ.',
                'navachar.process_business_model' => 'નવાચાર તક: ફક્ત વોક-ઇન નહીં, એપોઈન્ટમેન્ટ-લેડ સેલિંગ.',
                'quality.quality_promise' => '{biz} {offer}ને સરળ, વિશ્વસનીય અને કસ્ટમર-ફ્રેન્ડલી રાખે છે.',
                'quality.warranty_certification' => 'વોરંટી, ISO અથવા સેફ્ટી સર્ટિફિકેશન પુષ્ટિ પછી જ પ્રકાશિત કરો.',
                'founder.background_experience_vision' => 'ફાઉન્ડર માહિતી જરૂરી. અનુભવ, એજ્યુકેશન કે એવોર્ડ બનાવવામાં આવ્યા નથી.',
                'team.team_structure' => 'નામ અને રોલ કન્ફર્મ થાય પછી કસ્ટમર-ફેસિંગ ટીમ બતાવો.',
                'team.people' => 'વ્યક્તિગત નામો બનાવવામાં આવ્યા નથી.',
                'story.our_story' => 'બિઝનેસ સ્ટોરી માહિતી જરૂરી',
                'story.timeline_founding_year' => 'તારીખ અને ઇતિહાસ બનાવવામાં આવ્યા નથી.',
                'portfolio.portfolio' => 'રિયલ પ્રોજેક્ટ્સ અથવા કેસ સ્ટડી ઉમેરો. કંઈ બનાવવામાં આવ્યું નથી.',
                'results.results' => 'રેવેન્યુ, કસ્ટમર-કાઉન્ટ અથવા અચીવમેન્ટ નંબર બનાવવામાં આવ્યા નથી.',
                'certifications.certifications_awards' => 'ફક્ત વેરિફાઈડ સર્ટિફિકેટ અથવા એવોર્ડ બતાવો.',
                'clients.clients_partners' => 'ફક્ત જાહેરમાં નામ લઈ શકાય તેવા ક્લાયન્ટ અથવા પાર્ટનર લખો.',
                'testimonials.testimonials' => 'નકલી ટેસ્ટિમોનિયલ ન બનાવો. અસલ કસ્ટમર કોટ ઉમેરો.',
                'reviews.reviews' => 'Google / પ્લેટફોર્મ રિવ્યૂ કન્ફર્મ થાય ત્યારે સમરી ઉમેરો.',
                'videos.script_direction' => 'વિડિયો ટૂંકા રાખો: તમે કોણ, શું આપો છો, એન્ક્વાયરી કેવી રીતે.',
                'faq.what_do_you_offer' => '{biz} {offer} આપે છે.',
                'faq.who_is_it_for' => 'જે ગ્રાહકોને વિશ્વસનીય સ્થાનિક પાર્ટનર સાથે {offer} જોઈએ છે.',
                'faq.how_do_i_start' => '{cta} અથવા વોટ્સએપ પર તમારી જરૂરિયાત મોકલો.',
                'faq.how_much_does_it_cost' => 'તાજી કિંમત માટે સંપર્ક કરો / ક્વોટ મેળવો. કિંમત બનાવવામાં આવી નથી.',
                'offers.offers' => 'પબ્લિક ઓફર આપવામાં આવી નથી. નકલી ડિસ્કાઉન્ટ બનાવ્યા નથી.',
                'membership.loyalty_idea' => '{offer} માટે સરળ રિપીટ-કસ્ટમર ક્લબ ઉમેરી શકાય.',
                'experience.enquiry' => 'ગ્રાહક {biz}ને ફોન, વોટ્સએપ અથવા ફોર્મથી સંપર્ક કરે છે.',
                'experience.purchase_booking' => 'જરૂર કન્ફર્મ કરો, વિકલ્પો આપો, {offer} ઓર્ડર પૂરો કરો.',
                'experience.delivery_support' => 'ઓફર ડિલિવર કરો, ફોલો-અપ કરો અને ફીડબેક લો.',
                'experience.repeat_customer' => 'ફક્ત વેચાણ નહીં, ઉપયોગી અપડેટથી સંપર્કમાં રહો.',
                'cta.primary_cta' => '{cta}',
                'cta.supporting_ctas' => 'વોટ્સએપ કરો • કૉલ કરો • હવે પૂછો',
                'digital.phone_whatsapp' => 'કોન્ટેક્ટ નંબર જરૂરી',
                'digital.website' => 'વેબસાઈટ URL જરૂરી',
                'contact.address' => 'એડ્રેસ જરૂરી',
                'contact.phone' => 'ફોન જરૂરી',
                'contact.email' => 'ઈમેઈલ જરૂરી',
                'contact.business_hours' => 'બિઝનેસ અવર્સ જરૂરી',
                'contact.map_location' => 'મેપ લોકેશન જરૂરી',
                'contact.contact_form' => 'નામ, ફોન, જરૂરિયાત, પસંદગીનો સમય — {cta} પર મોકલો.',
                'profile.sub_category' => '{cat}',
                'profile.business_type' => 'લોકલ બિઝનેસ',
                'profile.industry' => '{cat}',
                'profile.target_customer' => 'જે ગ્રાહકોને વિશ્વસનીય સ્થાનિક પાર્ટનર સાથે {offer} જોઈએ છે.',
                'profile.business_description' => '{biz} {offer} આપે છે — વિશ્વસનીય {cat} પાર્ટનર.',
                'profile.main_services' => 'મેઈન પ્રોડક્ટ / સર્વિસ લિસ્ટ સાથે સમાવિષ્ટ.',
                'profile.related_products' => '{offer}ની સાથે બેસે તેવા સંબંધિત {cat} વિકલ્પો.',
                'profile.related_services' => '{offer} માટે કન્સલ્ટેશન, કસ્ટમાઈઝેશન અને આફ્ટર-કેર.',
                'profile.business_positioning' => '{biz} — {offer} માટે કસ્ટમર-ફર્સ્ટ {cat} બ્રાન્ડ.',
                'profile.customer_need' => '{offer} પસંદ કરવાનો સ્પષ્ટ અને વિશ્વસનીય રસ્તો.',
                'profile.market_context' => '{city} {cat} ગ્રાહકો વિશ્વસનીય {offer} શોધે છે.',
                'seo.meta' => '{biz} {offer} આપે છે{place}. સ્પષ્ટ આગળના પગલા માટે એન્ક્વાયરી કરો.',
                'seo.h2' => 'વિશ્વસનીય {cat} પાર્ટનર માટે {offer}',
                'journey.pending' => 'પેન્ડિંગ — વેરિફાઈડ માહિતી ઉમેરો',
                'journey.covered' => 'ડ્રાફ્ટમાં કવર થયેલ',
            ],
            'hi' => [
                'hero.tagline' => '{offer} जिस पर भरोसा किया जा सके',
                'hero.usp' => '{biz} {offer} को समझना और शुरू करना आसान बनाता है.',
                'hero.cta' => '{cta}',
                'hero.hero_image_concept' => '{offer} की साफ़ फ़ोटो, {biz} का नाम और एक स्पष्ट {cta} बटन.',
                'hero.subheadline' => '{cat}{place}',
                'introduction.who_we_are' => '{biz}{place} — {cat}',
                'introduction.business_overview' => '{biz} एक {cat} बिज़नेस है और {offer} देता है.',
                'introduction.key_highlights' => '{offer} • कस्टमर-फर्स्ट प्रक्रिया • {cta} के लिए स्पष्ट अगला कदम',
                'purpose.purpose' => 'ग्राहकों को {offer} स्पष्टता और भरोसे के साथ चुनने में मदद।',
                'purpose.vision' => '{cat} में याद रहने वाला नाम बनना{place}.',
                'purpose.mission' => 'ऐसा {offer} देना जो समझना आसान, खरीदना सरल और खरीद के बाद विश्वसनीय हो.',
                'purpose.values' => 'स्पष्टता, सेवा की देखभाल, ईमानदार दावे और लंबी कस्टमर केयर.',
                'purpose.future_direction' => 'डिजिटल इंक्वायरी, रिपीट कस्टमर और एक सिग्नेचर {offer} ऑफ़र मज़बूत करें.',
                'problem.needs' => 'स्पष्ट ऑफ़र, ईमानदार प्रक्रिया और इंक्वायरी का एक आसान रास्ता.',
                'problem.expectations' => 'ग्राहक चाहते हैं कि {biz} {offer} को सरलता से समझाए और तेज़ जवाब दे.',
                'solution.solution' => '{biz} {offer} से अनुमान हटाता है: ज़रूरत समझें, स्पष्ट विकल्प दिखाएं, बातचीत से इंक्वायरी पूरी करें.',
                'why.why_choose_us' => '{biz} {offer} को समझना और शुरू करना आसान बनाता है.',
                'why.proof_still_needed' => 'प्रमाण के लिए वेरिफाइड वर्क, रिव्यू या प्रोसेस फ़ोटो जोड़ें.',
                'products.product_category' => '{cat}',
                'products.benefits' => 'ग्राहक को स्पष्ट {offer} विकल्प, गाइडेंस और आसान अगला कदम मिलता है.',
                'products.use_cases' => 'नए खरीदार, रिपीट कस्टमर और {offer} विकल्प तुलना करने वाले लोग.',
                'products.related_products' => '{offer} के साथ बैठने वाले संबंधित {cat} विकल्प.',
                'services.related_services' => '{offer} के लिए कंसल्टेशन, कस्टमाइज़ेशन, आफ्टर-केयर और रिपीट-ऑर्डर सपोर्ट.',
                'signature.flagship_offering' => '{offer}',
                'signature.how_to_present_it' => '{biz} {offer} को वह कारण बनाता है जिससे ग्राहक यह ब्रांड चुनते हैं.',
                'pricing.pricing' => 'ताज़ा कीमत के लिए संपर्क करें / कोट पाएं',
                'pricing.note' => 'पब्लिक कीमत नहीं दी गई. कोई कीमत बनाई नहीं गई.',
                'navachar.product_innovation' => 'नवाचार अवसर: {offer} की नाम वाली सिग्नेचर कलेक्शन या पैकेज.',
                'navachar.service_innovation' => 'नवाचार अवसर: सेल से पहले सलाह वाला ब्रीफिंग.',
                'navachar.technology_digitalisation' => 'नवाचार अवसर: व्हाट्सएप कैटलॉग + वेबसाइट इंक्वायरी फ़ॉर्म.',
                'navachar.customer_experience' => 'नवाचार अवसर: हर इंक्वायरी के बाद एक ट्रैक फ़ॉलो-अप.',
                'navachar.process_business_model' => 'नवाचार अवसर: सिर्फ़ वॉक-इन नहीं, अपॉइंटमेंट-लेड सेलिंग.',
                'quality.quality_promise' => '{biz} {offer} को सरल, विश्वसनीय और कस्टमर-फ्रेंडली रखता है.',
                'quality.warranty_certification' => 'वारंटी, ISO या सेफ़्टी सर्टिफिकेशन पुष्टि के बाद ही प्रकाशित करें.',
                'founder.background_experience_vision' => 'फाउंडर जानकारी ज़रूरी. अनुभव, शिक्षा या अवॉर्ड नहीं बनाए गए.',
                'team.team_structure' => 'नाम और रोल कन्फ़र्म होने पर कस्टमर-फेसिंग टीम दिखाएं.',
                'team.people' => 'व्यक्तिगत नाम नहीं बनाए गए.',
                'story.our_story' => 'बिज़नेस स्टोरी जानकारी ज़रूरी',
                'story.timeline_founding_year' => 'तारीख और इतिहास नहीं बनाए गए.',
                'portfolio.portfolio' => 'रियल प्रोजेक्ट या केस स्टडी जोड़ें. कुछ भी नहीं बनाया गया.',
                'results.results' => 'रेवेन्यू, कस्टमर-काउंट या अचीवमेंट नंबर नहीं बनाए गए.',
                'certifications.certifications_awards' => 'केवल वेरिफाइड सर्टिफिकेट या अवॉर्ड दिखाएं.',
                'clients.clients_partners' => 'केवल सार्वजनिक रूप से नाम लिए जा सकने वाले क्लाइंट या पार्टनर लिखें.',
                'testimonials.testimonials' => 'नकली टेस्टिमोनियल न बनाएं. असली कस्टमर कोट जोड़ें.',
                'reviews.reviews' => 'Google / प्लेटफ़ॉर्म रिव्यू कन्फ़र्म होने पर समरी जोड़ें.',
                'videos.script_direction' => 'वीडियो छोटे रखें: आप कौन, क्या देते हैं, इंक्वायरी कैसे.',
                'faq.what_do_you_offer' => '{biz} {offer} देता है.',
                'faq.who_is_it_for' => 'जो ग्राहक विश्वसनीय लोकल पार्टनर के साथ {offer} चाहते हैं.',
                'faq.how_do_i_start' => '{cta} या व्हाट्सएप पर अपनी ज़रूरत भेजें.',
                'faq.how_much_does_it_cost' => 'ताज़ा कीमत के लिए संपर्क करें / कोट पाएं. कीमत नहीं बनाई गई.',
                'offers.offers' => 'पब्लिक ऑफ़र नहीं दिया गया. नकली डिस्काउंट नहीं बनाए गए.',
                'membership.loyalty_idea' => '{offer} के लिए सरल रिपीट-कस्टमर क्लब जोड़ा जा सकता है.',
                'experience.enquiry' => 'ग्राहक {biz} से फ़ोन, व्हाट्सएप या फ़ॉर्म से संपर्क करता है.',
                'experience.purchase_booking' => 'ज़रूरत कन्फ़र्म करें, विकल्प दें, {offer} ऑर्डर पूरा करें.',
                'experience.delivery_support' => 'ऑफ़र डिलीवर करें, फ़ॉलो-अप करें और फ़ीडबैक लें.',
                'experience.repeat_customer' => 'सिर्फ़ सेल नहीं, उपयोगी अपडेट से संपर्क में रहें.',
                'cta.primary_cta' => '{cta}',
                'cta.supporting_ctas' => 'व्हाट्सएप करें • कॉल करें • अभी पूछें',
                'digital.phone_whatsapp' => 'कॉन्टैक्ट नंबर ज़रूरी',
                'digital.website' => 'वेबसाइट URL ज़रूरी',
                'contact.address' => 'एड्रेस ज़रूरी',
                'contact.phone' => 'फ़ोन ज़रूरी',
                'contact.email' => 'ईमेल ज़रूरी',
                'contact.business_hours' => 'बिज़नेस आवर्स ज़रूरी',
                'contact.map_location' => 'मैप लोकेशन ज़रूरी',
                'contact.contact_form' => 'नाम, फ़ोन, ज़रूरत, पसंदीदा समय — {cta} पर भेजें.',
                'profile.sub_category' => '{cat}',
                'profile.business_type' => 'लोकल बिज़नेस',
                'profile.industry' => '{cat}',
                'profile.target_customer' => 'जो ग्राहक विश्वसनीय लोकल पार्टनर के साथ {offer} चाहते हैं.',
                'profile.business_description' => '{biz} {offer} देता है — विश्वसनीय {cat} पार्टनर.',
                'profile.main_services' => 'मेन प्रॉडक्ट / सर्विस लिस्ट के साथ शामिल.',
                'profile.related_products' => '{offer} के साथ बैठने वाले संबंधित {cat} विकल्प.',
                'profile.related_services' => '{offer} के लिए कंसल्टेशन, कस्टमाइज़ेशन और आफ्टर-केयर.',
                'profile.business_positioning' => '{biz} — {offer} के लिए कस्टमर-फर्स्ट {cat} ब्रांड.',
                'profile.customer_need' => '{offer} चुनने का स्पष्ट और विश्वसनीय तरीका.',
                'profile.market_context' => '{city} {cat} ग्राहक विश्वसनीय {offer} खोजते हैं.',
                'seo.meta' => '{biz} {offer} देता है{place}. स्पष्ट अगले कदम के लिए इंक्वायरी करें.',
                'seo.h2' => 'विश्वसनीय {cat} पार्टनर के लिए {offer}',
                'journey.pending' => 'पेंडिंग — वेरिफाइड जानकारी जोड़ें',
                'journey.covered' => 'ड्राफ्ट में कवर किया गया',
            ],
            'mr' => [
                'hero.tagline' => '{offer} ज्यावर विश्वास ठेवता येईल',
                'hero.usp' => '{biz} {offer} समजणे आणि सुरू करणे सोपे करते.',
                'hero.cta' => '{cta}',
                'hero.hero_image_concept' => '{offer}चा स्वच्छ फोटो, {biz}चे नाव आणि एक स्पष्ट {cta} बटण.',
                'hero.subheadline' => '{cat}{place}',
                'introduction.who_we_are' => '{biz}{place} — {cat}',
                'introduction.business_overview' => '{biz} हा {cat} बिझनेस आहे आणि {offer} देतो.',
                'introduction.key_highlights' => '{offer} • कस्टमर-फर्स्ट प्रक्रिया • {cta} साठी स्पष्ट पुढचे पाऊल',
                'purpose.purpose' => 'ग्राहकांना {offer} स्पष्टता आणि विश्वासाने निवडण्यास मदत.',
                'purpose.vision' => '{cat}मध्ये आठवणारे नाव होणे{place}.',
                'purpose.mission' => 'असे {offer} द्या जे समजणे सोपे, खरेदी करणे सोपे आणि खरेदीनंतर विश्वासार्ह असेल.',
                'purpose.values' => 'स्पष्टता, सेवेची काळजी, प्रामाणिक दावे आणि दीर्घ कस्टमर केअर.',
                'purpose.future_direction' => 'डिजिटल इन्क्वायरी, रिपीट कस्टमर आणि एक सिग्नेचर {offer} ऑफर मजबूत करा.',
                'problem.needs' => 'स्पष्ट ऑफर, प्रामाणिक प्रक्रिया आणि इन्क्वायरीचा एक सोपा मार्ग.',
                'problem.expectations' => 'ग्राहक अपेक्षा करतात की {biz} {offer} सोप्या रीतीने समजावेल आणि लवकर उत्तर देईल.',
                'solution.solution' => '{biz} {offer}ने अंदाज काढून टाकतो: गरज समजा, स्पष्ट पर्याय दाखवा, आणि संभाषणाने इन्क्वायरी पूर्ण करा.',
                'why.why_choose_us' => '{biz} {offer} समजणे आणि सुरू करणे सोपे करते.',
                'why.proof_still_needed' => 'पुराव्यासाठी व्हेरिफाइड वर्क, रिव्ह्यू किंवा प्रोसेस फोटो जोडा.',
                'products.product_category' => '{cat}',
                'products.benefits' => 'ग्राहकाला स्पष्ट {offer} पर्याय, मार्गदर्शन आणि सोपे पुढचे पाऊल मिळते.',
                'products.use_cases' => 'नवे खरेदीदार, रिपीट कस्टमर आणि {offer} पर्याय तुलना करणारे लोक.',
                'products.related_products' => '{offer}सोबत बसणारे संबंधित {cat} पर्याय.',
                'services.related_services' => '{offer}साठी कन्सल्टेशन, कस्टमायझेशन, आफ्टर-केअर आणि रिपीट-ऑर्डर सपोर्ट.',
                'signature.flagship_offering' => '{offer}',
                'signature.how_to_present_it' => '{biz} {offer}ला ते कारण बनवतो ज्यामुळे ग्राहक हा ब्रँड निवडतात.',
                'pricing.pricing' => 'ताजी किंमतसाठी संपर्क करा / कोट मिळवा',
                'pricing.note' => 'पब्लिक किंमत दिलेली नाही. कुठलीही किंमत तयार केलेली नाही.',
                'navachar.product_innovation' => 'नवाचार संधी: {offer}ची नाव असलेली सिग्नेचर कलेक्शन किंवा पॅकेज.',
                'navachar.service_innovation' => 'नवाचार संधी: विक्रीपूर्वी सल्लागाराचे ब्रीफिंग.',
                'navachar.technology_digitalisation' => 'नवाचार संधी: व्हॉट्सअॅप कॅटलॉग + वेबसाइट इन्क्वायरी फॉर्म.',
                'navachar.customer_experience' => 'नवाचार संधी: प्रत्येक इन्क्वायरीनंतर एक ट्रॅक फॉलो-अप.',
                'navachar.process_business_model' => 'नवाचार संधी: फक्त वॉक-इन नाही, अपॉइंटमेंट-लेड सेलिंग.',
                'quality.quality_promise' => '{biz} {offer} सोपे, विश्वासार्ह आणि कस्टमर-फ्रेंडली ठेवतो.',
                'quality.warranty_certification' => 'वॉरंटी, ISO किंवा सेफ्टी सर्टिफिकेशन पुष्टीनंतरच प्रकाशित करा.',
                'founder.background_experience_vision' => 'फाउंडर माहिती आवश्यक. अनुभव, शिक्षण किंवा अवॉर्ड तयार केलेले नाहीत.',
                'team.team_structure' => 'नाव आणि रोल कन्फर्म झाल्यावर कस्टमर-फेसिंग टीम दाखवा.',
                'team.people' => 'वैयक्तिक नावे तयार केलेली नाहीत.',
                'story.our_story' => 'बिझनेस स्टोरी माहिती आवश्यक',
                'story.timeline_founding_year' => 'तारीख आणि इतिहास तयार केलेला नाही.',
                'portfolio.portfolio' => 'रिअल प्रोजेक्ट किंवा केस स्टडी जोडा. काहीही तयार केलेले नाही.',
                'results.results' => 'रेव्हेन्यू, कस्टमर-काउंट किंवा अचिव्हमेंट नंबर तयार केलेले नाहीत.',
                'certifications.certifications_awards' => 'फक्त व्हेरिफाइड सर्टिफिकेट किंवा अवॉर्ड दाखवा.',
                'clients.clients_partners' => 'फक्त सार्वजनिक नाव घेता येतील असे क्लायंट किंवा पार्टनर लिहा.',
                'testimonials.testimonials' => 'नकली टेस्टिमोनियल तयार करू नका. खरे कस्टमर कोट जोडा.',
                'reviews.reviews' => 'Google / प्लॅटफॉर्म रिव्ह्यू कन्फर्म झाल्यावर समरी जोडा.',
                'videos.script_direction' => 'व्हिडिओ छोटे ठेवा: तुम्ही कोण, काय देता, इन्क्वायरी कशी.',
                'faq.what_do_you_offer' => '{biz} {offer} देतो.',
                'faq.who_is_it_for' => 'ज्या ग्राहकांना विश्वासार्ह स्थानिक पार्टनरसोबत {offer} हवे आहे.',
                'faq.how_do_i_start' => '{cta} किंवा व्हॉट्सअॅपवर तुमची गरज पाठवा.',
                'faq.how_much_does_it_cost' => 'ताजी किंमतसाठी संपर्क करा / कोट मिळवा. किंमत तयार केलेली नाही.',
                'offers.offers' => 'पब्लिक ऑफर दिलेली नाही. नकली डिस्काउंट तयार केलेले नाहीत.',
                'membership.loyalty_idea' => '{offer}साठी सोपा रिपीट-कस्टमर क्लब जोडता येईल.',
                'experience.enquiry' => 'ग्राहक {biz}ला फोन, व्हॉट्सअॅप किंवा फॉर्मने संपर्क करतो.',
                'experience.purchase_booking' => 'गरज कन्फर्म करा, पर्याय द्या, {offer} ऑर्डर पूर्ण करा.',
                'experience.delivery_support' => 'ऑफर डिलिव्हर करा, फॉलो-अप करा आणि फीडबॅक घ्या.',
                'experience.repeat_customer' => 'फक्त विक्री नाही, उपयुक्त अपडेटने संपर्कात रहा.',
                'cta.primary_cta' => '{cta}',
                'cta.supporting_ctas' => 'व्हॉट्सअॅप करा • कॉल करा • आता विचारा',
                'digital.phone_whatsapp' => 'कॉन्टॅक्ट नंबर आवश्यक',
                'digital.website' => 'वेबसाइट URL आवश्यक',
                'contact.address' => 'अड्रेस आवश्यक',
                'contact.phone' => 'फोन आवश्यक',
                'contact.email' => 'ईमेल आवश्यक',
                'contact.business_hours' => 'बिझनेस अवर्स आवश्यक',
                'contact.map_location' => 'मॅप लोकेशन आवश्यक',
                'contact.contact_form' => 'नाव, फोन, गरज, पसंतीचा वेळ — {cta} वर पाठवा.',
                'profile.sub_category' => '{cat}',
                'profile.business_type' => 'लोकल बिझनेस',
                'profile.industry' => '{cat}',
                'profile.target_customer' => 'ज्या ग्राहकांना विश्वासार्ह स्थानिक पार्टनरसोबत {offer} हवे आहे.',
                'profile.business_description' => '{biz} {offer} देतो — विश्वासार्ह {cat} पार्टनर.',
                'profile.main_services' => 'मेन प्रॉडक्ट / सर्व्हिस लिस्टसोबत समाविष्ट.',
                'profile.related_products' => '{offer}सोबत बसणारे संबंधित {cat} पर्याय.',
                'profile.related_services' => '{offer}साठी कन्सल्टेशन, कस्टमायझेशन आणि आफ्टर-केअर.',
                'profile.business_positioning' => '{biz} — {offer}साठी कस्टमर-फर्स्ट {cat} ब्रँड.',
                'profile.customer_need' => '{offer} निवडण्याचा स्पष्ट आणि विश्वासार्ह मार्ग.',
                'profile.market_context' => '{city} {cat} ग्राहक विश्वासार्ह {offer} शोधतात.',
                'seo.meta' => '{biz} {offer} देतो{place}. स्पष्ट पुढच्या पावलासाठी इन्क्वायरी करा.',
                'seo.h2' => 'विश्वासार्ह {cat} पार्टनरसाठी {offer}',
                'journey.pending' => 'पेंडिंग — व्हेरिफाइड माहिती जोडा',
                'journey.covered' => 'ड्राफ्टमध्ये कव्हर केले',
            ],
            default => [],
        };
    }
}
