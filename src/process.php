<?php
require __DIR__.'/bootstrap.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /'); exit;
}

$val = new Validator();
$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
$website = trim((string)($_POST['website'] ?? ''));
$csrf = $_POST['csrf'] ?? null;

if (!Csrf::verify($csrf)) {
    http_response_code(400);
    exit('CSRF inválido');
}

$val->honeypot('website', $website);
$val->required('name', $name);
if ($name !== '') $val->length('name', $name, 2, 80);

$val->required('email', $email);
if ($email !== '') $val->email('email', $email);

$val->required('message', $message);
if ($message !== '') $val->length('message', $message, 10, 2000);

$ip = client_ip();
if (!throttle_ip($ip, 10)) {
    $val->errors['global'] = 'Demasiadas peticiones. Inténtalo en un minuto.';
}

$repo = new Repository($pdo);
if (empty($val->errors) && $repo->tooFrequent($email, 60)) {
    $val->errors['global'] = 'Has enviado algo recientemente. Espera unos segundos.';
}

if (!$val->ok()) {
    $msg = $val->errors['global'] ?? reset($val->errors);
    header('Location: /?e='.urlencode((string)$msg));
    exit;
}

$repo->create([
    'name' => $name,
    'email' => mb_strtolower($email),
    'message' => $message,
    'ip' => $ip,
    'ua' => $_SERVER['HTTP_USER_AGENT'] ?? '',
]);

header('Location: /thanks.php');
