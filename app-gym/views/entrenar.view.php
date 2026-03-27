<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Entrenar | Rodríguez Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #2ea043; --border: #30363d; }
        body { background: var(--bg); color: #c9d1d9; padding: 15px; font-family: sans-serif; }
        .ex-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 15px; margin-bottom: 12px; }
        .completed { border-left: 5px solid var(--accent); opacity: 0.8; }
        .pending { border-left: 5px solid #8b949e; }
        .serie-dot { font-size: 0.75rem; background: #30363d; color: #8b949e; padding: 2px 8px; border-radius: 10px; margin-left: 8px; }
        .btn-log { width: 55px; height: 45px; border-radius: 10px; border: none; font-weight: bold; }
        .btn-log-pending { background: #0052cc; color: white; }
        .btn-log-done { background: var(--accent); color: white; }
        .detail-box { font-size: 0.8rem; color: #2ea043; border-top: 1px solid var(--border); margin-top: 10px; padding-top: 8px; display: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h4 class="text-white fw-bold mb-0"><?php echo $titulo_hoy; ?></h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">Inicio</a>
    </div>

    <div id="lista-ejercicios">
        <?php foreach($ejercicios_hoy as $ej): 
            $count = $progreso[$ej]['total'] ?? 0;
            $detalles = $progreso[$ej]['detalle'] ?? '';
            $isDone = ($count >= 4);
        ?>
        <div class="ex-card <?php echo $isDone ? 'completed' : 'pending'; ?>">
            <div class="d-flex justify-content-between align-items-center">
                <div onclick="toggleDetail(this)" style="flex-grow: 1; cursor: pointer;">
                    <h6 class="mb-0 text-white fw-bold"><?php echo $ej; ?> <span class="serie-dot"><?php echo $count; ?>/4</span></h6>
                    <small class="text-secondary small">Meta: 4 series</small>
                </div>
                <button class="btn-log <?php echo $isDone ? 'btn-log-done' : 'btn-log-pending'; ?>" onclick="abrirModal('<?php echo $ej; ?>', '<?php echo $ultimosPesos[$ej]; ?>')">
                    <?php echo $isDone ? '<i class="bi bi-check-lg"></i>' : 'LOG'; ?>
                </button>
            </div>
            <div class="detail-box"><?php echo $detalles ?: 'Iniciando entrenamiento...'; ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <button class="btn btn-outline-warning w-100 p-3 my-4 fw-bold" onclick="ejercicioExtra()">+ EJERCICIO EXTRA</button>

    <div class="modal fade" id="modalReg" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-secondary">
                <div class="modal-header border-secondary"><h5 class="modal-title text-success fw-bold" id="mTit"></h5></div>
                <form id="fReg">
                    <div class="modal-body">
                        <input type="hidden" id="mEj">
                        <div class="row g-3">
                            <div class="col-6"><label class="small text-secondary">PESO (LB)</label><input type="number" step="0.5" class="form-control bg-black text-white text-center" id="mP" required></div>
                            <div class="col-6"><label class="small text-secondary">REPS</label><input type="number" class="form-control bg-black text-white text-center" id="mR" required></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 p-3 fw-bold">GUARDAR SERIE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalUI = new bootstrap.Modal(document.getElementById('modalReg'));
    function toggleDetail(el) { const box = el.parentElement.nextElementSibling; box.style.display = (box.style.display === 'block') ? 'none' : 'block'; }
    function abrirModal(ej, ultimoPeso) { document.getElementById('mTit').innerText = ej; document.getElementById('mEj').value = ej; document.getElementById('mP').value = ultimoPeso > 0 ? ultimoPeso : ""; modalUI.show(); }
    function ejercicioExtra() { let n = prompt("¿Ejercicio extra?"); if (n) abrirModal(n, 0); }

    document.getElementById('fReg').onsubmit = function(e) {
        e.preventDefault();
        const fd = new FormData();
        fd.append('ejercicio', document.getElementById('mEj').value);
        fd.append('peso', document.getElementById('mP').value);
        fd.append('reps', document.getElementById('mR').value);
        fetch('guardar_serie.php', { method: 'POST', body: fd }).then(() => location.reload());
    };
</script>
</body>
</html>