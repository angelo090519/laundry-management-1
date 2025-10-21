<?php
require_once '../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isLoggedIn()) {
    sendError('Not authenticated', 401);
}

$user = getCurrentUser();

if (!$user) {
    sendError('User not found', 404);
}

sendResponse(['user' => $user]);
?>
