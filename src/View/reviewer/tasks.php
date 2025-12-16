<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

/**
 * Vykreslí 0.5 hvězdičky pomocí Bootstrap ikon.
 */
function renderStars($value)
{
    if ($value === null || $value === '') return '';

    $value = (float)$value;
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
?>

<div class="container my-4">

    <h2 class="mb-4">Vlastní přiřazené recenze</h2>

    <?php if (empty($tasks)): ?>
        <div class="alert alert-info">Nemáte žádné přiřazené recenze.</div>
    <?php endif; ?>

    <?php foreach ($tasks as $t): ?>

        <?php
        // Základní bezpečné údaje
        $title    = e($t['title'] ?? 'Bez názvu');
        $authors  = e($t['authors'] ?? 'Neznámí autoři');
        $abstract = nl2br(e($t['abstract'] ?? 'Bez abstraktu'));
        $filename = $t['filename'] ?? '';
        $fileId   = $t['ID_file'] ?? null;

        // rozhodnutí recenzenta
        $decision = $t['my_decision'] ?? null;

        // Header / badge
        if ($decision === null || $decision === '') {
            $badge = "<span class='badge bg-info text-dark'>Čeká na posouzení</span>";
            $headerClass = "bg-info text-dark";
        } elseif ($decision == 'approve' || $decision == 1) {
            $badge = "<span class='badge bg-success'>Moje rozhodnutí: schválit</span>";
            $headerClass = "bg-success text-white";
        } elseif ($decision == 'reject' || $decision == 2) {
            $badge = "<span class='badge bg-danger'>Moje rozhodnutí: zamítnout</span>";
            $headerClass = "bg-danger text-white";
        } else {
            $badge = "<span class='badge bg-warning text-dark'>Moje rozhodnutí: vyžaduje úpravy</span>";
            $headerClass = "bg-warning text-dark";
        }
        ?>

        <div class="card mb-4 shadow-sm">
            <div class="card-header <?= $headerClass ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <strong><?= $title ?></strong>
                    <?= $badge ?>
                </div>
            </div>

            <div class="card-body">

                <p><b>Autoři:</b> <?= $authors ?></p>
                <p><b>Abstrakt:</b><br><?= $abstract ?></p>

                <div class="d-flex gap-2 mb-3">
                    <a href="<?= $base ?>/reviewer/review?id=<?= $fileId ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Recenzovat
                    </a>

                    <?php if (!empty($filename)): ?>
                        <a href="<?= $base ?>/assets/uploads/<?= e($filename) ?>" class="btn btn-success btn-sm" target="_blank">
                            <i class="bi bi-download"></i> Stáhnout PDF
                        </a>
                    <?php endif; ?>
                </div>

                <?php if ($decision !== null && $decision !== ''): ?>

                    <?php
                    $s1 = $t['score1'] ?? null;
                    $s2 = $t['score2'] ?? null;
                    $s3 = $t['score3'] ?? null;

                    // CKEditor HTML — BEZ e()
                    $comment = $t['comment'] ?? '';
                    ?>

                    <hr>
                    <h6>Moje hodnocení:</h6>

                    <div><strong>Kvalita obsahu:</strong> <?= renderStars($s1) ?></div>
                    <div><strong>Přínos:</strong> <?= renderStars($s2) ?></div>
                    <div><strong>Jazyková úroveň:</strong> <?= renderStars($s3) ?></div>

                    <?php if (!empty($comment)): ?>
                        <?php
                        // HTMLPurifier – stejná konfigurace jako při ukládání
                        require_once __DIR__ . '/../../../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';
                        $config = \HTMLPurifier_Config::createDefault();
                        $config->set('HTML.SafeIframe', false);
                        $config->set('HTML.Allowed', 'p,b,i,u,strong,em,ul,ol,li,blockquote,br');
                        $config->set('HTML.ForbiddenElements', ['script','img']);
                        $config->set('AutoFormat.RemoveEmpty', true);
                        $purifier = new \HTMLPurifier($config);

                        $safeComment = $purifier->purify($comment);
                        ?>
                        <div class="mt-2">
                            <b>Komentář:</b>
                            <div class="border rounded p-2 bg-light">
                                <?= $safeComment ?>
                            </div>
                        </div>
                    <?php endif; ?>


                <?php endif; ?>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>
