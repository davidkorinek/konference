<?php
$base = '/konference/public';
?>
<nav class="navbar navbar-expand-lg navbar-color">
    <div class="container">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="mainNav" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">

                <!-- Vidí všichni -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>/">Informace</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>/program">Program konference</a>
                </li>

                <?php if ($userRole === 'author'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base ?>/author/files">Moje příspěvky</a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole === 'reviewer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base ?>/reviewer/tasks">Moje recenze</a>
                    </li>
                <?php endif; ?>

                <?php if ($userRole === 'admin' || $userRole === 'superadmin'): ?>
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Administrace
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">

                            <li><a class="dropdown-item bi-person" href="<?= $base ?>/admin/users">Uživatelé</a></li>
                            <li><a class="dropdown-item bi-journals" href="<?= $base ?>/admin/files">Recenzní řízení</a></li>
                            <li><a class="dropdown-item bi-paperclip" href="<?= $base ?>/admin/articles">Články</a></li>

                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>

