<?php
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';
?>

<div class="container my-4">

    <?php if (!empty($_SESSION['admin_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['admin_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['admin_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['admin_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['admin_error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['admin_error']); ?>
    <?php endif; ?>


    <h2>Správa uživatelů</h2>
    <p>Zde můžete měnit role a blokovat uživatele.</p>

    <form method="post" action="<?= $base ?>/admin/users/update-all">

        <table class="table table-bordered mt-3 align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Uživatel</th>
                <th>Email</th>
                <th>Role</th>
                <th>Blokace</th>
            </tr>
            </thead>

            <tbody>

            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['ID_user'] ?></td>
                    <td><?= e($u['username']) ?></td>
                    <td><?= e($u['email']) ?></td>

                    <!-- ROLE -->
                    <td>
                        <?php if ($u['role_name'] === 'superadmin'): ?>

                            <span class="badge bg-danger">Superadmin</span>
                            <input type="hidden" name="role[<?= $u['ID_user'] ?>]" value="superadmin">

                        <?php else: ?>

                            <?php if ($_SESSION['user']['role'] === 'admin' && $u['role_name'] === 'admin'): ?>
                                <span class="text-muted">Nelze upravit</span>
                                <input type="hidden" name="role[<?= $u['ID_user'] ?>]" value="<?= $u['role_name'] ?>">
                            <?php else: ?>
                                <select name="role[<?= $u['ID_user'] ?>]" class="form-select form-select-sm">
                                    <option value="author"   <?= $u['role_name']=="author"   ? "selected":"" ?>>Autor</option>
                                    <option value="reviewer" <?= $u['role_name']=="reviewer" ? "selected":"" ?>>Recenzent</option>
                                    <option value="admin"    <?= $u['role_name']=="admin"    ? "selected":"" ?>>Admin</option>
                                </select>
                            <?php endif; ?>

                        <?php endif; ?>
                    </td>

                    <!-- BLOKACE -->
                    <td>

                        <?php if ($u['ID_user'] == $_SESSION['user']['id']): ?>
                            <span class="text-muted">Nelze blokovat</span>
                            <input type="hidden" name="blocked[<?= $u['ID_user'] ?>]" value="<?= $u['blocked'] ?>">
                        <?php elseif ($u['role_name'] === 'superadmin'): ?>
                            <span class="text-muted">Nelze blokovat</span>
                            <input type="hidden" name="blocked[<?= $u['ID_user'] ?>]" value="<?= $u['blocked'] ?>">
                        <?php else: ?>
                            <input type="checkbox" name="blocked[<?= $u['ID_user'] ?>]" value="1"
                                    <?= $u['blocked'] ? 'checked' : '' ?>>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

        <button class="btn btn-primary mt-3">
            <i class="bi bi-save"></i> Uložit všechny změny
        </button>

    </form>

</div>

<script src="<?= $base ?>/assets/js/bootstrap.bundle.min.js"></script>


<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
