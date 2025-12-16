<?php
$base = '/konference/public';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

/* ⭐ Funkce pro hvězdičkové hodnocení (včetně půl bodů) */
function renderStars($value)
{
    if ($value === null || $value === '') return '';

    $value = floatval($value);
    $out = '';

    for ($i = 1; $i <= 5; $i++) {
        if ($value >= $i) {
            $out .= '<i class="bi bi-star-fill text-warning"></i>';
        } elseif ($value >= $i - 0.5) {
            $out .= '<i class="bi bi-star-half text-warning"></i>';
        } else {
            $out .= '<i class="bi bi-star text-warning"></i>';
        }
        $out .= ' ';
    }
    return $out;
}

/* Status badge */
$statusName = $file['status_name'] ?? '';
$status = strtolower((string)$statusName);
switch ($status) {
    case 'approved':
        $statusBadge = "<span class='badge bg-success'>Schváleno</span>";
        break;
    case 'rejected':
        $statusBadge = "<span class='badge bg-danger'>Zamítnuto</span>";
        break;
    case 'in_review':
        $statusBadge = "<span class='badge bg-primary'>V recenzním řízení</span>";
        break;
    default:
        $statusBadge = "<span class='badge bg-info text-dark'>Čeká na recenze</span>";
}
?>

<div class="container my-4">

    <h2 class="mb-4">Recenze článku</h2>

    <!-- INFO BLOK O ČLÁNKU -->
    <div class="p-3 bg-light border rounded mb-4 shadow-sm">
        <p><strong>Název:</strong> <?= e((string)($file['title'] ?? '')) ?></p>
        <p><strong>Autoři:</strong> <?= e((string)($file['authors'] ?? '')) ?></p>
        <p><strong>Status:</strong> <?= $statusBadge ?></p>

        <?php if (!empty($file['filename'])): ?>
            <a href="<?= $base ?>/assets/uploads/<?= e($file['filename']) ?>"
               class="btn btn-outline-primary btn-sm mt-2" target="_blank" rel="noopener">
                <i class="bi bi-filetype-pdf"></i> Otevřít PDF
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($reviews)): ?>

        <div class="alert alert-warning shadow-sm">
            Zatím nebyly odevzdány žádné recenze.
        </div>

    <?php else: ?>

        <table class="table table-bordered align-middle shadow-sm">
            <thead class="table-dark">
            <tr>
                <th width="15%">Recenzent</th>
                <th width="15%">Rozhodnutí</th>
                <th width="30%">Hodnocení</th>
                <th>Komentář</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($reviews as $r): ?>

                <?php
                /* Badge podle rozhodnutí */
                switch ($r['decision_name'] ?? '') {
                    case 'approve':
                        $decisionBadge = "<span class='badge bg-success'>Schválit</span>";
                        break;
                    case 'reject':
                        $decisionBadge = "<span class='badge bg-danger'>Zamítnout</span>";
                        break;
                    case 'needs_changes':
                        $decisionBadge = "<span class='badge bg-warning text-dark'>Vyžaduje úpravy</span>";
                        break;
                    default:
                        $decisionBadge = "<span class='badge bg-secondary'>Neurčeno</span>";
                }

                // Ošetření polí
                $username = e((string)($r['username'] ?? ''));
                $cmt = $r['comment'] ?? '';
                $s1 = $r['score1'] ?? null;
                $s2 = $r['score2'] ?? null;
                $s3 = $r['score3'] ?? null;
                ?>

                <tr>
                    <td><?= $username ?></td>

                    <td><?= $decisionBadge ?></td>

                    <td>
                        <div><strong>Kvalita:</strong> <?= renderStars($s1) ?></div>
                        <div><strong>Přínos:</strong> <?= renderStars($s2) ?></div>
                        <div><strong>Jazyk:</strong> <?= renderStars($s3) ?></div>
                    </td>

                    <td>
                        <div class="p-2 bg-light rounded border" style="white-space: pre-line;">
                            <?= $cmt ?>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <!-- ADMIN ROZHODNUTÍ -->
    <form method="post" action="<?= $base ?>/admin/file/decision" class="mt-4">

        <input type="hidden" name="file_id" value="<?= e((string)$file['ID_file'] ?? '') ?>">

        <button name="decision" value="approve" class="btn btn-success me-2">
            <i class="bi bi-check-circle"></i> Schválit
        </button>

        <button name="decision" value="reject" class="btn btn-danger">
            <i class="bi bi-x-circle"></i> Zamítnout
        </button>

    </form>

</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>


<?php require __DIR__ . '/../partials/footer.php'; ?>
