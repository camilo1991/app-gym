<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
// Lógica de Salida (Logout)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirige al login
    exit;
}
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/GymController.php';



$auth = new AuthController($pdo);
$gym  = new GymController($pdo);

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') { $auth->login(); exit; }
    if ($action === 'register') { $auth->registrar(); exit; }
}

if (!isset($_SESSION['user_id'])) {
    $action === 'register' ? $auth->showRegister() : $auth->showLogin();
} else {
    $page = $_GET['page'] ?? 'dashboard';
    switch ($page) {
        case 'entrenar': $gym->mostrarEntrenamiento($_SESSION['user_id']); break;
        case 'historial': $gym->mostrarHistorial($_SESSION['user_id']); break; // Activado
        default: $gym->mostrarDashboard($_SESSION['user_id']); break;
    }
}