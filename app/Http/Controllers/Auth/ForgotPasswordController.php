<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // CHECK EMAIL
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {

            return back()->with('error', 'Email không tồn tại');
        }

        // CREATE TOKEN
        $token = Str::random(64);

        DB::table('password_resets')->insert([

            'email' => $request->email,

            'token' => $token,

            'created_at' => now()
        ]);

        return redirect('/reset-password/' . $token);
    }

    // SHOW RESET FORM
    public function showResetForm(string $token)
    {
        return view('auth.reset-password', compact('token'));
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([

            'password' => 'required|min:6|confirmed'
        ]);

        $reset = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$reset) {

            return back()->with('error', 'Token không hợp lệ');
        }

        User::where('email', $reset->email)
            ->update([

                'password' => Hash::make($request->password)
            ]);

        DB::table('password_resets')
            ->where('email', $reset->email)
            ->delete();

        return redirect('/login')
            ->with('success', 'Đổi mật khẩu thành công');
    }
}