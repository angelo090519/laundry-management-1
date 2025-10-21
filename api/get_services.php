<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = getDBConnection();
$result = $conn->query("SELECT id, name, description, price, capacity FROM services ORDER BY name");

$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

$conn->close();

sendResponse(['services' => $services]);
?>
