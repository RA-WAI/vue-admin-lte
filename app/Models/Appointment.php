<?php

namespace App\Models;

use App\Enums\AppointmentSttus;
use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attributes\AppointmentAttribute;
use App\Models\Relationships\AppointmentRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, AppointmentAttribute, AppointmentRelationship;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'status' => AppointmentStatus::class,
        ];
    }

    protected $appends = [
        'formatted_start_time',
        'formatted_end_time',
    ];
}
