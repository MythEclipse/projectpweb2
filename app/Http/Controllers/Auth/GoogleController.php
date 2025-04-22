<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $avatar = $googleUser->getAvatar() ?? 'https://ui-avatars.com/api/?name=' . urlencode($googleUser->getName());

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Only update avatar if user doesn't have one
            $updateData = [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(24)),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now()
            ];
            if (empty($user->avatar)) {
                $updateData['avatar'] = $avatar;
            }
            $user->update($updateData);
        } else {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(24)),
                'google_id' => $googleUser->getId(),
                'avatar' => $avatar,
                'email_verified_at' => now()
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
