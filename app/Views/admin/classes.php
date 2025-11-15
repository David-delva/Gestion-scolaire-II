<!-- Gestion des Classes - Version Moderne -->
<div class="page-header glass-effect mb-4" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0"><i class="bi bi-building me-2"></i><?php echo $data['title']??'Gestion des Classes'; ?></h1>
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <i class="bi bi-plus-circle me-1"></i>Ajouter
        </button>
    </div>
</div>

<div class="card border-0 shadow-lg" data-aos="fade-up">
    <div class="card-header bg-gradient text-white py-3">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Classes</h5>
    </div>
    <div class="card-body p-4">
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" class="form-control" id="filter_search" placeholder="Rechercher une classe...">
            </div>
        </div>
        <div class="row g-3" id="classGrid">
            <?php if(!empty($data['classes'])): foreach($data['classes'] as $c): ?>
                <div class="col-md-4 col-lg-3 class-card" data-search="<?php echo htmlspecialchars(strtolower($c['name'])); ?>">
                    <div class="card border-0 shadow-sm h-100 class-item">
                        <div class="card-body text-center p-4">
                            <div class="class-icon-large bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-building-fill text-warning" style="font-size:2rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-3"><?php echo htmlspecialchars($c['name']); ?></h5>
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editClassModal" data-id="<?php echo $c['id']; ?>" data-name="<?php echo htmlspecialchars($c['name']); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteClassModal" data-id="<?php echo $c['id']; ?>" data-name="<?php echo htmlspecialchars($c['name']); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                        <p class="text-muted mt-3">Aucune classe</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nouvelle Classe</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/addClass" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Nom de la Classe</label>
                        <input type="text" class="form-control" name="name" placeholder="Ex: Terminale S" required>
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
<div class="modal fade" id="editClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier Classe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/editClass" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nom de la Classe</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
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
<div class="modal fade" id="deleteClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>admin/deleteClass" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Supprimer la classe <strong id="delete_name"></strong> ?</p>
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
.class-icon-large{width:80px;height:80px;display:flex;align-items:center;justify-content:center;}
.class-item{transition:all .3s ease;cursor:pointer;}
.class-item:hover{transform:translateY(-5px);box-shadow:0 .5rem 1rem rgba(0,0,0,.15)!important;}
</style>

<script>
function filterClasses(){
    const searchFilter=document.getElementById('filter_search').value.toLowerCase();
    const cards=document.querySelectorAll('.class-card');
    cards.forEach(card=>{
        const cardSearch=card.getAttribute('data-search');
        card.style.display=!searchFilter||cardSearch.includes(searchFilter)?'':'none';
    });
}
document.getElementById('filter_search')?.addEventListener('input',filterClasses);
document.getElementById('editClassModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('edit_id').value=btn.getAttribute('data-id');
    document.getElementById('edit_name').value=btn.getAttribute('data-name');
});
document.getElementById('deleteClassModal')?.addEventListener('show.bs.modal',function(e){
    const btn=e.relatedTarget;
    document.getElementById('delete_id').value=btn.getAttribute('data-id');
    document.getElementById('delete_name').textContent=btn.getAttribute('data-name');
});
</script>
