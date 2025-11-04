<?php
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
function ip_to_bin(string $ip): string {
    $packed = @inet_pton($ip);
    return $packed !== false ? $packed : '';
}
