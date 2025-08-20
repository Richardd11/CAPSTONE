<?php

namespace App\Shared\Repositories;

use App\Config\Database;
use PDO;
use PDOException;

class UserRepository implements UserRepositoryInterface
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findBySchoolId($school_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE school_id = ?");
            $stmt->execute([$school_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findById($user_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ?");
            $stmt->execute([$user_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getUsersByRole($role)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = ? ORDER BY full_name ASC");
            $stmt->execute([$role]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getStudentsByYearSection($year_level, $section)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = 'student' AND year_level = ? AND section = ? ORDER BY full_name ASC");
            $stmt->execute([$year_level, $section]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function create($data)
    {
        try {
            $plainPassword = $data['school_id'] . $data['full_name'];
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

            $sql = "INSERT INTO {$this->table} (school_id, full_name, password, role, year_level, section, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['school_id'],
                $data['full_name'],
                $hashedPassword,
                $data['role'],
                $data['role'] === 'student' ? $data['year_level'] : null,
                $data['role'] === 'student' ? $data['section'] : null
            ]);

            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($user_id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET school_id = ?, full_name = ?, role = ?, year_level = ?, section = ?, updated_at = NOW() 
                    WHERE user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['school_id'],
                $data['full_name'],
                $data['role'],
                $data['role'] === 'student' ? $data['year_level'] : null,
                $data['role'] === 'student' ? $data['section'] : null,
                $user_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($user_id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE user_id = ?");
            return $stmt->execute([$user_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function authenticate($school_id, $password)
    {
        $user = $this->findBySchoolId($school_id);
        if (!$user) { return false; }
        if (strpos($user['password'], '$') === 0) {
            return password_verify($password, $user['password']) ? $user : false;
        }
        return $password === $user['password'] ? $user : false;
    }
}

