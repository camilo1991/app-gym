<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rodr¨ªguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0d1117; color: #c9d1d9; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif; padding: 20px; }
        .stat-card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 24px; transition: 0.3s; }
        .stat-card:hover { border-color: #8b949e; }
        .weight-val { font-size: 2.5rem; font-weight: 800; color: #f0f6fc; margin: 0; }
        .weight-unit { font-size: 1rem; color: #8b949e; }
        .progress { height: 12px; background-color: #30363d; border-radius: 20px; overflow: hidden; }
        .progress-bar { background: linear-gradient(90deg, #238636 0%, #2ea043 100%); }
        .btn-main { background-color: #238636; border: 1px solid rgba(240, 246, 252, 0.1); color: #ffffff; padding: 15px; font-weight: 600; border-radius: 8px; width: 100%; transition: 0.2s; }
        .btn-main:hover { background-color: #2ea043; color: #fff; }
        .btn-secondary-gym { background-color: #21262d; border: 1px solid #30363d; color: #c9d1d9; padding: 12px; border-radius: 8px; width: 100%; text-decoration: none; display: block; text-align: center; }
        .btn-secondary-gym:hover { background-color: #30363d; color: #f0f6fc; }
        .logout-link { color: #f85149; text-decoration: none; font-size: 0.9rem; font-weight: 600; border: 1px solid #f85149; padding: 5px 15px; border-radius: 6px; transition: 0.2s; }
        .logout-link:hover { background: #f85149; color: white; }
    </style>
</head>
<body>
<div class="container" style="max-width: 500px;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; font-weight: bold; color: white;">
                <?php echo substr($usuario['nombre'], 0, 2); ?>
            </div>
            <div>
                <h1 class="h5 mb-0 text-white">Hola, <?php echo explode(' ', $usuario['nombre'])[0]; ?></h1>
                <small class="text-secondary"><?php echo date('l, d M'); ?></small>
            </div>
        </div>
        <a href="?action=logout" class="logout-link">Salir</a>
    </div>

    <div class="alert shadow-sm" style="background: #161b22; border: 1px solid #30363d; color: #8b949e;">
        <i class="fst-italic">"<?php echo $mensaje; ?>"</i>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="stat-card text-center">
                <small class="text-secondary text-uppercase fw-bold">Actual</small>
                <p class="weight-val"><?php echo number_format($usuario['peso_actual'], 1); ?><span class="weight-unit">kg</span></p>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-card text-center">
                <small class="text-secondary text-uppercase fw-bold">Meta</small>
                <p class="weight-val text-info"><?php echo number_format($usuario['peso_ideal'], 1); ?><span class="weight-unit">kg</span></p>
            </div>
        </div>
    </div>

    <div class="stat-card mb-5">
        <div class="d-flex justify-content-between mb-2">
            <small class="text-secondary fw-bold">Progreso a los <?php echo $usuario['peso_ideal']; ?>kg</small>
            <small class="text-success fw-bold"><?php echo round($porcentaje); ?>%</small>
        </div>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%"></div>
        </div>
    </div>

    <div class="d-grid gap-3">
        <a href="?page=entrenar" class="btn btn-main">INICIAR ENTRENAMIENTO</a>
        <a href="?page=historial" class="btn btn-secondary-gym">Ver Historial y Consistencia</a>
    </div>
</div>
</body>
</html>