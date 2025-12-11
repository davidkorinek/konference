<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';
?>

<div class="container my-4">

    <h2 class="mb-4">Správa článků a recenzí</h2>

    <?php if (empty($files)): ?>
        <div class="alert alert-info">Nenalezeny žádné články, které vyžadují administrátorský zásah.</div>
    <?php endif; ?>

    <?php foreach ($files as $f): ?>

        <?php
        // status stitek
        $status = strtolower($f['status_name']);

        switch ($status) {
            case 'uploaded':
            case 'waiting_for_check':
                $badgeClass  = 'badge bg-info text-dark';
                $statusLabel = 'Čeká na přiřazení recenzentů';
                break;

            case 'in_review':
                $badgeClass  = 'badge bg-primary';
                $statusLabel = 'Probíhá recenzní řízení';
                break;

            case 'approved':
                $badgeClass  = 'badge bg-success';
                $statusLabel = 'Schváleno';
                break;

            case 'rejected':
                $badgeClass  = 'badge bg-danger';
                $statusLabel = 'Zamítnuto';
                break;

            default:
                $badgeClass  = 'badge bg-secondary';
                $statusLabel = e($f['status_name']);
        }

        // pocet odevzdanych recenzi
        $reviewCount = (int)$f['review_count'];
        ?>

        <div class="card mb-4 shadow-sm">

            <!-- hlavicka karty -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="<?= $badgeClass ?> me-2"><?= $statusLabel ?></span>

                    <strong><?= e($f['title']) ?></strong>
                    <div class="small text-muted">
                        <?= date("d.m.Y H:i", strtotime($f['upload_date'])) ?>
                    </div>
                </div>

                <div class="text-end">

                    <!-- prirazeni recenzentu -->
                    <?php if ($status === 'uploaded' || $status === 'waiting_for_check'): ?>
                        <a href="<?= $base ?>/admin/file/assign?id=<?= $f['ID_file'] ?>"
                           class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-person-plus"></i> Přiřadit recenzenty
                        </a>
                    <?php endif; ?>

                    <!-- odevzdane recenze -->
                    <?php if ($reviewCount > 0): ?>
                        <a href="<?= $base ?>/admin/file/reviews?id=<?= $f['ID_file'] ?>"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-list-check"></i>
                            Recenze (<?= $reviewCount ?>/3)
                        </a>
                    <?php endif; ?>

                </div>
            </div>

            <!-- obsah karty -->
            <div class="card-body">

                <div class="mb-2">
                    <strong>Autoři:</strong> <?= e($f['authors']) ?>
                </div>

                <!-- abstrakt -->
                <p class="mb-2">
                    <strong>Abstrakt:</strong><br>
                    <?= nl2br(e($f['abstract'])) ?>
                </p>

                <!-- zobrazit PDF -->
                <button class="btn btn-sm btn-outline-success" onclick="togglePdf('pdf<?= $f['ID_file'] ?>')">
                    <i class="bi bi-file-earmark-pdf"></i> Zobrazit PDF
                </button>

                <!-- PDF viewer -->
                <div id="pdf<?= $f['ID_file'] ?>" class="mt-3" style="display:none;">
                    <iframe
                            src="<?= $base ?>/assets/uploads/<?= e($f['filename']) ?>"
                            width="100%"
                            height="420"
                            class="border rounded">
                    </iframe>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<script>
    function togglePdf(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = (el.style.display === "none") ? "block" : "none";
    }
</script>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>


<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
