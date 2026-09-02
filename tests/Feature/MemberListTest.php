<?php

namespace Tests\Feature;

use App\Models\ManageSession;
use App\Models\MemberProfile;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberListTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_members(): void
    {
        $this->get(route('admin.members.index'))->assertRedirect(route('login'));
    }

    public function test_member_list_shows_only_name_and_mobile_from_joined_tables(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create([
            'name' => 'Ravi Patel',
            'phone' => '9876543210',
        ]);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'whatsapp' => '9000000000',
        ]);
        User::factory()->create([
            'name' => 'No Profile User',
            'phone' => '9111111111',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.members.index'))
            ->assertOk()
            ->assertSee('Ravi Patel')
            ->assertSee('9876543210')
            ->assertSee('Type')
            ->assertSee('Member')
            ->assertSee('Action')
            ->assertSee('View')
            ->assertSee(route('admin.members.show', $member))
            ->assertDontSee('No Profile User')
            ->assertDontSee('Add Member')
            ->assertDontSee('memberBusinessModal');
    }

    public function test_member_list_shows_member_or_guest_type(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create(['name' => 'Ravi Patel']);
        MemberProfile::factory()->create(['user_id' => $member->id]);
        $guest = User::factory()->guestPerson()->create(['name' => 'Guest Visitor']);
        MemberProfile::factory()->create(['user_id' => $guest->id]);

        $this->actingAs($admin)
            ->get(route('admin.members.index'))
            ->assertOk()
            ->assertSee('Type')
            ->assertSee('Ravi Patel')
            ->assertSee('Guest Visitor')
            ->assertSee('Guest')
            ->assertSee('badge-staff', false)
            ->assertSee('badge-active', false);
    }

    public function test_view_opens_member_page_with_session_boxes(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create([
            'name' => 'Alice Student',
            'phone' => '6666666666',
        ]);
        MemberProfile::factory()->create([
            'user_id' => $member->id,
            'business_name' => 'Cadworld Infoways',
            'business_category' => 'Trading',
            'business_description' => 'We are distributors of various electrical brands.',
            'main_products_services' => 'Wires and cables and switchgears',
        ]);
        $session = ManageSession::factory()->create([
            'name' => 'Orientation Session',
            'details' => 'Welcome session covering course overview.',
        ]);
        SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'Orientation outline prompt',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.members.show', $member))
            ->assertOk()
            ->assertSee('Alice Student')
            ->assertSee('Cadworld Infoways')
            ->assertSee('Trading')
            ->assertSee('Wires and cables and switchgears')
            ->assertSee('Orientation Session')
            ->assertSee('Session 1')
            ->assertSee('Orientation outline prompt');
    }

    public function test_users_without_member_profile_cannot_open_member_page(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.members.show', $user))
            ->assertNotFound();
    }
}
