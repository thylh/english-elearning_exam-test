<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([

            'login' => 'required',

            'password' => 'required'
        ]);

        $loginInput = $request->login;

        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'name';

        $credentials = [

            $field => $loginInput,

            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()->with('error', 'Email hoặc username không chính xác');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}