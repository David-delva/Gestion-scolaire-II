<!-- Version moderne optimisée -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <h1 class="text-white mb-0"><i class="bi bi-grid-3x3-gap me-2"></i><?php echo $data['title']??'Mes Classes et Matières'; ?></h1>
</div>

<?php 
// Regrouper les affectations par classe
$assignmentsByClass = [];
if(!empty($data['teacherAssignments'])) {
    foreach($data['teacherAssignments'] as $assignment) {
        $className = $assignment['class_name'];
        if(!isset($assignmentsByClass[$className])) {
            $assignmentsByClass[$className] = [];
        }
        $assignmentsByClass[$className][] = $assignment;
    }
}
?>

<?php if(!empty($assignmentsByClass)): ?>
    <?php foreach($assignmentsByClass as $className => $assignments): ?>
        <div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
            <div class="card-header bg-gradient text-white py-3">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i><?php echo htmlspecialchars($className); ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php foreach($assignments as $assignment): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="subject-card p-3 rounded-3 bg-success bg-opacity-10 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="subject-icon bg-success rounded-circle p-3 me-3">
                                        <i class="bi bi-book text-white" style="font-size:1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-success"><?php echo htmlspecialchars($assignment['subject_name']); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card border-0 shadow-lg" data-aos="fade-up">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3 mb-0">Aucune affectation trouvée</p>
        </div>
    </div>
<?php endif; ?>

<style>
.subject-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.subject-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.subject-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
