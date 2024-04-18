<?php
namespace App\Http\Controllers\Admin;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentCreateRequest;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentRepositoryInterface $appointmentRepo
    )
    {

    }
    public function index()
    {
        return Appointment::with('client:id,first_name,last_name')
            ->orderBy('id', 'desc')->paginate()
            ->through(fn ($appointment) => [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'start_time' => $appointment->formatted_start_time,
                'end_time' => $appointment->formatted_end_time,
                'status' => [
                    'name' => $appointment->status->name,
                    'color' => $appointment->status->color(),
                ],
                'client' => $appointment->client,
            ]);
    }

    public function create(AppointmentCreateRequest $request)
    {
        try {
            $data = $request->only([
                'client_id',
                'title',
                'description',
                'start_time',
                'end_time',
                'end_time',
                'status',
            ]);

            $this->appointmentRepo->create($data);

            return response()->json(['message' => 'Appointment successfully created.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
