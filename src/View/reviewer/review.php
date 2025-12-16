<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';
?>

<div class="container my-4">

    <h2 class="mb-4">Recenze článku</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h4 class="fw-bold mb-2"><?= e($file['title']) ?></h4>
            <p><strong>Autoři:</strong> <?= e($file['authors']) ?></p>

            <iframe src="<?= $base ?>/assets/uploads/<?= e($file['filename']) ?>"
                    width="100%" height="500"
                    class="border rounded mb-4"></iframe>

            <?php $existingDecision = $existingReview['decision'] ?? null; ?>

            <?php if ($existingDecision): ?>
                <p>
                    <span class="badge
                        <?= $existingDecision === 'approve' ? 'bg-success' : '' ?>
                        <?= $existingDecision === 'reject' ? 'bg-danger' : '' ?>
                        <?= $existingDecision === 'needs_changes' ? 'bg-warning text-dark' : '' ?>">
                        <?= $existingDecision === 'approve' ? 'Schváleno' : '' ?>
                        <?= $existingDecision === 'reject' ? 'Zamítnuto' : '' ?>
                        <?= $existingDecision === 'needs_changes' ? 'Vyžaduje úpravy' : '' ?>
                    </span>
                </p>
            <?php endif; ?>

            <hr>

            <form method="post" action="<?= $base ?>/reviewer/review">

                <input type="hidden" name="assignment_id" value="<?= $file['ID_assignment'] ?>">

                <div class="row mb-4">

                    <div class="col-md-4">
                        <h5 class="mb-3">Posuzované vlastnosti:</h5>

                        <?php
                        $scores = [
                                ['label' => 'Kvalita obsahu', 'name' => 'score1'],
                                ['label' => 'Přínos', 'name' => 'score2'],
                                ['label' => 'Jazyková úroveň', 'name' => 'score3']
                        ];
                        ?>

                        <?php foreach ($scores as $sc): ?>
                            <?php $val = $existingReview[$sc['name']] ?? 3; ?>

                            <div class="mb-3">
                                <label class="form-label"><?= $sc['label'] ?></label>

                                <div class="star-rating-container">
                                    <input type="range"
                                           name="<?= $sc['name'] ?>"
                                           class="form-range rating-input"
                                           min="0.5" max="5" step="0.5"
                                           value="<?= $val ?>">

                                    <div class="rating-stars" data-target="<?= $sc['name'] ?>"></div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>

                    <div class="col-md-8">

                        <label class="form-label fw-semibold">Rozhodnutí</label>
                        <select name="decision" class="form-select mb-3">
                            <option value="approve"        <?= ($existingDecision==='approve')?'selected':'' ?>>Schválit</option>
                            <option value="reject"         <?= ($existingDecision==='reject')?'selected':'' ?>>Zamítnout</option>
                            <option value="needs_changes"  <?= ($existingDecision==='needs_changes')?'selected':'' ?>>Vyžaduje úpravy</option>
                        </select>

                        <textarea id="commentEditor" name="comment" rows="10" class="form-control">
                            <?= $existingReview['comment'] ?? '' ?>
                        </textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-success px-4">Uložit recenzi</button>
                    <a href="<?= $base ?>/reviewer/tasks" class="btn btn-secondary px-4">Zpět</a>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    function renderStars(element, value) {
        value = parseFloat(value);
        let html = "";

        for (let i = 1; i <= 5; i++) {
            if (value >= i) {
                html += `<i class="bi bi-star-fill text-warning"></i>`;
            } else if (value >= i - 0.5) {
                html += `<i class="bi bi-star-half text-warning"></i>`;
            } else {
                html += `<i class="bi bi-star text-warning"></i>`;
            }
        }

        element.innerHTML = html;
    }

    document.querySelectorAll(".rating-input").forEach(input => {
        let stars = document.querySelector(`.rating-stars[data-target="${input.name}"]`);

        function update() {
            renderStars(stars, input.value);
        }

        input.addEventListener("input", update);
        update();
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
