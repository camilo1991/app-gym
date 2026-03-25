<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0d1117; color: white; padding: 20px; }
        .stat-card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .progress { height: 25px; background-color: #30363d; }
        .progress-bar { background-color: #2ea043; font-weight: bold; }
        .btn-gym { background-color: #21262d; border: 1px solid #30363d; color: white; }
        .btn-gym:hover { background-color: #30363d; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Hola, <?php echo htmlspecialchars($usuario['nombre']); ?> 🦾</h2>
        <a href="?action=logout" class="btn btn-outline-danger btn-sm">Salir</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="stat-card text-center">
                <small class="text-secondary">Peso Actual</small>
                <h3><?php echo $usuario['peso_actual']; ?> kg</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card text-center">
                <small class="text-secondary">Meta</small>
                <h3><?php echo $usuario['peso_ideal']; ?> kg</h3>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <h5 class="mb-3">Progreso hacia tu meta</h5>
        <?php 
            // Cálculo rápido de progreso
            $diferencia = abs($usuario['peso_actual'] - $usuario['peso_ideal']);
            $porcentaje = 100 - ($diferencia * 2); // Simulación lógica
            $porcentaje = max(0, min(100, $porcentaje));
        ?>
        <div class="progress mb-2">
            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%;">
                <?php echo round($porcentaje); ?>%
            </div>
        </div>
    </div>

    <div class="d-grid gap-2">
        <a href="?page=entrenar" class="btn btn-success p-3 fw-bold">💪 INICIAR ENTRENAMIENTO DE HOY</a>
        <a href="?page=historial" class="btn btn-gym p-2">📅 Ver Historial</a>
    </div>
</div>

</body>
</html>