    </div> <!-- Cierre del container -->
    
    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="text-muted mb-0">
                <i class="bi bi-geo-alt"></i> Escuela Pablo Neruda - Barrio Las Malvinas, Sector 4 Berlín
            </p>
            <p class="text-muted mb-0">
                Sistema de Gestión Académica &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
<?php
// Limpiar sesión de datos antiguos
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>

