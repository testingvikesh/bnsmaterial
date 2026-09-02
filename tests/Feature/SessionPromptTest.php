<?php

namespace Tests\Feature;

use App\Models\ManageSession;
use App\Models\SessionPrompt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionPromptTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_prompts(): void
    {
        $this->get(route('admin.prompts.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_create_a_session_prompt(): void
    {
        $staff = User::factory()->create();
        $session = ManageSession::factory()->create(['name' => 'Workshop']);

        $this->actingAs($staff)->post(route('admin.prompts.store'), [
            'manage_session_id' => $session->id,
            'title' => 'Generate outline',
            'body' => 'Write a 10-point session outline.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.prompts.index', ['session_id' => $session->id]));

        $this->assertDatabaseHas('session_prompts', [
            'manage_session_id' => $session->id,
            'title' => 'Generate outline',
            'is_active' => 1,
        ]);
    }

    public function test_activating_a_prompt_turns_off_other_prompts_in_the_same_session(): void
    {
        $admin = User::factory()->admin()->create();
        $session = ManageSession::factory()->create();
        $first = SessionPrompt::factory()->active()->create([
            'manage_session_id' => $session->id,
            'title' => 'First prompt',
        ]);
        $second = SessionPrompt::factory()->create([
            'manage_session_id' => $session->id,
            'title' => 'Second prompt',
        ]);

        $this->actingAs($admin)->put(route('admin.prompts.update', $second), [
            'manage_session_id' => $session->id,
            'title' => 'Second prompt',
            'body' => $second->body,
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertFalse($first->fresh()->is_active);
        $this->assertTrue($second->fresh()->is_active);
    }
}
