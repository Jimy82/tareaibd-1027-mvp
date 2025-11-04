<?php
final class Validator {
    public array $errors = [];

    public function required(string $field, ?string $value, string $msg='Campo obligatorio'): void {
        if (!is_string($value) || trim($value)==='') $this->errors[$field] = $msg;
    }
    public function length(string $field, string $value, int $min, int $max): void {
        $len = mb_strlen($value);
        if ($len < $min || $len > $max) $this->errors[$field] = "Debe tener entre $min y $max caracteres";
    }
    public function email(string $field, string $value): void {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) $this->errors[$field] = "Email no válido";
    }
    public function honeypot(string $field, ?string $value): void {
        if (is_string($value) && trim($value)!=='') $this->errors[$field] = "Detección anti-bot";
    }
    public function ok(): bool { return empty($this->errors); }
}
