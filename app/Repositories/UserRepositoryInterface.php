<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function findByEmail($email);

    public function createToken($user);
}
