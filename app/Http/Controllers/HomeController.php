<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        // Get quote of the day
        $quoteResponse = Http::get('https://zenquotes.io/api/today');
        $quoteData = $quoteResponse->json()[0] ?? null;

        $quote  = $quoteData['q'] ?? 'Stay positive!';
        $author = $quoteData['a'] ?? 'Unknown';
        $today = Carbon::now()->format('l, d F Y');

        return view('dashboard', compact('tasks', 'totalTask', 'totalCompleted', 'totalNotCompleted', 'quote', 'author', 'today'));
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
