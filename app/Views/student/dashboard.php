<!-- Dashboard Étudiant - Version Moderne -->
<div class="student-hero glass-effect mb-5" data-aos="fade-down">
    <div class="container-fluid py-4 text-center">
        <div class="student-avatar mb-3"><i class="bi bi-mortarboard-fill"></i></div>
        <h1 class="display-4 fw-bold text-white mb-3"><?php echo $data['welcome_message']??'Bienvenue, Étudiant !'; ?></h1>
        <p class="lead text-white-50 mb-4">Votre portail académique personnalisé</p>
        <button class="btn btn-light btn-lg shadow" data-bs-toggle="modal" data-bs-target="#studentInfoModal">
            <i class="bi bi-person-badge me-2"></i>Mes Informations
        </button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4 text-center">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-primary bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-journal-text text-primary" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Mes Notes</h5>
                <p class="text-muted mb-4">Consultez vos notes par matière</p>
                <a href="<?php echo BASE_URL; ?>student/grades" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Voir
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4 text-center">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-danger bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-calendar-x text-danger" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Mes Absences</h5>
                <p class="text-muted mb-4">Historique et justifications</p>
                <a href="<?php echo BASE_URL; ?>student/absences" class="btn btn-danger w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Voir
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4 text-center">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-success bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-person-circle text-success" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Mon Profil</h5>
                <p class="text-muted mb-4">Gérez vos informations</p>
                <a href="<?php echo BASE_URL; ?>auth/profile" class="btn btn-success w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="action-card card border-0 shadow-lg h-100">
            <div class="card-body p-4 text-center">
                <div class="action-icon-wrapper mb-3">
                    <div class="action-icon bg-info bg-opacity-10 rounded-3 p-3 d-inline-block">
                        <i class="bi bi-calendar-week text-info" style="font-size:2.5rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Ma classe</h5>
                <p class="text-muted mb-4">Matières et enseignants</p>
                <a href="<?php echo BASE_URL; ?>student/schedule" class="btn btn-info w-100">
                    <i class="bi bi-arrow-right-circle me-2"></i>Voir
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="studentInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Mes Informations</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-person me-1"></i>Nom</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($_SESSION['user_first_name'].' '.$_SESSION['user_last_name']); ?></h6>
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
                            <small class="text-muted d-block mb-1"><i class="bi bi-card-text me-1"></i>Matricule</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($data['student_info']['student_id_number']??'N/A'); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-calendar me-1"></i>Date naissance</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars(date('d/m/Y',strtotime($data['student_info']['date_of_birth']??'now'))); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-telephone me-1"></i>Téléphone</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($data['student_info']['phone']??'Non renseigné'); ?></h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card p-3 rounded-3 bg-light">
                            <small class="text-muted d-block mb-1"><i class="bi bi-geo-alt me-1"></i>Adresse</small>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($data['student_info']['address']??'Non renseigné'); ?></h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 bg-primary bg-opacity-10">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-building me-2"></i>Ma Classe</h6>
                                <?php if(!empty($data['student_classes']['class_name'])): ?>
                                    <span class="badge bg-primary px-3 py-2"><?php echo htmlspecialchars($data['student_classes']['class_name']); ?></span>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Aucune classe assignée</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="<?php echo BASE_URL; ?>auth/profile" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Modifier</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.student-hero{background:linear-gradient(135deg,rgba(235,51,73,0.95),rgba(244,92,67,0.95));border-radius:20px;padding:3rem 1rem;}
.student-avatar i{font-size:5rem;color:white;}
</style>
