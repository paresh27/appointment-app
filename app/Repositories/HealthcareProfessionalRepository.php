<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use Carbon\Carbon;

class HealthcareProfessionalRepository implements HealthcareProfessionalRepositoryInterface
{
    public function all()
    {
        return HealthcareProfessional::all();
    }

    public function find($id)
    {
        return HealthcareProfessional::findOrFail($id);
    }

    public function create(array $data)
    {
        return HealthcareProfessional::create($data);
    }

    public function update($id, array $data)
    {
        $healthcareProfessional = HealthcareProfessional::findOrFail($id);
        $healthcareProfessional->update($data);

        return $healthcareProfessional;
    }

    public function delete($id)
    {
        $healthcareProfessional = HealthcareProfessional::findOrFail($id);

        return $healthcareProfessional->delete();
    }

    public function paginate(int $perPage = 10)
    {
        return HealthcareProfessional::paginate($perPage);
    }

    public function isAvailable($data): bool
    {
        return ! $this->find($data['healthcare_professional_id'])
            ->appointments()
            ->where('appointment_start_time', '>=', Carbon::parse($data['appointment_start_time'])->format('Y-m-d H:i:s'))
            ->where('appointment_end_time', '<=', Carbon::parse($data['appointment_end_time'])->format('Y-m-d H:i:s'))
            ->where(function ($query) {
                $query->orWhere('status', Appointment::BOOKED)
                    ->orwhere('status', Appointment::COMPLETED);
            })
            ->exists();
    }
}
