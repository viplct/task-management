<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/projects', [
            'title' => 'Project Alpha',
            'description' => 'Initial project description',
            'status' => 'active',
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Project Alpha']);

        $this->assertDatabaseHas('projects', ['title' => 'Project Alpha']);
    }

    public function test_user_can_list_their_projects(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(2)->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_user_can_update_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/projects/{$project->id}", [
            'title' => 'Updated Project',
            'status' => 'inactive',
        ]);

        $response->assertOk()->assertJsonFragment(['title' => 'Updated Project']);
    }

    public function test_user_can_delete_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/projects/{$project->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_user_cannot_access_other_users_project(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $project = Project::factory()->for($owner)->create();

        $this->actingAs($intruder, 'sanctum')->getJson("/api/projects/{$project->id}")
            ->assertForbidden();

        $this->actingAs($intruder, 'sanctum')->putJson("/api/projects/{$project->id}", ['title' => 'Hack'])
            ->assertForbidden();

        $this->actingAs($intruder, 'sanctum')->deleteJson("/api/projects/{$project->id}")
            ->assertForbidden();
    }
}

