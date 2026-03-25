<?php
class Serie {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Guarda el set y calcula el volumen total (peso * reps)
    public function registrarSet($user_id, $ejercicio, $peso, $reps, $n_serie) {
        $sql = "INSERT INTO series (user_id, ejercicio, peso, reps, n_serie, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
        return $this->db->prepare($sql)->execute([$user_id, $ejercicio, $peso, $reps, $n_serie]);
    }

    // Obtiene el progreso mensual (Promedio de peso levantado por mes)
    public function getProgresoMensual($user_id, $ejercicio = 'Press de Banca') {
        $sql = "SELECT 
                    DATE_FORMAT(fecha, '%b') as mes, 
                    MAX(peso) as max_peso,
                    SUM(reps) as total_reps
                FROM series 
                WHERE user_id = ? AND ejercicio = ?
                GROUP BY MONTH(fecha) 
                ORDER BY fecha ASC 
                LIMIT 6";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id, $ejercicio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene el último peso para la sugerencia automática
    public function getUltimoEsfuerzo($user_id, $ejercicio) {
        $sql = "SELECT peso, reps FROM series WHERE user_id = ? AND ejercicio = ? ORDER BY fecha DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id, $ejercicio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}