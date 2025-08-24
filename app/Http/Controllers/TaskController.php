<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $token = session('access_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Please signin first.');
        }

        $response = ApiClient::get('/tasks', $token);

        if ($response->failed()) {
            session()->forget(['access_token', 'user']);
            return redirect()->route('login')->with('error', 'Session expired, please signin again.');
        }

        $tasks = $response->json();

        $taskList = $tasks['data'] ?? [];

        $totalTask          = count($taskList);
        $totalCompleted     = collect($taskList)->where('status', 1)->count();
        $totalNotCompleted  = collect($taskList)->where('status', 0)->count();

        return view('task.task', compact('tasks', 'totalTask', 'totalCompleted', 'totalNotCompleted'));
    }

    public function create()
    {
        $token = session('access_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Please signin first.');
        }

        return view('task.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_date'   => 'required|date',
            'status'      => 'required|in:0,1',
        ]);

        $token = session('access_token');
        $user  = session('user');

        $response = ApiClient::post('/tasks', [
            'user_id'     => $user['id'],
            'title'       => $request->title,
            'description' => $request->description,
            'plan_date'   => $request->plan_date,
            'status'      => $request->status,
        ], $token);

        if ($response->failed()) {
            return back()->with('error', 'Failed to add task');
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    public function edit($id)
    {
        $token = session('access_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Please signin first.');
        }

        $response = ApiClient::get("/tasks/{$id}", $token);

        if ($response->failed()) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }

        $task = $response->json()['data'] ?? null;

        return view('task.edit', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_date'   => 'required|date',
            'status'      => 'required|in:0,1',
        ]);

        $token = session('access_token');
        $user  = session('user');

        $response = ApiClient::put("/tasks-update/{$id}", [
            'user_id'     => $user['id'],
            'title'       => $request->title,
            'description' => $request->description,
            'plan_date'   => $request->plan_date,
            'status'      => $request->status,
        ], $token);

        if ($response->failed()) {
            return back()->with('error', 'Failed to update task');
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }
}
