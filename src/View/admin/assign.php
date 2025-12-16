<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';
?>

<div class="container my-4">

    <h2 class="mb-3">Přiřadit recenzenty</h2>

    <?php if (!empty($_SESSION['admin_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['admin_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['admin_success']; unset($_SESSION['admin_success']); ?></div>
    <?php endif; ?>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= e($file['title'] ?? 'Článek') ?></h5>
            <p class="card-text"><strong>Autoři:</strong> <?= e($file['authors'] ?? '') ?></p>
            <p class="small text-muted">Datum: <?= isset($file['upload_date']) ? date("d.m.Y", strtotime($file['upload_date'])) : '' ?></p>
        </div>
    </div>

    <!-- vyber recenzenty -->
    <form id="assignForm" method="post" action="<?= $base ?>/admin/file/assign">
        <input type="hidden" name="file_id" value="<?= e($_GET['id'] ?? '') ?>">

        <div class="row g-2 align-items-center mb-3">
            <div class="col-auto">
                <label class="form-label">Přidat recenzenta:</label>
            </div>

            <div class="col-sm-5">
                <select id="reviewerSelect" class="form-select">
                    <option value="">-- vyber recenzenta --</option>
                    <?php foreach ($reviewers as $r): ?>
                        <option value="<?= $r['ID_user'] ?>"><?= e($r['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-auto">
                <button type="button" id="addReviewerBtn" class="btn btn-success">Přidat</button>
            </div>
        </div>

        <!-- seznam recenzentu -->
        <div id="selectedReviewers" class="mb-3"></div>

        <div class="mb-3">
            <small class="text-muted">Pozn.: Každý článek musí mít minimálně 3 recenzenty. Recenzent nemůže být přidán vícekrát.</small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Uložit přiřazení</button>
            <a href="<?= $base ?>/admin/files" class="btn btn-secondary">Zpět na seznam</a>
        </div>
    </form>

</div>

<script>
    (function(){
        const addBtn = document.getElementById('addReviewerBtn');
        const sel = document.getElementById('reviewerSelect');
        const list = document.getElementById('selectedReviewers');

        // ulozeni id aby se neopakovalo
        const added = new Set();

        addBtn.addEventListener('click', function(){
            const id = sel.value;
            const text = sel.options[sel.selectedIndex]?.text || '';
            if(!id) return alert('Vyberte recenzenta.');
            if(added.has(id)) return alert('Tento recenzent již byl přidán.');

            added.add(id);

            const row = document.createElement('div');
            row.className = 'd-flex align-items-center mb-2';

            const name = document.createElement('div');
            name.className = 'me-3';
            name.innerText = text;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-outline-danger';
            removeBtn.innerText = '❌ Odebrat';
            removeBtn.onclick = function(){
                added.delete(id);
                row.remove();
            };

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'reviewers[]';
            hidden.value = id;

            row.appendChild(hidden);
            row.appendChild(name);
            row.appendChild(removeBtn);

            list.appendChild(row);
        });
    })();
</script>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>


<?php require __DIR__ . '/../partials/footer.php'; ?>
