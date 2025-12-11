<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';
?>

<div class="container my-4">

    <h2>Upravit článek</h2>

    <form method="post" action="<?= $base ?>/author/files/edit">

        <input type="hidden" name="id" value="<?= $file['ID_file'] ?>">

        <div class="mb-3">
            <label class="form-label">Jména autorů</label>
            <input type="text" class="form-control" name="authors" value="<?= e($file['authors']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Název článku</label>
            <input type="text" class="form-control" name="title" value="<?= e($file['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Abstrakt</label>
            <textarea class="form-control" name="abstract" rows="5" required><?= e($file['abstract']) ?></textarea>
        </div>

        <button class="btn btn-success">Uložit změny</button>
        <a href="<?= $base ?>/author/files" class="btn btn-secondary">Zpět</a>
    </form>

</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>


<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
