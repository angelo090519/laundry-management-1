<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$contact = trim($data['contact'] ?? '');
$address = trim($data['address'] ?? '');

if (empty($name) || empty($email) || empty($password) || empty($contact)) {
    sendError('Name, email, password, and contact are required');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
    sendError('Please use a valid Gmail address');
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{8,15}$/', $password)) {
    sendError('Password must be 8-15 characters with uppercase, lowercase, and special character');
}

$conn = getDBConnection();

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    sendError('Email already registered');
}
$stmt->close();

// Insert new user
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password, contact, address, role) VALUES (?, ?, ?, ?, ?, 'user')");
$stmt->bind_param("sssss", $name, $email, $hashedPassword, $contact, $address);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    sendError('Registration failed');
}

$userIdFromDB = $conn->insert_id;
$stmt->close();
$conn->close();

// Set session
$_SESSION['user_id'] = $userIdFromDB;
$_SESSION['user_role'] = 'user';

$user = [
    'id' => $userIdFromDB,
    'name' => $name,
    'email' => $email,
    'contact' => $contact,
    'address' => $address,
    'role' => 'user',
    'photo' => ''
];

sendResponse(['user' => $user, 'message' => 'Registration successful']);
?>
