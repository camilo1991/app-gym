<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0d1117; color: white; padding: 20px; font-family: -apple-system, sans-serif; }
        .stat-card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .progress { height: 25px; background-color: #30363d; border-radius: 8px; }
        .progress-bar { background-color: #2ea043; font-weight: bold; }
        .btn-gym { background-color: #21262d; border: 1px solid #30363d; color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($usuario['nombre']); ?>&background=2ea043&color=fff" 
                 class="rounded-circle me-3 border border-success p-1" width="55" alt="Avatar">
            <div>
                <h2 class="mb-0 h4">Hola, <?php echo explode(' ', $usuario['nombre'])[0]; ?> 💪</h2>
                <small class="text-secondary"><?php echo date('l, d \d\e F'); ?></small>
            </div>
        </div>
        <a href="?action=logout" class="btn btn-outline-danger btn-sm">Salir</a>
    </div>

    <div class="alert alert-dark border-secondary py-3 mb-4">
        <span class="fst-italic small text-white-50"><?php echo $mensaje; ?></span>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="stat-card text-center">
                <small class="text-secondary d-block mb-1">Peso Actual</small>
                <h3 class="mb-0"><?php echo number_format($usuario['peso_actual'], 1); ?> <small class="h6 text-secondary">kg</small></h3>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-card text-center">
                <small class="text-secondary d-block mb-1">Meta</small>
                <h3 class="mb-0"><?php echo number_format($usuario['peso_ideal'], 1); ?> <small class="h6 text-secondary">kg</small></h3>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <h6 class="mb-3 text-secondary text-uppercase small">Progreso hacia tu meta</h6>
        <?php 
            $diferencia = abs($usuario['peso_actual'] - $usuario['peso_ideal']);
            $porcentaje = max(0, min(100, 100 - ($diferencia * 2)));
        ?>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%;">
                <?php echo round($porcentaje); ?>%
            </div>
        </div>
    </div>

    <div class="d-grid gap-3">
        <a href="?page=entrenar" class="btn btn-success p-3 fw-bold shadow">💪 INICIAR ENTRENAMIENTO</a>
        <a href="?page=historial" class="btn btn-gym p-2">📅 Ver Historial y Consistencia</a>
    </div>
</div>
</body>
</html>