<?php
session_start();
header('Content-Type: application/json');

// 1. Incluir conexión a BD
require_once 'config/db.php'; 

// 2. Validación de Seguridad (Usuario logueado)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesión no iniciada. Por favor, logueate de nuevo.']);
    exit;
}

// 3. Procesamiento del POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId    = $_SESSION['user_id'];
    
    // Recibimos los datos del FormData de JS
    $ejercicio = $_POST['ejercicio'] ?? null;
    $peso      = $_POST['peso'] ?? 0;
    $reps      = $_POST['reps'] ?? 0; // Capturamos 'reps' del POST...
    $serie     = $_POST['serie_num'] ?? 1;

    // Validación básica de campos
    if (empty($ejercicio)) {
        http_response_code(400);
        echo json_encode(['error' => 'El nombre del ejercicio es obligatorio.']);
        exit;
    }

    try {
        // ...pero insertamos en la columna 'repeticiones' que creamos en el SQL
        $sql = "INSERT INTO series_v2 (usuario_id, ejercicio, peso, repeticiones, serie_num, fecha) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $ejercicio, $peso, $reps, $serie]);
        
        // Respuesta de éxito para el fetch() de JS
        echo json_encode([
            'status' => 'success',
            'message' => 'Serie guardada correctamente',
            'data' => [
                'ejercicio' => $ejercicio,
                'peso' => $peso,
                'repeticiones' => $reps
            ]
        ]);

    } catch (PDOException $e) {
        // Si hay error en la BD (ej: columna mal escrita), lo atrapamos aquí
        http_response_code(500);
        echo json_encode([
            'error' => 'Error en la base de datos',
            'details' => $e->getMessage()
        ]);
    }
} else {
    // Si intentan entrar por GET al archivo
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}