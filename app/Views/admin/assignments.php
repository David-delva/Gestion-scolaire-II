<!-- Gestion des Affectations - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <h1 class="text-white mb-0"><i class="bi bi-link-45deg me-2"></i><?php echo $data['title']??'Gestion des Affectations'; ?></h1>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-workspace me-2"></i>Affectations Enseignants → Matières → Classes</h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addTeacherAssignmentModal">
                <i class="bi bi-plus-circle me-1"></i>Ajouter
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="filter_class">
                    <option value="">Toutes les classes</option>
                    <?php foreach($data['allClasses'] as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filter_subject">
                    <option value="">Toutes les matières</option>
                    <?php foreach($data['allSubjects'] as $s): ?>
                        <option value="<?php echo htmlspecialchars($s['name']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="filter_teacher" placeholder="Rechercher un enseignant...">
            </div>
        </div>
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0" id="assignmentTable">
                <thead class="table-light">
                    <tr><th>Classe</th><th>Matière</th><th>Enseignant</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['teacherAssignments'])): foreach($data['teacherAssignments'] as $a): ?>
                        <tr data-class="<?php echo htmlspecialchars($a['class_name']); ?>" data-subject="<?php echo htmlspecialchars($a['subject_name']); ?>" data-teacher="<?php echo htmlspecialchars($a['teacher_first_name'].' '.$a['teacher_last_name']); ?>">
                            <td><span class="badge bg-info px-3 py-2"><?php echo htmlspecialchars($a['class_name']); ?></span></td>
                            <td><span class="badge bg-success px-3 py-2"><?php echo htmlspecialchars($a['subject_name']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="teacher-icon bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <span><?php echo htmlspecialchars($a['teacher_first_name'].' '.$a['teacher_last_name']); ?></span>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTeacherAssignmentModal" data-id="<?php echo $a['id']; ?>" data-info="<?php echo htmlspecialchars($a['teacher_first_name'].' '.$a['teacher_last_name'].' - '.$a['subject_name'].' en '.$a['class_name']); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune affectation</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-lg-none" id="assignmentCards">
            <?php if(!empty($data['teacherAssignments'])): foreach($data['teacherAssignments'] as $a): ?>
                <div class="card mb-3 assignment-card" data-class="<?php echo htmlspecialchars($a['class_name']); ?>" data-subject="<?php echo htmlspecialchars($a['subject_name']); ?>" data-teacher="<?php echo htmlspecialchars($a['teacher_first_name'].' '.$a['teacher_last_name']); ?>">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-2">
                            <div class="teacher-icon bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo htmlspecialchars($a['teacher_first_name'].' '.$a['teacher_last_name']); ?></h6>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info me-1"><?php echo htmlspecialchars($a['class_name']); ?></span>
                            <span class="badge bg-success"><?php echo htmlspecialchars($a['subject_name']); ?></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteTeacherAssignmentModal" data-id="<?php echo $a['id']; ?>" data-info="<?php echo htmlspecialchars($a['teacher_first_name'].' '.$a['teacher_last_name'].' - '.$a['subject_name'].' en '.$a['class_name']); ?>">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune affectation</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Ajouter Affectation -->
<div class="modal fade" id="addTeacherAssignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nouvelle Affectation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/addTeacherAssignment" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Enseignant</label>
                        <select class="form-select" name="teacher_id" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach($data['allTeachers'] as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['first_name'].' '.$t['last_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matière</label>
                        <select class="form-select" name="subject_id" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach($data['allSubjects'] as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Classe</label>
                        <select class="form-select" name="class_id" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach($data['allClasses'] as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
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

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteTeacherAssignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/deleteTeacherAssignment" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Supprimer l'affectation : <strong id="delete_info"></strong> ?</p>
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
.teacher-icon{width:35px;height:35px;display:flex;align-items:center;justify-content:center;}
</style>

<script>
function filterAssignments(){
    const classFilter=document.getElementById('filter_class').value.toLowerCase();
    const subjectFilter=document.getElementById('filter_subject').value.toLowerCase();
    const teacherFilter=document.getElementById('filter_teacher').value.toLowerCase();
    const rows=document.querySelectorAll('#assignmentTable tbody tr[data-class]');
    const cards=document.querySelectorAll('#assignmentCards .assignment-card');
    rows.forEach(row=>{
        const rowClass=row.getAttribute('data-class').toLowerCase();
        const rowSubject=row.getAttribute('data-subject').toLowerCase();
        const rowTeacher=row.getAttribute('data-teacher').toLowerCase();
        const classMatch=!classFilter||rowClass===classFilter;
        const subjectMatch=!subjectFilter||rowSubject===subjectFilter;
        const teacherMatch=!teacherFilter||rowTeacher.includes(teacherFilter);
        row.style.display=(classMatch&&subjectMatch&&teacherMatch)?'':'none';
    });
    cards.forEach(card=>{
        const cardClass=card.getAttribute('data-class').toLowerCase();
        const cardSubject=card.getAttribute('data-subject').toLowerCase();
        const cardTeacher=card.getAttribute('data-teacher').toLowerCase();
        const classMatch=!classFilter||cardClass===classFilter;
        const subjectMatch=!subjectFilter||cardSubject===subjectFilter;
        const teacherMatch=!teacherFilter||cardTeacher.includes(teacherFilter);
        card.style.display=(classMatch&&subjectMatch&&teacherMatch)?'':'none';
    });
}
document.getElementById('filter_class')?.addEventListener('change',filterAssignments);
document.getElementById('filter_subject')?.addEventListener('change',filterAssignments);
document.getElementById('filter_teacher')?.addEventListener('input',filterAssignments);
document.getElementById('deleteTeacherAssignmentModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('delete_id').value=btn.getAttribute('data-id');
    document.getElementById('delete_info').textContent=btn.getAttribute('data-info');
});
</script>
