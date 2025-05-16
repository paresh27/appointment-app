<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ActionNotAllowed;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookAnAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repositories\AppointmentRepositoryInterface;
use App\Repositories\HealthcareProfessionalRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    private $appointmentRepository;

    private $healthcareProfessionalRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository, HealthcareProfessionalRepositoryInterface $healthcareProfessionalRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->healthcareProfessionalRepository = $healthcareProfessionalRepository;
    }

    /**
     * Get the list of user's appointments.
     * @return AppointmentResource
     */
    public function index()
    {

        $appointments = $this->appointmentRepository->getMyAppointments(auth('sanctum')->user()->id);

        return (new AppointmentResource($appointments))->additional([
            'status' => 'success',
            'message' => 'Appointments fetched successfully.',
        ]);

    }

    /**
     * Book an appointment.
     * @param \App\Http\Requests\BookAnAppointmentRequest $request
     * @return AppointmentResource|mixed|\Illuminate\Http\JsonResponse
     */
    public function store(BookAnAppointmentRequest $request)
    {

        $isProfessionalAvailable = $this->healthcareProfessionalRepository->isAvailable($request->only([
            'healthcare_professional_id',
            'appointment_start_time',
            'appointment_end_time',
        ]));

        if (! $isProfessionalAvailable) {
            return response()->json(['status' => 'error', 'message' => 'Doctor is not available during this time. Please choose another slot'], 422);
        }

        $appointment = $this->appointmentRepository->create([
            'user_id' => auth('sanctum')->user()->id,
            'healthcare_professional_id' => $request->healthcare_professional_id,
            'appointment_start_time' => $request->appointment_start_time,
            'appointment_end_time' => $request->appointment_end_time,
        ]);

        return (new AppointmentResource($appointment))->additional([
            'status' => 'success',
            'message' => 'Appointment booked successfully.',
        ]);
    }

    /**
     * Cancel a book appointment.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Appointment $appointment
     * @throws \App\Exceptions\ActionNotAllowed
     * @return AppointmentResource
     */
    public function cancel(Request $request, Appointment $appointment)
    {

        if (! auth('sanctum')->user()->can('update', $appointment)) {
            throw new ActionNotAllowed('You are not authorized to modify this appointment.', 403);
        }

        if (! $appointment->canBeCancelled()) {
            throw ValidationException::withMessages([
                'appointment' => 'Only booked appointments can be cancelled at least 24 hours in advance.',
            ]);
        }

        $appointment = $this->appointmentRepository->cancel($appointment->id);

        return (new AppointmentResource($appointment))->additional([
            'status' => 'success',
            'message' => 'Appointment cancelled successfully.',
        ]);
    }

    /**
     * Complte a booked appointment.
     * @param \App\Models\Appointment $appointment
     * @return AppointmentResource
     */
    public function complete(Appointment $appointment)
    {

        if (! $appointment->canBeCompleted()) {
            throw ValidationException::withMessages([
                'appointment' => 'Only booked appointments can be completed.',
            ]);
        }

        $appointment = $this->appointmentRepository->complete($appointment->id);

        return (new AppointmentResource($appointment))->additional([
            'status' => 'success',
            'message' => 'Appointment completed successfully.',
        ]);
    }
}
