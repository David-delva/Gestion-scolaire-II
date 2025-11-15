<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎓</text></svg>">
    <title><?php echo $data['title'] ?? 'Gestion des Étudiants'; ?></title>

    <!-- Intégration de Bootstrap 5.3 CSS -->
    <link href="https://bootswatch.com/5/flatly/bootstrap.min.css" rel="stylesheet">
    <!-- Intégration de Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


    <!-- Intégration de DataTables CSS pour Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Styles CSS personnalisés -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/dark-theme.css">
</head>
<body>
    <!-- En-tête de l'application -->
    <header>
        <?php
            // Déterminer le lien d'accueil en fonction du rôle de l'utilisateur
            $homeUrl = BASE_URL;
            if (isset($_SESSION['user_id'])) {
                switch ($_SESSION['role_name']) {
                    case 'Administrateur':
                        $homeUrl = BASE_URL . 'admin/dashboard';
                        break;
                    case 'Enseignant':
                        $homeUrl = BASE_URL . 'teacher/dashboard';
                        break;
                    case 'Étudiant':
                        $homeUrl = BASE_URL . 'student/dashboard';
                        break;
                }
            }
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-lg fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo $homeUrl; ?>">
                    <i class="bi bi-mortarboard-fill me-2"></i>GES
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo $homeUrl; ?>">Accueil</a>
                        </li>
                        <?php if (isset($_SESSION['user_id'])): // Si l'utilisateur est connecté ?>
                            <?php if ($_SESSION['role_name'] === 'Administrateur'): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Administration
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/users">Gérer les Utilisateurs</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/classes">Gérer les Classes</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/subjects">Gérer les Matières</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/teachers">Gérer les Enseignants</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/students">Gérer les Étudiants</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/assignments">Gérer les Affectations</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/stats">Statistiques Globales</a></li>
                                    </ul>
                                </li>
                            <?php elseif ($_SESSION['role_name'] === 'Enseignant'): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTeacher" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Enseignant
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTeacher">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>teacher/grades">Gérer les Notes</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>teacher/absences">Gérer les Absences</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>teacher/classes">Mes Classes/Matières</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>teacher/stats">Mes Statistiques</a></li>
                                    </ul>
                                </li>
                            <?php elseif ($_SESSION['role_name'] === 'Étudiant'): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownStudent" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Étudiant
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownStudent">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>student/grades">Mes Notes</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>student/absences">Mes Absences</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>student/profile">Mon Profil</a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>student/schedule">Info sur ma classe</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars(($_SESSION['user_first_name'] ?? '') . ' ' . ($_SESSION['user_last_name'] ?? '')); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/profile">Mon Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout">Déconnexion</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>auth/login"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenu principal de la page -->
    <main class="container main-content" style="margin-top: 100px;">
        <?php
        // Afficher les messages d'alerte (succès, erreur, info, etc.)
        if (isset($_SESSION['alert'])):
            $alert = $_SESSION['alert'];
            unset($_SESSION['alert']); // Supprimer l'alerte après l'affichage
        ?>
            <div class="alert alert-<?php echo htmlspecialchars($alert['type']); ?> alert-dismissible fade show animate__animated animate__bounceInDown" role="alert">
                <i class="bi bi-<?php echo $alert['type'] === 'success' ? 'check-circle-fill' : ($alert['type'] === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill'); ?> me-2"></i>
                <?php echo htmlspecialchars($alert['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Le contenu spécifique de chaque page sera inséré ici -->
        <?php
            // $data est passé par le contrôleur et contient les variables nécessaires à la vue.
            // Le contenu de la vue spécifique est inclus ici.
            // Par exemple, pour HomeController::index(), cela inclura app/Views/home/index.php
            if (isset($data['view_content'])) {
                echo $data['view_content'];
            } else {
                // Si la vue est incluse directement par le contrôleur, elle sera exécutée ici.
                // C'est le cas pour l'instant avec le HomeController.
            }
        ?>
    </main>

    <!-- Pied de page de l'application -->
    <footer class="footer mt-auto py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span>&copy; <?php echo date('Y'); ?> GES</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="social-icon text-white me-3" data-bs-toggle="tooltip" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon text-white me-3" data-bs-toggle="tooltip" title="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon text-white me-3" data-bs-toggle="tooltip" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-icon text-white" data-bs-toggle="tooltip" title="GitHub"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bouton retour en haut -->
    <button id="backToTop" class="btn btn-primary" title="Retour en haut">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Intégration de Bootstrap 5.3 JS (Bundle avec Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Intégration de jQuery, DataTables et initialisation -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('table.datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
                        },
                        "columnDefs": [
                            { "orderable": false, "targets": -1 }
                        ],
                        "autoWidth": false,
                        "destroy": true
                    });
                }
            });
        });
    </script>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Scripts JS personnalisés -->
    <script src="<?php echo BASE_URL; ?>main.js"></script>
    
    <script>
        // Initialiser AOS
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        }
        
        // Bouton retour en haut
        const backToTopBtn = document.getElementById('backToTop');
        if (backToTopBtn) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            });
            
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
        
        // Navbar scroll effect
        let lastScrollPos = 0;
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScrollPos = currentScroll;
        });
    </script>
</body>
</html>
