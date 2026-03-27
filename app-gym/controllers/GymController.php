<?php
class GymController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
        date_default_timezone_set('America/Bogota');
    }

    public function mostrarDashboard($userId) {
        // 1. C¨¢lculo de Racha
        $stmtF = $this->db->prepare("SELECT DISTINCT DATE(fecha) as f FROM series_v2 WHERE usuario_id = ? ORDER BY f DESC");
        $stmtF->execute([$userId]);
        $fechas = $stmtF->fetchAll(PDO::FETCH_COLUMN) ?: [];
        $racha = 0;
        if (!empty($fechas)) {
            $hoy = new DateTime(date('Y-m-d'));
            $ultima = new DateTime($fechas[0]);
            if ($hoy->diff($ultima)->days <= 1) {
                $current = $ultima;
                foreach ($fechas as $f) {
                    $fechaEntreno = new DateTime($f);
                    if ($current->diff($fechaEntreno)->days <= 1) {
                        $racha++;
                        $current = $fechaEntreno;
                    } else { break; }
                }
            }
        }

        // 2. Hall of Fame - Alias corregidos: max_p, reps, fecha_formateada
        $stmtTop = $this->db->prepare("
            SELECT s1.ejercicio, s1.peso as max_p, s1.repeticiones as reps, DATE_FORMAT(s1.fecha, '%d %b') as fecha_formateada
            FROM series_v2 s1
            INNER JOIN (
                SELECT ejercicio, MAX(peso) as max_peso
                FROM series_v2
                WHERE usuario_id = ? AND tipo_ejercicio = 'fuerza'
                GROUP BY ejercicio
            ) s2 ON s1.ejercicio = s2.ejercicio AND s1.peso = s2.max_peso
            WHERE s1.usuario_id = ?
            GROUP BY s1.ejercicio
            ORDER BY s1.peso DESC LIMIT 3
        ");
        $stmtTop->execute([$userId, $userId]);
        $topRecords = $stmtTop->fetchAll(PDO::FETCH_ASSOC) ?: [];

        include 'views/dashboard.view.php';
    }

    public function entrenar($userId) {
        $n = (int)date('N');
        $rutinas = [
            1 => ['Press Banca', 'Press Inclinado', 'Press Militar', 'Vuelos Laterales', 'Copa Tr¨ªceps'],
            2 => ['Sentadilla', 'Prensa Inclinada', 'Extensi¨®n Pierna', 'Pantorrilla', 'Abdominales'],
            3 => ['Dominadas', 'Remo con Barra', 'Remo Gironda', 'Curl de B¨ªceps', 'Curl Martillo'],
            4 => ['Press Banca (Refuerzo)', 'Aperturas Pecho', 'Press Militar (Mancuerna)', 'Vuelos Frontales'],
            5 => ['Peso Muerto', 'Zancadas', 'Prensa (Pies Altos)', 'Flexi¨®n Pierna', 'Abdominales'],
            6 => ['Sentadilla', 'Press Banca', 'Dominadas'],
            7 => ['Caminadora', 'Bicicleta', 'Escaladora']
        ];
        
        $ejercicios_base = $rutinas[$n] ?? [];
        $stmtCount = $this->db->prepare("SELECT ejercicio, COUNT(*) as total FROM series_v2 WHERE usuario_id = ? AND DATE(fecha) = CURDATE() GROUP BY ejercicio");
        $stmtCount->execute([$userId]);
        $conteoSeries = $stmtCount->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        $stmtExtras = $this->db->prepare("SELECT DISTINCT ejercicio FROM series_v2 WHERE usuario_id = ? AND DATE(fecha) = CURDATE()");
        $stmtExtras->execute([$userId]);
        $registrados_hoy = $stmtExtras->fetchAll(PDO::FETCH_COLUMN) ?: [];

        $ejercicios_finales = $ejercicios_base;
        foreach ($registrados_hoy as $extra) {
            if (!in_array($extra, $ejercicios_finales)) { $ejercicios_finales[] = $extra; }
        }

        $ultimosPesos = [];
        foreach ($ejercicios_finales as $ej) {
            $stmt = $this->db->prepare("SELECT peso, tiempo_minutos, tipo_ejercicio FROM series_v2 WHERE usuario_id = ? AND ejercicio = ? ORDER BY fecha DESC LIMIT 1");
            $stmt->execute([$userId, trim($ej)]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $ultimosPesos[$ej] = $res ? ($res['tipo_ejercicio'] == 'cardio' ? $res['tiempo_minutos']."m" : (int)$res['peso']."lb") : "--";
        }
        include 'views/entrenar.view.php';
    }

    public function guardarSerie($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ejercicio'])) {
            $tipo = $_POST['tipo_ejercicio'] ?? 'fuerza';
            if ($tipo === 'cardio') {
                $stmt = $this->db->prepare("INSERT INTO series_v2 (usuario_id, ejercicio, tipo_ejercicio, tiempo_minutos, velocidad, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$userId, $_POST['ejercicio'], $tipo, $_POST['tiempo'], $_POST['velocidad']]);
                header("Location: index.php?action=entrenar");
            } else {
                $stmt = $this->db->prepare("INSERT INTO series_v2 (usuario_id, ejercicio, tipo_ejercicio, peso, repeticiones, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$userId, $_POST['ejercicio'], $tipo, $_POST['peso'], $_POST['reps']]);
                header("Location: index.php?action=entrenar&rest=true");
            }
        }
        exit;
    }
}