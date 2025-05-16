<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProfessionalResource;
use App\Repositories\HealthcareProfessionalRepositoryInterface;

class HealthcareProfessionalController extends Controller
{
    private $healthcareProfessionalRepository;

    public function __construct(HealthcareProfessionalRepositoryInterface $healthcareProfessionalRepository)
    {
        $this->healthcareProfessionalRepository = $healthcareProfessionalRepository;
    }

    /**
     * Get the paginated list of all healthcare professionals.
     *
     * @return HealthcareProfessionalResource
     */
    public function index()
    {
        $professionals = $this->healthcareProfessionalRepository->paginate();

        return (new HealthcareProfessionalResource($professionals))
            ->additional([
                'status' => 'success',
                'message' => 'List fetched successfully',
            ]);
    }
}
