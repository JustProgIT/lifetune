<?php
// /api/_proxy.php
declare(strict_types=1);

require_once __DIR__ . '/../config.php'; // Load config & start session

// Show all warnings for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: application/json; charset=utf-8');

/**
 * Send JSON response and exit
 */
function send_json($data, int $statusCode = 200)
{
    http_response_code($statusCode);
    echo is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $data;
    exit;
}

/**
 * Debug logger (writes to PHP error log)
 */
function debug_log($msg)
{
    error_log("[_proxy.php] $msg");
}

/**
 * Make a request to Node.js backend using file_get_contents
 * Updated to use port 4001
 */
function node(string $path, string $method = 'GET', $body = null, array $extraHeaders = [])
{
    // ✅ Change port to 4001
    $url = 'http://127.0.0.1:4001' . $path;

    // Use guest email if not logged in
    $userEmail = $_SESSION['email'] ?? 'guest@example.com';

    $headers = [
        "Host: 127.0.0.1:4001", // Include port in Host header
        "X-User-Email: $userEmail",
        "Accept: application/json"
    ];

    // Merge extra headers
    foreach ($extraHeaders as $key => $value) {
        $headers[] = "$key: $value";
    }

    $options = [
        'http' => [
            'method'  => strtoupper($method),
            'header'  => implode("\r\n", $headers),
            'timeout' => 20,
        ]
    ];

    if ($body !== null) {
        if (is_array($body)) {
            $body = json_encode($body, JSON_UNESCAPED_UNICODE);
        }
        $options['http']['content'] = $body;
        $options['http']['header'] .= "\r\nContent-Type: application/json";
    }

    debug_log("Proxying $method $url with headers: " . json_encode($headers) . " body: " . var_export($body, true));

    $context = stream_context_create($options);
    $resp = @file_get_contents($url, false, $context);

    // Collect response headers
    global $http_response_header;
    debug_log("Node.js raw response headers: " . json_encode($http_response_header ?? []));

    if ($resp === false) {
        $lastErr = error_get_last();
        debug_log("file_get_contents failed: " . json_encode($lastErr));
        send_json([
            'error' => 'NODE_UNREACHABLE',
            'detail' => $lastErr['message'] ?? 'Unknown',
            'url' => $url,
            'headers' => $headers,
        ], 502);
    }

    debug_log("Node.js raw response body: $resp");

    $decoded = json_decode($resp, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        send_json($decoded);
    } else {
        debug_log("JSON decode error: " . json_last_error_msg());
        send_json([
            'error' => 'INVALID_NODE_RESPONSE',
            'raw'   => $resp,
            'url'   => $url,
        ], 502);
    }
}
