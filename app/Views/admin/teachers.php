<!-- Gestion des Enseignants - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0"><i class="bi bi-person-badge me-2"></i><?php echo $data['title']??'Gestion des Enseignants'; ?></h1>
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="bi bi-plus-circle me-1"></i>Ajouter
        </button>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Enseignants</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" class="form-control" id="filter_search" placeholder="Rechercher par nom, prénom, email ou téléphone...">
            </div>
        </div>
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0" id="teacherTable">
                <thead class="table-light">
                    <tr><th>Nom Complet</th><th>Email</th><th>Téléphone</th><th>Date d'Embauche</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['teachers'])): foreach($data['teachers'] as $t): ?>
                        <tr data-search="<?php echo htmlspecialchars(strtolower($t['first_name'].' '.$t['last_name'].' '.$t['email'].' '.($t['phone']??''))); ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="teacher-icon bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person-badge text-info"></i>
                                    </div>
                                    <span class="fw-bold"><?php echo htmlspecialchars($t['first_name'].' '.$t['last_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($t['email']); ?></td>
                            <td><?php echo htmlspecialchars($t['phone']??'N/A'); ?></td>
                            <td><span class="badge bg-success px-3 py-2"><?php echo htmlspecialchars(date('d/m/Y',strtotime($t['hire_date']))); ?></span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editTeacherModal" data-id="<?php echo $t['id']; ?>" data-uid="<?php echo $t['user_id']; ?>" data-fn="<?php echo htmlspecialchars($t['first_name']); ?>" data-ln="<?php echo htmlspecialchars($t['last_name']); ?>" data-em="<?php echo htmlspecialchars($t['email']); ?>" data-ph="<?php echo htmlspecialchars($t['phone']??''); ?>" data-hd="<?php echo htmlspecialchars($t['hire_date']); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTeacherModal" data-id="<?php echo $t['id']; ?>" data-name="<?php echo htmlspecialchars($t['first_name'].' '.$t['last_name']); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucun enseignant</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-lg-none" id="teacherCards">
            <?php if(!empty($data['teachers'])): foreach($data['teachers'] as $t): ?>
                <div class="card mb-3 teacher-card" data-search="<?php echo htmlspecialchars(strtolower($t['first_name'].' '.$t['last_name'].' '.$t['email'].' '.($t['phone']??''))); ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="teacher-icon bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person-badge text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($t['first_name'].' '.$t['last_name']); ?></h6>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($t['email']); ?></small>
                            </div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Tél: </small><?php echo htmlspecialchars($t['phone']??'N/A'); ?><br>
                            <small class="text-muted">Embauché le: </small><span class="badge bg-success"><?php echo htmlspecialchars(date('d/m/Y',strtotime($t['hire_date']))); ?></span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editTeacherModal" data-id="<?php echo $t['id']; ?>" data-uid="<?php echo $t['user_id']; ?>" data-fn="<?php echo htmlspecialchars($t['first_name']); ?>" data-ln="<?php echo htmlspecialchars($t['last_name']); ?>" data-em="<?php echo htmlspecialchars($t['email']); ?>" data-ph="<?php echo htmlspecialchars($t['phone']??''); ?>" data-hd="<?php echo htmlspecialchars($t['hire_date']); ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button type="button" class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteTeacherModal" data-id="<?php echo $t['id']; ?>" data-name="<?php echo htmlspecialchars($t['first_name'].' '.$t['last_name']); ?>">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucun enseignant</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="addTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nouvel Enseignant</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/addTeacher" method="POST">
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
                        <label class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date d'Embauche</label>
                        <input type="date" class="form-control" name="hire_date" required>
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
<div class="modal fade" id="editTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier Enseignant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/editTeacher" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="teacher_id" id="edit_id">
                    <input type="hidden" name="user_id" id="edit_uid">
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
                        <label class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="phone" id="edit_ph">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date d'Embauche</label>
                        <input type="date" class="form-control" name="hire_date" id="edit_hd" required>
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
<div class="modal fade" id="deleteTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/deleteTeacher" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Supprimer l'enseignant <strong id="delete_name"></strong> ?</p>
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
.teacher-icon{width:40px;height:40px;display:flex;align-items:center;justify-content:center;}
</style>

<script>
function filterTeachers(){
    const searchFilter=document.getElementById('filter_search').value.toLowerCase();
    const rows=document.querySelectorAll('#teacherTable tbody tr[data-search]');
    const cards=document.querySelectorAll('#teacherCards .teacher-card');
    rows.forEach(row=>{
        const rowSearch=row.getAttribute('data-search');
        row.style.display=!searchFilter||rowSearch.includes(searchFilter)?'':'none';
    });
    cards.forEach(card=>{
        const cardSearch=card.getAttribute('data-search');
        card.style.display=!searchFilter||cardSearch.includes(searchFilter)?'':'none';
    });
}
document.getElementById('filter_search')?.addEventListener('input',filterTeachers);
document.getElementById('editTeacherModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('edit_id').value=btn.getAttribute('data-id');
    document.getElementById('edit_uid').value=btn.getAttribute('data-uid');
    document.getElementById('edit_fn').value=btn.getAttribute('data-fn');
    document.getElementById('edit_ln').value=btn.getAttribute('data-ln');
    document.getElementById('edit_em').value=btn.getAttribute('data-em');
    document.getElementById('edit_ph').value=btn.getAttribute('data-ph');
    document.getElementById('edit_hd').value=btn.getAttribute('data-hd');
});
document.getElementById('deleteTeacherModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('delete_id').value=btn.getAttribute('data-id');
    document.getElementById('delete_name').textContent=btn.getAttribute('data-name');
});
</script>
