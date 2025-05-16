<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [

        \App\Repositories\UserRepositoryInterface::class => \App\Repositories\UserRepository::class,
        \App\Repositories\HealthcareProfessionalRepositoryInterface::class => \App\Repositories\HealthcareProfessionalRepository::class,
        \App\Repositories\AppointmentRepositoryInterface::class => \App\Repositories\AppointmentRepository::class,
    ];
}
