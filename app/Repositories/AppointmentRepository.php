<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function all()
    {
        return Appointment::all();
    }

    public function find($id)
    {
        return Appointment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function update($id, array $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);

        return $appointment;
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);

        return $appointment->delete();
    }

    public function getMyAppointments($userId)
    {
        return Appointment::where('user_id', $userId)->paginate();
    }

    public function complete($id)
    {
        return $this->update($id, ['status' => Appointment::COMPLETED]);
    }

    public function cancel($id)
    {
        return $this->update($id, ['status' => Appointment::CANCELLED]);
    }
}
