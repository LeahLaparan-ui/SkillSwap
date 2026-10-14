<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwapFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_add_teaching_and_learning_skills(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('skills.store'), [
            'name' => 'Guitar',
            'type' => 'teach',
            'proficiency' => 'advanced',
        ])->assertRedirect();

        $this->actingAs($user)->post(route('skills.store'), [
            'name' => 'Python',
            'type' => 'learn',
        ])->assertRedirect();

        $this->assertDatabaseHas('skills', ['name' => 'Guitar', 'slug' => 'guitar']);
        $this->assertDatabaseHas('user_skills', ['user_id' => $user->id, 'type' => 'teach']);
        $this->assertDatabaseHas('user_skills', ['user_id' => $user->id, 'type' => 'learn']);

        $this->assertSame(['Guitar'], $user->fresh()->teachSkills->pluck('name')->all());
        $this->assertSame(['Python'], $user->fresh()->learnSkills->pluck('name')->all());
    }

    public function test_dashboard_loads_for_a_student(): void
    {
        $user = User::factory()->create();
        $teacher = User::factory()->create();

        $wanted = Skill::findOrCreateByName('Spanish');
        $offered = Skill::findOrCreateByName('Guitar');

        $user->userSkills()->create(['skill_id' => $offered->id, 'type' => 'teach']);
        $user->userSkills()->create(['skill_id' => $wanted->id, 'type' => 'learn']);
        $teacher->userSkills()->create(['skill_id' => $wanted->id, 'type' => 'teach']);
        $teacher->userSkills()->create(['skill_id' => $offered->id, 'type' => 'learn']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($teacher->name);
    }

    public function test_student_can_browse_for_matches(): void
    {
        $user = User::factory()->create();
        $teacher = User::factory()->create();

        $skill = Skill::findOrCreateByName('Spanish');
        $teacher->userSkills()->create(['skill_id' => $skill->id, 'type' => 'teach']);

        $this->actingAs($user)
            ->get(route('skills.index', ['skill' => 'spanish', 'type' => 'teach']))
            ->assertOk()
            ->assertSee('Spanish')
            ->assertSee($teacher->name);
    }

    public function test_student_can_send_and_receiver_can_accept_a_swap(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $offered = Skill::findOrCreateByName('Guitar');
        $requested = Skill::findOrCreateByName('Python');

        $sender->userSkills()->create(['skill_id' => $offered->id, 'type' => 'teach']);
        $receiver->userSkills()->create(['skill_id' => $requested->id, 'type' => 'teach']);

        $this->actingAs($sender)->post(route('swaps.store', $receiver), [
            'offered_skill_id' => $offered->id,
            'requested_skill_id' => $requested->id,
            'message' => 'Hi, want to trade?',
        ])->assertRedirect(route('swaps.index'));

        $swap = SwapRequest::first();
        $this->assertNotNull($swap);
        $this->assertSame(SwapRequest::STATUS_PENDING, $swap->status);

        $this->actingAs($sender)
            ->patch(route('swaps.update', $swap), ['action' => 'accept'])
            ->assertForbidden();

        $this->actingAs($receiver)
            ->patch(route('swaps.update', $swap), ['action' => 'accept'])
            ->assertRedirect();

        $this->assertSame(SwapRequest::STATUS_ACCEPTED, $swap->fresh()->status);

        $this->actingAs($receiver)
            ->patch(route('swaps.update', $swap), ['action' => 'complete'])
            ->assertRedirect();

        $this->assertSame(SwapRequest::STATUS_COMPLETED, $swap->fresh()->status);
    }
}
