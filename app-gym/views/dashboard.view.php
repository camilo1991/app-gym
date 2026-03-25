<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #2ea043; --text: #c9d1d9; --border: #30363d; }
        body { background: var(--bg); color: var(--text); font-family: -apple-system, sans-serif; }
        .nav-header { background: var(--card); border-bottom: 1px solid var(--border); padding: 15px 0; }
        .welcome-section { padding: 30px 0; }
        
        .menu-card { 
            background: var(--card); 
            border: 1px solid var(--border); 
            border-radius: 16px; 
            padding: 25px; 
            text-align: center; 
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text);
            display: block;
            margin-bottom: 20px;
        }
        .menu-card:hover { 
            border-color: var(--accent); 
            transform: translateY(-5px); 
            background: #1c2128;
        }
        .menu-card i { font-size: 2.5rem; color: var(--accent); margin-bottom: 15px; display: block; }
        .menu-card h3 { font-size: 1.2rem; font-weight: bold; margin-bottom: 5px; }
        .menu-card p { font-size: 0.85rem; color: #8b949e; margin-bottom: 0; }

        .btn-logout { color: #f85149; border-color: rgba(248, 81, 73, 0.4); }
        .btn-logout:hover { background: rgba(248, 81, 73, 0.1); border-color: #f85149; }

        .racha-card { background: rgba(46, 160, 67, 0.05); border: 1px dashed var(--accent); border-radius: 12px; }
        .stat-number { font-size: 2.5rem; font-weight: 800; color: var(--accent); line-height: 1; margin: 10px 0; }
    </style>
</head>
<body>

<header class="nav-header">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold text-success text-uppercase" style="letter-spacing: 1px;">Rodríguez <span class="text-white">Gym OS</span></span>
        <a href="logout.php" class="btn btn-sm btn-outline-danger btn-logout px-3">Salir</a>
    </div>
</header>

<div class="container welcome-section text-center">
    <h1 class="h4 mb-1">¡Hola, Andrey!</h1>
    <p class="text-secondary small">¿Qué vamos a romper hoy? 💪</p>
</div>

<div class="container">
    <div class="row g-3">
        <div class="col-6">
            <a href="index.php?action=entrenar" class="menu-card shadow-sm h-100">
                <i class="bi bi-play-circle-fill"></i>
                <h3>Entrenar</h3>
                <p>Iniciar rutina</p>
            </a>
        </div>
        
        <div class="col-6">
            <a href="index.php?action=historial" class="menu-card shadow-sm h-100">
                <i class="bi bi-bar-chart-fill"></i>
                <h3>Historial</h3>
                <p>Ver evolución</p>
            </a>
        </div>

        <div class="col-12 mt-3">
            <div class="racha-card p-4 text-center">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-calendar-check text-success"></i>
                    <span class="small fw-bold text-white text-uppercase">Consistencia Mensual</span>
                </div>
                
                <div class="stat-number"><?php echo $diasEntrenados ?? 0; ?></div>
                
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">
                    Días entrenados en **<?php echo date('F'); ?>**
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>