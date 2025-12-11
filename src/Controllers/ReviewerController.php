<?php
namespace Davca\Konference\Controllers;

use Davca\Konference\Models\File;
use Davca\Konference\Models\Review;

class ReviewerController {

    /* ----------------------------------------------------
       Přístup pouze pro recenzenty
    ---------------------------------------------------- */
    private function requireReviewer(){
        session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: /konference/public/");
            exit;
        }

        if (!empty($_SESSION['user']['blocked'])) {
            session_destroy();
            header("Location: /konference/public/");
            exit;
        }

        if ($_SESSION['user']['role'] !== 'reviewer') {
            header("Location: /konference/public/");
            exit;
        }
    }



    /* ----------------------------------------------------
       SEZNAM ÚKOLŮ (recenzovaných článků)
    ---------------------------------------------------- */
    public function tasks(){
        $this->requireReviewer();

        $fileModel = new File();
        $tasks = $fileModel->getAssignedFilesOpen($_SESSION['user']['id']);

        require __DIR__ . '/../View/reviewer/tasks.php';
    }



    /* ----------------------------------------------------
       FORMULÁŘ RECENZE
    ---------------------------------------------------- */
    public function reviewForm(){
        $this->requireReviewer();

        $fileId = $_GET['id'] ?? null;
        if (!$fileId) {
            header("Location: /konference/public/reviewer/tasks");
            exit;
        }

        $fileModel = new File();
        $reviewModel = new Review();

        $file = $fileModel->getFileByIdForReviewer($fileId, $_SESSION['user']['id']);

        if (!$file) {
            $_SESSION['review_error'] = "Tento článek vám není přiřazen nebo už není aktivní.";
            header("Location: /konference/public/reviewer/tasks");
            exit;
        }

        $existingReview = $reviewModel->getReviewByAssignment($file['ID_assignment']);

        require __DIR__ . '/../View/reviewer/review.php';
    }



    /* ----------------------------------------------------
       ODESLÁNÍ RECENZE + OCHRANA PROTI XSS
    ---------------------------------------------------- */
    public function submitReview(){
        $this->requireReviewer();

        // čistá CKEditor data (zatím nebezpečná)
        $assignmentId = $_POST['assignment_id'] ?? null;
        $score1 = $_POST['score1'] ?? null;
        $score2 = $_POST['score2'] ?? null;
        $score3 = $_POST['score3'] ?? null;
        $decision = $_POST['decision'] ?? null;
        $comment = $_POST['comment'] ?? '';

        // bezpečnostní knihovna
        require_once __DIR__ . '/../../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';

        // konfigurace HTMLPurifieru
        $config = \HTMLPurifier_Config::createDefault();

        // povolené tagy (bezpečné)
        $config->set('HTML.Allowed',
            'p,b,strong,i,em,u,ul,ol,li,blockquote,br'
        );

        // zakázané prvky
        $config->set('HTML.ForbiddenElements', [
            'script', 'iframe', 'img', 'svg', 'object'
        ]);

        // zakáže onclick=, onerror= apod.
        $config->set('HTML.ForbiddenAttributes', ['on*']);

        // automatický cleanup
        $config->set('AutoFormat.RemoveEmpty', true);

        // Purifier instance
        $purifier = new \HTMLPurifier($config);

        // zde dostaneš 100% bezpečný HTML výstup
        $cleanComment = $purifier->purify($comment);

        // uložit recenzi
        $reviewModel = new Review();
        $reviewModel->saveReview(
            $assignmentId,
            $decision,
            $cleanComment,  // uložíme očištěný HTML
            $score1,
            $score2,
            $score3
        );

        $_SESSION['review_success'] = "Recenze byla uložena.";
        header("Location: /konference/public/reviewer/tasks");
        exit;
    }
}
