<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = getDBConnection();
$result = $conn->query("SELECT id, name, type, status, location FROM machines ORDER BY name");

$machines = [];
while ($row = $result->fetch_assoc()) {
    $machines[] = $row;
}

$conn->close();

sendResponse(['machines' => $machines]);
?>
