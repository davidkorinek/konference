<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/header.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<?php if (!empty($_SESSION['register_error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['register_success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
    </div>
<?php endif; ?>



<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Registrace – Konference Mars 2040</title>

    <link rel="stylesheet" href="<?= $base ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/custom.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* modrá karta */
        .register-box {
            border: 2px solid #2a7de1;
            border-radius: 10px;
            padding: 30px;
            background: #ffffffc7;
            backdrop-filter: blur(4px);
        }

        .form-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            opacity: .7;
        }

        .form-input {
            padding-left: 35px;
        }

        /* červené varování pro hesla */
        #passAlert {
            display: none;
        }

        /* Responsivní zmenšení */
        @media (max-width: 768px) {
            .register-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Registrace nového uživatele</h2>

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-9">

            <div class="register-box shadow">

                <form method="post" action="<?= $base ?>/register" onsubmit="return checkPass()">

                    <!-- LOGIN -->
                    <label class="fw-bold mb-1">Login pro přihlášení:</label>
                    <div class="position-relative mb-3">
                        <i class="bi bi-person-fill form-icon"></i>
                        <input type="text" name="username" class="form-control form-input" required>
                    </div>

                    <!-- HESLO -->
                    <label class="fw-bold mb-1">Heslo pro přihlášení (zopakujte 2×):</label>

                    <div class="position-relative mb-2">
                        <i class="bi bi-lock-fill form-icon"></i>
                        <input type="password" id="pass1" name="password1" class="form-control form-input" required>
                    </div>

                    <div class="position-relative mb-3">
                        <i class="bi bi-lock-fill form-icon"></i>
                        <input type="password" id="pass2" name="password2" class="form-control form-input" required>
                    </div>

                    <div id="passAlert" class="alert alert-danger py-2">
                        Hesla: <strong>nejsou stejná</strong> ⚡
                    </div>

                    <!-- EMAIL -->
                    <label class="fw-bold mb-1">E-mail pro potvrzení registrace:</label>
                    <div class="position-relative mb-3">
                        <i class="bi bi-envelope-fill form-icon"></i>
                        <input type="email" name="email" class="form-control form-input" required>
                    </div>

                    <!-- Registrace -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4">Registrovat</button>
                    </div>

                </form>

            </div>

            <p class="mt-3 small text-muted">
                * Vyplňte prosím všechna pole
            </p>

        </div>
    </div>
</div>

<script>
    function checkPass() {
        let p1 = document.getElementById("pass1").value;
        let p2 = document.getElementById("pass2").value;
        let alertBox = document.getElementById("passAlert");

        if (p1 !== p2) {
            alertBox.style.display = "block";
            return false;
        }

        alertBox.style.display = "none";
        return true;
    }
</script>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
