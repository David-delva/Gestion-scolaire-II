<!--
Fichier : app/Views/teacher/stats.php
Description : Statistiques enseignant - Design Moderne
Version : 2.0
Date : 2025
-->

<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white"><i class="bi bi-graph-up me-2"></i><?php echo $data['title'] ?? 'Mes Statistiques'; ?></h1>
        <select class="form-select w-auto" id="filter_stats_class">
            <option value="">Toutes les classes</option>
            <?php foreach ($data['teacherClasses'] as $class): ?>
                <option value="<?php echo htmlspecialchars($class['id']); ?>"><?php echo htmlspecialchars($class['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-journal-text text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalGrades'] ?? 0); ?></h2>
                <p class="text-muted mb-0">Notes Saisies</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-calendar-x text-warning" style="font-size: 2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalAbsences'] ?? 0); ?></h2>
                <p class="text-muted mb-0">Absences Gérées</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalJustifiedAbsences'] ?? 0); ?></h2>
                <p class="text-muted mb-0">Justifiées</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-danger bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalUnjustifiedAbsences'] ?? 0); ?></h2>
                <p class="text-muted mb-0">Non Justifiées</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-6" data-aos="fade-right">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient text-white py-3">
                <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Top 5 Étudiants</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($data['topStudentsByGrade'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($data['topStudentsByGrade'] as $index => $student): ?>
                            <div class="list-group-item border-0 d-flex align-items-center py-3">
                                <div class="rank-badge bg-primary text-white rounded-circle me-3"><?php echo $index + 1; ?></div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h6>
                                </div>
                                <span class="badge bg-success px-3 py-2"><?php echo htmlspecialchars(number_format($student['average_grade'], 2)); ?>/20</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i><p class="text-muted mt-3">Aucune donnée</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6" data-aos="fade-left">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-header bg-gradient text-white py-3">
                <h5 class="mb-0"><i class="bi bi-exclamation-circle me-2"></i>Plus Absents</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($data['mostAbsentStudents'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($data['mostAbsentStudents'] as $index => $student): ?>
                            <div class="list-group-item border-0 d-flex align-items-center py-3">
                                <div class="rank-badge bg-danger text-white rounded-circle me-3"><?php echo $index + 1; ?></div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h6>
                                </div>
                                <span class="badge bg-danger px-3 py-2"><?php echo htmlspecialchars($student['total_absences']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i><p class="text-muted mt-3">Aucune donnée</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-journal-check me-2"></i>Mes Notes et Absences Saisies</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <select class="form-select" id="filter_data_class">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($data['teacherClasses'] as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['name']); ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="filter_data_subject">
                    <option value="">Toutes les matières</option>
                    <?php foreach ($data['teacherSubjects'] as $subject): ?>
                        <option value="<?php echo htmlspecialchars($subject['name']); ?>"><?php echo htmlspecialchars($subject['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="grades-tab" data-bs-toggle="tab" data-bs-target="#grades" type="button" role="tab">
                    <i class="bi bi-journal-text me-1"></i>Notes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="absences-tab" data-bs-toggle="tab" data-bs-target="#absences" type="button" role="tab">
                    <i class="bi bi-calendar-x me-1"></i>Absences
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="grades" role="tabpanel">
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Étudiant</th><th>Classe</th><th>Matière</th><th>Note</th><th>Commentaire</th></tr>
                        </thead>
                        <tbody id="grades_tbody">
                            <?php if(!empty($data['myGrades'])): foreach($data['myGrades'] as $g): ?>
                                <tr data-class="<?php echo htmlspecialchars($g['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($g['subject_name']); ?>">
                                    <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($g['grade_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($g['student_first_name'].' '.$g['student_last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($g['class_name']??'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($g['subject_name']); ?></td>
                                    <td><span class="badge bg-<?php echo($g['grade_value']>=10)?'success':'danger'; ?> px-3 py-2"><?php echo htmlspecialchars(number_format($g['grade_value'],2)); ?>/20</span></td>
                                    <td><?php echo htmlspecialchars($g['comment']??'N/A'); ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-5">Aucune note</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-lg-none" id="grades_cards">
                    <?php if(!empty($data['myGrades'])): foreach($data['myGrades'] as $g): ?>
                        <div class="card mb-3" data-class="<?php echo htmlspecialchars($g['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($g['subject_name']); ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6><?php echo htmlspecialchars($g['student_first_name'].' '.$g['student_last_name']); ?></h6>
                                    <span class="badge bg-<?php echo($g['grade_value']>=10)?'success':'danger'; ?>"><?php echo htmlspecialchars(number_format($g['grade_value'],2)); ?>/20</span>
                                </div>
                                <small class="text-muted d-block">Date: <?php echo htmlspecialchars(date('d/m/Y',strtotime($g['grade_date']))); ?></small>
                                <small class="text-muted d-block">Classe: <?php echo htmlspecialchars($g['class_name']??'N/A'); ?></small>
                                <small class="text-muted d-block">Matière: <?php echo htmlspecialchars($g['subject_name']); ?></small>
                                <?php if($g['comment']): ?><small class="text-muted d-block">Commentaire: <?php echo htmlspecialchars($g['comment']); ?></small><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center py-5">Aucune note</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="absences" role="tabpanel">
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Étudiant</th><th>Classe</th><th>Matière</th><th>Justifiée</th><th>Détails</th></tr>
                        </thead>
                        <tbody id="absences_tbody">
                            <?php if(!empty($data['myAbsences'])): foreach($data['myAbsences'] as $a): ?>
                                <tr data-class="<?php echo htmlspecialchars($a['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($a['subject_name']??''); ?>">
                                    <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($a['student_first_name'].' '.$a['student_last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($a['class_name']??'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></td>
                                    <td><span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?>"><?php echo $a['justified']?'Oui':'Non'; ?></span></td>
                                    <td><?php echo htmlspecialchars($a['justification_details']??'N/A'); ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-5">Aucune absence</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-lg-none" id="absences_cards">
                    <?php if(!empty($data['myAbsences'])): foreach($data['myAbsences'] as $a): ?>
                        <div class="card mb-3" data-class="<?php echo htmlspecialchars($a['class_name']??''); ?>" data-subject="<?php echo htmlspecialchars($a['subject_name']??''); ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6><?php echo htmlspecialchars($a['student_first_name'].' '.$a['student_last_name']); ?></h6>
                                    <span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?>"><?php echo $a['justified']?'Justifiée':'Non justifiée'; ?></span>
                                </div>
                                <small class="text-muted d-block">Date: <?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></small>
                                <small class="text-muted d-block">Classe: <?php echo htmlspecialchars($a['class_name']??'N/A'); ?></small>
                                <small class="text-muted d-block">Matière: <?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></small>
                                <?php if($a['justification_details']): ?><small class="text-muted d-block">Détails: <?php echo htmlspecialchars($a['justification_details']); ?></small><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center py-5">Aucune absence</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Étudiants par Classe</h5>
    </div>
    <div class="card-body p-4">
        <select class="form-select mb-4" id="student_list_class">
            <option value="">Sélectionner une classe</option>
            <?php foreach ($data['teacherClasses'] as $class): ?>
                <option value="<?php echo htmlspecialchars($class['id']); ?>"><?php echo htmlspecialchars($class['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <div id="students_table_container" style="display:none;">
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>Matricule</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Date Naissance</th></tr></thead>
                    <tbody id="students_tbody"></tbody>
                </table>
            </div>
            <div class="d-md-none" id="students_cards"></div>
        </div>
        <div id="no_students_message" style="display:none;" class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i><p class="text-muted mt-3">Aucun étudiant</p>
        </div>
    </div>
</div>

<style>
.page-header{background:linear-gradient(135deg,rgba(102,126,234,0.95),rgba(118,75,162,0.95));border-radius:20px;padding:2rem;}
.stat-card-modern{transition:all .3s ease;cursor:pointer;}
.stat-card-modern:hover{transform:translateY(-10px);}
.stat-icon-modern{transition:all .3s ease;}
.stat-card-modern:hover .stat-icon-modern{transform:scale(1.1) rotate(5deg);}
.bg-gradient{background:linear-gradient(135deg,#667eea,#764ba2);}
.rank-badge{width:40px;height:40px;display:flex;align-items:center;justify-content:center;font-weight:bold;}
</style>

<script>
const allStudents=<?php echo json_encode($data['allStudents']??[]); ?>;
document.getElementById('filter_stats_class')?.addEventListener('change',function(){
    const classId=this.value;
    window.location.href='<?php echo BASE_URL; ?>teacher/stats'+(classId?'?class_id='+classId:'');
});

function filterData(){
    const classFilter=document.getElementById('filter_data_class').value.toLowerCase();
    const subjectFilter=document.getElementById('filter_data_subject').value.toLowerCase();
    document.querySelectorAll('#grades_tbody tr[data-class], #grades_cards .card[data-class]').forEach(el=>{
        const elClass=(el.getAttribute('data-class')||'').toLowerCase();
        const elSubject=(el.getAttribute('data-subject')||'').toLowerCase();
        const match=(!classFilter||elClass===classFilter)&&(!subjectFilter||elSubject===subjectFilter);
        el.style.display=match?'':'none';
    });
    document.querySelectorAll('#absences_tbody tr[data-class], #absences_cards .card[data-class]').forEach(el=>{
        const elClass=(el.getAttribute('data-class')||'').toLowerCase();
        const elSubject=(el.getAttribute('data-subject')||'').toLowerCase();
        const match=(!classFilter||elClass===classFilter)&&(!subjectFilter||elSubject===subjectFilter);
        el.style.display=match?'':'none';
    });
}
document.getElementById('filter_data_class')?.addEventListener('change',filterData);
document.getElementById('filter_data_subject')?.addEventListener('change',filterData);
document.getElementById('student_list_class')?.addEventListener('change',function(){
    const selectedClassId=parseInt(this.value);
    const container=document.getElementById('students_table_container');
    const tbody=document.getElementById('students_tbody');
    const noMsg=document.getElementById('no_students_message');
    const cardsContainer=document.getElementById('students_cards');
    if(!selectedClassId){container.style.display='none';cardsContainer.style.display='none';noMsg.style.display='none';return;}
    const classStudents=allStudents.filter(s=>s.class_id==selectedClassId);
    if(classStudents.length>0){
        tbody.innerHTML='';cardsContainer.innerHTML='';
        classStudents.forEach(s=>{
            const row=document.createElement('tr');
            row.innerHTML=`<td>${s.student_id_number||'N/A'}</td><td>${s.last_name} ${s.first_name}</td><td>${s.email||'N/A'}</td><td>${s.phone||'N/A'}</td><td>${s.date_of_birth?new Date(s.date_of_birth).toLocaleDateString('fr-FR'):'N/A'}</td>`;
            tbody.appendChild(row);
            const card=document.createElement('div');
            card.className='card mb-3';
            card.innerHTML=`<div class="card-body"><h6 class="mb-1">${s.last_name} ${s.first_name}</h6><span class="badge bg-primary mb-2">${s.student_id_number||'N/A'}</span><small class="text-muted d-block">Email: ${s.email||'N/A'}</small><small class="text-muted d-block">Tél: ${s.phone||'N/A'}</small><small class="text-muted d-block">Né(e) le: ${s.date_of_birth?new Date(s.date_of_birth).toLocaleDateString('fr-FR'):'N/A'}</small></div>`;
            cardsContainer.appendChild(card);
        });
        container.style.display='block';cardsContainer.style.display='block';noMsg.style.display='none';
    }else{container.style.display='none';cardsContainer.style.display='none';noMsg.style.display='block';}
});
</script>
