<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if (!isLoggedIn()) {
    sendError('Not authenticated', 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

$data = json_decode(file_get_contents('php://input'), true);
$serviceId = (int)($data['service_id'] ?? 0);
$machineId = isset($data['machine_id']) ? (int)$data['machine_id'] : null;
$notes = trim($data['notes'] ?? '');

if (!$serviceId) {
    sendError('Service ID is required');
}

$conn = getDBConnection();

// Get service details
$stmt = $conn->prepare("SELECT name, price FROM services WHERE id = ?");
$stmt->bind_param("i", $serviceId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    sendError('Service not found');
}

$service = $result->fetch_assoc();
$stmt->close();

// Generate transaction ID
$transactionId = 'T' . rand(10000, 99999);

// Insert transaction
$stmt = $conn->prepare("INSERT INTO transactions (id, user_id, service_id, machine_id, status, total, notes) VALUES (?, ?, ?, ?, 'pending', ?, ?)");
$stmt->bind_param("siiidss", $transactionId, $_SESSION['user_id'], $serviceId, $machineId, $service['price'], $notes);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    sendError('Transaction creation failed');
}

$stmt->close();
$conn->close();

$transaction = [
    'id' => $transactionId,
    'service' => $service['name'],
    'total' => $service['price'],
    'status' => 'pending',
    'notes' => $notes,
    'created_at' => date('Y-m-d H:i:s')
];

sendResponse(['transaction' => $transaction, 'message' => 'Transaction created successfully']);
?>
