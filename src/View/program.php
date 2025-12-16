<?php
$base = '/konference/public';
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/nav.php';
?>

<div class="container my-5">

    <h2 class="text-center mb-4">Program konference: Everyday AI – Život s umělou inteligencí</h2>

    <p class="lead text-center mb-5">
        Konference zaměřená na praktické využití umělé inteligence v běžném životě, práci i vzdělávání.
        Přinášíme ukázky, přednášky i panelové diskuze s odborníky.
    </p>

    <h3 class="mt-4">28.12.2025</h3>
    <!-- Dopolední blok -->
    <h3 class="mt-4">Dopolední blok (9:00–12:00)</h3>
    <div class="list-group mb-4">

        <div class="list-group-item py-3">
            <h5>9:00–9:45 — AI v každodenním životě: realita místo sci-fi</h5>
            <div><strong>Řečník:</strong> doc. Ing. Jan Konečný, Ph.D.</div>
            <p class="mb-1">
                Uvodní keynote o tom, jak AI skutečně zasahuje do našeho každodenního života –
                od chytrých domácností po personalizované vyhledávání.
            </p>
        </div>

        <div class="list-group-item py-3">
            <h5>10:00–10:45 — Bezpečné používání AI v době deepfake technologií</h5>
            <div><strong>Řečník:</strong> Mgr. Klára Benešová</div>
            <p class="mb-1">
                Praktický přehled rizik moderní AI a jak běžní uživatelé mohou odhalovat manipulaci obrazem,
                hlasem či textem.
            </p>
        </div>

        <div class="list-group-item py-3">
            <h5>11:00–12:00 — Panelová diskuze: Jak AI změní pracovní trh</h5>
            <div><strong>Moderátor:</strong> RNDr. Petr Smutný</div>
            <p class="mb-1">
                Odborníci z firem a univerzit diskutují o dopadu automatizace na budoucí pracovní pozice
                a nových příležitostech pro mladé profesionály.
            </p>
        </div>

    </div>

    <!-- Odpolední blok -->
    <h3 class="mt-4">Odpolední blok (13:00–17:00)</h3>
    <div class="list-group mb-4">

        <div class="list-group-item py-3">
            <h5>13:00–13:45 — AI jako váš osobní asistent</h5>
            <div><strong>Řečník:</strong> Ing. Michal Pavlík</div>
            <p class="mb-1">
                Jak chatboti a inteligentní asistenti mění způsob, jak pracujeme, plánujeme a rozhodujeme se.
                Ukázky praktického využití.
            </p>
        </div>

        <div class="list-group-item py-3">
            <h5>14:00–14:45 — Strojové učení pro úplné začátečníky</h5>
            <div><strong>Řečník:</strong> Bc. Simona Kopecká</div>
            <p class="mb-1">
                Workshop vysvětlující základní principy AI na jednoduchých příkladech, kterým porozumí i laik.
            </p>
        </div>

        <div class="list-group-item py-3">
            <h5>15:00–16:00 — AI ve vzdělávání: pomoc, nebo hrozba?</h5>
            <div><strong>Řečník:</strong> Mgr. David Hrnčíř</div>
            <p class="mb-1">
                Diskuze nad využitím AI při výuce, psaní prací, tvorbě úkolů a efektivitě studia.
            </p>
        </div>

        <div class="list-group-item py-3">
            <h5>16:00–17:00 — Závěrečné demo: Jak vytvořit vlastní AI aplikaci</h5>
            <div><strong>Řečník:</strong> Ing. Roman Dvořák</div>
            <p class="mb-1">
                Živé demo vysvětlující, jak vzniká jednoduchá AI aplikace od zadání až po finální výstup.
            </p>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="<?= $base ?>/assets/docs/program.pdf" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-pdf"></i> Stáhnout kompletní program (PDF)
        </a>
    </div>

</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
