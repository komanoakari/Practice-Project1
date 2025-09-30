<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            ['user_name' => $user->name],
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

    public function show()
    {
        $user = Auth::user();

        $listedProducts = $user->products()
            ->latest()
            ->paginate(8);

        $orders = $user->orders()
            ->where('status', 'paid')
            ->with('product')
            ->orderByDesc('paid_at')
            ->paginate(8);

        return view('profile.mypage', [
            'user' => $user,
            'profile' => $user->profile,
            'listedProducts' => $listedProducts,
            'orders' => $orders,
        ]);
    }
}
