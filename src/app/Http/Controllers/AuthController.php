<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginInfo = $request->only('email', 'password');

        if (Auth::attempt($loginInfo)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withInput()->with('auth_error', 'ログイン情報が登録されていません');
    }
}