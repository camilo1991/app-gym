<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Slim | Rodríguez Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --royal: #4169e1; --bg: #0a0c10; --card: #12151c; }
        body { background: var(--bg); color: #f0f2f5; font-family: 'Segoe UI', sans-serif; font-size: 0.85rem; }
        .container-slim { max-width: 600px; margin: 0 auto; padding: 10px; }
        .accordion-item { background: var(--card); border: 1px solid #232834; margin-bottom: 5px; border-radius: 8px !important; overflow: hidden; }
        .accordion-button { background: var(--card); color: var(--royal); font-weight: 800; padding: 10px 15px; font-size: 0.75rem; box-shadow: none !important; }
        .accordion-button:not(.collapsed) { background: #1c212c; color: #fff; border-bottom: 1px solid #232834; }
        .accordion-button::after { filter: invert(1) scale(0.7); }
        .log-row { display: flex; justify-content: space-between; padding: 8px 15px; border-bottom: 1px solid rgba(255,255,255,0.03); font-size: 0.75rem; }
        .ex-name { color: #ffffff; font-weight: 500; }
        .badge-data { color: #2ecc71; font-weight: bold; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container-slim">
        <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
            <h6 class="fw-bold m-0 text-white">HISTORIAL</h6>
            <a href="index.php" class="btn btn-sm btn-outline-secondary" style="font-size: 0.65rem;">VOLVER</a>
        </div>
        <div class="accordion" id="histAcordeon">
            <?php $i = 0; foreach ($historialAgrupado as $dia => $ejercicios): $id = "coll".$i; ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?php echo ($i>0)?'collapsed':''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $id; ?>">
                            📅 <?php echo strtoupper(date('d M, Y', strtotime($dia))); ?>
                        </button>
                    </h2>
                    <div id="<?php echo $id; ?>" class="accordion-collapse collapse <?php echo ($i===0)?'show' : ''; ?>" data-bs-parent="#histAcordeon">
                        <div class="accordion-body p-0">
                            <?php foreach ($ejercicios as $log): ?>
                                <div class="log-row">
                                    <span class="ex-name"><?php echo htmlspecialchars($log['ejercicio'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="badge-data">
                                        <?php echo ($log['tipo_ejercicio']=='cardio') ? $log['tiempo_minutos'].'m' : (int)$log['peso'].'lb x '.$log['repeticiones']; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php $i++; endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>