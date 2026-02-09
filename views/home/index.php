<?php 
$title = 'Inicio - Escuela Pablo Neruda';
require_once VIEWS_PATH . '/layout/header_public.php';
?>

<style>
    body {
        background-color: #ffffff !important;
    }
    
    /* Hero Section */
    .hero-section {
        background: white;
        color: #1e3a5f;
        padding: 5rem 0 4rem 0;
        margin-bottom: 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .hero-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    
    .hero-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        letter-spacing: -1px;
        color: #1e3a5f;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        font-weight: 400;
        color: #666;
        line-height: 1.6;
    }
    
    /* Stats Bar */
    .stats-bar {
        background: white;
        padding: 2.5rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e3a5f;
        font-family: 'Poppins', sans-serif;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    /* Sections */
    .section {
        padding: 4rem 0;
    }
    
    .section-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        color: #1e3a5f;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .section-subtitle {
        color: #666;
        font-size: 1.1rem;
        text-align: center;
        margin-bottom: 3rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Feature Cards */
    .feature-card {
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        border-color: #1e3a5f;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 58, 95, 0.1);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    
    .feature-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .feature-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.3rem;
        color: #1e3a5f;
        margin-bottom: 1rem;
    }
    
    .feature-text {
        color: #666;
        line-height: 1.7;
        margin: 0;
    }
    
    /* System Features */
    .system-section {
        background: #f8f9fa;
        padding: 4rem 0;
    }
    
    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .feature-list li {
        background: white;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        border-left: 4px solid #1e3a5f;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    .feature-list li:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .feature-list i {
        color: #1e3a5f;
        font-size: 1.5rem;
        margin-right: 1rem;
        min-width: 30px;
    }
    
    .feature-list strong {
        color: #333;
        margin-right: 0.5rem;
    }
    
    .feature-list span {
        color: #666;
    }
    
    /* Values Grid */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }
    
    .value-item {
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .value-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }
    
    .value-item:hover .value-icon {
        transform: scale(1.1);
    }
    
    .value-icon i {
        font-size: 2.5rem;
        color: white;
    }
    
    .value-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.25rem;
        color: #1e3a5f;
        margin: 0;
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .cta-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .cta-text {
        font-size: 1.15rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }
    
    .cta-button {
        background: white;
        color: #1e3a5f;
        border: none;
        padding: 1rem 3rem;
        font-size: 1.2rem;
        font-weight: 600;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        color: #1e3a5f;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .values-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .cta-title {
            font-size: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Escuela Pablo Neruda</h1>
            <p class="hero-subtitle">Institución educativa comprometida con la excelencia académica y el desarrollo integral de nuestros estudiantes desde hace más de 30 años.</p>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div class="stats-bar">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">30+</div>
                    <div class="stat-label">Años de experiencia</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Estudiantes activos</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">35+</div>
                    <div class="stat-label">Docentes calificados</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Compromiso</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<div class="section">
    <div class="container">
        <h2 class="section-title">Nuestra Propuesta Educativa</h2>
        <p class="section-subtitle">Formamos ciudadanos responsables, creativos y preparados para los desafíos del futuro con una educación de calidad.</p>
        
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-flag"></i>
                    </div>
                    <h3 class="feature-title">Nuestra Misión</h3>
                    <p class="feature-text">
                        Proporcionar una educación de calidad que fomente el pensamiento crítico, 
                        la creatividad y los valores éticos, preparando a nuestros estudiantes para 
                        ser líderes en una sociedad en constante cambio.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h3 class="feature-title">Nuestra Visión</h3>
                    <p class="feature-text">
                        Ser reconocidos como una institución educativa de excelencia, líder en 
                        innovación pedagógica y formación integral, que contribuye al desarrollo 
                        de una sociedad más justa y equitativa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Section -->
<div class="system-section">
    <div class="container">
        <h2 class="section-title">Sistema de Gestión Escolar</h2>
        <p class="section-subtitle">Plataforma digital moderna que facilita la gestión académica y administrativa</p>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <ul class="feature-list">
                    <li>
                        <i class="bi bi-people"></i>
                        <div>
                            <strong>Gestión de Estudiantes</strong>
                            <span>Control completo de información académica y personal</span>
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-journal-text"></i>
                        <div>
                            <strong>Registro de Notas</strong>
                            <span>Sistema eficiente para el seguimiento del rendimiento académico</span>
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <div>
                            <strong>Reportes y Boletines</strong>
                            <span>Generación automática de informes académicos</span>
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-gear"></i>
                        <div>
                            <strong>Panel Administrativo</strong>
                            <span>Herramientas completas para la gestión institucional</span>
                        </div>
                    </li>
                    <li>
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Acceso Seguro</strong>
                            <span>Protección de datos y privacidad garantizada</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="section">
    <div class="container">
        <h2 class="section-title">Nuestros Valores</h2>
        <p class="section-subtitle">Principios que guían nuestra labor educativa diaria</p>
        
        <div class="values-grid">
            <div class="value-item">
                <div class="value-icon">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <h4 class="value-title">Respeto</h4>
            </div>
            <div class="value-item">
                <div class="value-icon">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <h4 class="value-title">Innovación</h4>
            </div>
            <div class="value-item">
                <div class="value-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h4 class="value-title">Excelencia</h4>
            </div>
            <div class="value-item">
                <div class="value-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h4 class="value-title">Comunidad</h4>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="container">
        <h2 class="cta-title">¿Eres parte de nuestra institución?</h2>
        <p class="cta-text">Accede al sistema de gestión escolar</p>
        <a href="/login" class="cta-button">
            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
        </a>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
