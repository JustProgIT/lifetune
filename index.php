<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routes = [
    '' => 'home.php',
    '/' => 'home.php',
    '/index' => 'home.php',
    '/home' => 'home.php',
    '/onboarding' => 'onboarding.php',
    '/profile' => 'profile.php',
    '/profile_edit' => 'profile_edit.php',
    '/login' => 'login_google.php',
    '/register' => 'register.html',
    '/birth' => 'birth.php',
    '/birth_process' => 'birth_process.php',
    '/result' => 'result.php',
    '/aichat' => 'aichat.php',
    '/login' => 'login.php',
    '/forgotpassword' => 'forgotpassword.php',
    '/logingoogle' => 'login_google.php',
    '/questionaire' => 'questionaire.php',
    '/terms' => 'terms.php',
    '/privacy' => 'privacy.php',
    '/payment_refund' => 'payment_refund.php',
];

if (array_key_exists($request, $routes)) {
    include $routes[$request];
} else {
    http_response_code(404);
    echo "404 Not Found";
}
