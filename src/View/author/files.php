<?php
$base = '/konference/public';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

/* Funkce pro vykreslení hvězdiček s půl body */
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vlastní vložené články</h2>
        <a href="<?= $base ?>/author/files/new" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Přidat nový článek
        </a>
    </div>

    <?php foreach ($files as $file): ?>

        <?php
        // STATUS LOGIKA
        $status = strtolower($file['status_name']);

        switch ($status) {
            case 'uploaded':
            case 'waiting_for_check':
                $statusClass = "bg-info text-dark";
                $statusText  = "Čeká na posouzení";
                break;

            case 'in_review':
                $statusClass = "bg-primary text-white";
                $statusText  = "Probíhá recenzní řízení";
                break;

            case 'approved':
                $statusClass = "bg-success text-white";
                $statusText  = "Schváleno k publikaci";
                break;

            case 'rejected':
                $statusClass = "bg-danger text-white";
                $statusText  = "Zamítnuto";
                break;

            default:
                $statusClass = "bg-secondary text-white";
                $statusText  = e($file['status_name']);
        }

        $rating = floatval($file['rating'] ?? 0);
        ?>

        <div class="card shadow-sm mb-4">

            <div class="card-header <?= $statusClass ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <strong><?= e($file['title']) ?></strong>
                    <span><?= $statusText ?></span>
                </div>
            </div>

            <div class="card-body">

                <div class="text-end text-muted small mb-2">
                    <?= date("d.m.Y H:i", strtotime($file['upload_date'])) ?>
                </div>

                <p><strong>Autoři:</strong> <?= e($file['authors']) ?></p>

                <p>
                    <strong>Abstrakt:</strong><br>
                    <?= nl2br(e($file['abstract'])) ?>
                </p>

                <?php if ($rating > 0): ?>
                    <p><strong>Hodnocení:</strong> <?= renderStars($rating) ?></p>
                <?php endif; ?>

                <!-- AKCE -->
                <div class="d-flex gap-2 mb-2">
                    <a href="<?= $base ?>/author/files/edit?id=<?= $file['ID_file'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Upravit
                    </a>

                    <button class="btn btn-info btn-sm" onclick="togglePDF(<?= $file['ID_file'] ?>)">
                        <i class="bi bi-eye"></i> Zobrazit PDF
                    </button>

                    <button
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteConfirmModal"
                            data-id="<?= $file['ID_file'] ?>">
                        <i class="bi bi-trash"></i> Odebrat
                    </button>
                </div>

                <!-- PDF Viewer -->
                <div id="pdf<?= $file['ID_file'] ?>" class="mt-3" style="display:none;">
                    <iframe src="<?= $base ?>/assets/uploads/<?= e($file['filename']) ?>"
                            width="100%" height="600" class="border rounded"></iframe>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<script>
    function togglePDF(id) {
        let box = document.getElementById("pdf" + id);
        box.style.display = (box.style.display === "none") ? "block" : "none";
    }
</script>

<!-- MODAL SMAZÁNÍ -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Potvrzení smazání</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Opravdu chcete smazat tento článek? Tuto akci nelze vrátit.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                <a id="deleteConfirmBtn" class="btn btn-danger">Smazat</a>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById('deleteConfirmModal');
        modal.addEventListener('show.bs.modal', event => {
            const fileId = event.relatedTarget.getAttribute('data-id');
            document.getElementById('deleteConfirmBtn').href =
                "<?= $base ?>/author/files/delete?id=" + fileId;
        });
    });
</script>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>


<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
