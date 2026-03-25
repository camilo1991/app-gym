<?php
session_start();
session_unset();
session_destroy();
// 1. Iniciar la sesión para poder destruirla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Limpiar todas las variables de sesión
$_SESSION = array();

// 3. Destruir la sesión física en el servidor
session_destroy();

// 4. Limpiar la cookie del navegador (esto es clave para que no "recuerde" tu sesión)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// 5. Redirigir al INDEX (que es el punto de entrada real)
header("Location: ./");
exit();
?>