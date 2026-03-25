<?php
// index.php
session_start();
require_once 'config/db.php';
require_once 'controllers/GymController.php';

// Simulaci¨®n de login para pruebas (borrar cuando implementes login real)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}

$controller = new GymController($pdo);
$userId = $_SESSION['user_id'];

// Capturamos la acci¨®n: index.php?action=entrenar
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'entrenar':
        $controller->entrenar($userId);
        break;
    case 'historial':
        $controller->historial($userId);
        break;
    case 'dashboard':
    default:
        $controller->mostrarDashboard(); // Coincide con el m¨¦todo en el controlador
        break;
}