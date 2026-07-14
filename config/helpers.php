<?php
/**
 * Shared application helpers (loaded via Composer autoload files).
 */

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        if ($value === false || $value === null || $value === '') {
            return $default;
        }
        return match (strtolower((string) $value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'null', '(null)' => null,
            default => $value,
        };
    }
}

if (!function_exists('app_currency')) {
    function app_currency(): string
    {
        return (string) env('APP_CURRENCY', 'MWK');
    }
}

if (!function_exists('format_money')) {
    function format_money(float|int|string $amount): string
    {
        return app_currency() . ' ' . number_format((float) $amount, 2);
    }
}

if (!function_exists('require_login')) {
    function require_login(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }
}

if (!function_exists('require_merchant')) {
    function require_merchant(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ../login.php');
            exit;
        }
        if (($_SESSION['user_role'] ?? '') !== 'Merchant') {
            http_response_code(403);
            echo 'Access denied. Merchant account required.';
            exit;
        }
    }
}

if (!function_exists('require_customer')) {
    function require_customer(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        if (($_SESSION['user_role'] ?? '') === 'Merchant') {
            header('Location: merchant/');
            exit;
        }
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return !empty($_SESSION['user_id']);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(?string $token): bool
    {
        return is_string($token)
            && isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}
