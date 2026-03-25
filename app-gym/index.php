<?php
// index.php
session_start();
require_once 'config/db.php';
require_once 'controllers/GymController.php';
require_once 'controllers/AuthController.php';

$auth = new AuthController($pdo);
$action = $_GET['action'] ?? 'dashboard';

// 1. Si la acción es login o registrar, ejecutamos el método del controlador
if ($action === 'login') {
    $auth->login(); // Este método ya tiene el if(POST) dentro
    exit;
}

if ($action === 'logout') {
    $auth->logout();
    exit;
}

// 2. PROTECCIÓN DE RUTA: Si no hay sesión, mostramos el login y matamos el script
if (!isset($_SESSION['user_id'])) {
    $auth->showLogin();
    exit;
}

// En tu index.php, añade este caso al switch o antes de la validación de sesión
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->registrar(); // Llama al método de tu AuthController
    exit;
}

// Para mostrar la vista de registro (cuando el usuario hace clic en el link)
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $auth->showRegister();
    exit;
}

// 3. FLUJO NORMAL (Solo llega aquí si hay sesión real)
$userId = $_SESSION['user_id'];
$controller = new GymController($pdo);

// ... resto de tu switch case ...