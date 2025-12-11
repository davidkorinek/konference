<?php
namespace Davca\Konference\Controllers;

use Davca\Konference\Models\File;

class AuthorController {
    // obsah pouze pro autory
    private function requireAuthor(){
        session_start();

        // blokace
        if (!empty($_SESSION['user']['blocked'])) {
            session_destroy();
            header("Location: /konference/public/");
            exit;
        }

        // role
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'author') {
            header("Location: /konference/public/");
            exit;
        }
    }

    // clanky
    public function files(){
        $this->requireAuthor();

        $authorId = $_SESSION['user']['id'];
        $fileModel = new File();
        $files = $fileModel->getFilesByAuthor($authorId);

        require __DIR__ . '/../View/author/files.php';
    }

    // novy clanek
    public function newForm(){
        $this->requireAuthor();
        require __DIR__ . '/../View/author/new.php';
    }

    // tvorba noveho clanku
    public function create(){
        $this->requireAuthor();

        $authorId = $_SESSION['user']['id'];

        $errors   = [];
        $authors  = trim($_POST['authors'] ?? '');
        $title    = trim($_POST['title'] ?? '');
        $abstract = trim($_POST['abstract'] ?? '');

        if ($authors === '') $errors[] = "Vyplňte jména autorů.";
        if ($title === '') $errors[] = "Vyplňte název článku.";
        if ($abstract === '') $errors[] = "Vyplňte abstrakt.";

        // PDF kontrola
        if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Nahrání PDF se nezdařilo.";
        } else {
            $tmp = $_FILES['pdf']['tmp_name'];
            $type = mime_content_type($tmp);

            if ($type !== "application/pdf") {
                $errors[] = "Soubor musí být PDF.";
            }
            if ($_FILES['pdf']['size'] > 5 * 1024 * 1024) {
                $errors[] = "PDF může mít max 5 MB.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['upload_errors'] = $errors;
            header("Location: /konference/public/author/files/new");
            exit;
        }

        // bezpecne ulozeni
        $filename = uniqid("file_", true) . ".pdf";
        $path = __DIR__ . "/../../public/assets/uploads/" . $filename;
        move_uploaded_file($tmp, $path);

        // ulozit do databaze
        $fileModel = new File();
        $fileModel->createFile($authorId, $authors, $title, $abstract, $filename);

        header("Location: /konference/public/author/files");
        exit;
    }

    // uprava clanku
    public function editForm(){
        $this->requireAuthor();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /konference/public/author/files");
            exit;
        }

        $fileModel = new File();
        $file = $fileModel->getFileById($id);

        // clanek musi existovat
        if (!$file) {
            header("Location: /konference/public/author/files");
            exit;
        }

        // clanek musi patrit prihlasenemu autorovi
        if ($file['uploaded_by'] != $_SESSION['user']['id']) {
            header("Location: /konference/public/author/files");
            exit;
        }

        require __DIR__ . '/../View/author/edit.php';
    }


    // upravy vybraneho clanku
    public function edit(){
        $this->requireAuthor();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: /konference/public/author/files");
            exit;
        }

        $fileModel = new File();
        $file = $fileModel->getFileById($id);

        if ($file['uploaded_by'] != $_SESSION['user']['id']) {
            header("Location: /konference/public/author/files");
            exit;
        }

        if ($file['ID_status'] == 4) {
            $_SESSION['edit_error'] = "Publikovaný článek nelze upravovat.";
            header("Location: /konference/public/author/files");
            exit;
        }

        $authors  = trim($_POST['authors']);
        $title    = trim($_POST['title']);
        $abstract = trim($_POST['abstract']);

        $fileModel->updateFile($id, $authors, $title, $abstract);

        header("Location: /konference/public/author/files");
        exit;
    }

    // smazani clanku
    public function delete(){
        $this->requireAuthor();

        $fileId = $_GET['id'] ?? null;
        if (!$fileId) {
            header("Location: /konference/public/author/files");
            exit;
        }

        $fileModel = new File();
        $file = $fileModel->getFileById($fileId);

        if ($file['uploaded_by'] != $_SESSION['user']['id']) {
            header("Location: /konference/public/author/files");
            exit;
        }

        if ($file['ID_status'] == 4) {
            $_SESSION['delete_error'] = "Publikovaný článek nelze smazat.";
            header("Location: /konference/public/author/files");
            exit;
        }

        // smazani nahraneho pdf
        $path = __DIR__ . "/../../public/assets/uploads/" . $file['filename'];
        if (file_exists($path)) unlink($path);

        $fileModel->deleteFile($fileId);

        header("Location: /konference/public/author/files");
        exit;
    }
}
