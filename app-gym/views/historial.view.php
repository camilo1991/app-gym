<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial | Rodríguez Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #39d353; --border: #30363d; }
        body { background: var(--bg); color: #c9d1d9; font-family: sans-serif; padding: 15px; padding-bottom: 100px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 15px; padding: 15px; margin-bottom: 20px; }
        .day-group { border-left: 3px solid var(--accent); padding-left: 15px; margin-top: 25px; }
        .ex-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 12px; margin-bottom: 8px; }
        .search-box { background: var(--card); border: 1px solid var(--border); color: white; border-radius: 10px; padding: 12px; width: 100%; margin-bottom: 20px; }
        .btn-float { position: fixed; bottom: 20px; left: 20px; right: 20px; background: #238636; color: white; border-radius: 12px; padding: 15px; text-align: center; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h4 class="fw-bold text-white mb-3">Análisis de Carga</h4>

    <?php if (empty($registros)): ?>
        <div class="alert alert-info bg-dark text-info border-info">
            <i class="bi bi-info-circle me-2"></i> Aún no tienes series registradas. ¡A darle a los fierros!
        </div>
    <?php else: ?>
        <div class="stat-card">
            <h6 class="text-secondary small fw-bold text-uppercase mb-3">Volumen Total (LB)</h6>
            <canvas id="volChart" style="max-height: 180px;"></canvas>
        </div>

        <input type="text" id="gymSearch" class="search-box" placeholder="🔍 Filtrar ejercicio..." onkeyup="filtrar()">

        <div id="lista">
            <?php 
            $agrupado = [];
            foreach($registros as $r) { $agrupado[date('Y-m-d', strtotime($r['fecha']))][] = $r; }

            foreach($agrupado as $fecha => $series): 
                $volDia = 0;
                foreach($series as $s) $volDia += ($s['peso'] * $s['repeticiones']);
            ?>
                <div class="day-group">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white fw-bold"><?php echo date('d M', strtotime($fecha)); ?></span>
                        <span class="text-success small fw-bold"><?php echo number_format($volDia); ?> LB</span>
                    </div>
                    <?php foreach($series as $reg): ?>
                        <div class="ex-card d-flex justify-content-between align-items-center item-reg" data-ej="<?php echo strtolower($reg['ejercicio']); ?>">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-trash3 text-danger me-3" style="cursor:pointer;" onclick="borrar(<?php echo $reg['id']; ?>)"></i>
                                <div>
                                    <div class="text-white small fw-bold"><?php echo ucwords($reg['ejercicio']); ?></div>
                                    <div class="text-muted small"><?php echo date('h:i A', strtotime($reg['fecha'])); ?></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-success fw-bold"><?php echo (int)$reg['peso']; ?> LB</div>
                                <div class="text-secondary small">x <?php echo $reg['repeticiones']; ?> reps</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn-float">⬅️ VOLVER AL DASHBOARD</a>

    <script>
        <?php if (!empty($graficaData)): ?>
        const ctx = document.getElementById('volChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($graficaData, 'dia')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($graficaData, 'vol')); ?>,
                    borderColor: '#39d353',
                    backgroundColor: 'rgba(57, 211, 83, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { grid: { color: '#30363d' } } } }
        });
        <?php endif; ?>

        function filtrar() {
            const q = document.getElementById('gymSearch').value.toLowerCase();
            document.querySelectorAll('.item-reg').forEach(i => {
                i.style.display = i.getAttribute('data-ej').includes(q) ? 'flex' : 'none';
            });
        }

        function borrar(id) {
            if(confirm('¿Eliminar serie?')) {
                fetch('index.php?action=eliminar_serie&id=' + id)
                .then(r => r.json())
                .then(res => { if(res.success) location.reload(); });
            }
        }
    </script>
</body>
</html>