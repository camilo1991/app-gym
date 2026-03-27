<?php
// index.php - Rodríguez Gym OS
session_start();

require_once 'config/db.php';
require_once 'controllers/GymController.php';
require_once 'controllers/AuthController.php';

$auth = new AuthController($pdo);
$action = $_GET['action'] ?? 'dashboard';

// --- ACCIONES PÚBLICAS ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') { $auth->login(); exit; }
if ($action === 'register') {
    $_SERVER['REQUEST_METHOD'] === 'POST' ? $auth->registrar() : $auth->showRegister();
    exit;
}
if ($action === 'logout') { $auth->logout(); exit; }

// --- BARRERA DE SEGURIDAD ---
if (!isset($_SESSION['user_id'])) { $auth->showLogin(); exit; }

// --- FLUJO PRIVADO ---
$userId = $_SESSION['user_id'];
$controller = new GymController($pdo);

switch ($action) {
    case 'entrenar':
        $controller->entrenar($userId);
        break;
    case 'historial':
        $controller->mostrarHistorial($userId); // Corregido: antes decía $controller->historial
        break;
    case 'eliminar_serie':
        $controller->eliminarSerie($userId, $_GET['id'] ?? null);
        break;
    case 'dashboard':
    default:
        $controller->mostrarDashboard($userId);
        break;
}