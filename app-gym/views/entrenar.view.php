<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrenar | Rodríguez Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --royal: #4169e1; --cardio: #ff4757; --success: #2ecc71; --bg: #0d1117; --card: #161b22; }
        body { background: var(--bg); color: #c9d1d9; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif; padding-bottom: 90px; }
        
        .container-gym { max-width: 500px; margin: 0 auto; padding: 10px; }
        
        /* Listado de Ejercicios Slim */
        .card-gym { 
            background: var(--card); 
            border: 1px solid #30363d; 
            border-radius: 8px; 
            padding: 10px; 
            margin-bottom: 8px; 
            border-left: 4px solid var(--royal);
        }
        .is-complete { border-left-color: var(--success) !important; opacity: 0.8; }
        .is-cardio { border-left-color: var(--cardio) !important; }

        .exercise-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
        .exercise-title { font-size: 0.85rem; font-weight: 700; color: #f0f6fc; margin: 0; }
        .prev-data { font-size: 0.7rem; color: #8b949e; }
        .badge-series { font-size: 0.65rem; padding: 2px 8px; background: #21262d; border: 1px solid #30363d; color: #8b949e; border-radius: 12px; font-weight: bold; }
        .completado .badge-series { background: rgba(46, 204, 113, 0.1); color: var(--success); border-color: var(--success); }

        /* Grid de Inputs */
        .row-inputs { display: grid; grid-template-columns: 1fr 1fr 80px; gap: 8px; align-items: end; }
        .input-group-custom { display: flex; flex-direction: column; }
        .label-mini { font-size: 0.6rem; color: #8b949e; text-transform: uppercase; margin-bottom: 3px; font-weight: 800; letter-spacing: 0.5px; }
        
        .form-control-gym { 
            background: #0d1117 !important; 
            border: 1px solid #30363d !important; 
            color: #f0f6fc !important; 
            height: 34px; 
            font-size: 0.85rem; 
            border-radius: 6px;
            text-align: center;
        }

        .btn-save { 
            height: 34px; border-radius: 6px; border: none; font-size: 0.7rem; font-weight: 800; color: white; background: var(--royal);
        }
        .btn-save-cardio { background: var(--cardio); }

        /* Botón Flotante + Timer */
        .btn-float { 
            position: fixed; bottom: 20px; right: 20px; width: 56px; height: 56px; 
            border-radius: 50%; background: var(--success); color: #0d1117; 
            border: none; font-size: 28px; z-index: 1000; box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center;
        }
        #restTimerOverlay { position: fixed; bottom: 85px; left: 50%; transform: translateX(-50%); background: #1c212c; border: 2px solid var(--royal); color: white; padding: 10px 20px; border-radius: 50px; display: none; z-index: 999; }

        /* Estilos del Modal (Tarjeta Flotante) */
        .modal-content-custom { background: #161b22; border: 1px solid #30363d; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.6); }
        .modal-header-custom { border-bottom: 1px solid #30363d; padding: 15px; text-align: center; }
        .modal-title-custom { font-size: 0.9rem; font-weight: 800; color: #f0f6fc; margin: 0; text-transform: uppercase; }
        .form-select-custom, .form-control-modal { background: #0d1117 !important; border: 1px solid #30363d !important; color: #f0f6fc !important; border-radius: 6px; height: 40px; font-size: 0.9rem; padding-left: 10px; }
        .btn-registrar-modal { background: var(--success); color: #0d1117; font-weight: 800; text-transform: uppercase; border: none; padding: 12px; border-radius: 8px; width: 100%; margin-top: 10px; }
    </style>
</head>
<body>

    <button class="btn-float" data-bs-toggle="modal" data-bs-target="#modalNuevoEjercicio">+</button>

    <div id="restTimerOverlay">
        <span id="timerSeconds" class="fw-bold" style="font-size: 1.2rem;">60</span><small>s DESCANSANDO</small>
        <button onclick="closeTimer()" class="btn btn-sm btn-danger ms-2" style="font-size: 0.7rem;">OK</button>
    </div>

    <div class="container-gym">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold m-0" style="letter-spacing: 1px; color: #f0f6fc;">RUTINA DE HOY</h6>
            <a href="index.php" class="btn btn-sm btn-outline-secondary" style="font-size: 0.65rem; border-color: #30363d;">DASHBOARD</a>
        </div>

        <?php foreach ($ejercicios_finales as $ej): 
            $series = $conteoSeries[$ej] ?? 0;
            $ej_limpio = htmlspecialchars($ej, ENT_QUOTES, 'UTF-8');
            $esCardio = preg_match('/(escaladora|caminadora|bici|elip|trote|cardio)/i', $ej_limpio);
            $objetivo = $esCardio ? 1 : 4;
            $completado = ($series >= $objetivo);
        ?>
            <div class="card-gym <?php echo ($completado ? 'is-complete completado' : '') . ($esCardio ? ' is-cardio' : ''); ?>">
                <div class="exercise-header">
                    <div>
                        <h2 class="exercise-title"><?php echo strtoupper($ej_limpio); ?></h2>
                        <span class="prev-data">Anterior: <?php echo $ultimosPesos[$ej]; ?></span>
                    </div>
                    <span class="badge-series"><?php echo $series . "/" . $objetivo; ?></span>
                </div>

                <form action="index.php?action=guardar_serie" method="POST">
                    <input type="hidden" name="ejercicio" value="<?php echo $ej_limpio; ?>">
                    <input type="hidden" name="tipo_ejercicio" value="<?php echo $esCardio ? 'cardio' : 'fuerza'; ?>">
                    
                    <div class="row-inputs">
                        <?php if($esCardio): ?>
                            <div class="input-group-custom">
                                <label class="label-mini">Minutos</label>
                                <input type="number" name="tiempo" class="form-control-gym" required placeholder="0">
                            </div>
                            <div class="input-group-custom">
                                <label class="label-mini">Km/h</label>
                                <input type="number" step="0.1" name="velocidad" class="form-control-gym" placeholder="0.0">
                            </div>
                            <button type="submit" class="btn-save btn-save-cardio">OK</button>
                        <?php else: ?>
                            <div class="input-group-custom">
                                <label class="label-mini">Libras</label>
                                <input type="number" step="0.1" name="peso" class="form-control-gym" required placeholder="0">
                            </div>
                            <div class="input-group-custom">
                                <label class="label-mini">Reps</label>
                                <input type="number" name="reps" class="form-control-gym" required placeholder="0">
                            </div>
                            <button type="submit" class="btn-save">SAVE</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="modal fade" id="modalNuevoEjercicio" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 360px; margin: auto;">
            <div class="modal-content modal-content-custom">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">Añadir Ejercicio Extra</h5>
                </div>
                <form action="index.php?action=guardar_serie" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="label-mini">Nombre del Ejercicio</label>
                            <input type="text" name="ejercicio" id="inputNombreExtra" class="form-control form-control-modal w-100" required placeholder="Ej: Flexión de Pecho">
                        </div>
                        
                        <div class="mb-3">
                            <label class="label-mini">Tipo</label>
                            <select name="tipo_ejercicio" class="form-select form-select-custom w-100" id="tipoSelectModal">
                                <option value="fuerza">Fuerza (Pesas)</option>
                                <option value="cardio">Cardio (Máquinas)</option>
                            </select>
                        </div>

                        <div id="modalCamposFuerza" class="row gx-2">
                            <div class="col-6"><label class="label-mini">Libras</label><input type="number" step="0.1" name="peso" class="form-control form-control-modal w-100" placeholder="0"></div>
                            <div class="col-6"><label class="label-mini">Reps</label><input type="number" name="reps" class="form-control form-control-modal w-100" placeholder="0"></div>
                        </div>

                        <div id="modalCamposCardio" class="row gx-2 d-none">
                            <div class="col-6"><label class="label-mini">Minutos</label><input type="number" name="tiempo" class="form-control form-control-modal w-100" placeholder="0"></div>
                            <div class="col-6"><label class="label-mini">Km/h</label><input type="number" step="0.1" name="velocidad" class="form-control form-control-modal w-100" placeholder="0.0"></div>
                        </div>
                    </div>
                    <div class="p-3 pt-0">
                        <button type="submit" class="btn-registrar-modal">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bell = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        
        // Cambio dinámico de campos en modal
        document.getElementById('tipoSelectModal').addEventListener('change', function() {
            const isCardio = this.value === 'cardio';
            document.getElementById('modalCamposCardio').classList.toggle('d-none', !isCardio);
            document.getElementById('modalCamposFuerza').classList.toggle('d-none', isCardio);
        });

        // Foco automático
        document.getElementById('modalNuevoEjercicio').addEventListener('shown.bs.modal', () => {
            document.getElementById('inputNombreExtra').focus();
        });

        // Timer de descanso
        window.onload = function() {
            if (new URLSearchParams(window.location.search).has('rest')) { startTimer(); }
        };

        function startTimer() { 
            let timeLeft = 60;
            document.getElementById('restTimerOverlay').style.display = 'block'; 
            const timerInterval = setInterval(() => { 
                timeLeft--; 
                document.getElementById('timerSeconds').innerText = timeLeft; 
                if (timeLeft <= 0) { 
                    clearInterval(timerInterval); 
                    bell.play();
                    if (window.navigator.vibrate) window.navigator.vibrate([200, 100, 200]);
                } 
            }, 1000); 
        }
        function closeTimer() { document.getElementById('restTimerOverlay').style.display = 'none'; }
    </script>
</body>
</html>