<?php
final class Csrf {
    private const KEY = '__csrf_token';
    public static function token(): string {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }
    public static function verify(?string $t): bool {
        return is_string($t) && hash_equals($_SESSION[self::KEY] ?? '', $t);
    }
}
