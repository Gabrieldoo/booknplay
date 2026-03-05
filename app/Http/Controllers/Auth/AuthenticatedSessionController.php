<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // coba login
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

            return back()->withErrors([
                'login' => 'Email atau password salah'
            ])->withInput($request->only('email'));
        }

        // regenerate session
        $request->session()->regenerate();

        $user = Auth::user();

        // redirect sesuai role
        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        return redirect('/userdashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}