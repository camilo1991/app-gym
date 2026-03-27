<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rodríguez Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #39d353; --border: #30363d; }
        body { background: var(--bg); color: #c9d1d9; padding: 15px; font-family: sans-serif; }
        .stats-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 15px; margin-bottom: 20px; }
        .cal-grid { display: grid; grid-template-columns: repeat(10, 1fr); gap: 4px; margin-top: 10px; }
        .cal-dot { aspect-ratio: 1/1; border-radius: 2px; background: #21262d; }
        .cal-dot.active { background: var(--accent); box-shadow: 0 0 5px var(--accent); }
        .rec-item { background: #0d1117; border-radius: 8px; padding: 12px; margin-bottom: 10px; border: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h3 class="text-white fw-bold mb-0">¡Hola, Camilo!</h3>
        <a href="logout.php" class="text-danger"><i class="bi bi-power fs-4"></i></a>
    </div>

    <div class="stats-card text-center">
        <span class="text-secondary small fw-bold text-uppercase">Consistencia (30 días)</span>
        <div class="cal-grid">
            <?php 
            for($i=29; $i>=0; $i--): 
                $dia_check = date('Y-m-d', strtotime("-$i days"));
                $is_active = in_array($dia_check, $fechasEntrenadas);
            ?>
                <div class="cal-dot <?php echo $is_active ? 'active' : ''; ?>"></div>
            <?php endfor; ?>
        </div>
        <div class="mt-3 text-white h2 fw-bold"><i class="bi bi-fire text-danger"></i> <?php echo $racha; ?> Días</div>
    </div>

    <h6 class="text-secondary fw-bold mb-3">HALL OF FAME (PRs)</h6>
    <?php foreach($topRecords as $r): ?>
        <div class="rec-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-white fw-bold text-uppercase small"><?php echo $r['ejercicio']; ?></div>
                    <div class="text-secondary" style="font-size: 0.75rem;">
                        <i class="bi bi-calendar-event"></i> <?php echo $r['fecha_formateada']; ?>
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-success fw-bold h5 mb-0"><?php echo $r['max_p']; ?> <small>LB</small></div>
                    <div class="text-secondary small"><?php echo $r['reps']; ?> Reps</div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="d-grid gap-3 mt-4 mb-5">
        <a href="index.php?action=entrenar" class="btn btn-success p-3 fw-bold fs-5 shadow">EMPEZAR HOY</a>
        <a href="index.php?action=historial" class="btn btn-outline-secondary p-3 fw-bold">VER TODO EL HISTORIAL</a>
    </div>
</div>
</body>
</html>