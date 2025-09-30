<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $loginInfo = $request->only('email', 'password');

        if (Auth::attempt($loginInfo)) {
            $request->session()->regenerate();

            $to = $request->input('redirect');
            if ($to) {
                $to = urldecode($to);
                if (str_starts_with($to, '/'))
                    return redirect($to);
            }
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->onlyInput('email');
    }
}