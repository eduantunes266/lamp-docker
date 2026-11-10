<?php
// Database connection using PDO.
// The credentials come from the environment variables defined in docker-compose.yml.
// The host is "db": the service name on Docker's internal network (not "localhost").

$host = getenv('DB_HOST') ?: 'db';
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$name;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Error connecting to the database: ' . htmlspecialchars($e->getMessage()));
}
