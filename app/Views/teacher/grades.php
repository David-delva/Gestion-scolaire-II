<!-- Version moderne simplifiée - Remplacer grades.php par ce fichier -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="text-white mb-0"><i class="bi bi-journal-text me-2"></i><?php echo $data['title']??'Gestion des Notes'; ?></h1>
        <button class="btn btn-light btn-lg shadow" data-bs-toggle="modal" data-bs-target="#addGradeModal">
            <i class="bi bi-plus-circle me-2"></i>Ajouter une note
        </button>
    </div>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-body p-4">
        <select class="form-select w-auto" id="filter_grade_class">
            <option value="">Toutes les classes</option>
            <?php 
            $uniqueClasses=[];
            foreach($data['grades'] as $grade){
                if(!empty($grade['class_name'])&&!in_array($grade['class_name'],$uniqueClasses)){
                    $uniqueClasses[]=$grade['class_name'];
                }
            }
            foreach($uniqueClasses as $className): ?>
                <option value="<?php echo htmlspecialchars($className); ?>"><?php echo htmlspecialchars($className); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Notes</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>ID</th><th>Étudiant</th><th>Matière</th><th>Catégorie</th><th>Note</th><th>Date</th><th>Commentaire</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if(!empty($data['grades'])): foreach($data['grades'] as $grade): ?>
                        <tr data-class="<?php echo htmlspecialchars($grade['class_name']??''); ?>">
                            <td><?php echo htmlspecialchars($grade['id']); ?></td>
                            <td><?php echo htmlspecialchars($grade['student_first_name'].' '.$grade['student_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($grade['category']??'Devoir'); ?></span></td>
                            <td><span class="badge bg-<?php echo($grade['grade_value']>=10)?'success':'danger'; ?> px-3 py-2"><?php echo htmlspecialchars(number_format($grade['grade_value'],2)); ?></span></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($grade['grade_date']))); ?></td>
                            <td><?php echo htmlspecialchars($grade['comment']??'N/A'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editGradeModal"
                                    data-grade-id="<?php echo $grade['id']; ?>" data-student-id="<?php echo $grade['student_id']; ?>"
                                    data-subject-id="<?php echo $grade['subject_id']; ?>" data-category="<?php echo htmlspecialchars($grade['category']??'Devoir'); ?>" data-grade-value="<?php echo htmlspecialchars($grade['grade_value']); ?>"
                                    data-grade-date="<?php echo htmlspecialchars($grade['grade_date']); ?>" data-comment="<?php echo htmlspecialchars($grade['comment']??''); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGradeModal"
                                    data-grade-id="<?php echo $grade['id']; ?>"
                                    data-grade-info="<?php echo htmlspecialchars($grade['student_first_name'].' '.$grade['student_last_name'].' - '.$grade['subject_name'].' : '.number_format($grade['grade_value'],2)); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune note</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-lg-none p-3">
            <?php if(!empty($data['grades'])): foreach($data['grades'] as $grade): ?>
                <div class="card mb-3" data-class="<?php echo htmlspecialchars($grade['class_name']??''); ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($grade['student_first_name'].' '.$grade['student_last_name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($grade['subject_name']); ?> - <span class="badge bg-info"><?php echo htmlspecialchars($grade['category']??'Devoir'); ?></span></small>
                            </div>
                            <span class="badge bg-<?php echo($grade['grade_value']>=10)?'success':'danger'; ?> px-3 py-2"><?php echo htmlspecialchars(number_format($grade['grade_value'],2)); ?>/20</span>
                        </div>
                        <small class="text-muted d-block mb-2">Date: <?php echo htmlspecialchars(date('d/m/Y',strtotime($grade['grade_date']))); ?></small>
                        <?php if($grade['comment']): ?><small class="text-muted d-block mb-2">Commentaire: <?php echo htmlspecialchars($grade['comment']); ?></small><?php endif; ?>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#editGradeModal"
                                data-grade-id="<?php echo $grade['id']; ?>" data-student-id="<?php echo $grade['student_id']; ?>"
                                data-subject-id="<?php echo $grade['subject_id']; ?>" data-category="<?php echo htmlspecialchars($grade['category']??'Devoir'); ?>" data-grade-value="<?php echo htmlspecialchars($grade['grade_value']); ?>"
                                data-grade-date="<?php echo htmlspecialchars($grade['grade_date']); ?>" data-comment="<?php echo htmlspecialchars($grade['comment']??''); ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#deleteGradeModal"
                                data-grade-id="<?php echo $grade['id']; ?>"
                                data-grade-info="<?php echo htmlspecialchars($grade['student_first_name'].' '.$grade['student_last_name'].' - '.$grade['subject_name'].' : '.number_format($grade['grade_value'],2)); ?>">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune note</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals optimisés -->
<div class="modal fade" id="addGradeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Ajouter une note</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>teacher/addGrade" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="mb-3"><label class="form-label">Classe</label><select class="form-select" id="add_class_id" required><option value="">Sélectionner</option><?php foreach($data['teacherAssignments'] as $a): ?><option value="<?php echo $a['class_id']; ?>" data-subject-id="<?php echo $a['subject_id']; ?>"><?php echo htmlspecialchars($a['class_name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Matière</label><select class="form-select" id="add_subject_id" name="subject_id" required disabled><option>Choisir classe</option></select></div>
                    <div class="mb-3"><label class="form-label">Étudiant</label><select class="form-select" id="add_student_id" name="student_id" required disabled><option>Choisir classe</option></select></div>
                    <div class="mb-3"><label class="form-label">Catégorie</label><select class="form-select" name="category" required><option value="Devoir">Devoir</option><option value="Contrôle">Contrôle</option><option value="Examen">Examen</option><option value="Oral">Oral</option><option value="Projet">Projet</option><option value="TP">TP</option><option value="Autre">Autre</option></select></div>
                    <div class="mb-3"><label class="form-label">Note (sur 20)</label><input type="number" step="0.01" min="0" max="20" class="form-control" name="grade_value" required></div>
                    <div class="mb-3"><label class="form-label">Date</label><input type="date" class="form-control" name="grade_date" value="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="mb-3"><label class="form-label">Commentaire</label><textarea class="form-control" name="comment" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-success">Ajouter</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editGradeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier la note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>teacher/editGrade" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="grade_id" id="edit_grade_id">
                    <div class="mb-3"><label class="form-label">Étudiant</label><select class="form-select" id="edit_student_id" name="student_id" required><?php foreach($data['allStudents'] as $s): ?><option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['first_name'].' '.$s['last_name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Matière</label><select class="form-select" id="edit_subject_id" name="subject_id" required><?php foreach($data['allSubjects'] as $s): ?><option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label">Catégorie</label><select class="form-select" id="edit_category" name="category" required><option value="Devoir">Devoir</option><option value="Contrôle">Contrôle</option><option value="Examen">Examen</option><option value="Oral">Oral</option><option value="Projet">Projet</option><option value="TP">TP</option><option value="Autre">Autre</option></select></div>
                    <div class="mb-3"><label class="form-label">Note</label><input type="number" step="0.01" min="0" max="20" class="form-control" id="edit_grade_value" name="grade_value" required></div>
                    <div class="mb-3"><label class="form-label">Date</label><input type="date" class="form-control" id="edit_grade_date" name="grade_date" required></div>
                    <div class="mb-3"><label class="form-label">Commentaire</label><textarea class="form-control" id="edit_comment" name="comment" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-warning">Modifier</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteGradeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Confirmer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>teacher/deleteGrade" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_grade_id">
                    <p>Supprimer : <strong id="delete_grade_info"></strong> ?</p>
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
document.getElementById('add_class_id').addEventListener('change',function(){
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
document.getElementById('editGradeModal').addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    this.querySelector('#edit_grade_id').value=btn.getAttribute('data-grade-id');
    this.querySelector('#edit_student_id').value=btn.getAttribute('data-student-id');
    this.querySelector('#edit_subject_id').value=btn.getAttribute('data-subject-id');
    this.querySelector('#edit_category').value=btn.getAttribute('data-category');
    this.querySelector('#edit_grade_value').value=btn.getAttribute('data-grade-value');
    this.querySelector('#edit_grade_date').value=btn.getAttribute('data-grade-date');
    this.querySelector('#edit_comment').value=btn.getAttribute('data-comment');
});
document.getElementById('deleteGradeModal').addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    this.querySelector('#delete_grade_id').value=btn.getAttribute('data-grade-id');
    this.querySelector('#delete_grade_info').textContent=btn.getAttribute('data-grade-info');
});
document.getElementById('filter_grade_class')?.addEventListener('change',function(){
    const selectedClass=this.value.toLowerCase();
    document.querySelectorAll('table tbody tr').forEach(row=>{
        const gradeData=row.getAttribute('data-class')||'';
        row.style.display=!selectedClass||gradeData.toLowerCase().includes(selectedClass)?'':'none';
    });
});
</script>
