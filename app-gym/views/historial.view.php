<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #2ea043; --border: #30363d; --text-sec: #8b949e; }
        body { background-color: var(--bg); color: white; font-family: -apple-system, sans-serif; padding: 20px; }
        
        /* Cards y Layout */
        .resumen-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-bottom: 20px; height: 100%; transition: transform 0.2s; }
        .resumen-card:hover { border-color: var(--accent); }

        /* Calendario GitHub Style */
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; max-width: 230px; }
        .day-box { width: 28px; height: 28px; border-radius: 4px; border: 1px solid var(--border); font-size: 10px; display: flex; align-items: center; justify-content: center; color: var(--text-sec); }
        .day-done { background-color: var(--accent); border: none; color: white; font-weight: bold; }
        
        /* Badges de Sugerencia */
        .sug-badge { font-size: 0.75rem; padding: 5px 12px; border-radius: 6px; display: inline-block; margin-top: 10px; }
        .sug-up { background: rgba(46, 160, 67, 0.1); color: #3fb950; border: 1px solid rgba(46, 160, 67, 0.3); }
        .sug-keep { background: rgba(210, 153, 34, 0.1); color: #d29922; border: 1px solid rgba(210, 153, 34, 0.3); }

        /* Badge de NUEVO RÉCORD (Dorado) */
        .badge-pr {
            background: linear-gradient(45deg, #d29922, #f5e050);
            color: #000;
            font-weight: 800;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            box-shadow: 0 0 10px rgba(210, 153, 34, 0.4);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% { opacity: 0.8; }
            50% { opacity: 1; transform: scale(1.05); }
            100% { opacity: 0.8; }
        }

        .btn-back { background-color: #21262d; border: 1px solid var(--border); color: white; text-decoration: none; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-white">Tu Rendimiento</h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">Volver</a>
    </div>

    <div class="row mb-2">
        <div class="col-md-5 mb-3">
            <div class="resumen-card">
                <h6 class="text-secondary small text-uppercase mb-3">Consistencia (28D)</h6>
                <div class="calendar-grid mb-3">
                    <?php 
                    for($i = 27; $i >= 0; $i--) {
                        $f = date('Y-m-d', strtotime("-$i days"));
                        $ok = in_array($f, $diasEntrenados);
                        echo '<div class="day-box '.($ok ? 'day-done' : '').'">'.date('j', strtotime($f)).'</div>';
                    }
                    ?>
                </div>
                <small class="text-secondary">✓ <?php echo count($diasEntrenados); ?> entrenamientos registrados.</small>
            </div>
        </div>

        <div class="col-md-7 mb-3">
            <div class="resumen-card">
                <h6 class="text-secondary small text-uppercase mb-3">Evolución de Volumen (LB)</h6>
                <canvas id="volumenChart" style="max-height: 160px;"></canvas>
            </div>
        </div>
    </div>

    <h5 class="text-white mb-3">Análisis de hoy: <span class="text-success"><?php echo date('d/m/Y'); ?></span></h5>
    
    <div class="row">
        <?php if (!empty($resumenHoy)): ?>
            <?php foreach ($resumenHoy as $ej): 
                $esNuevoRecord = ($ej['max_h'] > $ej['record_historico']);
            ?>
                <div class="col-12 mb-3">
                    <div class="resumen-card py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="text-success mb-0 fw-bold"><?php echo htmlspecialchars($ej['ejercicio']); ?></h6>
                                    <?php if ($esNuevoRecord): ?>
                                        <span class="badge-pr">✨ NUEVO RÉCORD</span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-secondary"><?php echo $ej['total']; ?> series hoy</small>
                                <br>
                                <span class="sug-badge <?php echo $esNuevoRecord ? 'sug-up' : 'sug-keep'; ?>">
                                    <?php echo $esNuevoRecord ? '🔥 ¡Nivel superior alcanzado!' : '💪 Mantente constante'; ?>
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="h3 d-block mb-0"><?php echo number_format($ej['max_h'], 1); ?> <small class="h6 text-secondary">LB</small></span>
                                <small class="text-muted small">Prev: <?php echo number_format($ej['record_historico'] ?? 0, 1); ?> LB</small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="resumen-card text-center py-5">
                    <p class="text-secondary">Aún no hay registros para hoy. ¡A darle!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-2 mb-5">
        <a href="index.php" class="btn btn-back w-100 p-3 fw-bold shadow-sm">Volver al Dashboard</a>
    </div>
</div>

<script>
    const ctx = document.getElementById('volumenChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo isset($labels) ? $labels : "['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4']"; ?>,
            datasets: [{
                data: <?php echo isset($valores) ? $valores : "[0, 0, 0, 0]"; ?>,
                borderColor: '#2ea043',
                backgroundColor: 'rgba(46, 160, 67, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#30363d' }, ticks: { color: '#8b949e', font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { color: '#8b949e', font: { size: 10 } } }
            }
        }
    });
</script>

</body>
</html>