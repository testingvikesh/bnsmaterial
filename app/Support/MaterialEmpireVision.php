<?php

namespace App\Support;

class MaterialEmpireVision
{
    /**
     * @return list<string>
     */
    public static function titles(): array
    {
        return [
            'Flagship Business',
            'Core Product / Service Excellence',
            'Signature Product / Unique Offering',
            'Customer Experience Innovation',
            'Strong Brand Creation',
            'Customer Community & Loyalty',
            'New Product / Service Development',
            'R&D + Continuous Innovation',
            'Digital Business & Online Expansion',
            'Recurring Revenue Model',
            'B2B / Institutional Business',
            'Strategic Partnership & Collaboration',
            'Team Building & Talent Development',
            'Professional Management & Second-Line Leadership',
            'Technology + AI + Automation',
            'SOP + Process-Driven Business',
            'Data + ERP + MIS Management',
            'Financial Strength + Capital Strategy',
            'Profitability + Wealth Creation',
            'Risk Management + Business Continuity',
            'Centralized Operations',
            'Intellectual Property + Knowledge Assets',
            'Multi-Location Expansion',
            'Franchise / Licensing / Partnership Model',
            'Distribution / Network / Business Ecosystem',
            'Multiple Businesses / New Verticals',
            'Corporate Governance + Professional Management',
            'Succession Planning + Legacy',
            'National → International Expansion',
            'Global Business Empire + Social Impact',
        ];
    }

    /**
     * @param  array<string, string>  $facts
     * @return list<array{title: string, explanation: string, innovation: string, example: string, action: string, benefit: string}>
     */
    public static function points(array $facts): array
    {
        $biz = $facts['business_name'] ?: 'this business';
        $cat = $facts['category'] ?: 'this category';
        $products = $facts['products'] ?: $facts['intro'] ?: $cat;
        $out = [];
        foreach (self::pointTemplates() as $index => $template) {
            $out[] = [
                'title' => self::titles()[$index],
                'explanation' => self::fillTemplate($template['explanation'], $biz, $cat, $products),
                'innovation' => self::fillTemplate($template['innovation'], $biz, $cat, $products),
                'example' => self::fillTemplate($template['example'], $biz, $cat, $products),
                'action' => self::fillTemplate($template['action'], $biz, $cat, $products),
                'benefit' => self::fillTemplate($template['benefit'], $biz, $cat, $products),
            ];
        }

        return $out;
    }

    /**
     * @return list<array{explanation: string, innovation: string, example: string, action: string, benefit: string}>
     */
    private static function pointTemplates(): array
    {
        return [
            [
                'explanation' => '{biz} should become the flagship name people recall first in {cat} when they need {products}.',
                'innovation' => 'Give {biz} one named flagship service line for {products} with a written promise, price band and delivery time.',
                'example' => 'A new {cat} client searches for help and chooses {biz} because the flagship {products} offer is easy to understand.',
                'action' => 'Write a one-page flagship offer for {products} and use it in every first meeting this week.',
                'benefit' => '{biz} stops looking like a general advisor and becomes the first-choice {cat} brand.',
            ],
            [
                'explanation' => 'Excellence means {biz} delivers {products} with fewer errors, faster turnaround and clearer advice than nearby {cat} competitors.',
                'innovation' => 'Create a quality checklist for every {products} assignment so {biz} work is consistent from junior staff to the owner.',
                'example' => 'A repeat client of {biz} receives a clean {products} file on time, with notes that a non-expert can follow.',
                'action' => 'List the 10 most common quality mistakes in {products} and add a check before any file leaves {biz}.',
                'benefit' => 'Better delivery reduces rework, complaints and discount pressure in the {cat} practice.',
            ],
            [
                'explanation' => '{biz} needs one signature offering that no generic {cat} firm nearby can copy easily.',
                'innovation' => 'Design a packaged {products} solution with a unique name, steps and outcome that only {biz} sells.',
                'example' => 'A business owner asks three {cat} firms for help, then picks {biz} because the signature {products} package is clearer.',
                'action' => 'Name the signature {products} package, set its inclusions and print it on {biz} proposals.',
                'benefit' => 'A unique offer raises fees and makes {biz} easier to recommend.',
            ],
            [
                'explanation' => 'Clients should feel guided, not confused, every time they work with {biz} on {products}.',
                'innovation' => 'Add a simple client journey: first call, document list, progress update and closing summary for {products}.',
                'example' => 'A first-time {cat} client of {biz} gets a WhatsApp checklist and a next-step date after the meeting.',
                'action' => 'Create a 4-step client experience card and train the {biz} team to follow it on every {products} enquiry.',
                'benefit' => 'A smoother experience increases referrals and reduces follow-up calls.',
            ],
            [
                'explanation' => '{biz} should look like a trusted {cat} brand, not only like an individual professional.',
                'innovation' => 'Build a brand kit for {biz}: promise line, look, case stories and a consistent way of talking about {products}.',
                'example' => 'A prospect sees the same {biz} message on the visiting card, website and proposal and remembers the {products} promise.',
                'action' => 'Write a 12-word brand promise for {biz} and put it on the website, email footer and quotation.',
                'benefit' => 'A stronger brand supports higher fees and easier hiring in {cat}.',
            ],
            [
                'explanation' => '{biz} should keep past {products} clients close so they return instead of shopping every year.',
                'innovation' => 'Start a small client community: monthly tip, review call and loyalty benefit for regular {cat} clients of {biz}.',
                'example' => 'An old client of {biz} stays because they receive a useful {products} update before they have to ask.',
                'action' => 'Call 10 past clients this week, ask one need, and offer a next {products} review date.',
                'benefit' => 'Loyalty reduces new-client cost and makes revenue more predictable.',
            ],
            [
                'explanation' => '{biz} can grow by adding one new {products}-related service that current {cat} clients already need.',
                'innovation' => 'Launch a new service adjacent to {products}, sold first to existing {biz} clients before going to the market.',
                'example' => 'A client who already uses {biz} for {products} also buys the new add-on instead of going to another firm.',
                'action' => 'Ask 8 current clients what extra help they want after {products}, then design one paid add-on.',
                'benefit' => 'New services increase average billing without leaving the {cat} field.',
            ],
            [
                'explanation' => '{biz} should improve {products} every quarter using client feedback, not only when a problem appears.',
                'innovation' => 'Keep a simple R&D notebook: client questions, competitor offers and one monthly improvement to {products}.',
                'example' => 'After three clients ask the same {products} question, {biz} adds a new worksheet and starts charging for it.',
                'action' => 'Collect last month’s client questions and pick one improvement for the {biz} {products} process.',
                'benefit' => 'Continuous improvement keeps {biz} ahead of copycat {cat} firms.',
            ],
            [
                'explanation' => '{biz} should win more {cat} work online, not only through walk-ins and personal contacts.',
                'innovation' => 'Create a digital desk: website page, WhatsApp catalogue and booking form for {products} enquiries.',
                'example' => 'A business owner in another city finds {biz} online and books a {products} consultation the same day.',
                'action' => 'Publish one clear {products} landing page and connect it to a WhatsApp enquiry button.',
                'benefit' => 'Online reach brings leads beyond the current city circle of {biz}.',
            ],
            [
                'explanation' => '{biz} should earn regular monthly income from {products}, not only one-time assignments.',
                'innovation' => 'Offer a retainer or annual plan for {products} with a fixed monthly fee and defined reviews.',
                'example' => 'A {cat} client pays {biz} every month for ongoing {products} support instead of calling only in a crisis.',
                'action' => 'Design one 12-month {products} retainer and offer it to 5 existing clients this week.',
                'benefit' => 'Recurring fees stabilize cash flow and make team planning easier.',
            ],
            [
                'explanation' => '{biz} can grow faster by serving companies, societies and institutions that need {products} at scale.',
                'innovation' => 'Build a B2B offer: proposal format, SLA, billing cycle and a named contact person for institutional {products} work.',
                'example' => 'A company awards {biz} a yearly {products} mandate after receiving a professional institutional proposal.',
                'action' => 'Prepare one B2B proposal template and send it to 3 institutions that already know {biz}.',
                'benefit' => 'Institutional work raises ticket size and builds a more serious {cat} reputation.',
            ],
            [
                'explanation' => '{biz} should grow with partners who already meet the same {cat} clients.',
                'innovation' => 'Make a referral alliance with complementary professionals who can send {products} work to {biz}.',
                'example' => 'A partner introduces a client to {biz} for {products}, and {biz} later returns a suitable referral.',
                'action' => 'Meet two complementary partners and write a simple referral note for {products}.',
                'benefit' => 'Partnerships bring qualified leads without heavy advertising spend.',
            ],
            [
                'explanation' => '{biz} cannot become an empire if only one person can deliver {products}.',
                'innovation' => 'Define two roles under the owner: client handling and {products} execution, with weekly training.',
                'example' => 'When the owner of {biz} is travelling, a trained team member still completes a {products} request correctly.',
                'action' => 'Write role cards for the current team and run one {products} training hour this week.',
                'benefit' => 'A stronger team lets {biz} take more {cat} work without quality drop.',
            ],
            [
                'explanation' => '{biz} needs a second-line leader who can run daily {products} work with professional discipline.',
                'innovation' => 'Appoint or develop one deputy with authority on scheduling, client updates and file quality.',
                'example' => 'The deputy at {biz} closes routine {products} files and only escalates exceptions to the owner.',
                'action' => 'Name the second-line person, list 5 decisions they can take, and review them every Friday.',
                'benefit' => 'Second-line leadership frees the owner for growth and reduces key-person risk.',
            ],
            [
                'explanation' => '{biz} should use technology so {products} work is faster, tracked and less dependent on memory.',
                'innovation' => 'Automate reminders, document collection and status updates for {products} using simple tools.',
                'example' => 'A {biz} client gets an automatic reminder for pending papers, so the {products} file does not stall.',
                'action' => 'Pick one manual follow-up in {products} and move it to a reminder system this week.',
                'benefit' => 'Automation saves hours and makes {biz} look more professional than local {cat} rivals.',
            ],
            [
                'explanation' => 'Every repeating {products} task at {biz} should follow a written SOP, not a different method each time.',
                'innovation' => 'Write short process sheets for enquiry, onboarding, delivery and billing of {products}.',
                'example' => 'A new joiner at {biz} delivers a standard {products} file by following the SOP without guessing.',
                'action' => 'Document the most common {products} process in 8 steps and pin it for the team.',
                'benefit' => 'SOPs keep quality stable as {biz} grows the {cat} practice.',
            ],
            [
                'explanation' => '{biz} should run on numbers: pipeline, pending files, collections and client mix for {products}.',
                'innovation' => 'Create a weekly MIS sheet for {biz} covering enquiries, conversions, pending {products} work and dues.',
                'example' => 'The owner of {biz} sees that 12 {products} files are stuck and clears them before new work is taken.',
                'action' => 'Start a one-page Friday MIS for {products} enquiries, delivery and outstanding fees.',
                'benefit' => 'Data-based control improves cash and stops silent leakage in the {cat} business.',
            ],
            [
                'explanation' => '{biz} needs a capital plan so growth in {products} is funded without constant cash stress.',
                'innovation' => 'Separate owner drawings, operating reserve and growth fund for the {cat} practice.',
                'example' => 'When {biz} wants to hire or advertise, the reserve already exists instead of delaying the decision.',
                'action' => 'Set a monthly transfer from {products} collections into a {biz} reserve account.',
                'benefit' => 'Financial strength lets {biz} invest in team, systems and larger {cat} mandates.',
            ],
            [
                'explanation' => 'Profit at {biz} should come from better pricing and mix of {products}, not only from more hours.',
                'innovation' => 'Review fees by service type and drop or repriced low-value {products} work.',
                'example' => '{biz} replaces a cheap one-time {products} job with a higher-value packaged mandate.',
                'action' => 'List last 20 invoices, mark low-margin {products} work, and reset the fee for that type.',
                'benefit' => 'Higher profit funds wealth, systems and a more selective {cat} client book.',
            ],
            [
                'explanation' => '{biz} must protect continuity if a key person, client or system fails during {products} delivery.',
                'innovation' => 'Keep backups of files, a deputy contact and a written continuity note for critical {products} clients.',
                'example' => 'If the owner is unavailable, {biz} still meets a statutory or promised {products} deadline.',
                'action' => 'Identify the 5 most critical {products} clients and write a backup plan for each.',
                'benefit' => 'Risk control protects reputation and keeps {cat} clients during disruptions.',
            ],
            [
                'explanation' => '{biz} should run {products} from one coordinated centre, even if clients sit in different places.',
                'innovation' => 'Centralize enquiry logging, file storage and billing so every {cat} assignment is visible.',
                'example' => 'A client of {biz} is not asked twice for the same paper because the {products} file lives in one place.',
                'action' => 'Move all open {products} files into one tracker with owner, due date and status.',
                'benefit' => 'Central operations reduce confusion and support later multi-city growth.',
            ],
            [
                'explanation' => 'The methods, checklists and knowledge of {biz} around {products} are assets, not only daily work.',
                'innovation' => 'Turn repeat {products} knowledge into templates, notes and training material owned by {biz}.',
                'example' => 'A junior at {biz} uses the house template and delivers a {products} note that matches the firm standard.',
                'action' => 'Save the 5 best {products} working files as reusable templates with {biz} branding.',
                'benefit' => 'Knowledge assets make {biz} less person-dependent and easier to scale.',
            ],
            [
                'explanation' => '{biz} can serve more {cat} clients by opening a second location or a satellite desk.',
                'innovation' => 'Test a small second presence where existing {products} clients already travel or live.',
                'example' => 'A client in another area books {biz} locally instead of dropping the {products} relationship.',
                'action' => 'Map where current {products} clients sit and choose one area for a weekly visiting desk.',
                'benefit' => 'A second location increases reach without abandoning the core {cat} practice.',
            ],
            [
                'explanation' => '{biz} can multiply {products} through franchise, licensing or structured associate partners.',
                'innovation' => 'Create a partner kit: brand rules, {products} SOP, fee share and quality audit for associates.',
                'example' => 'An associate in another town delivers {biz}-branded {products} using the same method.',
                'action' => 'Write a one-page associate model for {products} and discuss it with one trusted professional.',
                'benefit' => 'A partner model grows geography faster than opening every office alone.',
            ],
            [
                'explanation' => '{biz} should sit at the centre of a {cat} network that feeds {products} work in both directions.',
                'innovation' => 'Build a small ecosystem of bankers, lawyers, vendors and clients who exchange work with {biz}.',
                'example' => 'A banker sends a {products} client to {biz}, and {biz} later supports that banker’s customer needs.',
                'action' => 'List 10 network contacts and schedule two introductions related to {products} this month.',
                'benefit' => 'A living network keeps a steady flow of {cat} opportunities.',
            ],
            [
                'explanation' => 'After the core is strong, {biz} can add a related vertical that still uses {products} skill.',
                'innovation' => 'Choose one adjacent vertical and run it as a separate offer under the {biz} house.',
                'example' => 'A {cat} client of {biz} also buys the new vertical because it solves the next problem after {products}.',
                'action' => 'Name one related vertical, write its first offer, and test it with 3 current clients.',
                'benefit' => 'New verticals increase size without leaving the industry {biz} already understands.',
            ],
            [
                'explanation' => '{biz} should be governed like a firm: roles, reviews, compliance and decisions written down.',
                'innovation' => 'Start a monthly management review covering {products} quality, clients, cash and people.',
                'example' => 'The {biz} review meeting catches a delayed {products} mandate before the client complains.',
                'action' => 'Hold a 45-minute monthly review with agenda, numbers and one decision owner.',
                'benefit' => 'Professional governance makes {biz} bankable, hireable and ready for larger {cat} work.',
            ],
            [
                'explanation' => '{biz} needs a succession path so {products} knowledge and client trust do not stop with one person.',
                'innovation' => 'Document who inherits client relationships, files and the {biz} brand over 3 to 7 years.',
                'example' => 'A long-term client stays with {biz} because they already know the next responsible person.',
                'action' => 'Write a one-page succession note covering clients, files and the {products} method.',
                'benefit' => 'A visible legacy plan increases client trust and long-term value of the {cat} firm.',
            ],
            [
                'explanation' => '{biz} can take proven {products} work from one city to other Indian markets, then abroad.',
                'innovation' => 'Standardize the {products} offer so it can be delivered to clients outside the home city.',
                'example' => 'A client group in another state hires {biz} after seeing the same {products} method work locally.',
                'action' => 'Pick one city or NRI/outstation segment and make a remote {products} delivery plan.',
                'benefit' => 'Geographic expansion turns {biz} from a local {cat} practice into a wider brand.',
            ],
            [
                'explanation' => 'The long aim is for {biz} to become a respected {cat} house that also creates social and industry impact.',
                'innovation' => 'Add one public-value activity: education session, ethics standard or access program around {products}.',
                'example' => 'A young entrepreneur first meets {biz} at a knowledge session and later becomes a {products} client.',
                'action' => 'Plan one knowledge session this quarter that shows how {biz} helps {cat} businesses with {products}.',
                'benefit' => 'Impact plus scale gives {biz} reputation, talent attraction and a true empire identity.',
            ],
        ];
    }

    private static function fillTemplate(string $template, string $biz, string $cat, string $products): string
    {
        return strtr($template, [
            '{biz}' => $biz,
            '{cat}' => $cat,
            '{products}' => $products,
        ]);
    }

    /**
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public static function extras(array $facts): array
    {
        $biz = $facts['business_name'] ?: 'This business';
        $cat = $facts['category'] ?: 'its category';
        $products = $facts['products'] ?: $facts['intro'] ?: $cat;

        return [
            'journey' => [
                'description' => "{$biz} can progress from a {$cat} business focused on {$products} into a professionally managed national brand and eventually a diversified Business House.",
                'steps' => [
                    'Business', 'Products / Services', 'Customers', 'Brand', 'Team', 'System',
                    'Innovation', 'Technology', 'Distribution Network', 'Multiple Locations',
                    'Multiple Verticals', 'Business House', 'National Brand', 'International Business',
                    'Global Business Empire',
                ],
            ],
            'timeline' => [
                'three_year' => [
                    "Build a strong {$cat} brand, professional team, standard SOP, digital client management and a loyal customer network around {$products}.",
                ],
                'five_year' => [
                    "Expand {$biz} into more cities, grow recurring advisory or service packages, and use technology so more customers can be served in a systematic way.",
                ],
                'ten_year' => [
                    "Develop {$biz} into a national {$cat} brand with a professional team, technology platform, partner network and related verticals as a long-term Business House.",
                ],
            ],
            'final' => [
                'title' => 'How can this Business House become a Business Empire?',
                'text' => "{$biz} can become a Business Empire by going beyond day-to-day {$cat} trading. The business can build a stronger team, clearer systems, technology-driven operations, loyal customers and multiple locations. Over time it can grow from {$products} into a complete industry ecosystem and a professionally managed Business House.",
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function languageLabels(): array
    {
        return [
            'en' => 'English',
            'gu' => 'Gujarati',
            'hi' => 'Hindi (Devanagari)',
            'mr' => 'Marathi (Devanagari)',
        ];
    }

    public static function languageRewriteInstruction(string $lang): string
    {
        $label = self::languageLabels()[$lang] ?? $lang;

        $samples = match ($lang) {
            'hi' => implode("\n", [
                'SCRIPT: Hindi Devanagari only. Never use Gujarati letters such as ને, કરવી, બનાવવી, મુંબઈની.',
                'WRONG (Gujarati): "Artiben’s Annapurna Hub ને trusted home-style meal brand બનાવવી."',
                'RIGHT (Hindi): "Artiben’s Annapurna Hub को Mumbai की trusted home-style meal brand बनाना."',
                'RIGHT: "केवल Finance Arrangement नहीं, साथ में संबंधित Advisory सेवाएं विकसित करनी."',
                'RIGHT: "Business Finance Planning Service जोड़ना."',
                'RIGHT: "ग्राहक को upcoming Business Finance Planning मिले."',
                'RIGHT: "Online Finance Consultation शुरू करनी."',
                'Use Hindi grammar: करना, चाहिए, नहीं, मिले, बनाना — not Gujarati કરવું / નહીં / મળે.',
            ]),
            'mr' => implode("\n", [
                'SCRIPT: Marathi Devanagari only. Never use Gujarati letters such as ને, કરવી, બનાવવી.',
                'WRONG (Gujarati): "Cadworld Infoways ને Mumbaiની trusted brand બનાવવી."',
                'RIGHT (Marathi): "Cadworld Infoways ला Mumbai ची trusted brand बनवायची."',
                'RIGHT: "फक्त Finance Arrangement नाही, सोबत संबंधित Advisory सेवा विकसित करावी."',
                'RIGHT: "Business Finance Planning Service जोडावी."',
                'RIGHT: "ग्राहकाला upcoming Business Finance Planning मिळावे."',
                'Use Marathi grammar: करावी, नाही, मिळावे, बनवायची — not Hindi करना / नहीं, not Gujarati કરવું.',
            ]),
            default => implode("\n", [
                'SCRIPT: Gujarati script only. Do not write Hindi or Marathi Devanagari.',
                'WRONG: "Cadworld Infoways को Mumbai की trusted brand बनाना."',
                'RIGHT: "Cadworld Infoways ને Mumbaiની trusted brand બનાવવી."',
                'RIGHT: "ફક્ત Finance Arrangement નહીં, સાથે સંબંધિત Advisory સેવાઓ વિકસાવવી."',
                'RIGHT: "Business Finance Planning Service ઉમેરવી."',
                'RIGHT: "ગ્રાહકને upcoming Business Finance Planning મળે."',
            ]),
        };

        return <<<TXT
Rewrite this Business Empire Vision Note for Indian businessmen in {$label}.

STYLE — spoken mixed-language ERP notes, NOT a full literary translation.

{$samples}

RULES:
- Write like a businessman talks: {$label} sentence + English business words kept as-is.
- Keep English names of services, packages, systems, Digital, R&D, Innovation, Advisory, Package, Consultation.
- Each cell = 1 short sentence.
- Do not translate word-for-word.
- Do not put the business name and the main product into every cell.
- Keep named English offers from the English master. If missing, invent a short English offer name that fits this business.
- Titles may stay English or mix {$label} + English.
- Keep the business name in original English script when you mention it.
- Return ONLY valid JSON with keys ui, vision_points, journey, timeline, final.
- vision_points must have exactly 30 items with title, explanation, innovation, example, action, benefit.
TXT;
    }

    /**
     * @param  array<string, mixed>  $pack
     */
    public static function languagePackScriptIsValid(string $lang, array $pack): bool
    {
        $chunks = [];
        foreach (is_array($pack['visions'] ?? null) ? $pack['visions'] : [] as $vision) {
            if (! is_array($vision)) {
                continue;
            }
            foreach (['explanation', 'innovation', 'example', 'action', 'benefit'] as $key) {
                $chunks[] = (string) ($vision[$key] ?? '');
            }
        }

        $text = trim(implode("\n", $chunks));
        if ($text === '') {
            return false;
        }

        $gujarati = preg_match_all('/\p{Gujarati}/u', $text) ?: 0;
        $devanagari = preg_match_all('/\p{Devanagari}/u', $text) ?: 0;

        return match ($lang) {
            'gu' => $gujarati >= 30 && $gujarati >= $devanagari,
            'hi', 'mr' => $devanagari >= 30 && $gujarati * 3 < $devanagari,
            default => true,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function ui(string $lang, string $businessName): array
    {
        $packs = [
            'en' => [
                'pageSubtitle' => 'Business House → Business Empire Vision',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'visionTitle' => '30-Point Business Empire Vision',
                'thNo' => 'No.',
                'thVision' => 'Vision Point',
                'thExplanation' => 'Simple Explanation',
                'thInnovation' => 'Innovation Idea',
                'thExample' => 'Easy Practical Example',
                'thAction' => 'Action Step',
                'thBenefit' => 'Business Benefit',
                'journeyTitle' => 'Business Empire Journey',
                'threeYearTitle' => '3-Year Vision',
                'fiveYearTitle' => '5-Year Vision',
                'tenYearTitle' => '10-Year Vision',
                'footer' => $businessName.' — Business House → Business Empire Vision',
            ],
            'gu' => [
                'pageSubtitle' => 'બિઝનેસ હાઉસ → બિઝનેસ એમ્પાયર વિઝન',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'visionTitle' => '30-Point Business Empire Vision',
                'thNo' => 'No.',
                'thVision' => 'વિઝન પોઇન્ટ',
                'thExplanation' => 'સરળ સમજણ',
                'thInnovation' => 'ઇનોવેશન આઈડિયા',
                'thExample' => 'સરળ પ્રેક્ટિકલ ઉદાહરણ',
                'thAction' => 'એક્શન સ્ટેપ',
                'thBenefit' => 'બિઝનેસ લાભ',
                'journeyTitle' => 'Business Empire Journey',
                'threeYearTitle' => '3 વર્ષનું Vision',
                'fiveYearTitle' => '5 વર્ષનું Vision',
                'tenYearTitle' => '10 વર્ષનું Vision',
                'footer' => $businessName.' — બિઝનેસ હાઉસ → બિઝનેસ એમ્પાયર વિઝન',
            ],
            'hi' => [
                'pageSubtitle' => 'बिज़नेस हाउस → बिज़नेस एम्पायर विज़न',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'visionTitle' => '30-Point Business Empire Vision',
                'thNo' => 'No.',
                'thVision' => 'विज़न पॉइंट',
                'thExplanation' => 'सरल समझ',
                'thInnovation' => 'इनोवेशन आइडिया',
                'thExample' => 'सरल प्रैक्टिकल उदाहरण',
                'thAction' => 'एक्शन स्टेप',
                'thBenefit' => 'बिज़नेस लाभ',
                'journeyTitle' => 'Business Empire Journey',
                'threeYearTitle' => '3 वर्ष का Vision',
                'fiveYearTitle' => '5 वर्ष का Vision',
                'tenYearTitle' => '10 वर्ष का Vision',
                'footer' => $businessName.' — बिज़नेस हाउस → बिज़नेस एम्पायर विज़न',
            ],
            'mr' => [
                'pageSubtitle' => 'बिझनेस हाउस → बिझनेस एम्पायर व्हिजन',
                'labelBusinessName' => 'Business Name:',
                'labelCategory' => 'Business Category:',
                'labelIntroduction' => 'Business Introduction:',
                'labelProducts' => 'Business Main Product:',
                'visionTitle' => '30-Point Business Empire Vision',
                'thNo' => 'No.',
                'thVision' => 'व्हिजन पॉइंट',
                'thExplanation' => 'सोपी समज',
                'thInnovation' => 'इनोव्हेशन आयडिया',
                'thExample' => 'सोपे प्रॅक्टिकल उदाहरण',
                'thAction' => 'अॅक्शन स्टेप',
                'thBenefit' => 'बिझनेस लाभ',
                'journeyTitle' => 'Business Empire Journey',
                'threeYearTitle' => '3 वर्षांचे Vision',
                'fiveYearTitle' => '5 वर्षांचे Vision',
                'tenYearTitle' => '10 वर्षांचे Vision',
                'footer' => $businessName.' — बिझनेस हाउस → बिझनेस एम्पायर व्हिजन',
            ],
        ];

        $base = $packs['en'];
        $chosen = $packs[$lang] ?? $packs['en'];

        return array_merge($base, $chosen);
    }
}
