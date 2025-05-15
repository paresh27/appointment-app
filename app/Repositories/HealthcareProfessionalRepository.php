<?php

namespace App\Repositories;

use App\Models\HealthcareProfessional;

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
    
    public function paginate(int $perPage = 10) {
        return HealthcareProfessional::paginate($perPage);
    }
}
