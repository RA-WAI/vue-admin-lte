<?php

namespace App\Helpers;

class AppHelper
{
    public static function getImageUrl()
    {
        return response()->json([
            'imageUrl' => asset('profile/default.png'),
        ]);
    }
}
