<!-- Statistiques Globales - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <h1 class="text-white mb-0"><i class="bi bi-graph-up me-2"></i><?php echo $data['title']??'Statistiques Globales'; ?></h1>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-people text-primary" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalUsers']??0); ?></h3>
                <p class="text-muted mb-0 small">Utilisateurs</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-mortarboard text-success" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalStudents']??0); ?></h3>
                <p class="text-muted mb-0 small">Étudiants</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-person-badge text-info" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalTeachers']??0); ?></h3>
                <p class="text-muted mb-0 small">Enseignants</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="250">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-building text-warning" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalClasses']??0); ?></h3>
                <p class="text-muted mb-0 small">Classes</p>
            </div>
        </div>
    </div>

        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-danger bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-book text-danger" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalSubjects']??0); ?></h3>
                <p class="text-muted mb-0 small">Matières</p>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-person-workspace me-2"></i>Enseignants par Classe & Matière</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <select class="form-select" id="filter_teacher_class">
                    <option value="">Toutes les classes</option>
                    <?php foreach($data['allClasses'] as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="filter_teacher_subject">
                    <option value="">Toutes les matières</option>
                    <?php foreach($data['allSubjects'] as $s): ?>
                        <option value="<?php echo htmlspecialchars($s['name']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0" id="teacherTable">
                <thead class="table-light">
                    <tr><th>Classe</th><th>Matière</th><th>Enseignant</th><th>Email</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['teacherAssignments'])): foreach($data['teacherAssignments'] as $ta): ?>
                        <tr data-class="<?php echo htmlspecialchars($ta['class_name']); ?>" data-subject="<?php echo htmlspecialchars($ta['subject_name']); ?>">
                            <td><span class="badge bg-info px-3 py-2"><?php echo htmlspecialchars($ta['class_name']); ?></span></td>
                            <td><span class="badge bg-success px-3 py-2"><?php echo htmlspecialchars($ta['subject_name']); ?></span></td>
                            <td><?php echo htmlspecialchars($ta['first_name'].' '.$ta['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($ta['email']); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune affectation</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-md-none" id="teacherCards">
            <?php if(!empty($data['teacherAssignments'])): foreach($data['teacherAssignments'] as $ta): ?>
                <div class="card mb-3" data-class="<?php echo htmlspecialchars($ta['class_name']); ?>" data-subject="<?php echo htmlspecialchars($ta['subject_name']); ?>">
                    <div class="card-body">
                        <h6 class="mb-2"><?php echo htmlspecialchars($ta['first_name'].' '.$ta['last_name']); ?></h6>
                        <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($ta['email']); ?></small>
                        <span class="badge bg-info me-1"><?php echo htmlspecialchars($ta['class_name']); ?></span>
                        <span class="badge bg-success"><?php echo htmlspecialchars($ta['subject_name']); ?></span>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune affectation</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Liste Complète des Notes</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <select class="form-select" id="filter_grade_class">
                    <option value="">Toutes les classes</option>
                    <?php foreach($data['allClasses'] as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="filter_grade_subject">
                    <option value="">Toutes les matières</option>
                    <?php foreach($data['allSubjects'] as $s): ?>
                        <option value="<?php echo htmlspecialchars($s['name']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-hover align-middle mb-0" id="gradeTable">
                <thead class="table-light">
                    <tr><th>Date</th><th>Étudiant</th><th>Classe</th><th>Matière</th><th>Note</th><th>Enseignant</th><th>Commentaire</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['allGrades'])): foreach($data['allGrades'] as $g): ?>
                        <tr data-class="<?php echo htmlspecialchars($g['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($g['subject_name']); ?>">
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($g['grade_date']))); ?></td>
                            <td><?php echo htmlspecialchars($g['student_first_name'].' '.$g['student_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($g['class_name']??'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($g['subject_name']); ?></td>
                            <td><span class="badge bg-<?php echo($g['grade_value']>=10)?'success':'danger'; ?> px-3 py-2"><?php echo htmlspecialchars(number_format($g['grade_value'],2)); ?>/20</span></td>
                            <td><?php echo htmlspecialchars($g['teacher_first_name'].' '.$g['teacher_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($g['comment']??'N/A'); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune note</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-lg-none" id="gradeCards">
            <?php if(!empty($data['allGrades'])): foreach($data['allGrades'] as $g): ?>
                <div class="card mb-3" data-class="<?php echo htmlspecialchars($g['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($g['subject_name']); ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($g['student_first_name'].' '.$g['student_last_name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars(date('d/m/Y',strtotime($g['grade_date']))); ?></small>
                            </div>
                            <span class="badge bg-<?php echo($g['grade_value']>=10)?'success':'danger'; ?> px-3 py-2"><?php echo htmlspecialchars(number_format($g['grade_value'],2)); ?>/20</span>
                        </div>
                        <small class="text-muted d-block">Classe: <?php echo htmlspecialchars($g['class_name']??'N/A'); ?></small>
                        <small class="text-muted d-block">Matière: <?php echo htmlspecialchars($g['subject_name']); ?></small>
                        <small class="text-muted d-block">Prof: <?php echo htmlspecialchars($g['teacher_first_name'].' '.$g['teacher_last_name']); ?></small>
                        <?php if($g['comment']): ?><small class="text-muted d-block mt-1">Commentaire: <?php echo htmlspecialchars($g['comment']); ?></small><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune note</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-calendar-x me-2"></i>Liste Complète des Absences</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="filter_absence_class">
                    <option value="">Toutes les classes</option>
                    <?php foreach($data['allClasses'] as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filter_absence_subject">
                    <option value="">Toutes les matières</option>
                    <?php foreach($data['allSubjects'] as $s): ?>
                        <option value="<?php echo htmlspecialchars($s['name']); ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filter_absence_justified">
                    <option value="">Toutes</option>
                    <option value="1">Justifiées</option>
                    <option value="0">Non Justifiées</option>
                </select>
            </div>
        </div>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0" id="absenceTable">
                <thead class="table-light">
                    <tr><th>Date</th><th>Étudiant</th><th>Classe</th><th>Matière</th><th>Justifiée</th><th>Détails</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['allAbsences'])): foreach($data['allAbsences'] as $a): ?>
                        <tr data-class="<?php echo htmlspecialchars($a['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($a['subject_name']??''); ?>" data-justified="<?php echo $a['justified']?'1':'0'; ?>">
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></td>
                            <td><?php echo htmlspecialchars($a['first_name'].' '.$a['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($a['class_name']??'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></td>
                            <td><span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?> px-3 py-2"><?php echo $a['justified']?'Oui':'Non'; ?></span></td>
                            <td><?php echo htmlspecialchars($a['justification_details']??'N/A'); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune absence</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-md-none" id="absenceCards">
            <?php if(!empty($data['allAbsences'])): foreach($data['allAbsences'] as $a): ?>
                <div class="card mb-3" data-class="<?php echo htmlspecialchars($a['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($a['subject_name']??''); ?>" data-justified="<?php echo $a['justified']?'1':'0'; ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($a['first_name'].' '.$a['last_name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></small>
                            </div>
                            <span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?>"><?php echo $a['justified']?'Justifiée':'Non justifiée'; ?></span>
                        </div>
                        <small class="text-muted d-block">Classe: <?php echo htmlspecialchars($a['class_name']??'N/A'); ?></small>
                        <small class="text-muted d-block">Matière: <?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></small>
                        <?php if($a['justification_details']): ?><small class="text-muted d-block mt-1">Détails: <?php echo htmlspecialchars($a['justification_details']); ?></small><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune absence</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function filterTable(tableId,classFilter,subjectFilter,justifiedFilter){
    const rows=document.querySelectorAll(`#${tableId} tbody tr[data-class]`);
    const cards=document.querySelectorAll(`#${tableId.replace('Table','Cards')} .card[data-class]`);
    rows.forEach(row=>{
        const rowClass=row.getAttribute('data-class')||'';
        const rowSubject=row.getAttribute('data-subject')||'';
        const rowJustified=row.getAttribute('data-justified')||'';
        const classMatch=!classFilter||rowClass===classFilter;
        const subjectMatch=!subjectFilter||rowSubject===subjectFilter;
        const justifiedMatch=!justifiedFilter||rowJustified===justifiedFilter;
        row.style.display=(classMatch&&subjectMatch&&justifiedMatch)?'':'none';
    });
    cards.forEach(card=>{
        const cardClass=card.getAttribute('data-class')||'';
        const cardSubject=card.getAttribute('data-subject')||'';
        const cardJustified=card.getAttribute('data-justified')||'';
        const classMatch=!classFilter||cardClass===classFilter;
        const subjectMatch=!subjectFilter||cardSubject===subjectFilter;
        const justifiedMatch=!justifiedFilter||cardJustified===justifiedFilter;
        card.style.display=(classMatch&&subjectMatch&&justifiedMatch)?'':'none';
    });
}
document.getElementById('filter_teacher_class')?.addEventListener('change',function(){
    filterTable('teacherTable',this.value,document.getElementById('filter_teacher_subject').value);
});
document.getElementById('filter_teacher_subject')?.addEventListener('change',function(){
    filterTable('teacherTable',document.getElementById('filter_teacher_class').value,this.value);
});
document.getElementById('filter_grade_class')?.addEventListener('change',function(){
    filterTable('gradeTable',this.value,document.getElementById('filter_grade_subject').value);
});
document.getElementById('filter_grade_subject')?.addEventListener('change',function(){
    filterTable('gradeTable',document.getElementById('filter_grade_class').value,this.value);
});
document.getElementById('filter_absence_class')?.addEventListener('change',function(){
    filterTable('absenceTable',this.value,document.getElementById('filter_absence_subject').value,document.getElementById('filter_absence_justified').value);
});
document.getElementById('filter_absence_subject')?.addEventListener('change',function(){
    filterTable('absenceTable',document.getElementById('filter_absence_class').value,this.value,document.getElementById('filter_absence_justified').value);
});
document.getElementById('filter_absence_justified')?.addEventListener('change',function(){
    filterTable('absenceTable',document.getElementById('filter_absence_class').value,document.getElementById('filter_absence_subject').value,this.value);
});
</script>
