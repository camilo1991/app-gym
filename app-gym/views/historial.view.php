<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #2ea043; --text: #c9d1d9; --border: #30363d; }
        body { background-color: var(--bg); color: var(--text); padding: 20px; font-family: sans-serif; }
        .resumen-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 15px; margin-bottom: 15px; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; max-width: 210px; }
        .day-box { width: 25px; height: 25px; border-radius: 3px; border: 1px solid var(--border); font-size: 9px; display: flex; align-items: center; justify-content: center; }
        .day-done { background-color: var(--accent); border: none; color: white; }
        .sug-badge { font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; display: inline-block; margin-top: 8px; }
        .sug-up { background: rgba(46, 160, 67, 0.15); color: #3fb950; border: 1px solid rgba(46, 160, 67, 0.3); }
    </style>
</head>
<body>
<div class="container">
    <h6 class="text-secondary small text-uppercase">Consistencia (Últimos 28 días)</h6>
    <div class="resumen-card">
        <div class="calendar-grid">
            <?php 
            for($i = 27; $i >= 0; $i--) {
                $f = date('Y-m-d', strtotime("-$i days"));
                $ok = in_array($f, $diasEntrenados);
                echo '<div class="day-box '.($ok ? 'day-done' : '').'">'.date('j', strtotime($f)).'</div>';
            }
            ?>
        </div>
        <p class="small text-secondary mt-2 mb-0">✓ <?php echo count($diasEntrenados); ?> entrenamientos registrados.</p>
    </div>

    <h4 class="text-white mb-3">Rutina de Hoy</h4>
    <?php if (!empty($resumenHoy)): ?>
        <?php foreach ($resumenHoy as $ej): 
            $rec = $sugerencias[$ej['ejercicio']] ?? 0;
            $subir = ($ej['max_h'] >= $rec);
        ?>
            <div class="resumen-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-bold text-white d-block"><?php echo htmlspecialchars($ej['ejercicio']); ?></span>
                        <small class="text-secondary"><?php echo $ej['total']; ?> series completadas</small>
                        <br>
                        <span class="sug-badge <?php echo $subir ? 'sug-up' : 'bg-dark text-warning border border-warning'; ?>">
                            <?php echo $subir ? '↑ Sugerencia: Subir +5 LB' : '⚠ Sugerencia: Mantener peso'; ?>
                        </span>
                    </div>
                    <div class="text-end">
                        <span class="h4 d-block text-white mb-0"><?php echo $ej['max_h']; ?> <small class="h6 text-secondary">LB</small></span>
                        <small class="text-muted">Récord: <?php echo $rec; ?> LB</small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="resumen-card text-center py-4 text-secondary">No hay registros hoy.</div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-outline-secondary w-100">Volver al Dashboard</a>
</div>
</body>
</html>