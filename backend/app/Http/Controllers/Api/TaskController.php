<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return TaskResource::collection(Task::latest()->get());
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }

    public function byProject(Project $project)
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks()
            ->orderBy('order')
            ->get();

        return TaskResource::collection($tasks);
    }

    public function reorder(Request $request, Project $project)
    {
        foreach ($request->get('tasks') as $taskData) {
            $task = $project->tasks()->where('id', $taskData['id'])->first();
            if ($task) {
                $task->update(['order' => $taskData['order']]);
            }
        }

        return response()->json(['message' => 'Tasks reordered successfully']);
    }
}
