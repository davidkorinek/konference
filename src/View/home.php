<?php
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/nav.php';
?>

<div class="page-bg"></div>

<main class="container my-4">

    <!-- Uvodni text -->
    <div class="text-center mb-5">
        <h1 class="display-6">
            Vítejte na konferenci Everyday AI. Objevte, jak může umělá inteligence
            zjednodušit vaši práci, podpořit kreativitu a stát se přirozenou součástí
            běžného života.
        </h1>
    </div>

    <hr class="my-4">

    <!-- Info header -->
    <div class="text-center mb-5">
        <h1>Naše cíle:</h1>
    </div>

    <!-- INFO BOXES -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="info-card green p-3 rounded shadow-sm h-100 d-flex align-items-center justify-content-center">
                <h5 class="text-center">podporovat zodpovědný rozvoj technologií</h5>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="info-card green p-3 rounded shadow-sm h-100 d-flex align-items-center justify-content-center">
                <h5 class="text-center align-middle">zpřístupnit nové poznatky široké veřejnosti</h5>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="info-card green p-3 rounded shadow-sm h-100 d-flex align-items-center justify-content-center">
                <h5 class="text-center">pomáhat lidem i organizacím orientovat se v rychle měnícím prostředí</h5>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="info-card green p-3 rounded shadow-sm h-100 d-flex align-items-center justify-content-center">
                <h5 class="text-center">vytvářet komunitu, která sdílý zkušenosti a inspiruje ostatní</h5>
            </div>
        </div>
    </div>

    <section class="mb-5">
        <p class="lead">
            Everyday AI je prostor, kde propojujeme výzkum, praxi a reálné zkušenosti lidí,
            kteří pracují s umělou inteligencí každý den. Konference vznikla s cílem podpořit
            otevřenou diskusi o tom, jak AI mění běžný život – ať už jde o efektivnější práci,
            dostupnější vzdělávání, osobní asistenci, či kreativní nástroje.
        </p>
    </section>

    <!-- PUBLIKOVANÉ PŘÍSPĚVKY -->
    <h3 class="mb-3">Publikované příspěvky</h3>

    <?php if (empty($files)): ?>
        <div class="alert alert-info">Zatím nebyly publikovány žádné příspěvky.</div>
    <?php else: ?>

        <div class="row g-3">
            <?php foreach ($files as $f): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">

                        <div class="card-body d-flex flex-column">

                            <h5 class="mb-2"><?= e($f['title']) ?></h5>

                            <div class="small text-muted mb-2">
                                <strong>Autoři:</strong>
                                <?= e($f['authors']) ?>
                            </div>

                            <p class="text-muted small">
                                <?= nl2br(e(mb_strimwidth($f['abstract'], 0, 200, '…'))) ?>
                            </p>

                            <div class="mt-auto">
                                <a href="<?= $base ?>/assets/uploads/<?= e($f['filename']) ?>"
                                   class="btn btn-outline-primary btn-sm"
                                   target="_blank">
                                    Otevřít PDF
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>

<?php require __DIR__ . '/partials/footer.php';?>
