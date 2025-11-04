<?php
declare(strict_types=1);

ini_set('session.use_strict_mode', '1');
session_start();

$env = getenv('APP_ENV') ?: 'prod';

function env(string $key, ?string $default=null): ?string {
    $v = getenv($key);
    return $v === false ? $default : $v;
}

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', env('DB_HOST','db'), env('DB_NAME','mvpdb'));
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, env('DB_USER','mvpuser'), env('DB_PASS','devpass'), $options);
} catch (Throwable $e) {
    http_response_code(500);
    echo "DB no disponible. Inténtelo más tarde.";
    exit;
}

require_once __DIR__.'/helpers.php';
require_once __DIR__.'/Validator.php';
require_once __DIR__.'/Csrf.php';
require_once __DIR__.'/Repository.php';
require_once __DIR__.'/rate_limiter.php';
