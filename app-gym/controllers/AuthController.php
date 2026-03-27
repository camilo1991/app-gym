<?php
// controllers/AuthController.php

class AuthController {
    private $usuarioModel;

    public function __construct($pdo) {
        // Asegúrate de que la ruta al modelo sea correcta
        require_once __DIR__ . '/../models/Usuario.php';
        $this->usuarioModel = new Usuario($pdo);
    }

    public function showLogin() {
        require_once __DIR__ . '/../views/login.view.php';
    }

    public function showRegister() {
        require_once __DIR__ . '/../views/registro.view.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';
            
            $auth = $this->usuarioModel->login($user, $pass);

            if ($auth) {
                $_SESSION['user_id'] = $auth['id'];
                $_SESSION['user_name'] = $auth['nombre'];
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                header("Location: index.php?error=Credenciales incorrectas");
                exit;
            }
        }
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'      => $_POST['nombre'],
                'username'    => $_POST['username'],
                'password'    => $_POST['password'],
                'peso_actual' => $_POST['peso_actual'] ?? 0,
                'peso_ideal'  => $_POST['peso_ideal'] ?? 0
            ];

            $resultado = $this->usuarioModel->registrar($data);

            if ($resultado) {
                header("Location: index.php?msg=Registro exitoso");
                exit;
            } else {
                die("Error al registrar: Revisar duplicados o conexión.");
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}