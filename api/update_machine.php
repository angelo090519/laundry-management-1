<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

if (!isAdmin()) {
    sendError('Admin access required', 403);
}

if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'])) {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$machineId = (int)($data['machine_id'] ?? 0);
$status = $data['status'] ?? '';

$validStatuses = ['available', 'in_use', 'maintenance'];
if (!$machineId || !in_array($status, $validStatuses)) {
    sendError('Valid machine ID and status are required');
}

$conn = getDBConnection();
$stmt = $conn->prepare("UPDATE machines SET status = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("si", $status, $machineId);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    sendError('Update failed');
}

$affectedRows = $stmt->affected_rows;
$stmt->close();
$conn->close();

if ($affectedRows === 0) {
    sendError('Machine not found', 404);
}

sendResponse(['message' => 'Machine status updated successfully']);
?>
