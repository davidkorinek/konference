<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

/* funkce pro hvězdičky */
function renderStars($value)
{
    if ($value === null) return "<span class='text-muted'>Bez hodnocení</span>";

    $value = floatval($value);
    $out = "";

    for ($i = 1; $i <= 5; $i++) {
        if ($value >= $i) {
            $out .= '<i class="bi bi-star-fill text-warning"></i>';
        } elseif ($value >= $i - 0.5) {
            $out .= '<i class="bi bi-star-half text-warning"></i>';
        } else {
            $out .= '<i class="bi bi-star text-warning"></i>';
        }
    }
    return $out;
}
?>

<div class="container my-4">

    <h2 class="mb-4">Seznam všech článků</h2>

    <?php foreach ($files as $f): ?>

        <?php
        $status = strtolower($f['status_name']);
        $rating = floatval($f['rating'] ?? 0);

        switch ($status) {
            case 'approved':
                $badge = "<span class='badge bg-success'>Schváleno</span>";
                break;
            case 'rejected':
                $badge = "<span class='badge bg-danger'>Zamítnuto</span>";
                break;
            case 'in_review':
                $badge = "<span class='badge bg-primary'>V recenzním řízení</span>";
                break;
            default:
                $badge = "<span class='badge bg-info text-dark'>Čeká na posouzení</span>";
        }
        ?>

        <div class="card mb-4 shadow-sm">

            <div class="card-header d-flex justify-content-between">
                <div>
                    <?= $badge ?>
                    <strong class="ms-2"><?= e($f['title']) ?></strong>
                </div>

                <div class="text-end small text-muted">
                    <?= date("d.m.Y H:i", strtotime($f['upload_date'])) ?>
                </div>
            </div>

            <div class="card-body">

                <p><strong>Autoři:</strong> <?= e($f['authors']) ?></p>

                <?php if ($rating > 0): ?>
                    <p><strong>Průměrné hodnocení:</strong> <?= renderStars($rating) ?></p>
                <?php endif; ?>

                <a href="<?= $base ?>/assets/uploads/<?= e($f['filename']) ?>"
                   class="btn btn-outline-primary btn-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Otevřít PDF
                </a>

                <a href="<?= $base ?>/author/files/edit?id=<?= $f['ID_file'] ?>"
                   class="btn btn-outline-secondary btn-sm ms-1">
                    <i class="bi bi-pencil"></i> Upravit
                </a>

                <!-- vrácení do recenzí -->
                <form method="post" action="<?= $base ?>/admin/file/reset" class="d-inline ms-1">
                    <input type="hidden" name="file_id" value="<?= $f['ID_file'] ?>">
                    <button class="btn btn-warning btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Vrátit do recenzí
                    </button>
                </form>

                <!-- smazat (jen superadmin) -->
                <?php if ($_SESSION['user']['role'] === 'superadmin'): ?>
                    <form method="post" action="<?= $base ?>/admin/file/delete" class="d-inline ms-1"
                          onsubmit="return confirm('Opravdu chcete smazat tento článek? Tuto akci nelze vrátit.')">
                        <input type="hidden" name="file_id" value="<?= $f['ID_file'] ?>">
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Smazat
                        </button>
                    </form>
                <?php endif; ?>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
