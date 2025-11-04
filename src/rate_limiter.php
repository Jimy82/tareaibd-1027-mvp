<?php
function throttle_ip(string $ip, int $maxPerMinute=10): bool {
    $bucketKey = 'rl_'.hash('sha256',$ip);
    $now = time();
    $_SESSION[$bucketKey] = array_values(array_filter(($_SESSION[$bucketKey] ?? []), fn($t) => ($now - $t) < 60));
    if (count($_SESSION[$bucketKey]) >= $maxPerMinute) return false;
    $_SESSION[$bucketKey][] = $now;
    return true;
}
