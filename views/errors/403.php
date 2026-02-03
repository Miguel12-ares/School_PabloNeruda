<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - Escuela Pablo Neruda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
        }
        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-code {
            font-size: 4rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .error-message {
            color: #666;
            margin: 1.5rem 0;
        }
        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: transform 0.3s;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            color: white;
        }
        .user-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <i class="bi bi-shield-exclamation error-icon"></i>
        <h1 class="error-code">403</h1>
        <h2>Acceso Denegado</h2>
        
        <p class="error-message">
            <strong>Lo sentimos, no tienes permisos para acceder a este recurso.</strong>
        </p>
        
        <p class="text-muted">
            Intentaste acceder a: <strong><?= htmlspecialchars($modulo) ?> - <?= htmlspecialchars($accion) ?></strong>
        </p>
        
        <?php if (isset($user) && $user): ?>
        <div class="user-info">
            <p class="mb-1">
                <i class="bi bi-person-circle"></i> 
                Usuario: <strong><?= htmlspecialchars($user['nombre_completo']) ?></strong>
            </p>
            <p class="mb-0">
                <i class="bi bi-shield-check"></i> 
                Rol: <strong><?= htmlspecialchars($rol['nombre_rol'] ?? 'Sin rol') ?></strong>
            </p>
        </div>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="javascript:history.back()" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="/index.php" class="btn-home">
                <i class="bi bi-house-door"></i> Ir al Inicio
            </a>
        </div>
        
        <p class="text-muted mt-4" style="font-size: 0.85rem;">
            Si crees que esto es un error, contacta al administrador del sistema.
        </p>
    </div>
</body>
</html>
