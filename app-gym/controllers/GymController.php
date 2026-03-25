<?php
// controllers/GymController.php
require_once __DIR__ . '/../models/Usuario.php';

class GymController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function mostrarDashboard($userId) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Lógica de mensaje dinámico para el Dashboard
            $hora = date('H');
            if ($hora < 11) {
                $mensaje = "¡El mundo es de los que madrugan! A darle con toda. 🔥";
            } elseif ($hora < 18) {
                $mensaje = "Cada repetición cuenta para tu meta de " . $usuario['peso_ideal'] . " kg. ⚡";
            } else {
                $mensaje = "Buen trabajo hoy, descansa para mañana. 🌙";
            }
            include __DIR__ . '/../views/dashboard.view.php';
        } else {
            header("Location: index.php?action=logout");
        }
    }

    public function mostrarHistorial($userId) {
        // 1. Obtener días con actividad para el calendario de cuadritos
        $stmtCal = $this->db->prepare("SELECT DISTINCT DATE(fecha) as dia FROM series WHERE usuario_id = ?");
        $stmtCal->execute([$userId]);
        $diasEntrenados = $stmtCal->fetchAll(PDO::FETCH_COLUMN);

        // 2. Obtener lo mejor de HOY segmentado por ejercicio
        $stmtHoy = $this->db->prepare("SELECT ejercicio, MAX(peso) as max_h, COUNT(*) as total 
                                       FROM series 
                                       WHERE usuario_id = ? AND DATE(fecha) = CURDATE() 
                                       GROUP BY ejercicio");
        $stmtHoy->execute([$userId]);
        $resumenHoy = $stmtHoy->fetchAll();

        // 3. Obtener récords previos para generar sugerencias técnicas
        $stmtSug = $this->db->prepare("SELECT ejercicio, MAX(peso) as record_ant 
                                       FROM series 
                                       WHERE usuario_id = ? AND DATE(fecha) < CURDATE() 
                                       GROUP BY ejercicio");
        $stmtSug->execute([$userId]);
        $sugerencias = $stmtSug->fetchAll(PDO::FETCH_KEY_PAIR);

        include __DIR__ . '/../views/historial.view.php';
    }

    public function mostrarEntrenamiento($userId) {
        $stmt = $this->db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $u = $stmt->fetch();
        include __DIR__ . '/../views/entrenar.view.php';
    }
}