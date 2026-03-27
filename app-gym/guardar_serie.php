<?php
// guardar_serie.php
session_start();
require_once 'config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Sesión expirada"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $ejercicio = $_POST['ejercicio'] ?? 'Ejercicio';
    $peso = $_POST['peso'] ?? 0; 
    $reps = $_POST['reps'] ?? 0; 

    try {
        $sql = "INSERT INTO series_v2 (usuario_id, ejercicio, peso, repeticiones, fecha) 
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $ejercicio, $peso, $reps]);
        
        echo json_encode(["status" => "ok"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error DB: " . $e->getMessage()]);
    }
}