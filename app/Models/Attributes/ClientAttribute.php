<?php
namespace App\Models\Attributes;

trait ClientAttribute {
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
