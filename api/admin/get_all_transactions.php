<?php
require_once '../../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isAdmin()) {
    sendError('Admin access required', 403);
}

$conn = getDBConnection();
$query = "SELECT t.id, t.user_id, u.name as user_name, u.email as user_email, t.service_id, s.name as service, t.machine_id, m.name as machine, t.status, t.total, t.notes, t.created_at, t.updated_at
          FROM transactions t
          LEFT JOIN users u ON t.user_id = u.id
          LEFT JOIN services s ON t.service_id = s.id
          LEFT JOIN machines m ON t.machine_id = m.id
          ORDER BY t.created_at DESC";

$result = $conn->query($query);

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

$conn->close();

sendResponse(['transactions' => $transactions]);
?>
