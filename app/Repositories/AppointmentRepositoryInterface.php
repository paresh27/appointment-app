<?php

namespace App\Repositories;

interface AppointmentRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getMyAppointments($userId);

    public function complete($id);

    public function cancel($id);
}
