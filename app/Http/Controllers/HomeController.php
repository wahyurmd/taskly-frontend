<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
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

        return view('dashboard', compact('tasks', 'totalTask', 'totalCompleted', 'totalNotCompleted'));
    }

    public function profile(Request $request)
    {
        $token = session('access_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Please signin first.');
        }

        $user  = session('user');

        return view('profile.profile', compact('user'));
    }
}
