<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isLoggedIn()) {
    sendError('Not authenticated', 401);
}

$userId = $_SESSION['user_id'];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;

$conn = getDBConnection();
$query = "SELECT t.id, t.service_id, s.name as service, t.machine_id, m.name as machine, m.type as machine_type, t.status, t.total, t.notes, t.created_at, t.updated_at
          FROM transactions t
          LEFT JOIN services s ON t.service_id = s.id
          LEFT JOIN machines m ON t.machine_id = m.id
          WHERE t.user_id = ?
          ORDER BY t.created_at DESC";

if ($limit) {
    $query .= " LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $limit);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
}

$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt->close();
$conn->close();

sendResponse(['transactions' => $transactions]);
?>
