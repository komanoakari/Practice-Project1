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
            return redirect('/');
        }

        return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->onlyInput('email');
    }
}