<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Entrenar | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #2ea043; --text: #c9d1d9; --border: #30363d; }
        body { background: var(--bg); color: var(--text); padding-bottom: 50px; font-family: -apple-system, sans-serif; }
        
        /* Cronómetro */
        .timer-box { background: var(--card); border: 1px solid var(--border); border-radius: 15px; padding: 20px; text-align: center; margin-bottom: 20px; }
        .timer-display { font-size: 3rem; font-weight: bold; color: var(--accent); font-family: monospace; text-shadow: 0 0 10px rgba(46, 160, 67, 0.3); }
        
        /* Lista de Ejercicios */
        .exercise-item { border-left: 4px solid var(--accent); padding: 15px; margin-bottom: 12px; background: var(--card); border-radius: 0 12px 12px 0; border-top: 1px solid var(--border); border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        
        /* Inputs Editables */
        .input-editable { background: transparent; border: none; color: #fff; font-weight: bold; font-size: 1.1rem; width: 85%; padding: 2px 5px; border-radius: 4px; }
        .input-editable:focus { background: #0d1117; outline: 1px solid var(--accent); }
        .edit-hint { font-size: 0.7rem; color: #8b949e; display: block; margin-top: -2px; }
        
        /* Badge de Carga Previa */
        .prev-load-badge { font-size: 0.75rem; color: #8b949e; background: rgba(255,255,255,0.05); padding: 2px 8px; border-radius: 4px; border: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="container mt-3">
    <div class="timer-box shadow-sm">
        <div class="timer-display" id="display">00:00:00</div>
        <div class="btn-group mt-2 w-100">
            <button class="btn btn-outline-success border-secondary text-white" id="startStop">Iniciar</button>
            <button class="btn btn-outline-secondary border-secondary text-white" id="reset">Reset</button>
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between mb-1">
            <small class="text-secondary small text-uppercase fw-bold">Progreso de hoy</small>
            <small class="text-success fw-bold" id="progreso-texto">0%</small>
        </div>
        <div class="progress" style="height: 8px; background: #30363d;">
            <div id="progreso-barra" class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
        </div>
    </div>

    <?php
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $dia_actual = $dias[date('w')];
        $rutinas = [
            'Lunes' => ['Pecho', 'Tríceps', 'Press Banca', 'Aperturas', 'Extensión Polea'],
            'Martes' => ['Espalda', 'Bíceps', 'Peso Muerto', 'Remo con Barra', 'Curl Martillo'],
            'Miércoles' => ['Pierna', 'Hombro', 'Sentadilla', 'Prensa', 'Press Militar'],
            'Jueves' => ['Pecho', 'Tríceps', 'Press Inclinado', 'Fondos', 'Copa'],
            'Viernes' => ['Espalda', 'Bíceps', 'Dominadas', 'Remo Gironda', 'Curl Concentrado'],
            'Sábado' => ['Pierna', 'Hombro', 'Zancadas', 'Elevaciones Laterales', 'Facepull']
        ];
        $hoy = $rutinas[$dia_actual] ?? null;
    ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Rutina de Hoy</h4>
        <span class="badge bg-success px-3 py-2"><?php echo $dia_actual; ?></span>
    </div>

    <?php if ($hoy): ?>
        <div class="mb-4">
            <h5 class="text-success small text-uppercase fw-bold mb-3"><?php echo $hoy[0] . " & " . $hoy[1]; ?></h5>
            
            <?php for($i=2; $i < count($hoy); $i++): 
                $nombreEj = $hoy[$i];
                // Lógica de carga previa
                $ultimoPeso = isset($ultimosPesos[$nombreEj]) ? $ultimosPesos[$nombreEj] . " LB" : "---";
            ?>
                <div class="exercise-item d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <input type="text" class="input-editable" value="<?php echo $nombreEj; ?>" id="name-<?php echo $i; ?>">
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="prev-load-badge">Última vez: <b class="text-success"><?php echo $ultimoPeso; ?></b></span>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-registrar fw-bold shadow-sm" 
                            data-index="<?php echo $i; ?>" 
                            onclick="abrirModal(document.getElementById('name-<?php echo $i; ?>').value, <?php echo $i; ?>)">
                        Registrar
                    </button>
                </div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-dark w-100 p-3 border-secondary mb-4">Volver al Dashboard</a>
</div>

<div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-white shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-success" id="titEj"></h5>
            </div>
            <form id="formSerie">
                <div class="modal-body text-center">
                    <div class="badge bg-success mb-3 p-2" id="contS">SERIE 1 DE 4</div>
                    <input type="hidden" id="ejHid">
                    <input type="hidden" id="idxHid">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="small text-secondary mb-1">LB (Peso)</label>
                            <input type="number" step="0.5" class="form-control bg-black text-white border-secondary p-3 text-center h4" id="p" placeholder="0.0" required>
                        </div>
                        <div class="col-6">
                            <label class="small text-secondary mb-1">REPS (Repeticiones)</label>
                            <input type="number" class="form-control bg-black text-white border-secondary p-3 text-center h4" id="r" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="submit" class="btn btn-success w-100 p-3 fw-bold">GUARDAR SERIE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let seg = {};
    let startTime, timerInterval, elapsedTime = 0;

    function abrirModal(nombre, index) {
        if (!seg[index]) seg[index] = 0;
        document.getElementById('titEj').innerText = nombre;
        document.getElementById('ejHid').value = nombre;
        document.getElementById('idxHid').value = index;
        document.getElementById('contS').innerText = `SERIE ${seg[index] + 1} DE 4`;
        new bootstrap.Modal(document.getElementById('modalRegistro')).show();
    }

    document.getElementById('formSerie').onsubmit = function(event) {
        event.preventDefault();
        const e = document.getElementById('ejHid').value;
        const idx = document.getElementById('idxHid').value;
        const p = document.getElementById('p').value;
        const r = document.getElementById('r').value;
        seg[idx]++;

        const formData = new FormData();
        formData.append('ejercicio', e);
        formData.append('peso', p);
        formData.append('reps', r);
        formData.append('serie_num', seg[idx]);

        fetch('guardar_serie.php', { method: 'POST', body: formData })
        .then(() => {
            if (seg[idx] >= 4) {
                bootstrap.Modal.getInstance(document.getElementById('modalRegistro')).hide();
                document.querySelectorAll('.btn-registrar').forEach(b => {
                    if(b.getAttribute('data-index') === idx) {
                        b.innerText = '✅ OK';
                        b.className = 'btn btn-success disabled';
                    }
                });
                actualizarBarra();
            } else {
                document.getElementById('contS').innerText = `SERIE ${seg[idx] + 1} DE 4`;
                this.reset();
            }
        });
    };

    function actualizarBarra() {
        const total = document.querySelectorAll('.btn-registrar').length;
        const hechos = document.querySelectorAll('.btn-registrar.disabled').length;
        const porcentaje = Math.round((hechos / total) * 100);
        
        document.getElementById('progreso-barra').style.width = porcentaje + '%';
        document.getElementById('progreso-texto').innerText = porcentaje + '%';

        if (hechos === total) {
            setTimeout(() => {
                alert("¡BRUTAL! Rutina terminada. Revisa tus PRs en el historial.");
                window.location.href = "index.php?page=historial";
            }, 600);
        }
    }

    // Cronómetro funcional
    document.getElementById('startStop').onclick = function() {
        if (this.innerText === "Iniciar") {
            startTime = Date.now() - elapsedTime;
            timerInterval = setInterval(() => {
                elapsedTime = Date.now() - startTime;
                let time = new Date(elapsedTime);
                document.getElementById('display').innerText = time.toISOString().substr(11, 8);
            }, 1000);
            this.innerText = "Pausar";
            this.classList.replace('btn-outline-success', 'btn-warning');
        } else {
            clearInterval(timerInterval);
            this.innerText = "Iniciar";
            this.classList.replace('btn-warning', 'btn-outline-success');
        }
    };

    document.getElementById('reset').onclick = function() {
        clearInterval(timerInterval);
        elapsedTime = 0;
        document.getElementById('display').innerText = "00:00:00";
        document.getElementById('startStop').innerText = "Iniciar";
    };
</script>
</body>
</html>