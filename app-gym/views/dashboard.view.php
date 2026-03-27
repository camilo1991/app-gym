<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rodríguez Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --royal-blue: #4169e1;
            --dark-bg: #0d1117;
            --card-bg: #161b22;
        }
        body { background: var(--dark-bg); color: #c9d1d9; font-family: sans-serif; }
        .card-custom { 
            background: var(--card-bg); 
            border: 1px solid #30363d; 
            border-radius: 15px; 
            padding: 20px;
            margin-bottom: 20px;
        }
        .stat-val { color: var(--royal-blue); font-size: 2.5rem; font-weight: 800; }
        .btn-main { 
            background: var(--royal-blue); 
            color: white; 
            border: none; 
            padding: 15px; 
            border-radius: 12px; 
            font-weight: bold; 
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .record-item { border-left: 4px solid var(--royal-blue); background: #21262d; padding: 10px; margin-bottom: 10px; border-radius: 0 8px 8px 0; }
    </style>
</head>
<body>
    <div class="container py-4">
        <h2 class="fw-bold text-white mb-4">¡Hola, <?php echo $_SESSION['user_name'] ?? 'Camilo'; ?>!</h2>

        <div class="row">
            <div class="col-6">
                <div class="card-custom text-center">
                    <div class="stat-val"><?php echo $racha; ?></div>
                    <div class="small text-uppercase opacity-50">Días Seguidos</div>
                </div>
            </div>
            
            <div class="col-12 mb-4">
                <a href="index.php?action=entrenar" class="btn-main">EMPEZAR ENTRENAMIENTO DE HOY</a>
            </div>
        </div>

        <div class="card-custom">
            <h6 class="fw-bold mb-3">Volumen Semanal</h6>
            <canvas id="volumenChart" height="150"></canvas>
        </div>

        <div class="card-custom">
            <h6 class="fw-bold mb-3">Hall of Fame (PRs)</h6>
            <?php foreach($topRecords as $top): ?>
                <div class="record-item d-flex justify-content-between">
                    <div>
                        <div class="fw-bold text-white"><?php echo $top['ejercicio']; ?></div>
                        <small class="text-muted"><?php echo $top['fecha_formateada']; ?></small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="color: var(--royal-blue);"><?php echo (int)$top['max_p']; ?> LB</div>
                        <small class="text-muted"><?php echo $top['reps']; ?> reps</small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="index.php?action=historial" class="text-secondary d-block text-center mt-3 text-decoration-none">Ver historial completo</a>
    </div>

    <script>
        const ctx = document.getElementById('volumenChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($datosGrafica, 'dia')); ?>,
                datasets: [{
                    label: 'Volumen',
                    data: <?php echo json_encode(array_column($datosGrafica, 'volumen')); ?>,
                    borderColor: '#4169e1',
                    backgroundColor: 'rgba(65, 105, 225, 0.2)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#4169e1'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: { grid: { display: false }, ticks: { color: '#8b949e' } }
                }
            }
        });
    </script>
</body>
</html>