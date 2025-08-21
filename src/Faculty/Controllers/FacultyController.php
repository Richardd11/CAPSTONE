<?php

namespace App\Faculty\Controllers;

use App\Auth\Services\AuthService;
use App\Core\View;

class FacultyController
{
    private $authService;
    private $view;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->view = new View(__DIR__ . '/../Views/');

        // Enforce authentication and faculty role
        $authResult = $this->authService->requireRole('faculty');
        if (!$authResult['success']) {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $basePath = dirname($scriptName);
            $redirect = $authResult['redirect'] ?? '/login';
            header('Location: ' . $basePath . $redirect);
            exit;
        }
    }

    public function dashboard()
    {
        $currentUser = $this->authService->getCurrentUser();

        $data = [
            'faculty' => $currentUser,
            // Placeholder data; hook up to real services later
            'classes' => [],
            'upcomingExams' => [],
            'recentActivity' => []
        ];

        $this->view->display('dashboard', $data);
    }
}

