<?php
// controllers/AuthController.php

// Cargamos el modelo de Usuario (Ruta absoluta desde este archivo)
require_once __DIR__ . '/../models/Usuario.php'; 

class AuthController {
    private $usuarioModel;

    public function __construct($pdo) {
        // Inicializamos el modelo pasando la conexión PDO
        $this->usuarioModel = new Usuario($pdo);
    }

    // --- MÉTODOS PARA MOSTRAR VISTAS (GET) ---

    public function showLogin() {
        // Carga el HTML del login
        include __DIR__ . '/../views/login.view.php';
    }

    public function showRegister() {
        // Carga el HTML del registro
        include __DIR__ . '/../views/registro.view.php';
    }

    // --- MÉTODOS PARA PROCESAR DATOS (POST) ---

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';
            
            $auth = $this->usuarioModel->login($user, $pass);

            if ($auth) {
                // Creamos la sesión del usuario
                $_SESSION['user_id'] = $auth['id'];
                $_SESSION['user_name'] = $auth['username'];
                $_SESSION['full_name'] = $auth['nombre'];
                
                // Redirigimos al Dashboard
                header("Location: index.php?page=dashboard");
                exit;
            } else {
                // Si falla, recargamos el login con un error
                header("Location: index.php?error=Credenciales incorrectas");
                exit;
            }
        }
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recogemos los datos del formulario de registro
            $data = [
                'nombre'      => $_POST['nombre'],
                'username'    => $_POST['username'],
                'password'    => $_POST['password'],
                'peso_actual' => $_POST['peso_actual'],
                'peso_ideal'  => $_POST['peso_ideal']
            ];

            $resultado = $this->usuarioModel->registrar($data);

            if ($resultado) {
                // Registro exitoso, mandamos al login
                header("Location: index.php?msg=Cuenta creada con éxito");
                exit;
            } else {
                echo "Error al registrar el usuario. Revisa que el username no esté duplicado.";
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}