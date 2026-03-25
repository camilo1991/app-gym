<?php
// controllers/GymController.php
class GymController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function mostrarDashboard() {
    $userId = $_SESSION['user_id'];
    
    // Contar d�as entrenados en el mes actual
    $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT DATE(fecha)) as total_dias 
        FROM series_v2 
        WHERE usuario_id = ? AND MONTH(fecha) = MONTH(CURRENT_DATE()) 
        AND YEAR(fecha) = YEAR(CURRENT_DATE())
    ");
    $stmt->execute([$userId]);
    $resumenMes = $stmt->fetch(PDO::FETCH_ASSOC);
    $diasEntrenados = $resumenMes['total_dias'] ?? 0;

    include 'views/dashboard.view.php';
}

    public function entrenar($userId) {
        // Traer �ltimos pesos para los badges
        $stmt = $this->db->prepare("
            SELECT ejercicio, peso 
            FROM series_v2 s1 
            WHERE usuario_id = ? AND id = (
                SELECT MAX(id) FROM series_v2 s2 
                WHERE s2.ejercicio = s1.ejercicio AND s2.usuario_id = s1.usuario_id
            )
        ");
        $stmt->execute([$userId]);
        $ultimosPesos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        include 'views/entrenar.view.php';
    }

    public function historial($userId) {
        // 1. Consistencia (Cuadritos verdes)
        $stmt = $this->db->prepare("SELECT DISTINCT DATE(fecha) FROM series_v2 WHERE usuario_id = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 28 DAY)");
        $stmt->execute([$userId]);
        $diasEntrenados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // 2. Resumen de hoy vs r�cord
        $stmtHoy = $this->db->prepare("
            SELECT ejercicio, MAX(peso) as max_h, COUNT(*) as total,
            (SELECT MAX(peso) FROM series_v2 s2 WHERE s2.ejercicio = s1.ejercicio AND s2.usuario_id = s1.usuario_id AND DATE(s2.fecha) < CURDATE()) as record_historico
            FROM series_v2 s1
            WHERE usuario_id = ? AND DATE(fecha) = CURDATE()
            GROUP BY ejercicio
        ");
        $stmtHoy->execute([$userId]);
        $resumenHoy = $stmtHoy->fetchAll(PDO::FETCH_ASSOC);

        // 3. Gr�fica (Datos mockeados por ahora)
        $labels = json_encode(['Sem 4', 'Sem 3', 'Sem 2', 'Hoy']);
        $valores = json_encode([3500, 4200, 3800, 4000]);

        include 'views/historial.view.php';
    }
}