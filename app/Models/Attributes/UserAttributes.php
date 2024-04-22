<?php
namespace App\Models\Attributes;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

Trait UserAttributes
{
    /**
     * Get the user's first name.
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format(config('app.date_format'));
    }

    public function role(): Attribute
    {
        return Attribute::make(

            get: fn($value) => RoleType::from($value)->name,

        );
    }

    public function profileImage(): Attribute
    {
        return Attribute::make(

            get: fn($value) => $value ? asset(Storage::url($value)) : asset(Storage::url('profile/default.png')),

        );
    }
}
