<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Crear Cuenta | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0d1117;
            --card-bg: #161b22;
            --border-color: #30363d;
            --accent-color: #2ea043;
        }
        
        body { 
            background-color: var(--bg-color); 
            color: #c9d1d9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .card-register {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.5);
        }

        h2 { color: #f0f6fc; font-weight: 600; text-align: center; margin-bottom: 25px; }

        label { font-size: 0.9rem; margin-bottom: 5px; color: #8b949e; }

        .form-control {
            background-color: #0d1117;
            border: 1px solid var(--border-color);
            color: white;
            padding: 12px;
            border-radius: 8px;
        }

        .form-control:focus {
            background-color: #0d1117;
            border-color: var(--accent-color);
            color: white;
            box-shadow: 0 0 0 3px rgba(46, 160, 67, 0.15);
        }

        .btn-submit {
            background-color: var(--accent-color);
            border: none;
            padding: 12px;
            font-weight: bold;
            border-radius: 8px;
            margin-top: 15px;
            transition: 0.2s;
        }

        .btn-submit:hover { background-color: #3fb950; transform: translateY(-1px); }

        .footer-link { text-align: center; margin-top: 20px; font-size: 0.9rem; }
        .footer-link a { color: #58a6ff; text-decoration: none; }
    </style>
</head>
<body>

<div class="card-register">
    <h2>Crear Cuenta 🖋️</h2>
    
    <form action="?action=register" method="POST">
        <div class="mb-3">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" class="form-control" placeholder="Ej: Andrey Peña" required>
        </div>

        <div class="mb-3">
            <label>Usuario (Nickname)</label>
            <input type="text" name="username" class="form-control" placeholder="andrey_sys" required>
        </div>

        <div class="row">
            <div class="col-6 mb-3">
                <label>Peso Actual (kg)</label>
                <input type="number" name="peso_actual" step="0.1" class="form-control" placeholder="85.0">
            </div>
            <div class="col-6 mb-3">
                <label>Peso Ideal (kg)</label>
                <input type="number" name="peso_ideal" step="0.1" class="form-control" placeholder="78.0">
            </div>
        </div>

        <div class="mb-4">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••••••" required>
        </div>

        <button type="submit" class="btn btn-success btn-submit w-100">REGISTRARME</button>
    </form>

    <div class="footer-link">
        <a href="index.php">¿Ya tienes cuenta? Logueate</a>
    </div>
</div>

</body>
</html>