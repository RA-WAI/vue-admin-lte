<?php
namespace App\Models\Attributes;
trait AppointmentAttribute {
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time->format(config('app.date_format'));
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time->format(config('app.date_format'));
    }
}
