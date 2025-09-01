<?php

// /api/_proxy.php
declare(strict_types=1);

require_once __DIR__ . '/../config.php'; // Load config & start session
header('Content-Type: application/json; charset=utf-8');

/**
 * Send JSON response and exit
 */
function send_json(array|string $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
    exit;
}

/**
 * Make a safe request to Node.js backend
 * @param string $path - Node endpoint
 * @param string $method - HTTP method
 * @param array|string|null $body - Request payload
 * @param array $extraHeaders - Additional headers
 */
function node(string $path, string $method = 'GET', array|string|null $body = null, array $extraHeaders = []): void
{
    $url = 'http://127.0.0.1:3000' . $path;

    // Use session email if logged in, otherwise guest
    $userEmail = $_SESSION['email'] ?? 'guest@example.com';

    $headers = array_merge([
        'X-User-Email: ' . $userEmail,
        'Accept: application/json'
    ], $extraHeaders);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => strtoupper($method),
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 20,
    ]);

    if ($body !== null) {
        if (is_array($body)) {
            $body = json_encode($body, JSON_UNESCAPED_UNICODE);
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        send_json(['error' => 'NODE_UNREACHABLE', 'detail' => $err], 502);
    }

    curl_close($ch);
    send_json($resp, $code);
}
