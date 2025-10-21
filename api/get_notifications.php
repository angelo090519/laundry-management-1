<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isLoggedIn()) {
    sendError('Not authenticated', 401);
}

$userId = $_SESSION['user_id'];

$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, title, message, type, is_read, created_at FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$conn->close();

sendResponse(['notifications' => $notifications]);
?>
