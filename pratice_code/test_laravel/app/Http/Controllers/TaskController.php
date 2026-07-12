<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = new Task();
        $data = $tasks->lists($request)->paginate(10);
        return response()->json($data, 200);
    }
    public function store(Request $request)
    {
        $task = new Task();
        $task->setData($request->all());
        $task->save();
    }
    public function getTaskById($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->setData($request->all());
        $task->save();
        return response()->json($task, 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->status = $request->input('status');
        $task->save();
        return response()->json($task, 200);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json(null, 204);
    }
}
