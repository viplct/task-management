<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function creating(Task $task)
    {
        if ($task->order === null) {
            $maxOrder = Task::where('project_id', $task->project_id)->max('order') ?? 0;
            $task->order = $maxOrder + 1;
        }
    }
}
