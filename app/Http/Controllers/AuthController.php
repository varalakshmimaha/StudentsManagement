<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email', // or username
            'password' => 'required'
        ]);
        
        // Flexible login logic: Check if email exists, if not, check username?
        // For simplicity, let's assume email for now, or use filter_var to direct to username/email.
        
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if (auth()->attempt([$fieldType => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            // Update last login
            if (auth()->check()) {
                $user = auth()->user();
                $user->last_login_at = now();
                $user->save();
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
