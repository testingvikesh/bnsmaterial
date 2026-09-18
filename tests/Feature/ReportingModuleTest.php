<?php

namespace Tests\Feature;

use App\Models\ManageSession;
use App\Models\MaterialEvent;
use App\Models\MaterialFile;
use App\Models\MemberProfile;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportingModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_open_reporting(): void
    {
        $this->get(route('admin.reports.index'))->assertRedirect(route('login'));
    }

    public function test_reporting_lists_member_and_session_view_and_read_counts(): void
    {
        Storage::fake('local');

        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create(['name' => '32 Gun']);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => '32 Gun Details',
            'body' => 'Complete Business Website Draft for {{businessName}}.',
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

        $this->actingAs($admin)
            ->get(route('admin.material.show', $file))
            ->assertOk()
            ->assertSee(route('admin.material.track', $file), false);

        $this->assertSame(1, MaterialEvent::query()->where('type', MaterialEvent::VIEW)->count());

        $this->actingAs($admin)
            ->postJson(route('admin.material.track', $file), ['type' => 'read'])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $pending = User::factory()->create(['name' => 'Bob Pending']);
        MemberProfile::factory()->create([
            'user_id' => $pending->id,
            'business_name' => 'Pending Traders',
        ]);
        $pendingFile = MaterialFile::query()->create([
            'user_id' => $pending->id,
            'manage_session_id' => $session->id,
            'session_prompt_id' => $file->session_prompt_id,
            'file_path' => 'materials/session_'.$session->id.'/prompt_'.$file->session_prompt_id.'/user_'.$pending->id.'.html',
            'generated_at' => now(),
            'duration_ms' => 120,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('Reporting')
            ->assertSee('Pending')
            ->assertSee('Complete')
            ->assertSee('status=pending', false)
            ->assertSee('Select a session or search a member, then click Show.')
            ->assertDontSee('Alice Student')
            ->assertDontSee('Bob Pending')
            ->assertDontSee('Cadworld Infoways');

        $this->actingAs($admin)
            ->get(route('admin.reports.index', ['session_id' => $session->id]))
            ->assertOk()
            ->assertSee('Pending members')
            ->assertSee('Bob Pending')
            ->assertSee('Pending Traders')
            ->assertDontSee('Alice Student')
            ->assertSee('32 Gun')
            ->assertSee('Views / Clicks')
            ->assertSee('Reads')
            ->assertSee(route('admin.material.show', $pendingFile), false)
            ->assertSee('sessionChart', false)
            ->assertSee('Session wise pending and complete');

        $this->actingAs($admin)
            ->get(route('admin.reports.index', ['session_id' => $session->id, 'status' => 'complete']))
            ->assertOk()
            ->assertSee('Complete members')
            ->assertSee('Alice Student')
            ->assertSee('Cadworld Infoways')
            ->assertDontSee('Bob Pending')
            ->assertSee(route('admin.material.show', $file), false);

        $this->actingAs($admin)
            ->get(route('admin.reports.index', ['q' => 'Alice', 'group' => 'session', 'status' => 'complete']))
            ->assertOk()
            ->assertSee('32 Gun')
            ->assertSee('Alice Student');

        $this->assertSame(1, MaterialEvent::query()->where('type', MaterialEvent::READ)->count());
    }
}
