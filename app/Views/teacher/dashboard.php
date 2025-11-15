<!--
Fichier : app/Views/teacher/dashboard.php
Description : Tableau de bord de l'enseignant - Design Moderne
Version : 2.0
Date : 2025
-->

<!-- Hero Section Enseignant -->
<div class="teacher-hero glass-effect mb-5" data-aos="fade-down">
    <div class="container-fluid py-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="teacher-avatar me-3">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold text-white mb-2">
                            <?php echo $data['welcome_message'] ?? 'Bienvenue, Enseignant !'; ?>
                        </h1>
                        <p class="text-white-50 mb-0">
                            <i class="bi bi-calendar-check me-2"></i><?php echo date('l d F Y'); ?>
                        </p>
                    </div>
                </div>
                <p class="lead text-white mb-0">
                    Gérez vos classes, notes et absences depuis votre espace personnalisé
                </p>
            </div>
            <div class="col-lg-4 text-end">
                <button class="btn btn-light btn-lg shadow" data-bs-toggle="modal" data-bs-target="#teacherInfoModal">
                    <i class="bi bi-person-badge me-2"></i>Mon Profil
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques Rapides -->
<div class="row g-4 mb-5">
    <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-book text-primary" style="font-size: 2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo count($data['teacher_subjects'] ?? []); ?></h3>
                <p class="text-muted mb-0">Matières</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-building text-success" style="font-size: 2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo count($data['teacher_classes'] ?? []); ?></h3>
                <p class="text-muted mb-0">Classes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-people text-warning" style="font-size: 2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $data['total_students'] ?? '0'; ?></h3>
                <p class="text-muted mb-0">Étudiants</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon bg-danger bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-calendar-x text-danger" style="font-size: 2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo $data['total_absences'] ?? '0'; ?></h3>
                <p class="text-muted mb-0">Absences</p>
            </div>
        </div>
    </div>
</div>

<!-- Actions Rapides -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-primary bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-journal-text text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h5 class="card-title fw-bold mb-3">Gestion des Notes</h5>
                <p class="card-text text-muted mb-4">Saisissez et consultez les notes de vos étudiants</p>
                <a href="<?php echo BASE_URL; ?>teacher/grades" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Accéder
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-danger bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-calendar-x text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h5 class="card-title fw-bold mb-3">Gestion des Absences</h5>
                <p class="card-text text-muted mb-4">Enregistrez les absences de vos étudiants</p>
                <a href="<?php echo BASE_URL; ?>teacher/absences" class="btn btn-danger w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Accéder
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-success bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-grid-3x3-gap text-success" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h5 class="card-title fw-bold mb-3">Mes Classes</h5>
                <p class="card-text text-muted mb-4">Consultez vos classes et matières assignées</p>
                <a href="<?php echo BASE_URL; ?>teacher/classes" class="btn btn-success w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Accéder
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-info bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-graph-up text-info" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h5 class="card-title fw-bold mb-3">Mes Statistiques</h5>
                <p class="card-text text-muted mb-4">Accédez à vos statistiques personnelles</p>
                <a href="<?php echo BASE_URL; ?>teacher/stats" class="btn btn-info w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Accéder
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Informations Détaillées -->
<div class="row g-4">
    <div class="col-lg-6" data-aos="fade-right">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient text-white py-3">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i>Mes Matières</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($data['teacher_subjects'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($data['teacher_subjects'] as $subject): ?>
                            <div class="list-group-item d-flex align-items-center border-0 py-3">
                                <div class="subject-icon bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($subject['name']); ?></h6>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Aucune matière assignée</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6" data-aos="fade-left">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient text-white py-3">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Mes Classes</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($data['teacher_classes'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($data['teacher_classes'] as $class): ?>
                            <div class="list-group-item d-flex align-items-center border-0 py-3">
                                <div class="class-icon bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-check-circle text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($class['name']); ?></h6>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Aucune classe assignée</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Informations Enseignant -->
<div class="modal fade" id="teacherInfoModal" tabindex="-1" aria-labelledby="teacherInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title" id="teacherInfoModalLabel">
                    <i class="bi bi-person-badge me-2"></i>Mes Informations
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-person me-1"></i>Nom complet</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-envelope me-1"></i>Email</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($_SESSION['user_email']); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-telephone me-1"></i>Téléphone</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($data['teacher_info']['phone'] ?? 'Non renseigné'); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-calendar-check me-1"></i>Date d'embauche</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars(date('d/m/Y', strtotime($data['teacher_info']['hire_date'] ?? 'now'))); ?></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-card p-3 rounded-3 bg-success bg-opacity-10">
                            <small class="text-muted d-block mb-1"><i class="bi bi-shield-check me-1"></i>Rôle</small>
                            <span class="badge bg-success px-3 py-2">Enseignant</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="<?php echo BASE_URL; ?>auth/profile" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Modifier mon profil
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.teacher-hero {
    background: linear-gradient(135deg, rgba(17, 153, 142, 0.95) 0%, rgba(56, 239, 125, 0.95) 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
}

.teacher-avatar i {
    font-size: 4rem;
    color: white;
}

.stat-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-10px);
}

.stat-icon {
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.action-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-card:hover {
    transform: translateY(-10px);
}

.action-icon-wrapper {
    transition: all 0.3s ease;
}

.action-card:hover .action-icon-wrapper {
    transform: scale(1.1);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.subject-icon, .class-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .teacher-avatar i {
        font-size: 3rem;
    }
    
    .display-5 {
        font-size: 1.8rem;
    }
}
</style>
