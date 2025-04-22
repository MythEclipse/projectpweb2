<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AvatarController extends Controller
{
    public function getAvatar($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            Log::error("User with ID {$userId} not found.");
            abort(404, 'User not found.');
        }

        Log::info("User found: ID {$userId}, Avatar: {$user->avatar}");

        // Jika avatar berupa URL eksternal
        if ($user->avatar && Str::startsWith($user->avatar, ['http://', 'https://'])) {
            $proxyUrl = 'https://asepharyana.cloud/api/imageproxy?url=' . urlencode($user->avatar);
            Log::info("Redirecting to proxy URL: {$proxyUrl}");
            return redirect($proxyUrl);
        }

        // Ambil path apa adanya (tanpa nambah 'profile_images/' lagi)
        $avatarPath = ltrim($user->avatar, '/');

        Log::info("Checking avatar path in public disk: {$avatarPath}");

        if (Storage::disk('public')->exists($avatarPath)) {
            $filePath = Storage::disk('public')->path($avatarPath);
            Log::info("Serving avatar from: {$filePath}");
            return response()->file($filePath);
        }

        Log::error("Avatar for user ID {$userId} not found at {$avatarPath}.");
        abort(404, 'Avatar not found.');
    }
}
