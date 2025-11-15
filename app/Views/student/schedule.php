<!-- Info sur ma classe - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <h1 class="text-white mb-0"><i class="bi bi-info-circle me-2"></i><?php echo $data['title']??'Info sur ma classe'; ?></h1>
</div>

<div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-building me-2"></i>Ma Classe</h5>
    </div>
    <div class="card-body p-4">
        <?php if(!empty($data['studentClasses']) && !empty($data['studentClasses']['class_name'])): ?>
            <div class="d-flex align-items-center p-4 bg-info bg-opacity-10 rounded-3">
                <div class="class-icon bg-info rounded-circle p-3 me-3">
                    <i class="bi bi-building text-white" style="font-size:2rem;"></i>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold text-info"><?php echo htmlspecialchars($data['studentClasses']['class_name']); ?></h3>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-3 mb-0">Aucune classe assignée</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-book me-2"></i>Mes Matières & Enseignants</h5>
    </div>
    <div class="card-body p-0">
        <?php if(!empty($data['studentSubjectsTeachers'])): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Matière</th><th>Enseignant</th><th>Contact</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['studentSubjectsTeachers'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="subject-icon bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-book text-success"></i>
                                        </div>
                                        <span class="fw-bold"><?php echo htmlspecialchars($item['subject_name']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="teacher-icon bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <span><?php echo htmlspecialchars($item['teacher_first_name'].' '.$item['teacher_last_name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($item['teacher_phone']??'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-3">Aucune matière assignée</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.class-icon{width:70px;height:70px;display:flex;align-items:center;justify-content:center;}
.subject-icon,.teacher-icon{width:40px;height:40px;display:flex;align-items:center;justify-content:center;}
</style>
