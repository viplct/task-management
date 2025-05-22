<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
            'project_id' => $project->id,
            'title' => 'Sample Task',
            'priority' => 'medium',
            'status' => 'todo',
        ]);

        $response->assertCreated()->assertJsonFragment(['title' => 'Sample Task']);
        $this->assertDatabaseHas('tasks', ['title' => 'Sample Task']);
    }

    public function test_user_can_list_tasks(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Task::factory()->count(3)->for($project)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/tasks');

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_user_can_update_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create();

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'status' => 'in_progress',
        ]);

        $response->assertOk()->assertJsonFragment(['title' => 'Updated Task']);
    }

    public function test_user_can_delete_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/tasks/{$task->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_access_other_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $project = Project::factory()->for($owner)->create();
        $task = Task::factory()->for($project)->create();

        $this->actingAs($intruder, 'sanctum')->getJson("/api/tasks/{$task->id}")
            ->assertForbidden();

        $this->actingAs($intruder, 'sanctum')->putJson("/api/tasks/{$task->id}", ['title' => 'Hack'])
            ->assertForbidden();

        $this->actingAs($intruder, 'sanctum')->deleteJson("/api/tasks/{$task->id}")
            ->assertForbidden();
    }
}

