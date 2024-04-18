<?php
namespace App\Repositories;

use App\Models\Appointment;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    /**
     * Create new appointment
     *
     * @param array $data
     */
    public function create($data)
    {
        return Appointment::create($data);
    }
}
