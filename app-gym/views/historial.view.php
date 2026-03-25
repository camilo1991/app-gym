<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #2ea043; --text: #c9d1d9; }
        body { background: var(--bg); color: var(--text); font-family: sans-serif; }
        .resumen-card { background: var(--card); border: 1px solid #30363d; border-radius: 12px; padding: 15px; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h4 class="text-success mb-4">Resumen de Entrenamiento</h4>
    
    <?php
    // Obtener los ejercicios registrados HOY
    $stmt = $pdo->prepare("SELECT ejercicio, MAX(peso) as max_hoy, COUNT(*) as total FROM series WHERE usuario_id = ? AND DATE(fecha) = CURDATE() GROUP BY ejercicio");
    $stmt->execute([$_SESSION['user_id']]);
    $hoy = $stmt->fetchAll();

    if ($hoy): 
        foreach($hoy as $ej): 
            // Buscar récord anterior
            $stmtP = $pdo->prepare("SELECT MAX(peso) as max_ant FROM series WHERE usuario_id = ? AND ejercicio = ? AND DATE(fecha) < CURDATE()");
            $stmtP->execute([$_SESSION['user_id'], $ej['ejercicio']]);
            $pasado = $stmtP->fetch();
            $diff = $pasado['max_ant'] ? ($ej['max_hoy'] - $pasado['max_ant']) : 0;
    ?>
        <div class="resumen-card">
            <div class="d-flex justify-content-between">
                <span class="fw-bold"><?php echo $ej['ejercicio']; ?></span>
                <span class="text-info"><?php echo $ej['max_hoy']; ?> LB</span>
            </div>
            <div class="small mt-1">
                <?php if($diff > 0): ?>
                    <span class="text-success">↑ +<?php echo $diff; ?> LB mejor que antes</span>
                <?php elseif($diff < 0): ?>
                    <span class="text-warning">↓ <?php echo abs($diff); ?> LB</span>
                <?php else: ?>
                    <span class="text-secondary">Mantuviste tu récord</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; else: ?>
        <p class="text-secondary text-center mt-5">No has registrado nada hoy.</p>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary w-100 mt-4">Volver al Inicio</a>
</div>
</body>
</html>