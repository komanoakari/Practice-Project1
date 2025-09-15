<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->profile;
        return view('profile.edit', compact('profile'));
    }

    public function update(ProfileRequest $request)
    {
        $profile = Auth::user()->profile;

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('profile_images', 'public');
        }

        $profile->update($data);

        return redirect()->route('profile.show');
    }

    public function show()
    {
        $user = Auth::user();

        $listedProducts = $user->products()
            ->latest()
            ->paginate(8);

        $orders = $user->orders()
            ->with('product')
            ->latest()
            ->paginate(8);

        return view('profile.mypage', [
            'user' => $user,
            'profile' => $user->profile,
            'listedProducts' => $listedProducts,
            'orders' => $orders,
        ]);
    }
}
