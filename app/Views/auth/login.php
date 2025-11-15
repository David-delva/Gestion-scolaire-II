<!--
Fichier : app/Views/auth/login.php
Description : Vue du formulaire de connexion.
Version : 2.0 - Design Moderne
Date : 2025
-->

<div class="login-page-wrapper">
    <div class="login-background">
        <div class="logo-background"></div>
        <div class="overlay-gradient"></div>
    </div>
    
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-10 col-lg-8">
                <div class="row g-0 login-card-wrapper" data-aos="zoom-in">
                    <!-- Section gauche avec logo et info -->
                    <div class="col-lg-6 login-left-section d-none d-lg-flex">
                        <div class="login-branding">
                            <div class="logo-circle mb-4">
                                <img src="<?php echo BASE_URL; ?>assets/logo (2).jpg" alt="Logo" class="img-fluid">
                            </div>
                            <h2 class="text-white fw-bold mb-3">Bienvenue !</h2>
                            <p class="text-white-50 mb-4">Connectez-vous pour accéder à votre espace de gestion scolaire</p>
                            <div class="features-list">
                                <div class="feature-item mb-3">
                                    <i class="bi bi-shield-check text-success"></i>
                                    <span>Connexion sécurisée</span>
                                </div>
                                <div class="feature-item mb-3">
                                    <i class="bi bi-lightning-charge text-warning"></i>
                                    <span>Accès instantané</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-phone text-info"></i>
                                    <span>Multi-plateforme</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section droite avec formulaire -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg login-form-card">
                            <div class="card-body p-5">
                                <div class="text-center mb-4 d-lg-none">
                                    <img src="<?php echo BASE_URL; ?>assets/logo (2).jpg" alt="Logo" class="mobile-logo mb-3">
                                </div>
                                
                                <h3 class="text-center fw-bold mb-4">
                                    <i class="bi bi-box-arrow-in-right text-primary"></i> Connexion
                                </h3>
                                
                                <?php if (!empty($data['error'])): ?>
                                    <div class="alert alert-danger animate__animated animate__shakeX" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <?php echo htmlspecialchars($data['error']); ?>
                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo BASE_URL; ?>auth/login" method="POST" class="login-form">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope me-2"></i>Adresse Email
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope-fill"></i>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="votre.email@example.com" required autofocus>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="bi bi-lock me-2"></i>Mot de passe
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   placeholder="••••••••" required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2 mb-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                        </button>
                                    </div>
                                    
                                    <div class="text-center">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Utilisez vos identifiants fournis par l'administration
                                        </small>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center bg-light border-0 py-3">
                                <small class="text-muted">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    &copy; <?php echo date('Y'); ?> Gestion des Étudiants - Connexion sécurisée
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-page-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow-y: auto;
}

.login-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.logo-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('<?php echo BASE_URL; ?>assets/logo (2).jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(8px) brightness(0.4);
    transform: scale(1.1);
}

.overlay-gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
}

.login-card-wrapper {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.login-left-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.login-left-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

.login-branding {
    position: relative;
    z-index: 1;
}

.logo-circle {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: pulse 3s infinite;
}

.logo-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.features-list {
    text-align: left;
}

.feature-item {
    display: flex;
    align-items: center;
    color: white;
    font-size: 1rem;
}

.feature-item i {
    font-size: 1.5rem;
    margin-right: 1rem;
    width: 30px;
}

.login-form-card {
    border-radius: 0 24px 24px 0;
}

.login-form .form-control {
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.login-form .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    transform: translateY(-2px);
}

.login-form .input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 12px 0 0 12px;
}

.login-form .btn-outline-secondary {
    border-radius: 0 12px 12px 0;
    border-left: none;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.mobile-logo {
    max-width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

@media (max-width: 991px) {
    .login-card-wrapper {
        margin: 2rem 0;
    }
    
    .login-form-card {
        border-radius: 24px;
    }
    
    .card-body {
        padding: 2rem 1.5rem !important;
    }
}

@media (max-width: 576px) {
    .mobile-logo {
        max-width: 60px;
        height: 60px;
    }
    
    .card-body {
        padding: 1.5rem 1rem !important;
    }
    
    h3 {
        font-size: 1.3rem;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            if (type === 'password') {
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            } else {
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            }
        });

        // Animation du formulaire
        const form = document.querySelector('.login-form');
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connexion...';
        });
    });
</script>
