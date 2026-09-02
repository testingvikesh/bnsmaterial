<?php

namespace App\Services;

use App\Models\ManageSession;
use App\Models\MemberProfile;
use App\Models\SessionPrompt;
use App\Models\User;
use App\Support\MaterialEmpireVision;
use App\Support\MaterialOneTo25;
use App\Support\MaterialReverseManagement;
use App\Support\MaterialSessionFormat;
use App\Support\MaterialTaglineMasterclass;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MaterialIdeaGenerator
{
    /**
     * @return array<string, mixed>
     */
    public function generate(ManageSession $session, SessionPrompt $prompt, User $user): array
    {
        $user->loadMissing('memberProfile');
        $profile = $user->memberProfile;
        if (! $profile instanceof MemberProfile) {
            throw new \RuntimeException('Selected user is not a member.');
        }

        $facts = [
            'business_name' => trim((string) ($profile->business_name ?: '')),
            'category' => trim((string) ($profile->business_category ?: '')),
            'intro' => trim((string) ($profile->business_description ?: '')),
            'products' => trim((string) ($profile->main_products_services ?: '')),
        ];
        $businessName = $facts['business_name'] !== '' ? $facts['business_name'] : ($user->name.' Business');
        $phone = trim((string) $user->phone);
        if ($phone === '') {
            $phone = trim((string) ($profile->whatsapp ?? ''));
        }

        $snapshot = [
            'memberName' => (string) $user->name,
            'businessName' => $businessName,
            'businessCategory' => $facts['category'],
            'businessIntroduction' => $facts['intro'],
            'mainProduct' => $facts['products'],
            'businessLocation' => (string) ($profile->business_location ?: ''),
            'businessAddress' => (string) ($profile->business_address ?: ''),
            'whatsapp' => $phone,
            'sessionName' => (string) $session->name,
            'sessionDetails' => (string) ($session->details ?: ''),
            'city' => (string) ($profile->city ?: $profile->business_location ?: ''),
        ];

        $filledPrompt = trim($this->memberContextBlock($snapshot, $businessName)."\n\n".$prompt->filled($snapshot));
        $format = MaterialSessionFormat::resolve($session, $prompt);

        if ($format === MaterialSessionFormat::EMPIRE) {
            return $this->generateEmpire($session, $prompt, $user, $profile, $facts, $businessName, $snapshot, $filledPrompt);
        }

        if ($format === MaterialSessionFormat::REVERSE) {
            return $this->generateReverse($session, $prompt, $user, $profile, $facts, $businessName, $snapshot, $filledPrompt);
        }

        if ($format === MaterialSessionFormat::TAGLINE) {
            return $this->generateTagline($session, $prompt, $user, $profile, $facts, $businessName, $snapshot, $filledPrompt);
        }

        $local = MaterialOneTo25::for([
            'biz' => $businessName,
            'cat' => $facts['category'] ?: 'Business',
            'desc' => $facts['intro'],
            'products' => $facts['products'] ?: $facts['intro'],
        ], (string) $user->name);

        $source = 'local';
        $ideas = [];

        if ($this->apiEnabled() && $filledPrompt !== '') {
            try {
                $ideas = $this->viaOpenAi($filledPrompt);
                $source = 'openai';
            } catch (\Throwable $e) {
                Log::warning('OpenAI material generate failed, using local engine.', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'session_id' => $session->id,
                    'prompt_id' => $prompt->id,
                ]);
            }
        }

        if ($ideas === []) {
            $ideas = $local;
            $source = $source === 'openai' ? 'openai_fallback' : 'local';
        }

        $clean = [];
        for ($n = 1; $n <= 25; $n++) {
            $idea = is_array($ideas[$n] ?? null) ? $ideas[$n] : ($local[$n] ?? []);
            $fromOpenAi = $source === 'openai' && isset($ideas[$n]) && is_array($ideas[$n]);

            $name = trim((string) ($idea['name'] ?? ''));
            if ($name === '') {
                $name = (string) ($local[$n]['name'] ?? ('Business idea '.$n));
            }

            $offers = $this->normalizeOffers($idea['product_ideas'] ?? []);
            if (! $fromOpenAi) {
                $fallbackOffers = is_array($local[$n]['product_ideas'] ?? null) ? $local[$n]['product_ideas'] : [];
                while (count($offers) < 10) {
                    $offers[] = $fallbackOffers[count($offers)] ?? MaterialOneTo25::offerLine($name, $facts['products'] ?: $facts['category'], count($offers) + 1);
                }
            } else {
                while (count($offers) < 10) {
                    $offers[] = MaterialOneTo25::offerLine($name, $facts['products'] ?: $facts['category'], count($offers) + 1);
                }
            }
            $offers = array_values(array_slice($offers, 0, 10));

            $concept = trim((string) ($idea['concept'] ?? ''));
            if ($concept === '') {
                $concept = trim((string) ($local[$n]['concept'] ?? ''));
            }
            if ($concept === '') {
                $concept = MaterialOneTo25::conceptFor($name, $businessName, $facts['category'], $facts['products'] ?: $facts['intro']);
            }

            $clean[$n] = [
                'name' => $name,
                'concept' => $concept,
                'product_ideas' => $offers,
            ];
        }

        return [
            'source' => $source,
            'prompt' => [
                'id' => $prompt->id,
                'title' => $prompt->title,
                'body' => $filledPrompt !== '' ? $filledPrompt : null,
            ],
            'member' => [
                'name' => (string) $user->name,
                'member_id' => (string) ($profile->member_id ?: ''),
                'business_name' => $businessName,
            ],
            'facts' => [
                'category' => $facts['category'],
                'intro' => $facts['intro'],
                'products' => $facts['products'],
            ],
            'snapshot' => $snapshot,
            'ideas' => $clean,
            'format' => 'one_to_25',
        ];
    }

    public function isEmpireFormat(ManageSession $session, SessionPrompt $prompt): bool
    {
        return MaterialSessionFormat::resolve($session, $prompt) === MaterialSessionFormat::EMPIRE;
    }

    /**
     * @param  array<string, string>  $facts
     * @param  array<string, string>  $snapshot
     * @return array<string, mixed>
     */
    private function generateReverse(
        ManageSession $session,
        SessionPrompt $prompt,
        User $user,
        MemberProfile $profile,
        array $facts,
        string $businessName,
        array $snapshot,
        string $filledPrompt
    ): array {
        $plan = MaterialReverseManagement::for([
            'business_name' => $businessName,
            'category' => $facts['category'],
            'intro' => $facts['intro'],
            'products' => $facts['products'],
        ]);

        return [
            'source' => 'local',
            'format' => MaterialSessionFormat::REVERSE,
            'prompt' => [
                'id' => $prompt->id,
                'title' => $prompt->title,
                'body' => $filledPrompt !== '' ? $filledPrompt : null,
            ],
            'member' => [
                'name' => (string) $user->name,
                'member_id' => (string) ($profile->member_id ?: ''),
                'business_name' => $businessName,
            ],
            'facts' => [
                'category' => $facts['category'],
                'intro' => $facts['intro'],
                'products' => $facts['products'],
            ],
            'snapshot' => $snapshot,
            'ideas' => [],
            'reverse' => $plan,
            'languages' => $plan['languages'] ?? [],
        ];
    }

    /**
     * @param  array<string, string>  $facts
     * @param  array<string, string>  $snapshot
     * @return array<string, mixed>
     */
    private function generateTagline(
        ManageSession $session,
        SessionPrompt $prompt,
        User $user,
        MemberProfile $profile,
        array $facts,
        string $businessName,
        array $snapshot,
        string $filledPrompt
    ): array {
        $plan = MaterialTaglineMasterclass::for([
            'business_name' => $businessName,
            'category' => $facts['category'],
            'intro' => $facts['intro'],
            'products' => $facts['products'],
            'location' => (string) ($profile->business_location ?: $snapshot['city'] ?? ''),
            'member_name' => (string) $user->name,
        ]);

        return [
            'source' => 'local',
            'format' => MaterialSessionFormat::TAGLINE,
            'prompt' => [
                'id' => $prompt->id,
                'title' => $prompt->title,
                'body' => $filledPrompt !== '' ? $filledPrompt : null,
            ],
            'member' => [
                'name' => (string) $user->name,
                'member_id' => (string) ($profile->member_id ?: ''),
                'business_name' => $businessName,
            ],
            'facts' => [
                'category' => $facts['category'],
                'intro' => $facts['intro'],
                'products' => $facts['products'],
            ],
            'snapshot' => $snapshot,
            'ideas' => [],
            'tagline' => $plan,
            'languages' => $plan['languages'] ?? [],
        ];
    }

    /**
     * @param  array<string, string>  $facts
     * @param  array<string, string>  $snapshot
     * @return array<string, mixed>
     */
    private function generateEmpire(
        ManageSession $session,
        SessionPrompt $prompt,
        User $user,
        MemberProfile $profile,
        array $facts,
        string $businessName,
        array $snapshot,
        string $filledPrompt
    ): array {
        $localFacts = ['business_name' => $businessName] + $facts;
        $local = MaterialEmpireVision::points($localFacts);
        $extras = MaterialEmpireVision::extras($localFacts);
        $source = 'local';
        $api = [];

        if ($this->apiEnabled() && $filledPrompt !== '') {
            try {
                $api = $this->viaOpenAi($filledPrompt, 'empire');
                $source = 'openai';
            } catch (\Throwable $e) {
                Log::warning('OpenAI empire generate failed, using local engine.', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'session_id' => $session->id,
                    'prompt_id' => $prompt->id,
                ]);
            }
        }

        $apiPoints = is_array($api['visions'] ?? null) ? $api['visions'] : [];
        $visions = [];
        for ($n = 0; $n < 30; $n++) {
            $fromApi = is_array($apiPoints[$n] ?? null) ? $apiPoints[$n] : [];
            $fallback = $local[$n] ?? [];
            $visions[] = [
                'title' => trim((string) ($fromApi['title'] ?? $fallback['title'] ?? ('Vision Point '.($n + 1)))),
                'explanation' => trim((string) ($fromApi['explanation'] ?? $fallback['explanation'] ?? '')),
                'innovation' => trim((string) ($fromApi['innovation'] ?? $fallback['innovation'] ?? '')),
                'example' => trim((string) ($fromApi['example'] ?? $fallback['example'] ?? '')),
                'action' => trim((string) ($fromApi['action'] ?? $fallback['action'] ?? '')),
                'benefit' => trim((string) ($fromApi['benefit'] ?? $fallback['benefit'] ?? '')),
            ];
        }

        if ($source === 'openai' && $this->empireVisionsLookCopied($visions)) {
            Log::warning('OpenAI empire points were repetitive, using unique local points.', [
                'user_id' => $user->id,
                'session_id' => $session->id,
            ]);
            $visions = $local;
            $source = 'local';
        }

        $journey = is_array($api['journey'] ?? null) ? $api['journey'] : [];
        $timeline = is_array($api['timeline'] ?? null) ? $api['timeline'] : [];
        $final = is_array($api['final'] ?? null) ? $api['final'] : [];

        $payload = [
            'source' => $source,
            'format' => 'empire',
            'prompt' => [
                'id' => $prompt->id,
                'title' => $prompt->title,
                'body' => $filledPrompt !== '' ? $filledPrompt : null,
            ],
            'member' => [
                'name' => (string) $user->name,
                'member_id' => (string) ($profile->member_id ?: ''),
                'business_name' => $businessName,
            ],
            'facts' => [
                'category' => $facts['category'],
                'intro' => $facts['intro'],
                'products' => $facts['products'],
            ],
            'snapshot' => $snapshot,
            'ideas' => [],
            'visions' => $visions,
            'journey' => [
                'description' => trim((string) ($journey['description'] ?? $extras['journey']['description'])),
                'steps' => $this->stringList($journey['steps'] ?? $extras['journey']['steps'] ?? []),
            ],
            'timeline' => [
                'three_year' => $this->stringList($timeline['three_year'] ?? $extras['timeline']['three_year'] ?? []),
                'five_year' => $this->stringList($timeline['five_year'] ?? $extras['timeline']['five_year'] ?? []),
                'ten_year' => $this->stringList($timeline['ten_year'] ?? $extras['timeline']['ten_year'] ?? []),
            ],
            'final' => [
                'title' => trim((string) ($final['title'] ?? $extras['final']['title'])),
                'text' => trim((string) ($final['text'] ?? $extras['final']['text'])),
            ],
        ];

        $englishPack = [
            'ui' => MaterialEmpireVision::ui('en', $businessName),
            'visions' => $payload['visions'],
            'journey' => $payload['journey'],
            'timeline' => $payload['timeline'],
            'final' => $payload['final'],
        ];

        $languages = ['en' => $englishPack];
        foreach (['gu', 'hi', 'mr'] as $lang) {
            $generated = [];
            if ($source === 'openai' && $this->apiEnabled()) {
                try {
                    $generated = $this->viaOpenAiEmpireLanguage($englishPack, $lang);
                } catch (\Throwable $e) {
                    Log::warning('OpenAI empire language generate failed.', [
                        'language' => $lang,
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                    ]);
                }
            }

            $languages[$lang] = $this->mergeEmpireLanguage($englishPack, $generated, $lang, $businessName);
        }

        $payload['languages'] = $languages;

        return $this->personalizeEmpireResult($payload, $snapshot, $businessName, $facts);
    }

    public function apiEnabled(): bool
    {
        if (! filter_var(config('services.openai.enabled', true), FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        return filled(config('services.openai.key'));
    }

    /**
     * @return array<int, array{name: string, concept: string, product_ideas: array<int, string>}>
     */
    private function viaOpenAi(string $userPrompt, string $format = 'one_to_25'): array
    {
        if ($format === 'empire') {
            return $this->viaOpenAiEmpire($userPrompt);
        }

        $json = $this->openaiChat([
            ['role' => 'system', 'content' => $this->systemPrompt($format)],
            ['role' => 'user', 'content' => $userPrompt."\n\n".$this->jsonInstruction($format)],
        ]);

        $content = (string) data_get($json, 'choices.0.message.content');
        if (trim($content) === '') {
            throw new \RuntimeException('OpenAI returned empty content.');
        }

        $out = $this->parseIdeasJson($content);
        if (count($out) < 25) {
            throw new \RuntimeException('OpenAI returned too few business ideas ('.count($out).').');
        }

        return $out;
    }

    /**
     * @return array{visions: list<array<string, string>>, journey: array<string, mixed>, timeline: array<string, mixed>, final: array<string, string>, ui: array<string, mixed>}
     */
    private function viaOpenAiEmpire(string $userPrompt): array
    {
        $full = $this->openaiEmpireOnce($userPrompt);
        if (count($full['visions']) >= 30 && ! $this->empireVisionsLookCopied($full['visions'])) {
            return $full;
        }

        $titles = MaterialEmpireVision::titles();
        $visions = [];
        $merged = $full;
        foreach ([[1, 10], [11, 20], [21, 30]] as [$from, $to]) {
            $slice = array_slice($titles, $from - 1, ($to - $from) + 1);
            $lines = [];
            foreach ($slice as $i => $title) {
                $lines[] = ($from + $i).'. '.$title;
            }
            $extra = implode("\n", [
                "Generate ONLY vision points {$from} to {$to}.",
                'Required titles in this exact order:',
                implode("\n", $lines),
                'Return exactly '.count($slice).' vision_points.',
                'Each explanation, innovation, example, action and benefit must be unique and 1 short sentence.',
                'Invent a named offer for each point. Do not repeat the main product in every cell.',
                $from === 21 ? 'Also include journey, timeline and final.' : 'Do not include journey, timeline or final.',
            ]);
            $chunk = $this->openaiEmpireOnce($userPrompt, $extra);
            foreach ($chunk['visions'] as $vision) {
                $visions[] = $vision;
            }
            foreach (['journey', 'timeline', 'final'] as $key) {
                if (($chunk[$key] ?? []) !== []) {
                    $merged[$key] = $chunk[$key];
                }
            }
        }

        $merged['visions'] = array_slice($visions, 0, 30);
        if (count($merged['visions']) < 30 || $this->empireVisionsLookCopied($merged['visions'])) {
            throw new \RuntimeException('OpenAI returned too few unique vision points ('.count($merged['visions']).').');
        }

        return $merged;
    }

    /**
     * @return array{visions: list<array<string, string>>, journey: array<string, mixed>, timeline: array<string, mixed>, final: array<string, string>, ui: array<string, mixed>}
     */
    private function openaiEmpireOnce(string $userPrompt, string $extra = ''): array
    {
        $json = $this->openaiChat([
            ['role' => 'system', 'content' => $this->systemPrompt('empire')],
            ['role' => 'user', 'content' => trim($userPrompt."\n\n".$this->jsonInstruction('empire')."\n\n".$extra)],
        ]);

        $content = (string) data_get($json, 'choices.0.message.content');
        if (trim($content) === '') {
            Log::warning('OpenAI empire empty content.', [
                'finish' => data_get($json, 'choices.0.finish_reason'),
                'usage' => data_get($json, 'usage'),
                'model' => data_get($json, 'model'),
            ]);

            return ['visions' => [], 'journey' => [], 'timeline' => [], 'final' => [], 'ui' => []];
        }

        return $this->parseEmpireJson($content);
    }

    /**
     * @return array{visions: list<array<string, string>>, journey: array<string, mixed>, timeline: array<string, mixed>, final: array<string, string>}
     */
    public function parseEmpireJson(string $content): array
    {
        $decoded = $this->decodeJson($content);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON from OpenAI.');
        }

        $raw = $decoded['vision_points'] ?? $decoded['visions'] ?? $decoded['points'] ?? [];
        $visions = [];
        if (is_array($raw)) {
            foreach ($raw as $idea) {
                if (! is_array($idea)) {
                    continue;
                }
                $visions[] = [
                    'title' => trim((string) ($idea['title'] ?? $idea['name'] ?? $idea['vision_point'] ?? '')),
                    'explanation' => trim((string) ($idea['explanation'] ?? $idea['simple_explanation'] ?? '')),
                    'innovation' => trim((string) ($idea['innovation'] ?? $idea['innovation_idea'] ?? '')),
                    'example' => trim((string) ($idea['example'] ?? $idea['practical_example'] ?? '')),
                    'action' => trim((string) ($idea['action'] ?? $idea['action_step'] ?? '')),
                    'benefit' => trim((string) ($idea['benefit'] ?? $idea['expected_benefit'] ?? '')),
                ];
                if (count($visions) >= 30) {
                    break;
                }
            }
        }

        $journey = is_array($decoded['journey'] ?? null) ? $decoded['journey'] : [];
        $timeline = is_array($decoded['timeline'] ?? null) ? $decoded['timeline'] : [];
        $final = is_array($decoded['final'] ?? null) ? $decoded['final'] : [];

        return [
            'visions' => $visions,
            'journey' => $journey,
            'timeline' => $timeline,
            'final' => $final,
            'ui' => is_array($decoded['ui'] ?? null) ? $decoded['ui'] : [],
        ];
    }

    /**
     * @param  array<string, mixed>  $english
     * @return array<string, mixed>
     */
    private function viaOpenAiEmpireLanguage(array $english, string $lang): array
    {
        $compact = [
            'ui' => $english['ui'] ?? [],
            'vision_points' => $english['visions'] ?? [],
            'journey' => $english['journey'] ?? [],
            'timeline' => $english['timeline'] ?? [],
            'final' => $english['final'] ?? [],
        ];

        $label = MaterialEmpireVision::languageLabels()[$lang] ?? $lang;
        $instruction = MaterialEmpireVision::languageRewriteInstruction($lang);
        $master = json_encode($compact, JSON_UNESCAPED_UNICODE);
        $parsed = $this->openaiEmpireLanguageOnce($instruction, $master);

        if (! MaterialEmpireVision::languagePackScriptIsValid($lang, $parsed)) {
            Log::warning('OpenAI empire language used the wrong script, retrying.', ['language' => $lang]);
            $retry = $instruction."\n\nCRITICAL RETRY: The previous answer used the WRONG Indian script for {$label}. Rewrite every cell again in {$label} only.";
            $parsed = $this->openaiEmpireLanguageOnce($retry, $master);
        }

        if (! MaterialEmpireVision::languagePackScriptIsValid($lang, $parsed)) {
            throw new \RuntimeException('OpenAI returned the wrong script for '.$label.'.');
        }

        return $parsed;
    }

    /**
     * @return array<string, mixed>
     */
    private function openaiEmpireLanguageOnce(string $instruction, string $masterJson): array
    {
        $json = $this->openaiChat([
            ['role' => 'system', 'content' => 'You are a senior Indian business writer. Write short mixed-language ERP notes in the requested Indian language script only. Never mix Gujarati script into Hindi or Marathi. Return ONLY valid JSON.'],
            ['role' => 'user', 'content' => $instruction."\n\nENGLISH MASTER:\n".$masterJson],
        ], 'gpt-4o-mini');

        $content = (string) data_get($json, 'choices.0.message.content');
        if (trim($content) === '') {
            throw new \RuntimeException('OpenAI returned empty content.');
        }

        return $this->parseEmpireJson($content);
    }

    /**
     * @param  list<array{role: string, content: string}>  $messages
     * @return array<string, mixed>
     */
    private function openaiChat(array $messages, ?string $forceModel = null): array
    {
        $key = (string) config('services.openai.key');
        $base = rtrim((string) (config('services.openai.base') ?: 'https://api.openai.com/v1'), '/');
        $timeout = max(30, min(180, (int) (config('services.openai.timeout') ?: 180)));
        $maxTokens = max(4096, min(16384, (int) (config('services.openai.max_tokens') ?: 16384)));

        $models = array_values(array_unique(array_filter([
            $forceModel,
            $this->normalizeOpenaiModel(),
            'gpt-5.6',
            'gpt-5.6-luna',
            'gpt-4o-mini',
        ])));

        $lastError = 'OpenAI request failed.';
        foreach ($models as $model) {
            $payload = [
                'model' => $model,
                'response_format' => ['type' => 'json_object'],
                'messages' => $messages,
            ];

            if (preg_match('/^(o1|o3|gpt-5)/i', $model)) {
                $payload['max_completion_tokens'] = $maxTokens;
                $payload['reasoning_effort'] = 'none';
            } else {
                $payload['temperature'] = 0.55;
                $payload['max_tokens'] = $maxTokens;
            }

            $response = Http::timeout($timeout)
                ->connectTimeout(15)
                ->withToken($key)
                ->acceptJson()
                ->post($base.'/chat/completions', $payload);

            if (! $response->successful() && $response->status() === 400 && isset($payload['reasoning_effort'])) {
                unset($payload['reasoning_effort']);
                $response = Http::timeout($timeout)
                    ->connectTimeout(15)
                    ->withToken($key)
                    ->acceptJson()
                    ->post($base.'/chat/completions', $payload);
            }

            if ($response->successful()) {
                return $response->json() ?? [];
            }

            $lastError = 'OpenAI API error: '.$response->status().' '.Str::limit((string) $response->body(), 300);
            if ($response->status() !== 404) {
                throw new \RuntimeException($lastError);
            }

            Log::warning('OpenAI model not found, trying next model.', ['model' => $model]);
        }

        throw new \RuntimeException($lastError);
    }

    private function normalizeOpenaiModel(): string
    {
        $model = strtolower(trim(str_replace([' ', '_'], '-', (string) (config('services.openai.model') ?: 'gpt-5.6'))));

        return match ($model) {
            'gpt5.6', 'gpt-5-6', 'gpt-5.6-sol' => 'gpt-5.6',
            default => $model,
        };
    }

    /**
     * @param  array<string, string>  $snapshot
     */
    private function memberContextBlock(array $snapshot, string $businessName): string
    {
        return implode("\n", [
            'PRESENT MEMBER BUSINESS — use these exact names in every sentence.',
            'Never write placeholders such as [Business Name], [Business Category], [Main Product / Service] or [Businessman Name].',
            'Businessman: '.($snapshot['memberName'] ?? ''),
            'Business name: '.$businessName,
            'Category: '.($snapshot['businessCategory'] ?? ''),
            'Introduction: '.($snapshot['businessIntroduction'] ?? ''),
            'Main product / service: '.($snapshot['mainProduct'] ?? ''),
            'City: '.($snapshot['city'] ?? ''),
            'Address: '.($snapshot['businessAddress'] ?? ''),
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $snapshot
     * @param  array<string, string>  $facts
     * @return array<string, mixed>
     */
    public function personalizeEmpireResult(array $payload, array $snapshot, string $businessName, array $facts): array
    {
        $products = $facts['products'] ?: ($facts['intro'] ?? '') ?: ($snapshot['mainProduct'] ?? '');
        $category = $facts['category'] ?: ($snapshot['businessCategory'] ?? '');
        $member = $snapshot['memberName'] ?? '';

        $map = [
            '[Business Name]' => $businessName,
            '[Business name]' => $businessName,
            '[business name]' => $businessName,
            '[Business Category]' => $category,
            '[business category]' => $category,
            '[Main Product / Service]' => $products,
            '[Main Product/Service]' => $products,
            '[Main Product \/ Service]' => $products,
            '[main product / service]' => $products,
            '[Businessman Name]' => $member,
            '[Businessman name]' => $member,
            '[businessman name]' => $member,
            '[Businessman]' => $member,
            'this member business' => $businessName,
            'This member business' => $businessName,
            'આ સભ્ય બિઝનેસ' => $businessName,
            'इस सदस्य व्यवसाय' => $businessName,
            'या सदस्य व्यवसाया' => $businessName,
        ];

        return $this->replacePlaceholders($payload, $map);
    }

    /**
     * @param  array<string, mixed>|list<mixed>  $value
     * @param  array<string, string>  $map
     * @return array<string, mixed>|list<mixed>
     */
    private function replacePlaceholders(array $value, array $map): array
    {
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->replacePlaceholders($item, $map);
            } elseif (is_string($item)) {
                $value[$key] = str_ireplace(array_keys($map), array_values($map), $item);
            }
        }

        return $value;
    }

    /**
     * @return list<string>
     */
    private function stringList(mixed $value): array
    {
        if (is_string($value)) {
            $parts = preg_split('/\s*(?:→|->|\n|;)\s*/u', $value) ?: [];
            $value = $parts;
        }

        if (! is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $item) {
            if (is_array($item)) {
                $item = implode(' ', array_filter($item, 'is_string'));
            }
            $item = trim((string) $item);
            if ($item !== '') {
                $out[] = $item;
            }
        }

        return array_values($out);
    }

    /**
     * @param  list<array<string, string>>  $visions
     */
    private function empireVisionsLookCopied(array $visions): bool
    {
        $explanations = [];
        foreach ($visions as $vision) {
            $text = strtolower(trim((string) ($vision['explanation'] ?? '')));
            if ($text !== '') {
                $explanations[] = $text;
            }
        }

        if (count($explanations) < 20) {
            return true;
        }

        return count(array_unique($explanations)) < 20;
    }

    /**
     * @param  array<string, mixed>  $english
     * @param  array<string, mixed>  $generated
     * @return array<string, mixed>
     */
    private function mergeEmpireLanguage(array $english, array $generated, string $lang, string $businessName): array
    {
        $ui = MaterialEmpireVision::ui($lang, $businessName);

        $visions = [];
        $fromApi = is_array($generated['visions'] ?? null) ? $generated['visions'] : [];
        $fallback = is_array($english['visions'] ?? null) ? $english['visions'] : [];
        for ($n = 0; $n < 30; $n++) {
            $item = is_array($fromApi[$n] ?? null) ? $fromApi[$n] : [];
            $base = is_array($fallback[$n] ?? null) ? $fallback[$n] : [];
            $visions[] = [
                'title' => trim((string) ($item['title'] ?? $base['title'] ?? '')),
                'explanation' => trim((string) ($item['explanation'] ?? $base['explanation'] ?? '')),
                'innovation' => trim((string) ($item['innovation'] ?? $base['innovation'] ?? '')),
                'example' => trim((string) ($item['example'] ?? $base['example'] ?? '')),
                'action' => trim((string) ($item['action'] ?? $base['action'] ?? '')),
                'benefit' => trim((string) ($item['benefit'] ?? $base['benefit'] ?? '')),
            ];
        }

        $journey = is_array($generated['journey'] ?? null) ? $generated['journey'] : [];
        $timeline = is_array($generated['timeline'] ?? null) ? $generated['timeline'] : [];
        $final = is_array($generated['final'] ?? null) ? $generated['final'] : [];

        return [
            'ui' => $ui,
            'visions' => $visions,
            'journey' => [
                'description' => trim((string) ($journey['description'] ?? $english['journey']['description'] ?? '')),
                'steps' => $this->stringList($journey['steps'] ?? $english['journey']['steps'] ?? []),
            ],
            'timeline' => [
                'three_year' => $this->stringList($timeline['three_year'] ?? $english['timeline']['three_year'] ?? []),
                'five_year' => $this->stringList($timeline['five_year'] ?? $english['timeline']['five_year'] ?? []),
                'ten_year' => $this->stringList($timeline['ten_year'] ?? $english['timeline']['ten_year'] ?? []),
            ],
            'final' => [
                'title' => trim((string) ($final['title'] ?? $english['final']['title'] ?? '')),
                'text' => trim((string) ($final['text'] ?? $english['final']['text'] ?? '')),
            ],
        ];
    }

    /**
     * @return array<int, array{name: string, concept: string, product_ideas: array<int, string>}>
     */
    public function parseIdeasJson(string $content): array
    {
        $decoded = $this->decodeJson($content);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid JSON from OpenAI.');
        }

        return $this->mapIdeas($decoded);
    }

    /**
     * @param  array<string, mixed>  $decoded
     * @return array<int, array{name: string, concept: string, product_ideas: array<int, string>}>
     */
    private function mapIdeas(array $decoded): array
    {
        $raw = $decoded['business_ideas'] ?? $decoded['ideas'] ?? $decoded;
        if (! is_array($raw) || $raw === []) {
            return [];
        }

        $out = [];
        foreach ($raw as $key => $idea) {
            if (! is_array($idea)) {
                continue;
            }

            $name = trim((string) ($idea['name'] ?? $idea['businessName'] ?? $idea['business_name'] ?? $idea['title'] ?? ''));
            $concept = trim((string) (
                $idea['concept']
                ?? $idea['business_concept']
                ?? $idea['businessConcept']
                ?? $idea['idea']
                ?? $idea['intro']
                ?? $idea['business_idea']
                ?? $idea['description']
                ?? ''
            ));
            $offers = $idea['product_ideas']
                ?? $idea['service_product_ideas']
                ?? $idea['service_ideas']
                ?? $idea['products']
                ?? [];

            if ($name === '' && $concept === '' && (! is_array($offers) || $offers === [])) {
                continue;
            }

            $n = is_numeric($key) ? (int) $key : (count($out) + 1);
            if ($n < 1 || $n > 25) {
                $n = count($out) + 1;
            }

            $out[$n] = [
                'name' => $name !== '' ? $name : 'Business idea '.$n,
                'concept' => $concept,
                'product_ideas' => $this->normalizeOffers($offers),
            ];
        }

        ksort($out);

        $ordered = [];
        $i = 1;
        foreach ($out as $idea) {
            $ordered[$i] = $idea;
            $i++;
            if ($i > 25) {
                break;
            }
        }

        return $ordered;
    }

    /**
     * @return list<string>
     */
    private function normalizeOffers(mixed $offers): array
    {
        if (is_string($offers)) {
            $offers = preg_split('/\r\n|\n|\r/', $offers) ?: [];
        }
        if (! is_array($offers)) {
            return [];
        }

        $out = [];
        foreach ($offers as $offer) {
            $line = trim(preg_replace('/^[\s\-\*\d\.\)\(]+/u', '', (string) $offer) ?? '');
            if ($line !== '') {
                $out[] = $line;
            }
        }

        return array_values(array_unique($out));
    }

    private function systemPrompt(string $format = 'one_to_25'): string
    {
        if ($format === 'empire') {
            return implode("\n", [
                'You are a senior Indian business consultant writing practical ERP notes.',
                'Follow the USER PROMPT. Generate a personalized 30-point Business House to Business Empire Vision.',
                'Stay in the same industry as the present business. English only.',
                'Write short, practical cells like a businessman note — not a corporate essay.',
                'Each cell must be 1 short sentence.',
                'For every point invent ONE new named offer, package, system or service in English. Do not only restate the current main product.',
                'Do not repeat the business name or the main product in every column. Mention the business name at most once in a row.',
                'Never write placeholders such as [Business Name], [Business Category], [Main Product / Service] or [Businessman Name].',
                'Never copy the same explanation, innovation, example, action or benefit across points.',
                'Return ONLY valid JSON.',
            ]);
        }

        return implode("\n", [
            'You are a senior Indian business consultant.',
            'Follow the USER PROMPT exactly. It is the only instruction set.',
            'Do not shorten, replace, or ignore the prompt rules.',
            'If the prompt asks for a 2–4 sentence Business Concept, write 2–4 sentences — never a one-line slogan.',
            'Stay in the same industry as the present business. English only.',
            'Personalize every idea from this member business only.',
            'Return ONLY valid JSON.',
        ]);
    }

    private function jsonInstruction(string $format = 'one_to_25'): string
    {
        if ($format === 'empire') {
            return <<<'TXT'
OUTPUT FORMAT — return ONLY valid JSON:
{
  "vision_points": [
    {
      "title": "Vision Point title",
      "explanation": "Simple explanation for this member business",
      "innovation": "Innovation idea for this member business",
      "example": "Easy practical example using this member business",
      "action": "One clear action step",
      "benefit": "Expected business benefit"
    }
  ],
  "journey": {
    "description": "Business-specific empire journey paragraph",
    "steps": ["Business","Products / Services","Customers","Brand","Team","System","Innovation","Technology","Distribution Network","Multiple Locations","Multiple Verticals","Business House","National Brand","International Business","Global Business Empire"]
  },
  "timeline": {
    "three_year": ["point 1","point 2","point 3","point 4","point 5"],
    "five_year": ["point 1","point 2","point 3","point 4","point 5"],
    "ten_year": ["point 1","point 2","point 3","point 4","point 5"]
  },
  "final": {
    "title": "How can this Business House become a Business Empire?",
    "text": "Business-specific final statement"
  }
}
Exactly 30 vision_points. Keep the 30 standard vision titles in order starting with Flagship Business. English only.
Each field = 1 short practical sentence, same meaning-style as this gold sample:
title: New Service Development
explanation: Not only the current service — develop related advisory services.
innovation: Add Business Finance Planning Service.
example: Plan the customer's next finance requirement.
action: Make a list of new advisory services.
benefit: More business opportunity from each customer.
For each point invent a named English offer/system (Finance Advisory Desk, Digital Client Dashboard, Annual Finance Advisory Package).
Do not paste the same product phrase into every cell.
journey.steps = 10 to 13 short English steps specific to this business, like: Finance Arrangement & Advisory → Customer Trust → Strong Brand → Professional Team → System & SOP → Technology + AI → Finance Network → Multiple Locations → New Verticals → Business House → National Brand → Global Business Empire
timeline.three_year / five_year / ten_year = ONE paragraph each, not 5 bullets.
final.text = 2 short paragraphs of meaning, not a slogan.
TXT;
        }

        return <<<'TXT'
OUTPUT FORMAT — return ONLY valid JSON with keys "1" through "25":
{
  "business_ideas": {
    "1": {
      "name": "Distinct business name connected to the present business",
      "concept": "2 to 4 complete English sentences as required by the prompt above.",
      "product_ideas": ["1","2","3","4","5","6","7","8","9","10"]
    },
    "25": {
      "name": "Distinct business name connected to the present business",
      "concept": "2 to 4 complete English sentences as required by the prompt above.",
      "product_ideas": ["1","2","3","4","5","6","7","8","9","10"]
    }
  }
}
Each concept must be 2–4 sentences. Each product_ideas array must have exactly 10 specific, sellable items. All 25 names must be different. English only.
TXT;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeJson(string $content): ?array
    {
        $content = trim($content);
        if ($content === '') {
            return null;
        }
        if (preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $content, $m)) {
            $content = trim($m[1]);
        }
        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        $start = strpos($content, '{');
        $end = strrpos($content, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $decoded = json_decode(substr($content, $start, $end - $start + 1), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }
}
