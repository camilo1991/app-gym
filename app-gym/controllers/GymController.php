<?php
class GymController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
        date_default_timezone_set('America/Bogota');
    }

    public function mostrarDashboard($userId) {
        $racha = $this->getSumRacha($userId);
        
        // Consulta simplificada para evitar Error 500 si la tabla está vacía
        $stmtTop = $this->db->prepare("SELECT ejercicio, MAX(peso) as max_p FROM series_v2 WHERE usuario_id = ? GROUP BY ejercicio ORDER BY max_p DESC LIMIT 5");
        $stmtTop->execute([$userId]);
        $topRecords = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

        // Datos para el Heatmap
        $stmtCal = $this->db->prepare("SELECT DISTINCT DATE(fecha) as dia FROM series_v2 WHERE usuario_id = ? AND fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        $stmtCal->execute([$userId]);
        $fechasEntrenadas = $stmtCal->fetchAll(PDO::FETCH_COLUMN);

        include 'views/dashboard.view.php';
    }

    public function entrenar($userId) {
        $n = (int)date('N'); 
        $rutinas = [
            1 => ['Press Banca', 'Press Inclinado', 'Press Militar', 'Vuelos Laterales'],
            2 => ['Sentadilla', 'Prensa', 'Extensión Pierna', 'Pantorrilla'],
            3 => ['Dominadas', 'Remo con Barra', 'Remo Gironda', 'Curl de Bíceps'],
            4 => ['Press Banca (Refuerzo)', 'Aperturas Pecho', 'Press Militar (Mancuerna)', 'Vuelos Frontales'],
            5 => ['Peso Muerto', 'Zancadas', 'Prensa (Pies Altos)', 'Flexión Pierna'],
            6 => ['Full Body'], 7 => ['Descanso']
        ];
        $ejercicios_hoy = $rutinas[$n] ?? [];
        include 'views/entrenar.view.php';
    }

    public function mostrarHistorial($userId) {
        try {
            // 1. Registros
            $stmt = $this->db->prepare("SELECT id, fecha, ejercicio, peso, repeticiones FROM series_v2 WHERE usuario_id = ? ORDER BY fecha DESC");
            $stmt->execute([$userId]);
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Datos Gráfica
            $stmtG = $this->db->prepare("SELECT DATE(fecha) as dia, SUM(peso * repeticiones) as vol FROM series_v2 WHERE usuario_id = ? GROUP BY DATE(fecha) ORDER BY dia DESC LIMIT 7");
            $stmtG->execute([$userId]);
            $graficaData = array_reverse($stmtG->fetchAll(PDO::FETCH_ASSOC));

            include 'views/historial.view.php';
        } catch (PDOException $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function eliminarSerie($userId, $idSerie) {
        $stmt = $this->db->prepare("DELETE FROM series_v2 WHERE id = ? AND usuario_id = ?");
        $success = $stmt->execute([$idSerie, $userId]);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    private function getSumRacha($userId) {
        $stmt = $this->db->prepare("SELECT DISTINCT DATE(fecha) as f FROM series_v2 WHERE usuario_id = ? ORDER BY f DESC");
        $stmt->execute([$userId]);
        $fechas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if(!$fechas) return 0;
        $racha = 0; $hoy = new DateTime(date('Y-m-d'));
        foreach($fechas as $f){
            $fe = new DateTime($f);
            $diff = $hoy->diff($fe)->days;
            if($diff <= 1){ $racha++; $hoy = $fe; } else break;
        }
        return $racha;
    }
}