<?php
namespace Davca\Konference\Controllers;

use Davca\Konference\Models\File;
use Davca\Konference\Models\Review;
use Davca\Konference\Models\User;

class AdminController {
    // obsah pouze pro adminy
    private function requireAdmin(){
        session_start();

        // neprihlasen
        if (!isset($_SESSION['user'])) {
            header("Location: /konference/public/");
            exit;
        }

        // blokovany
        if (!empty($_SESSION['user']['blocked'])) {
            session_destroy();
            header("Location: /konference/public/");
            exit;
        }

        // neni admin
        if (!in_array($_SESSION['user']['role'], ['admin', 'superadmin'])) {
            header("Location: /konference/public/");
            exit;
        }
    }

    //adminske seznamy
    public function files(){
        $this->requireAdmin();
        $fileModel = new File();
        $files = $fileModel->getAllAdminReviewFiles();
        require __DIR__ . '/../View/admin/files.php';
    }

    public function users(){
        $this->requireAdmin();
        $userModel = new User();
        $users = $userModel->getAllUsers();
        require __DIR__ . '/../View/admin/users.php';
    }

    public function articles(){
        $this->requireAdmin();

        $fileModel = new File();
        $files = $fileModel->getAllArticles();

        require __DIR__ . '/../View/admin/articles.php';
    }

    //prirazovani recenzentu

    public function assignForm(){
        $this->requireAdmin();

        $fileId = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$fileId) {
            header("Location: /konference/public/admin/files");
            exit;
        }

        $fileModel = new File();
        $file = $fileModel->getFileByIdWithStatus($fileId) ?? null;
        if (!$file) {
            $_SESSION['admin_error'] = "Článek nenalezen.";
            header("Location: /konference/public/admin/files");
            exit;
        }

        $reviewers = $fileModel->getReviewers();

        require __DIR__ . '/../View/admin/assign.php';
    }

    //ulozeni prirazeni
    public function assignSave(){
        $this->requireAdmin();

        $fileId = isset($_POST['file_id']) ? (int) $_POST['file_id'] : null;
        $selected = $_POST['reviewers'] ?? [];

        if (!$fileId) {
            $_SESSION['admin_error'] = "Chybí ID článku.";
            header("Location: /konference/public/admin/files");
            exit;
        }

        if (count($selected) < 3) {
            $_SESSION['admin_error'] = "Musíte vybrat alespoň 3 recenzenty!";
            header("Location: /konference/public/admin/file/assign?id=$fileId");
            exit;
        }

        $fileModel = new File();
        $fileModel->clearAssignments($fileId);

        foreach ($selected as $r) {
            $fileModel->assignReviewer($fileId, (int)$r);
        }

        $fileModel->setStatus($fileId, 'in_review');

        header("Location: /konference/public/admin/files");
        exit;
    }

    //souhrn recenzi
    public function reviewSummary(){
        $this->requireAdmin();

        $fileId = $_GET['id'] ?? null;
        if (!$fileId) exit;

        $fileModel = new File();
        $file = $fileModel->getFileByIdWithStatus($fileId);

        if (!$file) exit;

        $reviewModel = new Review();
        $reviews = $reviewModel->getReviewsForFile($fileId); // vždy vrací 3 řádky

        require __DIR__ . '/../View/admin/reviews.php';
    }

    //potvrzeni rozhodnuti
    public function publishDecision(){
        $this->requireAdmin();

        $fileId = isset($_POST['file_id']) ? (int) $_POST['file_id'] : null;
        $decision = $_POST['decision'] ?? null;

        if (!$fileId || !$decision) {
            $_SESSION['admin_error'] = "Chybné parametry.";
            header("Location: /konference/public/admin/files");
            exit;
        }

        $fileModel = new File();

        $statusName = ($decision === 'approve') ? 'approved' : 'rejected';
        $fileModel->setStatus($fileId, $statusName);

        $_SESSION['admin_success'] = ($statusName === 'approved') ? "Článek byl schválen." : "Článek byl zamítnut.";
        header("Location: /konference/public/admin/files");
        exit;
    }

    //aktualizace uzivatalu
    public function updateAllUsers(){
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /konference/public/admin/users");
            exit;
        }

        $roles   = $_POST['role'] ?? [];
        $blocks  = $_POST['blocked'] ?? [];

        $userModel = new User();
        $users = $userModel->getAllUsers();

        foreach ($users as $u) {
            $id = (int)$u['ID_user'];
            $newRole = $roles[$id] ?? $u['role_name'];
            $newBlocked = isset($blocks[$id]) ? 1 : 0;

            // superadmin je nemenny
            if ($u['role_name'] === 'superadmin') {
                continue;
            }

            // admin nemuze menit jineho admina
            if ($_SESSION['user']['role'] === 'admin' && $u['role_name'] === 'admin') {
                continue;
            }

            // admin nemuze menit sam sebe
            if ($id == $_SESSION['user']['id']) {
                continue;
            }

            // superadmin roli nelze nastavit
            if ($newRole === 'superadmin') {
                continue;
            }

            $userModel->updateUser($id, $newRole, $newBlocked);
        }

        $_SESSION['admin_success'] = "Změny byly úspěšně uloženy.";
        header("Location: /konference/public/admin/users");
        exit;
    }

    // mazani clanku (pouze superadmin)
    public function deleteFile(){
        $this->requireAdmin();

        // pouze superadmin muze mazat
        if ($_SESSION['user']['role'] !== 'superadmin') {
            $_SESSION['admin_error'] = "Pouze superadmin může mazat články.";
            header("Location: /konference/public/admin/files");
            exit;
        }

        $fileId = $_POST['file_id'] ?? null;
        if (!$fileId) exit;

        $fileModel = new File();
        $fileModel->deleteFileAdmin($fileId);

        $_SESSION['admin_success'] = "Článek byl smazán.";
        header("Location: /konference/public/admin/articles");
        exit;
    }

    // vraceni publikovaneho clanku do recenzniho rizeni
    public function resetReview(){
        $this->requireAdmin();

        $fileId = $_POST['file_id'] ?? null;
        if (!$fileId) exit;

        $fileModel = new File();
        $fileModel->resetToReview($fileId);

        $_SESSION['admin_success'] = "Článek byl vrácen do recenzního řízení.";
        header("Location: /konference/public/admin/files");
        exit;
    }
}
