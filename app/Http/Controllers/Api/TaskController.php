<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
  protected $taskService;

  public function __construct(TaskService $taskService)
  {
    $this->taskService = $taskService;
  }

  public function index(): JsonResponse
  {
    try {
      $tasks = $this->taskService->getAllTasks();
      return response()->json($tasks, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to retrieve tasks'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function show(int $id): JsonResponse
  {
    try {
      $task = $this->taskService->getTask($id);

      if (!$task) {
        return response()->json([
          'message' => $task
        ], Response::HTTP_NOT_FOUND);
      }

      return response()->json($task, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to retrieve task'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function store(TaskRequest $request): JsonResponse
  {
    try {
      $task = $this->taskService->createTask($request->validated());
      return response()->json($task, Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to create task'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function update(TaskRequest $request, Task $task): JsonResponse
  {
    try {
      $updatedTask = $this->taskService->updateTask($task, $request->validated());
      return response()->json($updatedTask, Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to update task'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function destroy(Task $task): JsonResponse
  {
    try {
      $deleted = $this->taskService->deleteTask($task);
      
      if (!$deleted) {
        return response()->json([
          'message' => 'Failed to delete task'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      return response()->json(null, Response::HTTP_NO_CONTENT);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to delete task'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
