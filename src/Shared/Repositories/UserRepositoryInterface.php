<?php

namespace App\Shared\Repositories;

interface UserRepositoryInterface
{
    public function findBySchoolId($school_id);
    public function findById($user_id);
    public function getAllUsers();
    public function getUsersByRole($role);
    public function getStudentsByYearSection($year_level, $section);
    public function create($data);
    public function update($user_id, $data);
    public function delete($user_id);
    public function authenticate($school_id, $password);
}

