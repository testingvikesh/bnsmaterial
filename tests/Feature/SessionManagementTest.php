<?php

namespace Tests\Feature;

use App\Models\ManageSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_sessions(): void
    {
        $this->get(route('admin.sessions.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_create_a_session(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)->post(route('admin.sessions.store'), [
            'name' => 'Morning Batch',
            'details' => 'Daily 9 AM to 11 AM',
        ])->assertRedirect(route('admin.sessions.index'));

        $this->assertDatabaseHas('manage_sessions', [
            'name' => 'Morning Batch',
            'details' => 'Daily 9 AM to 11 AM',
        ]);
    }

    public function test_admin_can_update_and_delete_a_session(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create([
            'name' => 'Old Name',
            'details' => 'Old details',
        ]);

        $this->actingAs($admin)->put(route('admin.sessions.update', $session), [
            'name' => 'Updated Name',
            'details' => 'Updated details',
        ])->assertRedirect(route('admin.sessions.index'));

        $this->assertDatabaseHas('manage_sessions', [
            'id' => $session->id,
            'name' => 'Updated Name',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.sessions.destroy', $session))
            ->assertRedirect(route('admin.sessions.index'));

        $this->assertDatabaseMissing('manage_sessions', [
            'id' => $session->id,
        ]);
    }

    public function test_session_details_can_be_longer_than_5000_characters(): void
    {
        $admin = User::factory()->admin()->create();
        $details = str_repeat("Business details line.\n", 300);

        $this->actingAs($admin)->post(route('admin.sessions.store'), [
            'name' => 'Business Vision',
            'details' => $details,
        ])->assertRedirect(route('admin.sessions.index'))
            ->assertSessionHasNoErrors();

        $this->assertGreaterThan(5000, strlen($details));
        $this->assertDatabaseHas('manage_sessions', [
            'name' => 'Business Vision',
        ]);
    }

    public function test_session_list_hides_database_id_and_shows_row_numbers(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create([
            'name' => 'Tagline Session',
            'details' => 'Tagline class details',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.sessions.index'))
            ->assertOk()
            ->assertSee('No.')
            ->assertSee('Tagline Session')
            ->assertDontSee('Search id / name / details')
            ->assertDontSee('>Id</th>', false);

        $this->actingAs($admin)
            ->get(route('admin.sessions.edit', $session))
            ->assertOk()
            ->assertSee('Tagline Session')
            ->assertDontSee('form-label">Id', false);

        $this->actingAs($admin)
            ->get(route('admin.sessions.index', ['q' => (string) $session->id]))
            ->assertOk()
            ->assertDontSee('Tagline Session');

        $this->actingAs($admin)
            ->get(route('admin.sessions.index', ['q' => 'Tagline']))
            ->assertOk()
            ->assertSee('Tagline Session');
    }

    public function test_session_list_is_ordered_by_id_ascending(): void
    {
        $admin = User::factory()->admin()->create();
        $older = ManageSession::factory()->create(['name' => 'Older Session']);
        $newer = ManageSession::factory()->create(['name' => 'Newer Session']);

        $html = $this->actingAs($admin)
            ->get(route('admin.sessions.index'))
            ->assertOk()
            ->getContent();

        $this->assertTrue($older->id < $newer->id);
        $this->assertTrue(
            strpos($html, 'Older Session') < strpos($html, 'Newer Session')
        );
    }
}
