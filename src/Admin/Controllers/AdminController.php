<?php

namespace App\Admin\Controllers;

use App\Auth\Services\AuthService;
use App\Shared\Services\UserService;
use App\Shared\Repositories\UserRepository;
use App\Core\View;

class AdminController
{
    private $authService;
    private $userService;
    private $view;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->userService = new UserService(new UserRepository());
        $this->view = new View(__DIR__ . '/../Views/');
        $this->authService->requireAuth();
        $this->authService->requireRole('admin');
    }

    public function dashboard()
    {
        $currentUser = $this->authService->getCurrentUser();
        $students = $this->userService->getUsersByRole('student');
        $faculty = $this->userService->getUsersByRole('faculty');
        $data = [
            'admin' => $currentUser,
            'students' => $students,
            'faculty' => $faculty,
            'yearSections' => $this->getYearSections($students)
        ];
        $this->view->display('dashboard', $data);
    }

    public function logout()
    {
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
            $this->authService->logout();
            $scriptName = $_SERVER['SCRIPT_NAME'];
            $basePath = dirname($scriptName);
            $loginUrl = $basePath . '/login';
            header('Location: ' . $loginUrl);
            return;
        } else {
            $this->showLogoutConfirmation();
        }
    }

    private function showLogoutConfirmation()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        $logoutUrl = $basePath . '/admin/logout?confirm=true';
        $dashboardUrl = $basePath . '/admin/dashboard';
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirm Logout - Admin Dashboard</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-white">
                                <h4 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Confirm Logout
                                </h4>
                            </div>
                            <div class="card-body text-center">
                                <i class="fas fa-sign-out-alt fa-3x text-warning mb-3"></i>
                                <h5>Are you sure you want to logout?</h5>
                                <p class="text-muted">You will be redirected to the login page.</p>
                                
                                <div class="mt-4">
                                    <a href="' . $logoutUrl . '" class="btn btn-warning me-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Yes, Logout
                                    </a>
                                    <a href="' . $dashboardUrl . '" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        return;
    }

    private function getYearSections($students)
    {
        $yearSections = [];
        foreach ($students as $student) {
            $key = $student['year_level'] . ' ' . $student['section'];
            if (!isset($yearSections[$key])) { $yearSections[$key] = 0; }
            $yearSections[$key]++;
        }
        return $yearSections;
    }

    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method.'); return; }
        $result = $this->userService->createUser($_POST);
        if ($result['success']) { $this->showSuccess($result['message']); } else { $this->showError($result['message']); }
    }

    public function addStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method.'); return; }
        $result = $this->userService->createUser($_POST);
        if ($result['success']) { $_SESSION['success_message'] = $result['message']; $this->redirectToDashboard(); }
        else { $_SESSION['error_message'] = $result['message']; $this->redirectToDashboard(); }
    }

    private function redirectToDashboard()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        header('Location: ' . $basePath . '/admin/dashboard');
        exit;
    }

    public function editUser($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method.'); return; }
        $result = $this->userService->updateUser($userId, $_POST);
        if ($result['success']) { $this->showSuccess($result['message']); } else { $this->showError($result['message']); }
    }

    public function editStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method.'); return; }
        $userId = $_POST['user_id'] ?? null;
        if (!$userId) { $this->showError('User ID is required.'); return; }
        $result = $this->userService->updateUser($userId, $_POST);
        if ($result['success']) { $_SESSION['success_message'] = $result['message']; $this->redirectToDashboard(); }
        else { $_SESSION['error_message'] = $result['message']; $this->redirectToDashboard(); }
    }

    public function deleteUser($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method.'); return; }
        $result = $this->userService->deleteUser($userId);
        if ($result['success']) { $this->showSuccess($result['message']); } else { $this->showError($result['message']); }
    }

    public function deleteStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method.'); return; }
        $userId = $_POST['user_id'] ?? null;
        if (!$userId) { $this->showError('User ID is required.'); return; }
        $result = $this->userService->deleteUser($userId);
        if ($result['success']) { $_SESSION['success_message'] = $result['message']; $this->redirectToDashboard(); }
        else { $_SESSION['error_message'] = $result['message']; $this->redirectToDashboard(); }
    }

    public function addFaculty()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method'); return; }
        $data = [
            'school_id' => $_POST['school_id'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'role' => 'faculty',
            'password' => $_POST['password'] ?? ''
        ];
        if (empty($data['school_id']) || empty($data['full_name'])) { $this->showError('School ID and Full Name are required'); $this->redirectToDashboard(); return; }
        try {
            $result = $this->userService->createUser($data);
            if ($result['success']) { $this->showSuccess('Faculty member added successfully'); $this->redirectToDashboard(); }
            else { $this->showError($result['message']); $this->redirectToDashboard(); }
        } catch (\Exception $e) {
            $this->showError('Error adding faculty member: ' . $e->getMessage());
            $this->redirectToDashboard();
        }
    }

    public function editFaculty()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method'); return; }
        $userId = $_POST['user_id'] ?? '';
        if (empty($userId)) { $this->showError('User ID is required'); return; }
        $data = [
            'user_id' => $userId,
            'school_id' => $_POST['school_id'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'role' => 'faculty'
        ];
        if (empty($data['school_id']) || empty($data['full_name'])) { $this->showError('School ID and Full Name are required'); return; }
        try {
            $result = $this->userService->updateUser($data);
            if ($result['success']) { $this->showSuccess('Faculty member updated successfully'); }
            else { $this->showError($result['message']); }
        } catch (\Exception $e) {
            $this->showError('Error updating faculty member: ' . $e->getMessage());
        }
    }

    public function deleteFaculty()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->showError('Invalid request method'); return; }
        $userId = $_POST['user_id'] ?? '';
        if (empty($userId)) { $this->showError('User ID is required'); return; }
        try {
            $result = $this->userService->deleteUser($userId);
            if ($result['success']) { $this->showSuccess('Faculty member deleted successfully'); $this->redirectToDashboard(); }
            else { $this->showError($result['message']); $this->redirectToDashboard(); }
        } catch (\Exception $e) {
            $this->showError('Error deleting faculty member: ' . $e->getMessage());
            $this->redirectToDashboard();
        }
    }

    private function showSuccess($message)
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => $message]);
    }

    private function showError($message)
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $message]);
    }
}

