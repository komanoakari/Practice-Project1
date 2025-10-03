<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'user_name' => $user->name,
                'postal_code' => '',
                'address' => '',
                'building' => '',
                'image' => null,
            ],
        );
        return view('profile.edit', compact('user','profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $old = optional($user->profile)->image;
            if($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            $data['image'] = $request->file('image')->store('profile_images', 'public');
        }

        $user->profile()->updateOrCreate([], $data);

        $back = $request->input('back', 'mypage');
        if ($back === 'products') {
            return redirect()->route('products.index')->with('status', 'プロフィールを登録しました');
        }

        return redirect()->route('profile.show')->with('status', 'プロフィールを更新しました');
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $tab = $request->query('page', 'sell');

        $orders = $user->orders()
            ->where('status', 'paid')
            ->with('product')
            ->orderByDesc('paid_at')
            ->get();

        $listedProducts = $user->products()
            ->latest()
            ->get();

        return view('profile.mypage', compact('user', 'profile', 'tab', 'orders', 'listedProducts'));
    }
}
