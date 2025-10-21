<?php
session_start(); // Ensure sessions are started (add if not in db_config.php)
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    sendError('Email and password are required');
}

// Check for hardcoded admin
if ($email === 'admin@laundry.com' && password_verify($password, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')) {
    $adminUser = [
        'id' => 1,
        'name' => 'Administrator',
        'email' => 'admin@laundry.com',
        'role' => 'admin',
        'contact' => 'N/A',
        'address' => 'N/A',
        'photo' => ''
    ];
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'admin';
    sendResponse(['user' => $adminUser, 'message' => 'Login successful']);
    exit; // Prevent continuing to DB check
}

// If not admin, check database for regular users
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, name, email, password, contact, address, role, photo FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    sendError('Invalid credentials');
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!password_verify($password, $user['password'])) {
    sendError('Invalid credentials');
}

// Remove password from response
unset($user['password']);

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_role'] = $user['role'];

sendResponse(['user' => $user, 'message' => 'Login successful']);
?>