<!-- Mes Absences - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <h1 class="text-white mb-0"><i class="bi bi-calendar-x me-2"></i><?php echo $data['title']??'Mes Absences'; ?></h1>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-calendar-x text-primary" style="font-size:2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalAbsences']??0); ?></h2>
                <p class="text-muted mb-0">Total Absences</p>
            </div>
        </div>
    </div>
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-check-circle text-success" style="font-size:2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalJustifiedAbsences']??0); ?></h2>
                <p class="text-muted mb-0">Justifiées</p>
            </div>
        </div>
    </div>
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-modern card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
                <div class="stat-icon-modern bg-danger bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size:2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($data['totalUnjustifiedAbsences']??0); ?></h2>
                <p class="text-muted mb-0">Non Justifiées</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Détail de mes Absences</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Date</th><th>Matière</th><th>Classe</th><th>Justifiée</th><th>Détails</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['absences'])): foreach($data['absences'] as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></td>
                            <td><?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($a['class_name']??'N/A'); ?></td>
                            <td><span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?> px-3 py-2"><?php echo $a['justified']?'Oui':'Non'; ?></span></td>
                            <td><?php echo htmlspecialchars($a['justification_details']??'N/A'); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune absence</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-md-none p-3">
            <?php if(!empty($data['absences'])): foreach($data['absences'] as $a): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0"><?php echo htmlspecialchars(date('d/m/Y',strtotime($a['absence_date']))); ?></h6>
                            <span class="badge bg-<?php echo $a['justified']?'success':'danger'; ?>"><?php echo $a['justified']?'Justifiée':'Non justifiée'; ?></span>
                        </div>
                        <small class="text-muted d-block mb-1">Matière: <?php echo htmlspecialchars($a['subject_name']??'N/A'); ?></small>
                        <small class="text-muted d-block mb-1">Classe: <?php echo htmlspecialchars($a['class_name']??'N/A'); ?></small>
                        <?php if($a['justification_details']): ?><small class="text-muted d-block">Détails: <?php echo htmlspecialchars($a['justification_details']); ?></small><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center py-5"><i class="bi bi-inbox text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3">Aucune absence</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
