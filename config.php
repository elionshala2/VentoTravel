<?php
// Loads .env (needs: composer install) and connects to the database.
$__autoload = __DIR__ . '/vendor/autoload.php';
if (is_file($__autoload)) {
    require_once $__autoload;
    if (class_exists('Dotenv\Dotenv')) { Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad(); }
}
if (!function_exists('env')) {
    function env(string $key, $default = null) {
        $v = $_ENV[$key] ?? getenv($key);
        return ($v === false || $v === null || $v === '') ? $default : $v;
    }
}

$server   = env('DB_HOST', 'localhost');
$user     = env('DB_USER', 'root');
$pass     = env('DB_PASS', '');
$database = env('DB_NAME', 'VentoTravel');

$conn = @mysqli_connect($server, $user, $pass, $database);
if (!$conn) { die('Database connection failed.'); }
mysqli_set_charset($conn, 'utf8mb4');

require_once __DIR__ . '/includes/schema.php';
vt_ensure_schema($conn);
