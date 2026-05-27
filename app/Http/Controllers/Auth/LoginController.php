<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'login' => 'required',

            'password' => 'required|min:6'

        ], [

            'login.required' => 'Vui lòng nhập email hoặc username',

            'password.required' => 'Vui lòng nhập mật khẩu',

            'password.min' => 'Mật khẩu tối thiểu 6 ký tự'
        ]);

        if ($validator->fails()) {

            return response()->json([

                'success' => false,

                'errors' => $validator->errors()

            ], 422);
        }

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

            return response()->json([

                'success' => true,

                'redirect' => '/dashboard'
            ]);
        }

        return response()->json([

            'success' => false,

            'message' => 'Email hoặc username không chính xác'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}