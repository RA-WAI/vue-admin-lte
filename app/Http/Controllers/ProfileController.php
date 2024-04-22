<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['email', 'name', 'role', 'profile_image']);
    }

    public function uploadProfileImage(Request $request)
    {
        if($request->hasFile('profile_image'))
        {
            $link = Storage::put('/profile', $request->file('profile_image'));
            $request->user()->update(['profile_image' => $link]);
            return response()->json(['message' => 'Profile image successfully updated.']);
        }
    }
}
