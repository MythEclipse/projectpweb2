<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    /**
     * Update the user's profile image.
     */
    public function updateImage(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Hash isi file
            $imageHash = md5_file($image->getRealPath());
            $extension = $image->getClientOriginalExtension();
            $imageName = $imageHash . '.' . $extension;

            $storagePath = 'public/profile_images/' . $imageName;

            // Simpan hanya jika belum ada
            if (!Storage::exists($storagePath)) {
                $image->storeAs('public/profile_images', $imageName);
            }

            // Hapus file lama jika beda
            if ($user->avatar && $user->avatar !== '/storage/public/profile_images/' . $imageName) {
                $oldFileName = str_replace('/storage/public/profile_images/', '', $user->avatar);
                Storage::delete('public/profile_images/' . $oldFileName);
            }

            // Simpan path dengan '/public' sesuai kebutuhanmu
            $user->avatar = '/storage/public/profile_images/' . $imageName;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-image-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
