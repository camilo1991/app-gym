<?php
class GymController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // ESTA ES LA FUNCIÓN QUE TE ESTÁ PIDIENDO LA LÍNEA 34
    public function mostrarDashboard() {
        // Aquí podrías cargar datos rápidos para el dashboard si fuera necesario
        include 'views/dashboard.view.php'; 
    }

    public function entrenar($userId) {
        // Obtener el último peso registrado por cada ejercicio
        $stmt = $this->db->prepare("
            SELECT ejercicio, peso 
            FROM series s1 
            WHERE usuario_id = ? AND id = (
                SELECT MAX(id) FROM series s2 
                WHERE s2.ejercicio = s1.ejercicio AND s2.usuario_id = s1.usuario_id
            )
        ");
        $stmt->execute([$userId]);
        $ultimosPesos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        include 'views/entrenar.view.php';
    }

    public function historial($userId) {
        // 1. Días entrenados (Consistencia 28D)
        $stmt = $this->db->prepare("SELECT DISTINCT DATE(fecha) FROM series WHERE usuario_id = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 28 DAY)");
        $stmt->execute([$userId]);
        $diasEntrenados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // 2. Resumen de Hoy
        $stmtHoy = $this->db->prepare("
            SELECT ejercicio, MAX(peso) as max_h, COUNT(*) as total,
            (SELECT MAX(peso) FROM series s2 WHERE s2.ejercicio = s1.ejercicio AND s2.usuario_id = s1.usuario_id AND DATE(s2.fecha) < CURDATE()) as record_historico
            FROM series s1
            WHERE usuario_id = ? AND DATE(fecha) = CURDATE()
            GROUP BY ejercicio
        ");
        $stmtHoy->execute([$userId]);
        $resumenHoy = $stmtHoy->fetchAll(PDO::FETCH_ASSOC);

        // 3. Labels y Valores para Chart.js
        $labels = json_encode(['Sem 4', 'Sem 3', 'Sem 2', 'Sem Actual']);
        $valores = json_encode([4200, 4500, 4100, 4800]); 

        include 'views/historial.view.php';
    }
}