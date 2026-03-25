<?php
session_start();
require_once __DIR__ . '/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO series (usuario_id, ejercicio, peso, repeticiones, serie_numero, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $_SESSION['user_id'], 
            $_POST['ejercicio'], 
            $_POST['peso'], 
            $_POST['reps'], 
            $_POST['serie_num']
        ]);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}