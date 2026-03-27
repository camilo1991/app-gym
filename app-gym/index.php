<?php
// 1. Errores activos para desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Conexión centralizada
require_once 'config/db.php'; 

$fecha_hoy = date('Y-m-d');

// 3. LÓGICA DE GUARDADO (Punto funcional)
if (isset($_GET['action']) && $_GET['action'] == 'guardar_serie') {
    try {
        $ejercicio = $_POST['ejercicio'] ?? '';
        $tipo = $_POST['tipo_ejercicio'] ?? 'fuerza';

        if ($tipo == 'cardio') {
            $tiempo = $_POST['tiempo'] ?? 0;
            $velocidad = $_POST['velocidad'] ?? 0;
            $sql = "INSERT INTO entrenamiento (ejercicio, tiempo, velocidad, fecha, tipo) VALUES (?, ?, ?, ?, 'cardio')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ejercicio, $tiempo, $velocidad, $fecha_hoy]);
        } else {
            $peso = $_POST['peso'] ?? 0;
            $reps = $_POST['reps'] ?? 0;
            $sql = "INSERT INTO entrenamiento (ejercicio, peso, reps, fecha, tipo) VALUES (?, ?, ?, ?, 'fuerza')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ejercicio, $peso, $reps, $fecha_hoy]);
        }

        // Redirigir para limpiar el POST y activar el descanso de 90s
        header("Location: index.php?action=entrenar&rest=1");
        exit();

    } catch (Exception $e) {
        die("Error crítico al guardar: " . $e->getMessage());
    }
}

// 4. PREPARAR DATOS PARA LA VISTA (Evita los Warnings)
try {
    // Lista de ejercicios a mostrar
    $ejercicios_finales = $pdo->query("SELECT nombre FROM lista_ejercicios")->fetchAll(PDO::FETCH_COLUMN) ?: [];

    // Conteo de series hoy (Cambia 0/4 por 1/4, 2/4, etc.)
    $stmt_c = $pdo->prepare("SELECT ejercicio, COUNT(*) as total FROM entrenamiento WHERE fecha = ? GROUP BY ejercicio");
    $stmt_c->execute([$fecha_hoy]);
    $conteoSeries = $stmt_c->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

    // Historial anterior
    $ultimosPesos = [];
    foreach ($ejercicios_finales as $ej) {
        $stmt_h = $pdo->prepare("SELECT CONCAT(peso, ' Lb x ', reps) FROM entrenamiento WHERE ejercicio = ? ORDER BY id DESC LIMIT 1");
        $stmt_h->execute([$ej]);
        $ultimosPesos[$ej] = $stmt_h->fetchColumn() ?: '--';
    }
} catch (Exception $e) {
    // Si las tablas no existen, inicializamos vacíos para que no salga Warning
    $ejercicios_finales = [];
    $conteoSeries = [];
    $ultimosPesos = [];
}

include 'views/entrenar.view.php';