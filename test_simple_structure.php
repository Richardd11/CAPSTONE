<?php

// Test script to verify simple structure
require_once 'vendor/autoload.php';

echo "🧪 Testing Simple Structure...\n\n";

try {
    // Test DAO
    echo "1. Testing DAO:\n";
    
    $userDAO = new \App\DAO\Auth\UserDAO();
    echo "✅ UserDAO loaded successfully\n\n";
    
    // Test Service
    echo "2. Testing Service:\n";
    
    $authService = new \App\Services\Auth\AuthService();
    echo "✅ AuthService loaded successfully\n\n";
    
    // Test Controller
    echo "3. Testing Controller:\n";
    
    $authController = new \App\Controllers\Auth\AuthController();
    echo "✅ AuthController loaded successfully\n\n";
    
    // Test Core classes
    echo "4. Testing Core Classes:\n";
    
    $router = new \App\Core\Router();
    echo "✅ Router loaded successfully\n";
    
    $view = new \App\Core\View();
    echo "✅ View loaded successfully\n\n";
    
    echo "🎉 All components loaded successfully!\n";
    echo "📁 Simple Structure:\n";
    echo "   - Controllers in src/App/Controllers/Auth/\n";
    echo "   - Services in src/App/Services/Auth/\n";
    echo "   - DAOs in src/App/DAO/Auth/\n";
    echo "   - No interfaces, no dependency injection\n";
    echo "   - Simple and straightforward!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}