<!-- Mes Notes - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <h1 class="text-white mb-0"><i class="bi bi-journal-text me-2"></i><?php echo $data['title']??'Mes Notes'; ?></h1>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <select class="form-select" id="filter_subject">
                    <option value="">Toutes les matières</option>
                    <?php 
                    $subjects = array_unique(array_column($data['grades'], 'subject_name'));
                    foreach($subjects as $s): ?>
                        <option value="<?php echo htmlspecialchars($s); ?>"><?php echo htmlspecialchars($s); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="filter_category">
                    <option value="">Toutes les catégories</option>
                    <option value="Devoir">Devoir</option>
                    <option value="Contrôle">Contrôle</option>
                    <option value="Examen">Examen</option>
                    <option value="Oral">Oral</option>
                    <option value="Projet">Projet</option>
                    <option value="TP">TP</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Toutes mes Notes</h5>
    </div>
    <div class="card-body p-0">
        <?php if(!empty($data['grades'])): ?>
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Matière</th><th>Catégorie</th><th>Note</th><th>Date</th><th>Enseignant</th><th>Commentaire</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['grades'] as $g): ?>
                            <tr data-subject="<?php echo htmlspecialchars($g['subject_name']); ?>" data-category="<?php echo htmlspecialchars($g['category']??'Devoir'); ?>">
                                <td><div class="d-flex align-items-center"><div class="subject-icon bg-primary bg-opacity-10 rounded-circle p-2 me-3"><i class="bi bi-book text-primary"></i></div><span class="fw-bold"><?php echo htmlspecialchars($g['subject_name']); ?></span></div></td>
                                <td><span class="badge bg-info"><?php echo htmlspecialchars($g['category']??'Devoir'); ?></span></td>
                                <td><span class="badge bg-<?php echo($g['grade_value']>=10)?'success':'danger'; ?> px-3 py-2 fs-6"><?php echo htmlspecialchars(number_format($g['grade_value'],2)); ?>/20</span></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($g['grade_date']))); ?></td>
                                <td><?php echo htmlspecialchars($g['teacher_first_name'].' '.$g['teacher_last_name']); ?></td>
                                <td><?php echo htmlspecialchars($g['comment']??'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-md-none p-3">
                <?php foreach($data['grades'] as $g): ?>
                    <div class="card mb-3 grade-card" data-subject="<?php echo htmlspecialchars($g['subject_name']); ?>" data-category="<?php echo htmlspecialchars($g['category']??'Devoir'); ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="subject-icon bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-book text-primary"></i>
                                    </div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($g['subject_name']); ?></h6>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge bg-info mb-1"><?php echo htmlspecialchars($g['category']??'Devoir'); ?></span>
                                    <span class="badge bg-<?php echo($g['grade_value']>=10)?'success':'danger'; ?> px-3 py-2"><?php echo htmlspecialchars(number_format($g['grade_value'],2)); ?>/20</span>
                                </div>
                            </div>
                            <small class="text-muted d-block mb-1">Date: <?php echo htmlspecialchars(date('d/m/Y',strtotime($g['grade_date']))); ?></small>
                            <small class="text-muted d-block mb-1">Prof: <?php echo htmlspecialchars($g['teacher_first_name'].' '.$g['teacher_last_name']); ?></small>
                            <?php if($g['comment']): ?><small class="text-muted d-block">Commentaire: <?php echo htmlspecialchars($g['comment']); ?></small><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:4rem;"></i>
                <p class="text-muted mt-3 fs-5">Aucune note enregistrée</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.subject-icon{width:40px;height:40px;display:flex;align-items:center;justify-content:center;}
</style>

<script>
function filterGrades(){
    const subject=document.getElementById('filter_subject').value;
    const category=document.getElementById('filter_category').value;
    document.querySelectorAll('table tbody tr[data-subject]').forEach(row=>{
        const match=(!subject||row.getAttribute('data-subject')===subject)&&(!category||row.getAttribute('data-category')===category);
        row.style.display=match?'':'none';
    });
    document.querySelectorAll('.grade-card').forEach(card=>{
        const match=(!subject||card.getAttribute('data-subject')===subject)&&(!category||card.getAttribute('data-category')===category);
        card.style.display=match?'':'none';
    });
}
document.getElementById('filter_subject')?.addEventListener('change',filterGrades);
document.getElementById('filter_category')?.addEventListener('change',filterGrades);
</script>
