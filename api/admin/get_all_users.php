<?php
require_once '../../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isAdmin()) {
    sendError('Admin access required', 403);
}

$conn = getDBConnection();
$result = $conn->query("SELECT id, name, email, contact, address, role, photo, created_at FROM users ORDER BY created_at DESC");

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$conn->close();

sendResponse(['users' => $users]);
?>
