<?php 
$title = 'Iniciar Sesión - Escuela Pablo Neruda';
require_once VIEWS_PATH . '/layout/header_public.php';
?>

<style>
    body {
        background-color: white;
    }
    
    .login-container {
        max-width: 500px;
        margin: 3rem auto;
        padding: 20px;
    }
    
    .login-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 3rem 2.5rem;
    }
    
    .login-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 3rem;
        color: #1e3a5f;
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .login-description {
        text-align: center;
        color: #666;
        margin-bottom: 2rem;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 2px solid #e0e0e0;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #1e3a5f;
        box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.15);
    }
    
    .btn-login {
        background: #1e3a5f;
        border: none;
        border-radius: 8px;
        padding: 0.85rem;
        font-weight: 600;
        font-size: 1.05rem;
        width: 100%;
        color: white;
        margin-top: 1.5rem;
        transition: all 0.3s;
    }
    
    .btn-login:hover {
        background: #2c5282;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
    }
    
    .alert {
        border-radius: 8px;
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .alert-danger {
        background-color: #fee;
        color: #c33;
    }
    
    .alert-success {
        background-color: #efe;
        color: #3c3;
    }
    
    .alert.blocked {
        background-color: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        bottom: 12px;
        cursor: pointer;
        color: #666;
        font-size: 1.2rem;
    }
    
    .back-link {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .back-link a {
        color: #1e3a5f;
        text-decoration: none;
        font-weight: 500;
    }
    
    .back-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <h1 class="login-title">Pablo Neruda</h1>
        <p class="login-description">
            Sistema de Gestión Escolar
        </p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger <?= isset($_SESSION['error_type']) && $_SESSION['error_type'] === 'blocked' ? 'blocked' : '' ?>" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php 
            unset($_SESSION['error']); 
            unset($_SESSION['error_type']);
            ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="bi bi-person"></i> Usuario o Email
                </label>
                <input type="text" 
                       class="form-control" 
                       id="username" 
                       name="username" 
                       placeholder="Ingresa tu usuario o email"
                       required 
                       autofocus>
            </div>
            
            <div class="password-wrapper mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       placeholder="Ingresa tu contraseña"
                       required>
                <i class="bi bi-eye password-toggle" id="togglePassword"></i>
            </div>
            
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </button>
        </form>
        
        <div class="back-link">
            <a href="/home">
                <i class="bi bi-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
</body>
</html>
