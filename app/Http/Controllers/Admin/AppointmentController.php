<?php
namespace App\Http\Controllers\Admin;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::with('client:id,first_name,last_name')
            ->orderBy('id', 'desc')->paginate()
            ->through(fn ($appointment) => [
                'id' => $appointment->id,
                'start_time' => $appointment->formatted_start_time,
                'end_time' => $appointment->formatted_end_time,
                'status' => [
                    'name' => $appointment->status->name,
                    'color' => $appointment->status->color(),
                ],
                'client' => $appointment->client,
            ]);
    }
}
