<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->profile;
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $profile = Auth::user()->profile;
        if ($request->file('image')) {
            $data['image'] = $request->file('image')->store('profile_images', 'public');
        }

        $profile->update($data);

        return redirect('/mypage');
    }

    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('mypage', compact('user', 'profile'));
    }
}
