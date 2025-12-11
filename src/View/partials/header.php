<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base = '/konference/public';

// Stav přihlášení
$loggedIn   = isset($_SESSION['user']);
$currentUser = $loggedIn ? $_SESSION['user'] : null;
$userRole    = $currentUser['role'] ?? 'visitor';

// Chyba přihlášení?
$loginError = $_SESSION['login_error'] ?? null;
?>
<header class="site-header py-3 shadow-sm bg-white">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Logo + název -->
        <div class="d-flex align-items-center">
            <img src="<?= $base ?>/assets/img/logo.png"
                 class="me-3 header-logo d-none d-md-block"
                 alt="logo">

            <div>
                <h1 class="site-title mb-0">
                    Konference <span class="title-accent">Everyday AI</span>
                </h1>
                <p class="site-subtitle mb-0">Život s AI pomocí...</p>
            </div>
        </div>

        <!-- LOGIN PANEL -->
        <div>
            <?php if (!$loggedIn): ?>

                <a href="<?= $base ?>/register" class="me-3">
                    Registrace
                </a>

                <!-- DROPDOWN LOGIN -->
                <div class="dropdown d-inline-block" id="loginDropdown">

                    <button class="btn btn-outline-secondary dropdown-toggle"
                            id="loginBtn"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-lock-fill"></i> Login
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width:260px;">

                        <?php if ($loginError): ?>
                            <div class="alert alert-danger py-1 text-center small">
                                <?= htmlspecialchars($loginError) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= $base ?>/login">
                            <input name="username" class="form-control mb-2" placeholder="Login" required>
                            <input name="password" type="password" class="form-control mb-2" placeholder="Heslo" required>
                            <button class="btn btn-primary w-100 mb-2">Přihlásit</button>
                        </form>

                    </div>
                </div>

            <?php else: ?>

                <!-- PŘIHLÁŠENÝ UŽIVATEL -->
                <div class="dropdown d-inline-block">

                    <button class="btn btn-outline-secondary dropdown-toggle"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                        <?= e($currentUser['username']) ?>
                        (<?= e($userRole) ?>)
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= $base ?>/logout">Odhlásit se</a></li>
                    </ul>

                </div>

            <?php endif; ?>
        </div>

    </div>
</header>


<!-- AUTO-OPEN LOGIN DROPDOWN WHEN ERROR -->
<?php if (!empty($loginError)): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const loginBtn = document.getElementById("loginBtn");
            if (!loginBtn) return;

            // Bootstrap dropdown instance
            const dropdown = bootstrap.Dropdown.getOrCreateInstance(loginBtn);

            // Open dropdown
            dropdown.show();

            // Focus username input
            const input = document.querySelector("#loginDropdown input[name='username']");
            if (input) input.focus();

            // Remove login_error from session (via AJAX)
            fetch("<?= $base ?>/clear-login-error", { method: "POST" });
        });
    </script>
<?php endif; ?>
