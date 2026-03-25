<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Rodríguez Gym OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0d1117; color: white; display: flex; align-items: center; height: 100vh; }
        .card-login { background: #161b22; border: 1px solid #30363d; padding: 2rem; border-radius: 15px; width: 100%; max-width: 400px; margin: auto; }
    </style>
</head>
<body>
    <div class="card-login">
        <h3 class="text-center mb-4">Gym OS Login 🦾</h3>
        <form action="?action=login" method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control bg-dark text-white border-secondary" placeholder="Usuario" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="?action=register" class="text-secondary small">¿No tienes cuenta? Regístrate aquí</a>
        </div>
    </div>
</body>
</html>