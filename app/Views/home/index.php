<!-- Hero Section avec effet moderne -->
<div class="hero-section glass-effect" data-aos="fade-in">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="hero-badge mb-3">
                    <span class="badge bg-light text-primary px-4 py-2">
                        <i class="bi bi-star-fill me-2"></i>Plateforme N°1 de Gestion Scolaire
                    </span>
                </div>
                <h1 class="display-2 fw-bold mb-4 text-white" data-typing>
                    <?php echo htmlspecialchars($data['welcome_message'] ?? 'Bienvenue !'); ?>
                </h1>
                <p class="lead mb-4 text-white-50" data-aos="fade-up" data-aos-delay="200">
                    Transformez votre établissement avec notre plateforme moderne de gestion scolaire. 
                    Optimisez l'administration, l'enseignement et le suivi des étudiants en temps réel.
                </p>
                <div class="d-flex gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="400">
                    <a class="btn btn-primary btn-lg px-5 pulse" href="<?php echo BASE_URL; ?>auth/login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </a>
                    <a class="btn btn-outline-light btn-lg px-5" href="#features">
                        <i class="bi bi-info-circle me-2"></i>Découvrir
                    </a>
                </div>
                <div class="mt-4 d-flex gap-4 text-white-50" data-aos="fade-up" data-aos-delay="600">
                    <div>
                        <i class="bi bi-shield-check fs-4 text-success"></i>
                        <small class="d-block">100% Sécurisé</small>
                    </div>
                    <div>
                        <i class="bi bi-lightning-charge fs-4 text-warning"></i>
                        <small class="d-block">Ultra Rapide</small>
                    </div>
                    <div>
                        <i class="bi bi-phone fs-4 text-info"></i>
                        <small class="d-block">Responsive</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-illustration position-relative">
                    <div class="floating-card card border-0 shadow-lg">
                        <div class="card-body text-center p-5">
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 8rem;"></i>
                            <div class="mt-4">
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                                </div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span><i class="bi bi-people-fill text-primary"></i> 500+ Étudiants</span>
                                    <span><i class="bi bi-graph-up text-success"></i> 95% Réussite</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section avec design moderne -->
<div id="features" class="container my-5 py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <span class="badge bg-primary px-4 py-2 mb-3">
            <i class="bi bi-stars me-2"></i>Fonctionnalités
        </span>
        <h2 class="display-4 fw-bold mb-3">Des Outils Adaptés à Chaque Rôle</h2>
        <p class="lead text-white-50">Une solution complète pour tous les acteurs de l'éducation</p>
    </div>
    
    <div class="row g-4">
        <!-- Admin Card -->
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 feature-card border-0 shadow-lg card-shine">
                <div class="card-body text-center p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon-bg bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block">
                            <i class="bi bi-shield-check text-primary" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <h3 class="card-title mb-3 fw-bold">Administrateurs</h3>
                    <p class="card-text text-muted mb-4">
                        Contrôle total et vision à 360° de votre établissement avec des outils d'analyse avancés.
                    </p>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Gestion complète</strong> des utilisateurs
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Statistiques</strong> en temps réel
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Configuration</strong> système avancée
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Rapports</strong> détaillés
                        </li>
                    </ul>
                    <a class="btn btn-primary w-100 btn-lg" href="<?php echo BASE_URL; ?>auth/login">
                        <i class="bi bi-arrow-right-circle me-2"></i>Accéder au Tableau de Bord
                    </a>
                </div>
            </div>
        </div>

        <!-- Teacher Card -->
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 feature-card border-0 shadow-lg card-shine">
                <div class="card-body text-center p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon-bg bg-success bg-opacity-10 rounded-circle p-4 d-inline-block">
                            <i class="bi bi-person-workspace text-success" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <h3 class="card-title mb-3 fw-bold">Enseignants</h3>
                    <p class="card-text text-muted mb-4">
                        Outils intuitifs pour gérer vos classes, notes et suivre la progression de vos élèves.
                    </p>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Saisie rapide</strong> des notes
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Gestion</strong> des absences
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Statistiques</strong> de classe
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Communication</strong> facilitée
                        </li>
                    </ul>
                    <a class="btn btn-success w-100 btn-lg" href="<?php echo BASE_URL; ?>auth/login">
                        <i class="bi bi-arrow-right-circle me-2"></i>Espace Enseignant
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Card -->
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 feature-card border-0 shadow-lg card-shine">
                <div class="card-body text-center p-5">
                    <div class="feature-icon-wrapper mb-4">
                        <div class="feature-icon-bg bg-danger bg-opacity-10 rounded-circle p-4 d-inline-block">
                            <i class="bi bi-person-badge text-danger" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <h3 class="card-title mb-3 fw-bold">Étudiants</h3>
                    <p class="card-text text-muted mb-4">
                        Accédez instantanément à vos résultats, absences et informations académiques.
                    </p>
                    <ul class="list-unstyled text-start mb-4">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Consultation</strong> des notes
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Historique</strong> d'absences
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Emploi du temps</strong> personnalisé
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Profil</strong> étudiant
                        </li>
                    </ul>
                    <a class="btn btn-danger w-100 btn-lg" href="<?php echo BASE_URL; ?>auth/login">
                        <i class="bi bi-arrow-right-circle me-2"></i>Mon Espace Étudiant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section avec animations -->
<div class="stats-section glass-effect py-5 my-5" data-aos="fade-up">
    <div class="container">
        <div class="row text-center text-white g-4">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-icon-wrapper mb-3">
                        <i class="bi bi-people-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="stat-number display-3 fw-bold mb-2">500</h2>
                    <p class="lead mb-0">Étudiants Actifs</p>
                    <small class="text-white-50">En constante croissance</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-icon-wrapper mb-3">
                        <i class="bi bi-person-video3" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="stat-number display-3 fw-bold mb-2">50</h2>
                    <p class="lead mb-0">Enseignants</p>
                    <small class="text-white-50">Experts qualifiés</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-icon-wrapper mb-3">
                        <i class="bi bi-book-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="stat-number display-3 fw-bold mb-2">30</h2>
                    <p class="lead mb-0">Matières</p>
                    <small class="text-white-50">Programme complet</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-icon-wrapper mb-3">
                        <i class="bi bi-building" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="stat-number display-3 fw-bold mb-2">20</h2>
                    <p class="lead mb-0">Classes</p>
                    <small class="text-white-50">Tous niveaux</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avantages Section -->
<div class="container my-5 py-5">
    <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
            <span class="badge bg-success px-4 py-2 mb-3">
                <i class="bi bi-lightning-charge me-2"></i>Pourquoi Nous Choisir
            </span>
            <h2 class="display-5 fw-bold mb-4 text-white">Une Plateforme Pensée Pour Vous</h2>
            <div class="feature-list">
                <div class="feature-item d-flex mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon-small bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-lightning-charge fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white">Performance Optimale</h5>
                        <p class="text-white-50 mb-0">Interface ultra-rapide et réactive pour une expérience fluide</p>
                    </div>
                </div>
                <div class="feature-item d-flex mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon-small bg-success text-white rounded-circle p-3 me-3">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white">Sécurité Maximale</h5>
                        <p class="text-white-50 mb-0">Vos données sont protégées avec les dernières technologies</p>
                    </div>
                </div>
                <div class="feature-item d-flex mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon-small bg-info text-white rounded-circle p-3 me-3">
                        <i class="bi bi-phone fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white">100% Responsive</h5>
                        <p class="text-white-50 mb-0">Accessible sur tous vos appareils, partout et à tout moment</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-graph-up-arrow text-success" style="font-size: 10rem; opacity: 0.2;"></i>
                    <div class="mt-4">
                        <h4 class="fw-bold mb-3">Taux de Satisfaction</h4>
                        <div class="progress mb-3" style="height: 30px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                                <strong>95%</strong>
                            </div>
                        </div>
                        <p class="text-muted">Nos utilisateurs nous font confiance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section glass-effect text-center py-5 my-5" data-aos="zoom-in">
    <div class="container">
        <i class="bi bi-rocket-takeoff text-white mb-4" style="font-size: 5rem;"></i>
        <h2 class="display-4 fw-bold text-white mb-4">Prêt à Commencer ?</h2>
        <p class="lead text-white-50 mb-4">Rejoignez des centaines d'établissements qui nous font confiance</p>
        <a href="<?php echo BASE_URL; ?>auth/login" class="btn btn-light btn-lg px-5 py-3">
            <i class="bi bi-arrow-right-circle me-2"></i>Accéder à la Plateforme
        </a>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
    border-radius: 24px;
    margin: 2rem 0;
    overflow: hidden;
    position: relative;
}

.floating-card {
    animation: floatCard 3s ease-in-out infinite;
}

@keyframes floatCard {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(2deg); }
}

.feature-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.feature-card:hover {
    transform: translateY(-15px) scale(1.03);
}

.feature-icon-wrapper {
    transition: all 0.4s ease;
}

.feature-card:hover .feature-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.stats-section {
    background: linear-gradient(135deg, rgba(17, 153, 142, 0.95) 0%, rgba(56, 239, 125, 0.95) 100%);
    border-radius: 24px;
    position: relative;
    overflow: hidden;
}

.stat-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-item:hover {
    transform: scale(1.1);
}

.stat-icon-wrapper {
    transition: all 0.3s ease;
}

.stat-item:hover .stat-icon-wrapper {
    transform: rotate(360deg);
}

.feature-icon-small {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cta-section {
    background: linear-gradient(135deg, rgba(235, 51, 73, 0.95) 0%, rgba(244, 92, 67, 0.95) 100%);
    border-radius: 24px;
}

.hero-badge {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
</style>
