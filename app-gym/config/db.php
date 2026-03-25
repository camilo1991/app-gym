<?php
// config/db.php
$host = 'localhost';
$db   = 'mndcuvmq_gym_os'; // Verifica que este sea el nombre real en tu cPanel
$user = 'mndcuvmq_admin_gym';
$pass = 'Camilo1012380314*';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Si falla, te dirá exactamente por qué (usuario, clave o bd)
    die("Error de conexión: " . $e->getMessage());
}