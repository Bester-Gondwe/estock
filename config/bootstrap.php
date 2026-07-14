<?php
/**
 * Application bootstrap — Composer autoload, environment, session.
 */

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!is_readable($autoload)) {
    http_response_code(500);
    echo 'Dependencies missing. Run <code>composer install</code> in the project root.';
    exit;
}

require_once $autoload;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$root = dirname(__DIR__);
if (is_readable($root . '/.env')) {
    Dotenv\Dotenv::createImmutable($root)->safeLoad();
}
