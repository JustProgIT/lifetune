<?php
// /api/_proxy.php
require_once __DIR__ . '/../config.php';   // your existing config; starts session
header('Content-Type: application/json; charset=utf-8');

// 1) Require login (so we have the email)
$email = $_SESSION['email'] ?? '';
if ($email === '') {
  http_response_code(401);
  echo json_encode(['error' => 'NOT_LOGGED_IN']);
  exit;
}

// 2) A helper to call Node safely
function node($path, $method = 'GET', $body = null, $extraHeaders = []) {
  $url = 'http://127.0.0.1:3000' . $path;

  $headers = array_merge([
    'X-User-Email: ' . $_SESSION['email'],
    'Accept: application/json'
  ], $extraHeaders);

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_TIMEOUT        => 20,
  ]);

  if ($body !== null) {
    // If you pass an array, auto-JSON it
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
    http_response_code(502);
    echo json_encode(['error' => 'NODE_UNREACHABLE', 'detail' => $err]);
    exit;
  }
  curl_close($ch);

  http_response_code($code);
  echo $resp;
  exit;
}
