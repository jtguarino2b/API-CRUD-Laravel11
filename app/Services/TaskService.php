<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
  public function getAllTasks()
  {
    return Task::all()->map(function ($task) {
      return [
        'id' => $task->id,
        'title' => $task->title,
        'description' => $task->description
      ];
    });
  }

  public function getTask(int $id): ?array
  {
    $task = Task::find($id);

    return [
      'id' => $task->id,
      'title' => $task->title,
      'description' => $task->description
    ];
  }

  public function createTask(array $data): array
  {
    $task = Task::create($data);

    return [
      'id' => $task->id,
      'title' => $task->title,
      'description' => $task->description
    ];
  }

  public function updateTask(Task $task, array $data): array
  {
    $task->update($data);

    return [
      'id' => $task->id,
      'title' => $task->title,
      'description' => $task->description
    ];
  }

  public function deleteTask(Task $task): bool
  {
    return $task->delete();
  }
}
