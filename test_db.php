<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;
use App\Models\User;

echo "<h1>Database Connection Test</h1>";

try {
    // Test database connection
    $db = Database::getInstance();
    $connection = $db->getConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if users table exists
    $stmt = $connection->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Users table exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ Users table does not exist!</p>";
        exit;
    }
    
    // Test if users exist
    $stmt = $connection->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p>Total users in database: " . $result['count'] . "</p>";
    
    // Show all users
    $stmt = $connection->query("SELECT user_id, school_id, full_name, role FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h2>Available Users:</h2>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>School ID</th><th>Name</th><th>Role</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['user_id'] . "</td>";
        echo "<td>" . $user['school_id'] . "</td>";
        echo "<td>" . $user['full_name'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test authentication
    echo "<h2>Authentication Test:</h2>";
    $userModel = new User();
    
    // Test with admin user
    $admin = $userModel->authenticate('ADMIN001', 'password123');
    if ($admin) {
        echo "<p style='color: green;'>✅ Admin login successful!</p>";
    } else {
        echo "<p style='color: red;'>❌ Admin login failed!</p>";
    }
    
    // Test with student user
    $student = $userModel->authenticate('2020-001', 'password123');
    if ($student) {
        echo "<p style='color: green;'>✅ Student login successful!</p>";
    } else {
        echo "<p style='color: red;'>❌ Student login failed!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>