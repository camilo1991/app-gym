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
        // 1. Configurar fecha local
        $fecha_actual = date('l, d \d\e F');

        // 2. Generar mensaje dinámico según la hora
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
    // ... (Mantén tus consultas de días entrenados y gráfica de volumen)

    // Consulta de hoy incluyendo el récord histórico para comparar
    $stmtHoy = $this->db->prepare("
        SELECT 
            s.ejercicio, 
            MAX(s.peso) as max_h, 
            COUNT(*) as total,
            (SELECT MAX(peso) FROM series WHERE usuario_id = ? AND ejercicio = s.ejercicio AND DATE(fecha) < CURDATE()) as record_historico
        FROM series s
        WHERE s.usuario_id = ? AND DATE(s.fecha) = CURDATE() 
        GROUP BY s.ejercicio
    ");
    $stmtHoy->execute([$userId, $userId]);
    $resumenHoy = $stmtHoy->fetchAll();

    include __DIR__ . '/../views/historial.view.php';
}

    public function mostrarEntrenamiento($userId) {
        // Obtenemos el nombre del usuario (ya lo tenías)
        $stmt = $this->db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $u = $stmt->fetch();

        // --- NUEVO BLOQUE: Consulta de últimos pesos ---
        // Esta consulta busca el último registro de cada ejercicio para mostrarlo como referencia
        $stmtLast = $this->db->prepare("
            SELECT ejercicio, peso 
            FROM series 
            WHERE usuario_id = ? 
            AND id IN (SELECT MAX(id) FROM series WHERE usuario_id = ? GROUP BY ejercicio)
        ");
        $stmtLast->execute([$userId, $userId]);
        
        // Creamos un array asociativo: ['Prensa' => 544.0, 'Sentadilla' => 200.0]
        $ultimosPesos = $stmtLast->fetchAll(PDO::FETCH_KEY_PAIR);
        // -----------------------------------------------

        // Finalmente, incluimos la vista que ahora podrá usar la variable $ultimosPesos
        include __DIR__ . '/../views/entrenar.view.php';
    }
}