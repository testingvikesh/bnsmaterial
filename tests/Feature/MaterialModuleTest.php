<?php

namespace Tests\Feature;

use App\Models\ManageSession;
use App\Models\MaterialFile;
use App\Models\MemberProfile;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaterialModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_open_material_module(): void
    {
        $this->get(route('admin.material.index'))->assertRedirect(route('login'));
    }

    public function test_material_page_shows_session_prompt_and_member_list(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Orientation Session']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Orientation outline prompt',
            'body' => 'Create a worksheet for {{memberName}} at {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student', 'phone' => '6666666666']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('Prompt')
            ->assertSee('Session Prompt')
            ->assertSee('Orientation outline prompt')
            ->assertSee('Create a worksheet for {{memberName}} at {{businessName}}.')
            ->assertSee('data-bs-target="#materialPromptModal"', false)
            ->assertSee('Member List')
            ->assertSee('Type')
            ->assertSee('Alice Student')
            ->assertSee('Cadworld Infoways')
            ->assertSee('Generate')
            ->assertSee('Generate selected')
            ->assertSee('Not Generate')
            ->assertSee('name="user_ids[]"', false)
            ->assertSee('Time')
            ->assertDontSee('>Download</a>', false)
            ->assertDontSee('material-download-link', false);
    }

    public function test_material_page_hides_prompt_and_members_until_session_search(): void
    {
        $admin = User::factory()->admin()->create();
        $first = ManageSession::factory()->create(['name' => 'One To 25 Business']);
        $second = ManageSession::factory()->create(['name' => 'Business Vision']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $first->id,
            'title' => 'One to 25 Business',
            'body' => 'Create ideas for {{memberName}}.',
        ]);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $second->id,
            'title' => 'Empire prompt',
            'body' => 'Create a vision for {{businessName}}.',
        ]);

        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.material.index'))
            ->assertOk()
            ->assertSee('Select session')
            ->assertSee('value="'.$first->id.'"', false)
            ->assertSee('One To 25 Business')
            ->assertSee('Select a session and click Search to load the prompt and member list.')
            ->assertDontSee('Ready prompt', false)
            ->assertDontSee('Create ideas for {{memberName}}.')
            ->assertDontSee('Alice Student')
            ->assertDontSee('Cadworld Infoways');

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => 999]))
            ->assertOk()
            ->assertSee('Select session')
            ->assertDontSee('Ready prompt', false)
            ->assertDontSee('Create ideas for {{memberName}}.')
            ->assertDontSee('Alice Student');

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $first->id]))
            ->assertOk()
            ->assertSee('Prompt')
            ->assertSee('Ready prompt')
            ->assertSee('data-bs-target="#materialPromptModal"', false)
            ->assertSee('Member List')
            ->assertSee('One To 25 Business')
            ->assertSee('One to 25 Business')
            ->assertSee('Create ideas for {{memberName}}.')
            ->assertSee('Alice Student')
            ->assertSee('Cadworld Infoways')
            ->assertSee('Generate');
    }

    public function test_material_page_filters_generated_and_not_generated_members(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Orientation Session']);
        $prompt = SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Orientation outline prompt',
            'body' => 'Create a worksheet for {{memberName}}.',
        ]);

        $alice = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $alice->id,
            'business_name' => 'Cadworld Infoways',
        ]);
        $bob = User::factory()->create(['name' => 'Bob Merchant']);
        MemberProfile::factory()->create([
            'user_id' => $bob->id,
            'business_name' => 'Bob Traders',
        ]);

        MaterialFile::query()->create([
            'user_id' => $alice->id,
            'manage_session_id' => $session->id,
            'session_prompt_id' => $prompt->id,
            'file_path' => 'materials/session_'.$session->id.'/prompt_'.$prompt->id.'/user_'.$alice->id.'.html',
            'generated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('Not Generate')
            ->assertSee('Alice Student')
            ->assertSee('Bob Merchant')
            ->assertDontSee('material-download-link', false);

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $session->id, 'status' => 'generated']))
            ->assertOk()
            ->assertSee('Alice Student')
            ->assertDontSee('Bob Merchant');

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $session->id, 'status' => 'pending']))
            ->assertOk()
            ->assertDontSee('Alice Student')
            ->assertSee('Bob Merchant');
    }

    public function test_generate_stores_html_material_file(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Orientation Session']);
        $prompt = SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Orientation outline prompt',
            'body' => 'Create a worksheet for {{memberName}} at {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
            'business_category' => 'Trading',
            'main_products_services' => 'Wires and cables',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect(route('admin.material.index', ['session_id' => $session->id]));

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $this->assertNotNull($file->duration_ms);
        $this->assertGreaterThan(0, $file->duration_ms);
        Storage::disk('local')->assertExists($file->file_path);

        $html = Storage::disk('local')->get($file->file_path);
        $this->assertStringContainsString('Alice Student', $html);
        $this->assertStringContainsString('Cadworld Infoways', $html);
        $this->assertStringContainsString('Trading', $html);
        $this->assertStringNotContainsString('Session Prompt', $html);
        $this->assertStringContainsString('<!DOCTYPE html>', $html);

        $this->actingAs($admin)
            ->get(route('admin.material.show', $file))
            ->assertOk()
            ->assertSee('Alice Student', false)
            ->assertSee('Cadworld Infoways', false);
    }

    public function test_json_generate_returns_member_duration(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Orientation Session']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Orientation outline prompt',
            'body' => 'Create a worksheet for {{memberName}} at {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('user_id', $member->id)
            ->assertJsonPath('name', 'Alice Student')
            ->assertJsonPath('cached', false)
            ->assertJsonStructure(['duration_ms', 'duration_label', 'show_url', 'download_url', 'cached', 'source']);

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $this->assertGreaterThan(0, (int) $file->duration_ms);
    }

    public function test_repeat_generate_reuses_existing_html_and_json(): void
    {
        Storage::fake('local');
        config([
            'services.openai.enabled' => true,
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
            'services.openai.base' => 'https://api.openai.com/v1',
        ]);

        $ideas = [];
        for ($n = 1; $n <= 25; $n++) {
            $ideas[(string) $n] = [
                'name' => 'API Idea '.$n,
                'concept' => 'Concept '.$n,
                'product_ideas' => array_map(fn ($i) => 'Offer '.$n.'.'.$i, range(1, 10)),
            ];
        }

        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode(['business_ideas' => $ideas]),
                    ],
                ]],
            ], 200),
        ]);

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'One To 25 Business']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'One to 25 Business',
            'body' => 'Create ideas for {{memberName}} at {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertOk()
            ->assertJsonPath('cached', false)
            ->assertJsonPath('source', 'openai');

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $generatedAt = $file->generated_at?->toJSON();
        $durationMs = (int) $file->duration_ms;
        $html = Storage::disk('local')->get($file->file_path);
        $json = Storage::disk('local')->get($file->jsonPath());

        Http::assertSentCount(1);

        $this->actingAs($admin)
            ->postJson(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('cached', true)
            ->assertJsonPath('source', 'cache')
            ->assertJsonPath('file_id', $file->id);

        Http::assertSentCount(1);
        $this->assertSame(1, MaterialFile::query()->count());

        $file->refresh();
        $this->assertSame($generatedAt, $file->generated_at?->toJSON());
        $this->assertSame($durationMs, (int) $file->duration_ms);
        $this->assertSame($html, Storage::disk('local')->get($file->file_path));
        $this->assertSame($json, Storage::disk('local')->get($file->jsonPath()));
    }

    public function test_force_generate_bypasses_cache_and_calls_openai(): void
    {
        Storage::fake('local');
        config([
            'services.openai.enabled' => true,
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
            'services.openai.base' => 'https://api.openai.com/v1',
        ]);

        $ideas = [];
        for ($n = 1; $n <= 25; $n++) {
            $ideas[(string) $n] = [
                'name' => 'Forced Idea '.$n,
                'concept' => 'Concept '.$n,
                'product_ideas' => array_map(fn ($i) => 'Offer '.$n.'.'.$i, range(1, 10)),
            ];
        }

        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode(['business_ideas' => $ideas]),
                    ],
                ]],
            ], 200),
        ]);

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'One To 25 Business']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'One to 25 Business',
            'body' => 'Create ideas for {{memberName}} at {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertOk()
            ->assertJsonPath('cached', false);

        Http::assertSentCount(1);
        $firstGeneratedAt = MaterialFile::query()->first()?->generated_at?->toJSON();

        $this->travel(2)->seconds();

        $this->actingAs($admin)
            ->postJson(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
                'force' => true,
            ])
            ->assertOk()
            ->assertJsonPath('cached', false)
            ->assertJsonPath('source', 'openai');

        Http::assertSentCount(2);
        $this->assertSame(1, MaterialFile::query()->count());
        $this->assertNotSame($firstGeneratedAt, MaterialFile::query()->first()?->generated_at?->toJSON());
        $this->assertStringContainsString('Forced Idea 1', Storage::disk('local')->get(MaterialFile::query()->first()->file_path));
    }

    public function test_generate_uses_openai_and_member_business_in_prompt(): void
    {
        Storage::fake('local');
        config([
            'services.openai.enabled' => true,
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
            'services.openai.base' => 'https://api.openai.com/v1',
        ]);

        $ideas = [];
        for ($n = 1; $n <= 25; $n++) {
            $ideas[(string) $n] = [
                'name' => 'API Idea '.$n,
                'concept' => 'Concept '.$n.' built from Cadworld Infoways trading of wires and cables.',
                'product_ideas' => array_map(fn ($i) => 'Offer '.$n.'.'.$i, range(1, 10)),
            ];
        }

        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode(['business_ideas' => $ideas]),
                    ],
                ]],
            ], 200),
        ]);

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'One To 25 Business']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'One to 25 Business',
            'body' => "PRESENT BUSINESS:\nMember: {{memberName}}\nBusiness name: {{businessName}}\nCategory: {{businessCategory}}\nMain Product: {{mainProduct}}",
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
            'business_category' => 'Trading',
            'main_products_services' => 'Wires and cables',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect();

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $html = Storage::disk('local')->get($file->file_path);
        $this->assertStringContainsString('API Idea 1', $html);
        $this->assertStringContainsString('API Idea 25', $html);
        $this->assertStringContainsString('Alice Student', $html);
        $this->assertStringContainsString('Cadworld Infoways', $html);

        Http::assertSent(function ($request) {
            $body = $request->data();
            $userMessage = $body['messages'][1]['content'] ?? '';

            return str_contains($userMessage, 'Alice Student')
                && str_contains($userMessage, 'Cadworld Infoways')
                && str_contains($userMessage, 'Trading')
                && str_contains($userMessage, 'Wires and cables');
        });
    }

    public function test_session_two_generates_empire_vision_html(): void
    {
        Storage::fake('local');
        config([
            'services.openai.enabled' => true,
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
            'services.openai.base' => 'https://api.openai.com/v1',
        ]);

        $points = [];
        for ($n = 1; $n <= 30; $n++) {
            $points[] = [
                'title' => 'Vision Point '.$n,
                'explanation' => 'Explanation '.$n.' for Cadworld Infoways',
                'innovation' => 'Innovation '.$n,
                'example' => 'Example '.$n,
                'action' => 'Action '.$n,
                'benefit' => 'Benefit '.$n,
            ];
        }

        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'vision_points' => $points,
                            'journey' => [
                                'description' => 'Cadworld Infoways empire journey.',
                                'steps' => ['Business', 'Brand', 'Business House'],
                            ],
                            'timeline' => [
                                'three_year' => ['Grow distribution'],
                                'five_year' => ['Expand cities'],
                                'ten_year' => ['National brand'],
                            ],
                            'final' => [
                                'title' => 'How can this Business House become a Business Empire?',
                                'text' => 'Cadworld Infoways can become a Business Empire.',
                            ],
                        ]),
                    ],
                ]],
            ], 200),
        ]);

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Business House to Business Empire']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => '30-Point Business Empire Vision',
            'body' => "Generate 30 Vision Points for {{businessName}} in {{businessCategory}} around {{mainProduct}}.",
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
            'business_category' => 'Trading',
            'business_description' => 'Electrical Brand Distributor',
            'main_products_services' => 'Wires, Cables, Switchgears',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect();

        $file = MaterialFile::query()->first();
        $html = Storage::disk('local')->get($file->file_path);
        $this->assertStringNotContainsString('Session Prompt', $html);
        $this->assertStringNotContainsString('Generate 30 Vision Points for Cadworld Infoways in Trading around Wires, Cables, Switchgears.', $html);
        $this->assertStringContainsString('Cadworld Infoways', $html);
        $this->assertStringContainsString('Vision Point 1', $html);
        $this->assertStringContainsString('Vision Point 30', $html);
        $this->assertStringContainsString('Business House → Business Empire Vision', $html);
        $this->assertStringContainsString('language-buttons', $html);
        $this->assertStringContainsString('languagePacks', $html);
        $this->assertStringContainsString('"gu"', $html);
        $this->assertStringContainsString('"hi"', $html);
        $this->assertStringContainsString('"mr"', $html);
        $this->assertStringNotContainsString('ERP translation master', $html);

        $this->actingAs($admin)
            ->get(route('admin.material.show', $file))
            ->assertOk()
            ->assertDontSee('Session Prompt', false)
            ->assertDontSee('Generate 30 Vision Points for Cadworld Infoways', false)
            ->assertSee('Cadworld Infoways', false);
    }

    public function test_reverse_management_session_generates_seven_market_plan(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Reverse Management Session']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Reverse Management',
            'body' => 'Create a reverse management plan for {{businessName}} in {{businessCategory}} around {{mainProduct}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'ABC Jewellery',
            'business_category' => 'Jewellery',
            'business_description' => 'Gold & Diamond Jewellery Retail Business',
            'main_products_services' => 'Gold & Diamond Jewellery',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect();

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $html = Storage::disk('local')->get($file->file_path);
        $this->assertStringContainsString('Reverse Management', $html);
        $this->assertStringContainsString('ABC Jewellery', $html);
        $this->assertStringContainsString('Gold Upgrade Offer', $html);
        $this->assertStringContainsString('Existing Customer', $html);
        $this->assertStringContainsString('Virtual Jewellery Shopping', $html);
        $this->assertStringContainsString('My Desired Annual Turnover', $html);
        $this->assertStringContainsString('Member Price', $html);
        $this->assertStringContainsString('Generate Plan', $html);
        $this->assertStringContainsString('VIEW 01 — Jewellery Business', $html);
        $this->assertStringContainsString('XYZ Properties', $html);
        $this->assertStringContainsString('ABC Manufacturing', $html);
        $this->assertStringContainsString('Fast Lamination Centre', $html);
        $this->assertStringContainsString('Shree Mithai House', $html);
        $this->assertStringContainsString('Luxury Property', $html);
        $this->assertStringContainsString('Festival Family Sweet Box', $html);
        $this->assertStringContainsString('Revenue Gap', $html);
        $this->assertStringContainsString('Target Achievement', $html);
        $this->assertStringContainsString('language-buttons', $html);
        $this->assertStringContainsString('ENGLISH', $html);
        $this->assertStringContainsString('ગુજરાતી', $html);
        $this->assertStringContainsString('हिन्दी', $html);
        $this->assertStringContainsString('मराठी', $html);
        $this->assertStringContainsString('languagePacks', $html);
        $this->assertStringContainsString('#0a1d37', $html);
        $this->assertStringContainsString('#ff6b00', $html);
        $this->assertStringContainsString('point-list', $html);
        $this->assertStringContainsString('member-intro', $html);
        $this->assertStringContainsString('function toPoints', $html);
        $this->assertStringNotContainsString('Session Prompt', $html);
        $this->assertStringNotContainsString('Create a reverse management plan for ABC Jewellery', $html);

        $this->actingAs($admin)
            ->get(route('admin.material.show', $file))
            ->assertOk()
            ->assertSee('ABC Jewellery', false)
            ->assertSee('Gold Upgrade Offer', false)
            ->assertDontSee('Session Prompt', false);
    }

    public function test_reverse_session_shows_dashboard_button_and_command_center(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $empire = ManageSession::factory()->create(['name' => 'Business Vision']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $empire->id,
            'title' => 'Empire',
            'body' => 'Create a vision for {{businessName}}.',
        ]);
        $session = ManageSession::factory()->create(['name' => 'Reverse Management Session']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Reverse Management',
            'body' => 'Create a reverse management plan for {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'ABC Jewellery',
            'business_category' => 'Jewellery',
            'business_description' => 'Gold & Diamond Jewellery Retail Business',
            'main_products_services' => 'Gold & Diamond Jewellery',
            'business_location' => 'Mumbai',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $empire->id]))
            ->assertOk()
            ->assertDontSee('material-dashboard-btn', false);

        $this->actingAs($admin)
            ->get(route('admin.material.index', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('material-dashboard-btn', false)
            ->assertSee('Dashboard');

        $this->actingAs($admin)
            ->get(route('admin.material.dashboard', ['session_id' => $empire->id]))
            ->assertRedirect(route('admin.material.index', ['session_id' => $empire->id]));

        $this->actingAs($admin)
            ->get(route('admin.material.dashboard', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('Global Reverse Management Command Center')
            ->assertSee('Alice Student')
            ->assertSee('ABC Jewellery')
            ->assertSee('7 Market Analysis')
            ->assertSee('Member-wise Reverse Management');

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->get(route('admin.material.dashboard', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('ABC Jewellery')
            ->assertSee('₹90 L')
            ->assertSee('90%')
            ->assertSee('Existing Customer')
            ->assertSee('View Member');
    }

    public function test_reverse_dashboard_member_table_shows_five_then_more_members(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Reverse Management Session']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Reverse Management',
            'body' => 'Create a reverse management plan for {{businessName}}.',
        ]);

        foreach (range(1, 6) as $i) {
            $user = User::factory()->create(['name' => 'Member '.$i]);
            MemberProfile::factory()->create([
                'user_id' => $user->id,
                'business_name' => 'Business '.$i,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.material.dashboard', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('Member 1')
            ->assertSee('Member 6')
            ->assertSee('rmd-more-row', false)
            ->assertSee('More Members (1)', false)
            ->assertSee('id="rmdMoreMembers"', false);
    }

    public function test_tagline_masterclass_session_generates_member_and_example_taglines(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'BNS Tagline Masterclass']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Business Tagline',
            'body' => 'Create 15 taglines for {{businessName}} in {{businessCategory}} around {{mainProduct}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'ABC Jewellery',
            'business_category' => 'Jewellery',
            'business_description' => 'Gold & Diamond Jewellery Retail Business',
            'main_products_services' => 'Gold & Diamond Jewellery',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect();

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $html = Storage::disk('local')->get($file->file_path);
        $this->assertStringContainsString('BNS Tagline Masterclass', $html);
        $this->assertStringContainsString('ABC Jewellery', $html);
        $this->assertStringContainsString('Jewellery That Tells Your Story', $html);
        $this->assertStringContainsString('Building Better Tomorrows', $html);
        $this->assertStringContainsString('Education That Creates Leaders', $html);
        $this->assertStringContainsString('Technology That Moves Business', $html);
        $this->assertStringContainsString('Sweetness That Brings People Together', $html);
        $this->assertStringContainsString('#fff3e0', $html);
        $this->assertStringContainsString('#e8f4ff', $html);
        $this->assertStringContainsString('#eef2f7', $html);
        $this->assertStringContainsString('#ffe8f0', $html);
        $this->assertStringContainsString('#fff6d8', $html);
        $this->assertStringContainsString('language-buttons', $html);
        $this->assertStringContainsString('ગુજરાતી', $html);
        $this->assertStringContainsString('point-list', $html);
        $this->assertStringContainsString('member-intro', $html);
        $this->assertStringContainsString('<li>Gold &amp; Diamond Jewellery Retail Business</li>', $html);
        $this->assertStringContainsString('<li>Gold &amp; Diamond Jewellery</li>', $html);
        $this->assertStringContainsString('function toPoints', $html);
        $this->assertStringNotContainsString('Create 15 taglines for ABC Jewellery', $html);
    }

    public function test_empire_view_hides_session_prompt_from_generated_html(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Business House to Business Empire']);
        $prompt = SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'My Business Vision',
            'body' => 'Create a vision note for {{businessName}} using {{mainProduct}}.',
        ]);
        $member = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
            'main_products_services' => 'Wires and cables',
        ]);

        $path = 'materials/session_'.$session->id.'/prompt_'.$prompt->id.'/user_'.$member->id.'.html';
        Storage::disk('local')->put($path, '<html><body>Old empire html without prompt</body></html>');
        Storage::disk('local')->put(preg_replace('/\.html$/', '.json', $path), json_encode([
            'format' => 'empire',
            'source' => 'local',
            'snapshot' => [
                'memberName' => 'Alice Student',
                'businessName' => 'Cadworld Infoways',
                'businessCategory' => 'Trading',
                'businessIntroduction' => 'Distributor',
                'mainProduct' => 'Wires and cables',
                'businessLocation' => 'Mumbai',
                'businessAddress' => 'Mumbai',
                'whatsapp' => '9999999999',
                'sessionName' => $session->name,
                'sessionDetails' => 'Business Vision',
                'city' => 'Mumbai',
            ],
            'visions' => [],
            'languages' => ['en' => ['ui' => [], 'visions' => [], 'journey' => [], 'timeline' => [], 'final' => []]],
        ], JSON_UNESCAPED_UNICODE));

        $file = MaterialFile::query()->create([
            'user_id' => $member->id,
            'manage_session_id' => $session->id,
            'session_prompt_id' => $prompt->id,
            'file_path' => $path,
            'generated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.material.show', $file))
            ->assertOk()
            ->assertDontSee('Session Prompt', false)
            ->assertDontSee('My Business Vision', false)
            ->assertDontSee('Create a vision note for Cadworld Infoways using Wires and cables.', false)
            ->assertSee('Cadworld Infoways', false);
    }

    public function test_bulk_generate_creates_material_for_selected_members(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Orientation Session']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Orientation outline prompt',
            'body' => 'Create a worksheet for {{memberName}} at {{businessName}}.',
        ]);

        $alice = User::factory()->create(['name' => 'Alice Student']);
        MemberProfile::factory()->create([
            'user_id' => $alice->id,
            'business_name' => 'Cadworld Infoways',
        ]);
        $bob = User::factory()->create(['name' => 'Bob Merchant']);
        MemberProfile::factory()->create([
            'user_id' => $bob->id,
            'business_name' => 'Bob Traders',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_ids' => [$alice->id, $bob->id],
            ])
            ->assertRedirect(route('admin.material.index', ['session_id' => $session->id]))
            ->assertSessionHas('success');

        $this->assertSame(2, MaterialFile::query()->count());
        $this->assertTrue(MaterialFile::query()->where('user_id', $alice->id)->exists());
        $this->assertTrue(MaterialFile::query()->where('user_id', $bob->id)->exists());

        $aliceHtml = Storage::disk('local')->get(MaterialFile::query()->where('user_id', $alice->id)->first()->file_path);
        $bobHtml = Storage::disk('local')->get(MaterialFile::query()->where('user_id', $bob->id)->first()->file_path);
        $this->assertStringContainsString('Alice Student', $aliceHtml);
        $this->assertStringContainsString('Cadworld Infoways', $aliceHtml);
        $this->assertStringContainsString('Bob Merchant', $bobHtml);
        $this->assertStringContainsString('Bob Traders', $bobHtml);
    }

    public function test_bulk_generate_requires_selected_members(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create();
        SessionPrompt::factory()->active()->create(['manage_session_id' => $session->id]);

        $this->actingAs($admin)
            ->from(route('admin.material.index', ['session_id' => $session->id]))
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
            ])
            ->assertRedirect(route('admin.material.index', ['session_id' => $session->id]))
            ->assertSessionHasErrors(['user_id', 'user_ids']);
    }

    public function test_hindi_empire_pack_uses_devanagari_not_gujarati(): void
    {
        Storage::fake('local');
        config([
            'services.openai.enabled' => true,
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
            'services.openai.base' => 'https://api.openai.com/v1',
        ]);

        $englishPoints = $this->empirePoints('Build a trusted brand in Mumbai.');
        $gujaratiPoints = $this->empirePoints('Artiben Annapurna Hub '.json_decode('"\u0aa8\u0ac7"').' Mumbai trusted brand.');
        $hindiPoints = $this->empirePoints('Artiben Annapurna Hub '.json_decode('"\u0915\u094b"').' Mumbai '.json_decode('"\u0915\u0940"').' trusted meal brand '.json_decode('"\u092c\u0928\u093e\u0928\u093e"').'.');
        $marathiPoints = $this->empirePoints('Cadworld Infoways '.json_decode('"\u0932\u093e"').' Mumbai trusted brand.');
        $this->assertTrue(\App\Support\MaterialEmpireVision::languagePackScriptIsValid('hi', ['visions' => $hindiPoints]));

        $markers = [];
        Http::fake(function ($request) use ($englishPoints, $gujaratiPoints, $hindiPoints, $marathiPoints, &$markers) {
            $hay = $request->body();
            $points = $englishPoints;
            $marker = 'en';
            if (str_contains($hay, 'Hindi (Devanagari)')) {
                $points = $hindiPoints;
                $marker = 'hi';
            } elseif (str_contains($hay, 'Marathi (Devanagari)')) {
                $points = $marathiPoints;
                $marker = 'mr';
            } elseif (str_contains($hay, 'Rewrite this Business Empire Vision Note') && str_contains($hay, 'Gujarati')) {
                $points = $gujaratiPoints;
                $marker = 'gu';
            }
            $markers[] = $marker;

            return Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'vision_points' => $points,
                            'journey' => ['description' => 'Journey', 'steps' => ['Business', 'Brand']],
                            'timeline' => ['three_year' => ['Grow'], 'five_year' => ['Expand'], 'ten_year' => ['National']],
                            'final' => ['title' => 'Empire?', 'text' => 'Grow with systems.'],
                        ], JSON_UNESCAPED_UNICODE),
                    ],
                ]],
            ], 200);
        });

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => 'Business House to Business Empire']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => '30-Point Business Empire Vision',
            'body' => 'Generate 30 Vision Points for {{businessName}}.',
        ]);
        $member = User::factory()->create(['name' => 'Arti Bavishi']);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Artiben’s Annapurna Hub',
            'business_category' => 'Service Business',
            'main_products_services' => 'Tiffin Service',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.material.generate'), [
                'manage_session_id' => $session->id,
                'user_id' => $member->id,
            ])
            ->assertRedirect();

        $file = MaterialFile::query()->first();
        $this->assertNotNull($file);
        $this->assertContains('hi', $markers, 'OpenAI calls: '.json_encode($markers));
        $payload = json_decode((string) Storage::disk('local')->get(preg_replace('/\.html$/', '.json', $file->file_path)), true);
        $hindi = (string) data_get($payload, 'languages.hi.visions.0.explanation');

        $this->assertStringContainsString(json_decode('"\u092c\u0928\u093e\u0928\u093e"'), $hindi);
        $this->assertStringNotContainsString(json_decode('"\u0aac\u0aa8\u0abe\u0ab5\u0ab5\u0ac0"'), $hindi);
        $this->assertTrue(\App\Support\MaterialEmpireVision::languagePackScriptIsValid('hi', $payload['languages']['hi'] ?? []));
    }

    /**
     * @return list<array<string, string>>
     */
    private function empirePoints(string $sentence): array
    {
        $points = [];
        for ($n = 1; $n <= 30; $n++) {
            $points[] = [
                'title' => 'Vision Point '.$n,
                'explanation' => $sentence.' Point '.$n,
                'innovation' => $sentence.' Offer '.$n,
                'example' => $sentence.' Example '.$n,
                'action' => $sentence.' Action '.$n,
                'benefit' => $sentence.' Benefit '.$n,
            ];
        }

        return $points;
    }
}
