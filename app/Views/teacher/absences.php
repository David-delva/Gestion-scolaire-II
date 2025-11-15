<!-- Version moderne optimisée -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="text-white mb-0"><i class="bi bi-calendar-x me-2"></i><?php echo $data['title']??'Gestion des Absences'; ?></h1>
        <button class="btn btn-light btn-lg shadow" data-bs-toggle="modal" data-bs-target="#addAbsenceModal"><i class="bi bi-plus-circle me-2"></i>Déclarer</button>
    </div>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-body p-4">
        <select class="form-select w-auto" id="filter_absence_class">
            <option value="">Toutes les classes</option>
            <?php foreach($data['allClasses'] as $class): ?><option value="<?php echo htmlspecialchars($class['name']); ?>"><?php echo htmlspecialchars($class['name']); ?></option><?php endforeach; ?>
        </select>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3"><h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Absences</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>ID</th><th>Étudiant</th><th>Date</th><th>Matière</th><th>Classe</th><th>Justifiée</th><th>Détails</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if(!empty($data['absences'])): foreach($data['absences'] as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['id']); ?></td>
                            <td><?php echo htmlspecialchars($a['student_first_name'].' '.$a['student_last_name']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></td>
                            <td><?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($a['class_name']??'N/A'); ?></td>
                            <td><span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?> px-3 py-2"><?php echo $a['justified']?'Oui':'Non'; ?></span></td>
                            <td><?php echo htmlspecialchars($a['justification_details']??'N/A'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editAbsenceModal"
                                    data-absence-id="<?php echo $a['id']; ?>" data-student-id="<?php echo $a['student_id']; ?>"
                                    data-subject-id="<?php echo $a['subject_id']??''; ?>" data-class-id="<?php echo $a['class_id']??''; ?>"
                                    data-absence-date="<?php echo $a['absence_date']; ?>" data-justified="<?php echo $a['justified']; ?>"
                                    data-justification-details="<?php echo htmlspecialchars($a['justification_details']??''); ?>"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAbsenceModal"
                                    data-absence-id="<?php echo $a['id']; ?>"
                                    data-absence-info="<?php echo htmlspecialchars($a['student_first_name'].' '.$a['student_last_name'].' le '.date('d/m/Y',strtotime($a['absence_date']))); ?>"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune absence</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-lg-none p-3">
            <?php if(!empty($data['absences'])): foreach($data['absences'] as $a): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($a['student_first_name'].' '.$a['student_last_name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></small>
                            </div>
                            <span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?>"><?php echo $a['justified']?'Justifiée':'Non justifiée'; ?></span>
                        </div>
                        <small class="text-muted d-block mb-1">Matière: <?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></small>
                        <small class="text-muted d-block mb-2">Classe: <?php echo htmlspecialchars($a['class_name']??'N/A'); ?></small>
                        <?php if($a['justification_details']): ?><small class="text-muted d-block mb-2">Détails: <?php echo htmlspecialchars($a['justification_details']); ?></small><?php endif; ?>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editAbsenceModal"
                                data-absence-id="<?php echo $a['id']; ?>" data-student-id="<?php echo $a['student_id']; ?>"
                                data-subject-id="<?php echo $a['subject_id']??''; ?>" data-class-id="<?php echo $a['class_id']??''; ?>"
                                data-absence-date="<?php echo $a['absence_date']; ?>" data-justified="<?php echo $a['justified']; ?>"
                                data-justification-details="<?php echo htmlspecialchars($a['justification_details']??''); ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteAbsenceModal"
                                data-absence-id="<?php echo $a['id']; ?>"
                                data-absence-info="<?php echo htmlspecialchars($a['student_first_name'].' '.$a['student_last_name'].' le '.date('d/m/Y',strtotime($a['absence_date']))); ?>">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune absence</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="addAbsenceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white"><h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Déclarer une absence</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="<?php echo BASE_URL; ?>teacher/addAbsence" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="mb-3"><label class="form-label">Classe</label><select class="form-select" id="add_absence_class_id" name="class_id" required><option value="">Sélectionner</option><?php foreach($data['allClasses'] as $c): ?><option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Matière</label><select class="form-select" id="add_subject_id" name="subject_id" required disabled><option>Choisir classe</option></select></div>
                    <div class="mb-3"><label class="form-label">Étudiant</label><select class="form-select" id="add_student_id" name="student_id" required disabled><option>Choisir classe</option></select></div>
                    <div class="mb-3"><label class="form-label">Date</label><input type="date" class="form-control" name="absence_date" value="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" id="add_justified" name="justified"><label class="form-check-label" for="add_justified">Justifiée</label></div>
                    <div class="mb-3"><label class="form-label">Détails</label><textarea class="form-control" name="justification_details" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-success">Déclarer</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAbsenceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="<?php echo BASE_URL; ?>teacher/editAbsence" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="absence_id" id="edit_absence_id">
                    <div class="mb-3"><label class="form-label">Étudiant</label><select class="form-select" id="edit_student_id" name="student_id" required><?php foreach($data['allStudents'] as $s): ?><option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['first_name'].' '.$s['last_name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Date</label><input type="date" class="form-control" id="edit_absence_date" name="absence_date" required></div>
                    <div class="mb-3"><label class="form-label">Matière</label><select class="form-select" id="edit_subject_id" name="subject_id"><option value="">Sélectionner</option><?php foreach($data['allSubjects'] as $s): ?><option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Classe</label><select class="form-select" id="edit_class_id" name="class_id"><option value="">Sélectionner</option><?php foreach($data['allClasses'] as $c): ?><option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" id="edit_justified" name="justified"><label class="form-check-label" for="edit_justified">Justifiée</label></div>
                    <div class="mb-3"><label class="form-label">Détails</label><textarea class="form-control" id="edit_justification_details" name="justification_details" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-warning">Modifier</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAbsenceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white"><h5 class="modal-title"><i class="bi bi-trash me-2"></i>Confirmer</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="<?php echo BASE_URL; ?>teacher/deleteAbsence" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_absence_id">
                    <p>Supprimer l'absence de <strong id="delete_absence_info"></strong> ?</p>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-danger">Supprimer</button></div>
            </form>
        </div>
    </div>
</div>

<script>
const allStudents=<?php echo json_encode($data['allStudents']); ?>;
const allSubjects=<?php echo json_encode($data['allSubjects']); ?>;
const assignments=<?php echo json_encode($data['teacherAssignments']); ?>;
document.getElementById('add_absence_class_id').addEventListener('change',function(){
    const classId=this.value;
    const subjectSelect=document.getElementById('add_subject_id');
    const studentSelect=document.getElementById('add_student_id');
    if(!classId){subjectSelect.disabled=true;studentSelect.disabled=true;return;}
    const classSubjects=assignments.filter(a=>a.class_id==classId);
    subjectSelect.disabled=false;subjectSelect.innerHTML='<option value="">Sélectionner</option>';
    classSubjects.forEach(a=>{const opt=document.createElement('option');opt.value=a.subject_id;opt.textContent=a.subject_name;subjectSelect.appendChild(opt);});
    const classStudents=allStudents.filter(s=>s.class_id==classId);
    studentSelect.disabled=false;studentSelect.innerHTML='<option value="">Sélectionner</option>';
    classStudents.forEach(s=>{const opt=document.createElement('option');opt.value=s.id;opt.textContent=s.first_name+' '+s.last_name;studentSelect.appendChild(opt);});
});
document.getElementById('editAbsenceModal').addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    this.querySelector('#edit_absence_id').value=btn.getAttribute('data-absence-id');
    this.querySelector('#edit_student_id').value=btn.getAttribute('data-student-id');
    this.querySelector('#edit_subject_id').value=btn.getAttribute('data-subject-id');
    this.querySelector('#edit_class_id').value=btn.getAttribute('data-class-id');
    this.querySelector('#edit_absence_date').value=btn.getAttribute('data-absence-date');
    this.querySelector('#edit_justified').checked=(btn.getAttribute('data-justified')==1);
    this.querySelector('#edit_justification_details').value=btn.getAttribute('data-justification-details');
});
document.getElementById('deleteAbsenceModal').addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    this.querySelector('#delete_absence_id').value=btn.getAttribute('data-absence-id');
    this.querySelector('#delete_absence_info').textContent=btn.getAttribute('data-absence-info');
});
document.getElementById('filter_absence_class')?.addEventListener('change',function(){
    const selectedClass=this.value.toLowerCase();
    document.querySelectorAll('table tbody tr').forEach(row=>{
        const classCell=row.cells[4]?.textContent.toLowerCase();
        row.style.display=!selectedClass||classCell.includes(selectedClass)?'':'none';
    });
});
</script>
