<?php
// controllers/GymController.php

// Cargamos los modelos necesarios (Rutas absolutas)
require_once __DIR__ . '/../models/Usuario.php';
// require_once __DIR__ . '/../models/Serie.php'; // Descomenta cuando crees este modelo

class GymController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Muestra el panel principal del usuario
     */
    public function mostrarDashboard($userId) {
        // 1. Obtenemos los datos frescos del usuario (Pesos, metas, etc.)
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch();

        // 2. Aquí podrías traer también el resumen de la última rutina
        // $ultimaSerie = ... lógica para el modelo Serie

        // 3. Cargamos la vista (Ruta absoluta)
        if ($usuario) {
            include __DIR__ . '/../views/dashboard.view.php';
        } else {
            // Si por alguna razón no hay usuario, cerramos sesión
            header("Location: index.php?action=logout");
        }
    }
public function mostrarHistorial($userId) {
    $stmt = $this->db->prepare("SELECT * FROM series WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 20");
    $stmt->execute([$userId]);
    $historial = $stmt->fetchAll();
    
    include __DIR__ . '/../views/historial.view.php';
}
    /**
     * Muestra la interfaz para registrar ejercicios
     */
    public function mostrarEntrenamiento($userId) {
        // Datos del usuario para personalizar la experiencia
        $stmt = $this->db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $u = $stmt->fetch();

        // Cargamos la vista de entrenamiento (Asegúrate de crear este archivo después)
        include __DIR__ . '/../views/entrenar.view.php';
    }

    /**
     * Panel de administración (Solo accesible si eres Andrey)
     */
    public function mostrarAdmin() {
        // Consultas para estadísticas globales
        $totalUsers = $this->db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        
        include __DIR__ . '/../views/admin.view.php';
    }

    /**
     * Procesa el guardado de una serie de ejercicio (POST)
     */
    public function guardarSerie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lógica para recibir peso, reps y rpe del formulario
            // $resultado = $this->serieModel->insertar($_POST);
            
            header("Location: index.php?page=entrenar&status=success");
            exit;
        }
    }
}