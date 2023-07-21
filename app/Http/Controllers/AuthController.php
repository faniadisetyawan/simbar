<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function logout()
    {
        return view('auth.logout');
    }

    public function handleLogin(Request $request) 
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }

        return back()->withInput();
    }

    public function handleLogout() 
    {
        Auth::logout();

        return redirect('/auth/logout');
    }
}
