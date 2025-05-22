<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return ProjectResource::collection(
            Auth::user()?->projects()->withCount('tasks')->latest()->get()
        );
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Auth::user()?->projects()->create($request->validated());
        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($request->validated());
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->noContent();
    }
}
