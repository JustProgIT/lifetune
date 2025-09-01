<?php
// /api/postChat.php
require __DIR__ . '/_proxy.php';

// Read JSON from POST body
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!$input || !isset($input['historys'], $input['userMessage'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Forward POST to Node backend
node('/chat', 'POST', $input);
