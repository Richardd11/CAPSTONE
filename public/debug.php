<?php

session_start();

require_once '../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\Auth\AuthController;

echo "<h1>Debug Information</h1>";

// Show current request info
echo "<h2>Request Information:</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Test path processing
echo "<h2>Path Processing Test:</h2>";
$originalPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$subdirectory = dirname($scriptName);

echo "<p><strong>Original Path:</strong> " . $originalPath . "</p>";
echo "<p><strong>Script Name:</strong> " . $scriptName . "</p>";
echo "<p><strong>Subdirectory:</strong> " . $subdirectory . "</p>";

if ($subdirectory !== '/' && strpos($originalPath, $subdirectory) === 0) {
    $processedPath = substr($originalPath, strlen($subdirectory));
    if (empty($processedPath)) {
        $processedPath = '/';
    }
    echo "<p><strong>Processed Path:</strong> " . $processedPath . "</p>";
} else {
    echo "<p><strong>Processed Path:</strong> " . $originalPath . " (no subdirectory removed)</p>";
}

// Test if classes load
echo "<h2>Class Loading Test:</h2>";
try {
    $router = new Router();
    echo "<p>✅ Router loaded successfully</p>";
} catch (Exception $e) {
    echo "<p>❌ Router error: " . $e->getMessage() . "</p>";
}

try {
    $authController = new AuthController();
    echo "<p>✅ AuthController loaded successfully</p>";
} catch (Exception $e) {
    echo "<p>❌ AuthController error: " . $e->getMessage() . "</p>";
}

// Test routing
echo "<h2>Routing Test:</h2>";
$router = new Router();
$router->get('/test', function() {
    echo "<p>✅ Test route works!</p>";
});

$router->get('/login', function() {
    echo "<p>✅ Login route works!</p>";
});

echo "<p>Testing route handling...</p>";
$_SERVER['REQUEST_URI'] = '/test';
$_SERVER['REQUEST_METHOD'] = 'GET';
$router->handleRequest();
?>