<?php
namespace App\Repositories\Interfaces;

interface AppointmentRepositoryInterface {
    /**
     * Create new appointment
     *
     * @param array $data
     */
    public function create($data);
}
