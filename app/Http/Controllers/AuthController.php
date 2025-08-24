<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AuthController extends Controller
{
    function index() : View {
        return view('login');
    }

    public function login(Request $request)
    {
        $response = ApiClient::post('/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Invalid credentials.');
        }

        $data = $response->json();

        session([
            'access_token'  => $data['data']['access_token'],
            'user'          => $data['data']['user']
        ]);

        return redirect()->route('home')->with('success', 'Login Successful, welcome '.$data['data']['user']['name']);
    }

    function register() : View {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $response = ApiClient::post('/register', [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Registration failed, please try again.');
        }

        $data = $response->json();

        session([
            'access_token' => $data['data']['access_token'],
            'user'         => $data['data']['user'],
        ]);

        return redirect()->route('home')->with('success', 'Registration successful, welcome '.$data['data']['user']['name']);
    }

    public function logout(Request $request)
    {
        $token = session('access_token');

        if ($token) {
            ApiClient::post('/logout', [], $token);
        }

        session()->forget(['access_token', 'user']);

        return redirect()->route('login')->with('success', 'Logout successful, see you!');
    }
}
