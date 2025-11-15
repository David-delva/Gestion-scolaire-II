<!-- Dashboard Admin - Version Premium -->
<div class="admin-hero-premium mb-5" data-aos="fade-down">
    <div class="hero-background-pattern"></div>
    <div class="container-fluid py-5 position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="admin-avatar-premium me-4">
                        <i class="bi bi-shield-check"></i>
                        <div class="avatar-glow"></div>
                    </div>
                    <div>
                        <div class="badge bg-light text-dark mb-2 px-3 py-2">
                            <i class="bi bi-star-fill text-warning me-1"></i>Accès Administrateur
                        </div>
                        <h1 class="display-3 fw-bold text-white mb-2 hero-title"><?php echo $data['welcome_message']??'Bienvenue, Administrateur !'; ?></h1>
                        <p class="text-dark mb-0 fs-5 fw-semibold">
                            <i class="bi bi-calendar-event me-2"></i><?php $jours=['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']; $mois=['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre']; echo $jours[date('w')].' '.date('d').' '.$mois[date('n')].' '.date('Y'); ?>
                        </p>
                    </div>
                </div>
                <div class="hero-description">
                    <p class="lead text-dark mb-0 fs-4 fw-semibold">
                        <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                        Contrôle total sur l'application et gestion complète de l'établissement
                    </p>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-light btn-lg shadow-lg px-4 py-3 hero-btn">
                    <i class="bi bi-people-fill me-2"></i>Gérer Utilisateurs
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-primary bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-people-fill text-primary" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Utilisateurs</h5>
                <p class="text-muted mb-4">Gérez tous les comptes utilisateurs</p>
                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-success bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-bookmark-fill text-success" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Classes</h5>
                <p class="text-muted mb-4">Gérez les classes de l'établissement</p>
                <a href="<?php echo BASE_URL; ?>admin/classes" class="btn btn-success w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-info bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-book-fill text-info" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Matières</h5>
                <p class="text-muted mb-4">Définissez les matières enseignées</p>
                <a href="<?php echo BASE_URL; ?>admin/subjects" class="btn btn-info w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="250">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-warning bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-person-badge-fill text-warning" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Enseignants</h5>
                <p class="text-muted mb-4">Gérez les enseignants et affectations</p>
                <a href="<?php echo BASE_URL; ?>admin/teachers" class="btn btn-warning w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-danger bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-mortarboard-fill text-danger" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Étudiants</h5>
                <p class="text-muted mb-4">Gérez les étudiants et informations</p>
                <a href="<?php echo BASE_URL; ?>admin/students" class="btn btn-danger w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="350">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-secondary bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-link-45deg text-secondary" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Affectations</h5>
                <p class="text-muted mb-4">Associez enseignants et étudiants</p>
                <a href="<?php echo BASE_URL; ?>admin/assignments" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12" data-aos="fade-up" data-aos-delay="400">
        <div class="action-card card border-0 shadow-lg">
            <div class="card-body p-4 text-center">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-primary bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-graph-up text-primary" style="font-size:3rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3">Statistiques Globales</h4>
                <p class="text-muted mb-4">Consultez les statistiques générales de l'établissement</p>
                <a href="<?php echo BASE_URL; ?>admin/stats" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-bar-chart-line me-2"></i>Voir les Statistiques
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);}

/* Hero Section Premium */
.admin-hero-premium {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
}

.hero-background-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.admin-avatar-premium {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    animation: float 3s ease-in-out infinite;
}

.admin-avatar-premium i {
    font-size: 3.5rem;
    color: white;
    z-index: 2;
}

.avatar-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse-glow {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.2); opacity: 0; }
}

.hero-title {
    text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    animation: slideInLeft 0.8s ease-out;
}

@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

.hero-description {
    animation: slideInLeft 0.8s ease-out 0.2s both;
}

.hero-btn {
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    animation: slideInRight 0.8s ease-out;
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

.hero-btn:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 40px rgba(255, 255, 255, 0.4) !important;
}
</style>
