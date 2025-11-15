<!-- Gestion des Utilisateurs - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0"><i class="bi bi-people me-2"></i><?php echo $data['title']??'Gestion des Utilisateurs'; ?></h1>
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-circle me-1"></i>Ajouter
        </button>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Utilisateurs</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="filter_role">
                    <option value="">Tous les rôles</option>
                    <?php foreach($data['roles'] as $r): ?>
                        <option value="<?php echo htmlspecialchars($r['name']); ?>"><?php echo htmlspecialchars($r['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" id="filter_search" placeholder="Rechercher par nom, prénom ou email...">
            </div>
        </div>
        <!-- Vue Desktop -->
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0" id="userTable">
                <thead class="table-light">
                    <tr><th>Nom Complet</th><th>Email</th><th>Rôle</th><th>Date Création</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['users'])): foreach($data['users'] as $u): ?>
                        <tr data-role="<?php echo htmlspecialchars($u['role_name']); ?>" data-search="<?php echo htmlspecialchars(strtolower($u['first_name'].' '.$u['last_name'].' '.$u['email'])); ?>" style="<?php echo !$u['is_active']?'opacity:0.5;':''; ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-icon bg-<?php echo $u['role_name']=='Administrateur'?'danger':($u['role_name']=='Enseignant'?'info':'success'); ?> bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-<?php echo $u['role_name']=='Administrateur'?'danger':($u['role_name']=='Enseignant'?'info':'success'); ?>"></i>
                                    </div>
                                    <span class="fw-bold"><?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?></span>
                                    <?php if(!$u['is_active']): ?><span class="badge bg-secondary ms-2">Inactif</span><?php endif; ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><span class="badge bg-<?php echo $u['role_name']=='Administrateur'?'danger':($u['role_name']=='Enseignant'?'info':'success'); ?> px-3 py-2"><?php echo htmlspecialchars($u['role_name']); ?></span></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($u['created_at']))); ?></td>
                            <td>
                                <?php if($u['id']!=$_SESSION['user_id']): ?>
                                <form action="<?php echo BASE_URL; ?>admin/toggleUserStatus" method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                    <input type="hidden" name="is_active" value="<?php echo $u['is_active']?'0':'1'; ?>">
                                    <button type="submit" class="btn btn-sm btn-<?php echo $u['is_active']?'secondary':'success'; ?> me-1" title="<?php echo $u['is_active']?'Désactiver':'Activer'; ?>">
                                        <i class="bi bi-<?php echo $u['is_active']?'x-circle':'check-circle'; ?>"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="<?php echo $u['id']; ?>" data-fn="<?php echo htmlspecialchars($u['first_name']); ?>" data-ln="<?php echo htmlspecialchars($u['last_name']); ?>" data-em="<?php echo htmlspecialchars($u['email']); ?>" data-rid="<?php echo $u['role_id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <?php if($u['id']!=$_SESSION['user_id']): ?>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="<?php echo $u['id']; ?>" data-name="<?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucun utilisateur</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Vue Mobile -->
        <div class="d-lg-none" id="userCards">
            <?php if(!empty($data['users'])): foreach($data['users'] as $u): ?>
                <div class="card mb-3 user-card" data-role="<?php echo htmlspecialchars($u['role_name']); ?>" data-search="<?php echo htmlspecialchars(strtolower($u['first_name'].' '.$u['last_name'].' '.$u['email'])); ?>" style="<?php echo !$u['is_active']?'opacity:0.5;':''; ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="user-icon bg-<?php echo $u['role_name']=='Administrateur'?'danger':($u['role_name']=='Enseignant'?'info':'success'); ?> bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person text-<?php echo $u['role_name']=='Administrateur'?'danger':($u['role_name']=='Enseignant'?'info':'success'); ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?></h6>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($u['email']); ?></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-<?php echo $u['role_name']=='Administrateur'?'danger':($u['role_name']=='Enseignant'?'info':'success'); ?>"><?php echo htmlspecialchars($u['role_name']); ?></span>
                            <?php if(!$u['is_active']): ?><span class="badge bg-secondary">Inactif</span><?php endif; ?>
                            <small class="text-muted"><?php echo htmlspecialchars(date('d/m/Y',strtotime($u['created_at']))); ?></small>
                        </div>
                        <div class="d-flex gap-2">
                            <?php if($u['id']!=$_SESSION['user_id']): ?>
                            <form action="<?php echo BASE_URL; ?>admin/toggleUserStatus" method="POST" class="flex-fill">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                <input type="hidden" name="is_active" value="<?php echo $u['is_active']?'0':'1'; ?>">
                                <button type="submit" class="btn btn-sm btn-<?php echo $u['is_active']?'secondary':'success'; ?> w-100">
                                    <i class="bi bi-<?php echo $u['is_active']?'x-circle':'check-circle'; ?>"></i> <?php echo $u['is_active']?'Désactiver':'Activer'; ?>
                                </button>
                            </form>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="<?php echo $u['id']; ?>" data-fn="<?php echo htmlspecialchars($u['first_name']); ?>" data-ln="<?php echo htmlspecialchars($u['last_name']); ?>" data-em="<?php echo htmlspecialchars($u['email']); ?>" data-rid="<?php echo $u['role_id']; ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <?php if($u['id']!=$_SESSION['user_id']): ?>
                            <button type="button" class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="<?php echo $u['id']; ?>" data-name="<?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?>">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucun utilisateur</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nouvel Utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/addUser" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select class="form-select" name="role_id" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach($data['roles'] as $r): ?>
                                <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/editUser" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="first_name" id="edit_fn" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="last_name" id="edit_ln" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_em" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select class="form-select" name="role_id" id="edit_rid" required>
                            <?php foreach($data['roles'] as $r): ?>
                                <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe (optionnel)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/deleteUser" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Supprimer l'utilisateur <strong id="delete_name"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.user-icon{width:35px;height:35px;display:flex;align-items:center;justify-content:center;}
@media (max-width:991px){.user-card{border-left:3px solid #dee2e6;}}
</style>

<script>
function filterUsers(){
    const roleFilter=document.getElementById('filter_role').value.toLowerCase();
    const searchFilter=document.getElementById('filter_search').value.toLowerCase();
    const rows=document.querySelectorAll('#userTable tbody tr[data-role]');
    const cards=document.querySelectorAll('#userCards .user-card[data-role]');
    rows.forEach(row=>{
        const rowRole=row.getAttribute('data-role').toLowerCase();
        const rowSearch=row.getAttribute('data-search');
        const roleMatch=!roleFilter||rowRole===roleFilter;
        const searchMatch=!searchFilter||rowSearch.includes(searchFilter);
        row.style.display=(roleMatch&&searchMatch)?'':'none';
    });
    cards.forEach(card=>{
        const cardRole=card.getAttribute('data-role').toLowerCase();
        const cardSearch=card.getAttribute('data-search');
        const roleMatch=!roleFilter||cardRole===roleFilter;
        const searchMatch=!searchFilter||cardSearch.includes(searchFilter);
        card.style.display=(roleMatch&&searchMatch)?'':'none';
    });
}
document.getElementById('filter_role')?.addEventListener('change',filterUsers);
document.getElementById('filter_search')?.addEventListener('input',filterUsers);
document.getElementById('editUserModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('edit_id').value=btn.getAttribute('data-id');
    document.getElementById('edit_fn').value=btn.getAttribute('data-fn');
    document.getElementById('edit_ln').value=btn.getAttribute('data-ln');
    document.getElementById('edit_em').value=btn.getAttribute('data-em');
    document.getElementById('edit_rid').value=btn.getAttribute('data-rid');
});
document.getElementById('deleteUserModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('delete_id').value=btn.getAttribute('data-id');
    document.getElementById('delete_name').textContent=btn.getAttribute('data-name');
});
</script>
