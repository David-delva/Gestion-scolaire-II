<!--
Fichier : app/Views/auth/profile.php
Description : Vue pour l'affichage et la modification du profil utilisateur.
Version : 1.0
Date : Octobre 2025
-->

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0"><i class="bi bi-person-circle"></i> Mon Profil</h3>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $data['success']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>auth/profile" method="POST">
                    <!-- Champ CSRF Token pour la sécurité -->
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                    <h5 class="mb-3">Informations personnelles</h5>
                    <?php if ($_SESSION['role_name'] !== 'Étudiant'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($data['user']['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($data['user']['last_name'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="first_name" value="<?php echo htmlspecialchars($data['user']['first_name'] ?? ''); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="last_name" value="<?php echo htmlspecialchars($data['user']['last_name'] ?? ''); ?>" disabled>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['user']['email'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <input type="text" class="form-control" id="role" value="<?php echo htmlspecialchars($data['user']['role_name'] ?? ''); ?>" disabled>
                    </div>

                    <h5 class="mt-4 mb-3">Changer le mot de passe</h5>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_new_password" class="form-label">Confirmer nouveau mot de passe</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center text-muted">
                <small>Dernière mise à jour : <?php echo htmlspecialchars($data['user']['updated_at'] ?? 'N/A'); ?></small>
            </div>
        </div>
    </div>
</div>
