<!-- Gestion des Étudiants - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0"><i class="bi bi-mortarboard me-2"></i><?php echo $data['title']??'Gestion des Étudiants'; ?></h1>
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="bi bi-plus-circle me-1"></i>Ajouter
        </button>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Étudiants</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="filter_class">
                    <option value="">Toutes les classes</option>
                    <?php foreach($data['classes'] as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8">
                <input type="text" class="form-control" id="filter_search" placeholder="Rechercher par nom, prénom, email ou matricule...">
            </div>
        </div>
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0" id="studentTable">
                <thead class="table-light">
                    <tr><th>Matricule</th><th>Nom Complet</th><th>Email</th><th>Classe</th><th>Date Naissance</th><th>Téléphone</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['students'])): foreach($data['students'] as $s): ?>
                        <tr data-class="<?php echo htmlspecialchars($s['class_name']??''); ?>" data-search="<?php echo htmlspecialchars(strtolower($s['first_name'].' '.$s['last_name'].' '.$s['email'].' '.$s['student_id_number'])); ?>">
                            <td><span class="badge bg-primary px-3 py-2"><?php echo htmlspecialchars($s['student_id_number']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="student-icon bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-success"></i>
                                    </div>
                                    <span><?php echo htmlspecialchars($s['first_name'].' '.$s['last_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td><?php echo $s['class_name']?'<span class="badge bg-info px-3 py-2">'.htmlspecialchars($s['class_name']).'</span>':'<span class="text-muted">Non assigné</span>'; ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($s['date_of_birth']))); ?></td>
                            <td><?php echo htmlspecialchars($s['phone']??'N/A'); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editStudentModal" data-id="<?php echo $s['id']; ?>" data-uid="<?php echo $s['user_id']; ?>" data-num="<?php echo htmlspecialchars($s['student_id_number']); ?>" data-fn="<?php echo htmlspecialchars($s['first_name']); ?>" data-ln="<?php echo htmlspecialchars($s['last_name']); ?>" data-em="<?php echo htmlspecialchars($s['email']); ?>" data-cid="<?php echo htmlspecialchars($s['class_id']??''); ?>" data-dob="<?php echo htmlspecialchars($s['date_of_birth']); ?>" data-addr="<?php echo htmlspecialchars($s['address']??''); ?>" data-ph="<?php echo htmlspecialchars($s['phone']??''); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal" data-id="<?php echo $s['id']; ?>" data-name="<?php echo htmlspecialchars($s['first_name'].' '.$s['last_name']); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucun étudiant</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-lg-none" id="studentCards">
            <?php if(!empty($data['students'])): foreach($data['students'] as $s): ?>
                <div class="card mb-3 student-card" data-class="<?php echo htmlspecialchars($s['class_name']??''); ?>" data-search="<?php echo htmlspecialchars(strtolower($s['first_name'].' '.$s['last_name'].' '.$s['email'].' '.$s['student_id_number'])); ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="student-icon bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($s['first_name'].' '.$s['last_name']); ?></h6>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($s['email']); ?></small>
                                <span class="badge bg-primary mt-1"><?php echo htmlspecialchars($s['student_id_number']); ?></span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Classe: </small><?php echo $s['class_name']?'<span class="badge bg-info">'.htmlspecialchars($s['class_name']).'</span>':'<span class="text-muted">Non assigné</span>'; ?><br>
                            <small class="text-muted">Né(e) le: </small><?php echo htmlspecialchars(date('d/m/Y',strtotime($s['date_of_birth']))); ?><br>
                            <small class="text-muted">Tél: </small><?php echo htmlspecialchars($s['phone']??'N/A'); ?>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editStudentModal" data-id="<?php echo $s['id']; ?>" data-uid="<?php echo $s['user_id']; ?>" data-num="<?php echo htmlspecialchars($s['student_id_number']); ?>" data-fn="<?php echo htmlspecialchars($s['first_name']); ?>" data-ln="<?php echo htmlspecialchars($s['last_name']); ?>" data-em="<?php echo htmlspecialchars($s['email']); ?>" data-cid="<?php echo htmlspecialchars($s['class_id']??''); ?>" data-dob="<?php echo htmlspecialchars($s['date_of_birth']); ?>" data-addr="<?php echo htmlspecialchars($s['address']??''); ?>" data-ph="<?php echo htmlspecialchars($s['phone']??''); ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button type="button" class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteStudentModal" data-id="<?php echo $s['id']; ?>" data-name="<?php echo htmlspecialchars($s['first_name'].' '.$s['last_name']); ?>">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucun étudiant</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nouvel Étudiant</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/addStudent" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Matricule</label>
                            <input type="text" class="form-control" name="student_id_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Classe</label>
                            <select class="form-select" name="class_id">
                                <option value="">Aucune classe</option>
                                <?php foreach($data['classes'] as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" name="date_of_birth" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="address">
                        </div>
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
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier Étudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/editStudent" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="student_id" id="edit_id">
                    <input type="hidden" name="user_id" id="edit_uid">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="first_name" id="edit_fn" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="last_name" id="edit_ln" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_em" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Matricule</label>
                            <input type="text" class="form-control" name="student_id_number" id="edit_num" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Classe</label>
                            <select class="form-select" name="class_id" id="edit_cid">
                                <option value="">Aucune classe</option>
                                <?php foreach($data['classes'] as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" name="date_of_birth" id="edit_dob" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" class="form-control" name="phone" id="edit_ph">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nouveau mot de passe (optionnel)</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="address" id="edit_addr">
                        </div>
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
<div class="modal fade" id="deleteStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/deleteStudent" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Supprimer l'étudiant <strong id="delete_name"></strong> ?</p>
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
.student-icon{width:35px;height:35px;display:flex;align-items:center;justify-content:center;}
</style>

<script>
function filterStudents(){
    const classFilter=document.getElementById('filter_class').value.toLowerCase();
    const searchFilter=document.getElementById('filter_search').value.toLowerCase();
    const rows=document.querySelectorAll('#studentTable tbody tr[data-class]');
    const cards=document.querySelectorAll('#studentCards .student-card');
    rows.forEach(row=>{
        const rowClass=row.getAttribute('data-class').toLowerCase();
        const rowSearch=row.getAttribute('data-search');
        const classMatch=!classFilter||rowClass===classFilter;
        const searchMatch=!searchFilter||rowSearch.includes(searchFilter);
        row.style.display=(classMatch&&searchMatch)?'':'none';
    });
    cards.forEach(card=>{
        const cardClass=card.getAttribute('data-class').toLowerCase();
        const cardSearch=card.getAttribute('data-search');
        const classMatch=!classFilter||cardClass===classFilter;
        const searchMatch=!searchFilter||cardSearch.includes(searchFilter);
        card.style.display=(classMatch&&searchMatch)?'':'none';
    });
}
document.getElementById('filter_class')?.addEventListener('change',filterStudents);
document.getElementById('filter_search')?.addEventListener('input',filterStudents);
document.getElementById('editStudentModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('edit_id').value=btn.getAttribute('data-id');
    document.getElementById('edit_uid').value=btn.getAttribute('data-uid');
    document.getElementById('edit_num').value=btn.getAttribute('data-num');
    document.getElementById('edit_fn').value=btn.getAttribute('data-fn');
    document.getElementById('edit_ln').value=btn.getAttribute('data-ln');
    document.getElementById('edit_em').value=btn.getAttribute('data-em');
    document.getElementById('edit_cid').value=btn.getAttribute('data-cid');
    document.getElementById('edit_dob').value=btn.getAttribute('data-dob');
    document.getElementById('edit_addr').value=btn.getAttribute('data-addr');
    document.getElementById('edit_ph').value=btn.getAttribute('data-ph');
});
document.getElementById('deleteStudentModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('delete_id').value=btn.getAttribute('data-id');
    document.getElementById('delete_name').textContent=btn.getAttribute('data-name');
});
</script>
