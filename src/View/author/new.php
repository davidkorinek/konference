<?php
$base = '/konference/public';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';
?>

<div class="container my-4">

    <h2>Přidat nový článek</h2>
    <p>Vyplňte informace o článku a nahrajte PDF soubor.</p>

    <?php if (!empty($_SESSION['upload_errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['upload_errors'] as $e): ?>
                <div><?= e($e) ?></div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['upload_errors']); ?>
    <?php endif; ?>

    <form method="post" action="<?= $base ?>/author/files/new" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Jména autorů</label>
            <input type="text" name="authors" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Název článku</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Abstrakt</label>
            <textarea name="abstract" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">PDF soubor</label>
            <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
        </div>

        <button class="btn btn-success">Uložit článek</button>
        <a href="<?= $base ?>/author/files" class="btn btn-secondary">Zpět</a>

    </form>
</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
