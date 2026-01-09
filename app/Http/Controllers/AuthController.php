<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function showRegisterForm(): Response
    {
        return Inertia::render('Auth/Register', []);
    }

    public function register(RegisterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('login');
    }

    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Login', []);
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->only('username', 'password');

        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('ticketsIndex');
        }

        return redirect()->back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }
}
