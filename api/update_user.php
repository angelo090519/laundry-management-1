<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

if (!isLoggedIn()) {
    sendError('Not authenticated', 401);
}

if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'])) {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$contact = trim($data['contact'] ?? '');
$address = trim($data['address'] ?? '');
$photo = trim($data['photo'] ?? '');

if (empty($name) || empty($contact)) {
    sendError('Name and contact are required');
}

$conn = getDBConnection();
$stmt = $conn->prepare("UPDATE users SET name = ?, contact = ?, address = ?, photo = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("ssssi", $name, $contact, $address, $photo, $_SESSION['user_id']);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    sendError('Update failed');
}

$stmt->close();
$conn->close();

// Get updated user data
$user = getCurrentUser();

sendResponse(['user' => $user, 'message' => 'Profile updated successfully']);
?>
