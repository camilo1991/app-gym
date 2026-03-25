<?php
session_start();
// Activa esto solo para ver el error real, luego bórralo
// ini_set('display_errors', 1); 

require_once 'config/db.php'; 

// Verificamos que el usuario esté logueado y lleguen los datos
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesión no iniciada']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId    = $_SESSION['user_id'];
    $ejercicio = $_POST['ejercicio'] ?? '';
    $peso      = $_POST['peso'] ?? 0;
    $reps      = $_POST['reps'] ?? 0;
    $serie     = $_POST['serie_num'] ?? 1;

    try {
        $stmt = $pdo->prepare("INSERT INTO series (usuario_id, ejercicio, peso, reps, serie_num, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$userId, $ejercicio, $peso, $reps, $serie]);
        
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}