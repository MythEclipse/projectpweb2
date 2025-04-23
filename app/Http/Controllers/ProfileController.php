<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\User;

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

            // Buat nama unik dari isi file
            $imageHash = md5_file($image->getRealPath());
            $extension = $image->getClientOriginalExtension();
            $imageName = $imageHash . '.' . $extension;

            $storagePath = 'profile_images/' . $imageName;

            // Upload jika belum ada
            if (!Storage::exists($storagePath)) {
                $image->storeAs('profile_images', $imageName);
            }

            // Hapus file lama kalau bukan URL dan berbeda
            if ($user->avatar && !Str::startsWith($user->avatar, ['http://', 'https://'])) {
                $oldFile = basename($user->avatar);
                if ($oldFile !== $imageName && Storage::exists('profile_images/' . $oldFile)) {
                    Storage::delete('profile_images/' . $oldFile);
                }
            }

            // Simpan path relatif
            $user->avatar = 'profile_images/' . $imageName;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('status', 'profile-image-updated');
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
    /**
     * Get the user's avatar.
     */
    public function getAvatar($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            abort(404, 'User not found.');
        }

        // Jika avatar berupa URL eksternal
        if ($user->avatar && Str::startsWith($user->avatar, ['http://', 'https://'])) {
            $proxyUrl = 'https://asepharyana.cloud/api/imageproxy?url=' . urlencode($user->avatar);
            return redirect($proxyUrl);
        }

        // Ambil path apa adanya (tanpa nambah 'profile_images/' lagi)
        $avatarPath = ltrim($user->avatar, '/');

        if (Storage::disk('public')->exists($avatarPath)) {
            $filePath = Storage::disk('public')->path($avatarPath);
            return response()->file($filePath);
        }

        abort(404, 'Avatar not found.');
    }
}
