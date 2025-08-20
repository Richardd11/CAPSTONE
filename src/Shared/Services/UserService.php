<?php

namespace App\Shared\Services;

use App\Shared\Repositories\UserRepositoryInterface;

class UserService
{
    private $userDAO;

    public function __construct(UserRepositoryInterface $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function createUser($data)
    {
        if (empty($data['school_id']) || empty($data['full_name']) || empty($data['role'])) {
            return ['success' => false, 'message' => 'School ID, full name, and role are required.'];
        }

        $existingUser = $this->userDAO->findBySchoolId($data['school_id']);
        if ($existingUser) {
            return ['success' => false, 'message' => 'School ID already exists.'];
        }

        $validRoles = ['admin', 'faculty', 'student'];
        if (!in_array($data['role'], $validRoles)) {
            return ['success' => false, 'message' => 'Invalid role. Must be admin, faculty, or student.'];
        }

        if ($data['role'] === 'student') {
            if (empty($data['year_level']) || empty($data['section'])) {
                return ['success' => false, 'message' => 'Year level and section are required for students.'];
            }
        }

        $userId = $this->userDAO->create($data);
        if ($userId) {
            return ['success' => true, 'message' => 'User created successfully!', 'user_id' => $userId];
        }
        return ['success' => false, 'message' => 'Failed to create user.'];
    }

    public function updateUser($userId, $data)
    {
        $existingUser = $this->userDAO->findById($userId);
        if (!$existingUser) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        if (isset($data['school_id']) && $data['school_id'] !== $existingUser['school_id']) {
            $userWithSchoolId = $this->userDAO->findBySchoolId($data['school_id']);
            if ($userWithSchoolId) {
                return ['success' => false, 'message' => 'School ID already exists.'];
            }
        }

        $result = $this->userDAO->update($userId, $data);
        if ($result) { return ['success' => true, 'message' => 'User updated successfully!']; }
        return ['success' => false, 'message' => 'Failed to update user.'];
    }

    public function deleteUser($userId)
    {
        $existingUser = $this->userDAO->findById($userId);
        if (!$existingUser) { return ['success' => false, 'message' => 'User not found.']; }
        $result = $this->userDAO->delete($userId);
        if ($result) { return ['success' => true, 'message' => 'User deleted successfully!']; }
        return ['success' => false, 'message' => 'Failed to delete user.'];
    }

    public function getAllUsers()
    {
        return $this->userDAO->getAllUsers();
    }

    public function getUsersByRole($role)
    {
        return $this->userDAO->getUsersByRole($role);
    }

    public function getStudentsByYearSection($yearLevel, $section)
    {
        return $this->userDAO->getStudentsByYearSection($yearLevel, $section);
    }
}

