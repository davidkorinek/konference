<?php
namespace Davca\Konference\Models;

use Davca\Konference\Core\Database;

/*
 * model reprezentujici odborny clanek v systemu
 *
 * zajistena sprava clanku (CRUD), prace se statusy, nacitani recenzui
 *
 * tabulky se kterymi pracuje
 *   Files               – hlavni tabulka clanku
 *   FileStatuses        – status workflow (uploaded, in_review, approved…)
 *   ReviewAssignments   – prirazeni recenzentu ke clanku
 *   Reviews             – samotne recenze
 *   Users               – kvuli nacitani recenzentu
 */
class File {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    // homepage - publikovane clanky
    public function getPublishedFiles(){
        $sql = "
        SELECT f.ID_file, f.title, f.authors, f.abstract, f.filename, f.upload_date
        FROM Files f
        JOIN FileStatuses s ON s.ID_status = f.ID_status
        WHERE s.status_name = 'approved'
        ORDER BY f.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // vlastni clanky autora
    public function getFilesByAuthor($authorId){
        $sql = "
        SELECT f.ID_file, f.title, f.authors, f.abstract, f.filename, f.upload_date, s.status_name,
        (SELECT AVG(r.score1 + r.score2 + r.score3) / 3
         FROM Reviews r
         JOIN ReviewAssignments ra ON ra.ID_assignment = r.ID_assignment
         WHERE ra.ID_file = f.ID_file) AS rating
        FROM Files f
        JOIN FileStatuses s ON s.ID_status = f.ID_status
        WHERE f.uploaded_by = ?
        ORDER BY f.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$authorId]);
        return $stmt->fetchAll();
    }

    // CRUD - CreateReadUpdateDelete
    public function createFile($authorId, $authors, $title, $abstract, $filename){
        $sql = "INSERT INTO Files (authors, title, abstract, filename, original_name,
            mime_type, size, uploaded_by, ID_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$authors, $title, $abstract, $filename, $filename,
            "application/pdf", 0, $authorId]);
    }

    public function updateFile($id, $authors, $title, $abstract){
        $stmt = $this->db->prepare("
            UPDATE Files SET authors = ?, title = ?, abstract = ? WHERE ID_file = ?");
        $stmt->execute([$authors, $title, $abstract, $id]);
    }

    public function deleteFile($id){
        $stmt = $this->db->prepare("DELETE FROM Files WHERE ID_file = ?");
        $stmt->execute([$id]);
    }

    // admin seznam clanku
    public function getAllAdminReviewFiles(){
        $sql = "SELECT f.*, fs.status_name,
       (SELECT COUNT(*) FROM Reviews r
        JOIN ReviewAssignments ra ON ra.ID_assignment = r.ID_assignment
        WHERE ra.ID_file = f.ID_file) AS review_count
        FROM Files f
        JOIN FileStatuses fs ON fs.ID_status = f.ID_status
        WHERE fs.status_name NOT IN ('approved', 'rejected')
        ORDER BY f.upload_date DESC";

        return $this->db->query($sql)->fetchAll();
    }

    // recenzent prirazene clanky
    public function getAssignedFilesOpen($reviewerId){
        $sql = "SELECT ra.ID_assignment, f.*, fs.status_name, r.ID_decision AS my_decision,
            r.score1, r.score2, r.score3, r.comment
        FROM ReviewAssignments ra
        JOIN Files f ON f.ID_file = ra.ID_file
        JOIN FileStatuses fs ON fs.ID_status = f.ID_status
        LEFT JOIN Reviews r ON r.ID_assignment = ra.ID_assignment
        WHERE ra.ID_reviewer = ?
          AND fs.status_name IN ('in_review','uploaded','waiting_for_check')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewerId]);
        return $stmt->fetchAll();
    }

    // recenzent detail clanku
    public function getFileByIdForReviewer($fileId, $reviewerId){
        $sql = "SELECT ra.ID_assignment, f.*, fs.status_name
        FROM ReviewAssignments ra
        JOIN Files f ON f.ID_file = ra.ID_file
        JOIN FileStatuses fs ON fs.ID_status = f.ID_status
        WHERE f.ID_file = ? AND ra.ID_reviewer = ?
        LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fileId, $reviewerId]);
        return $stmt->fetch();
    }

    // admin recenzni souhrn
    public function getFileByIdWithStatus($id){
        $stmt = $this->db->prepare("SELECT f.*, fs.status_name
        FROM Files f
        JOIN FileStatuses fs ON fs.ID_status = f.ID_status
        WHERE f.ID_file = ?
        LIMIT 1");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // vsischni recenzenti
    public function getReviewers(){
        $sql = "SELECT u.ID_user, u.username FROM Users u
        JOIN UserRoles ur ON ur.ID_user = u.ID_user
        JOIN Roles r ON r.ID_roles = ur.ID_role
        WHERE r.role_name = 'reviewer'";

        return $this->db->query($sql)->fetchAll();
    }

    // prirazeni recenzentu
    public function assignReviewer($fileId, $reviewerId){
        $stmt = $this->db->prepare("
            INSERT INTO ReviewAssignments (ID_file, ID_reviewer) VALUES (?, ?)");
        $stmt->execute([$fileId, $reviewerId]);
    }

    public function clearAssignments($fileId){
        $stmt = $this->db->prepare("DELETE FROM ReviewAssignments WHERE ID_file = ?");
        $stmt->execute([$fileId]);
    }

    public function setStatus($fileId, $statusName){
        $stmt = $this->db->prepare("UPDATE Files 
            SET ID_status = (SELECT ID_status FROM FileStatuses WHERE status_name = ?) 
            WHERE ID_file = ?");
        $stmt->execute([$statusName, $fileId]);
    }

    public function getFileById($id){
        $stmt = $this->db->prepare("SELECT * FROM Files WHERE ID_file = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function deleteFileAdmin($fileId){
        // nedriv smazat prirazene recenzenty
        $this->db->prepare("DELETE FROM ReviewAssignments WHERE ID_file = ?")
            ->execute([$fileId]);

        // pak teprve soubor
        $stmt = $this->db->prepare("DELETE FROM Files WHERE ID_file = ?");
        return $stmt->execute([$fileId]);
    }

    public function resetToReview($fileId){
        $stmt = $this->db->prepare("UPDATE Files 
        SET ID_status = (SELECT ID_status FROM FileStatuses WHERE status_name = 'in_review')
        WHERE ID_file = ?");
        return $stmt->execute([$fileId]);
    }

    public function getAllArticles(){
        $sql = "SELECT f.*, fs.status_name,
        (SELECT AVG((r.score1 + r.score2 + r.score3) / 3) FROM Reviews r
        JOIN ReviewAssignments ra ON ra.ID_assignment = r.ID_assignment
        WHERE ra.ID_file = f.ID_file) AS rating FROM Files f
        JOIN FileStatuses fs ON fs.ID_status = f.ID_status
        ORDER BY f.upload_date DESC";

        return $this->db->query($sql)->fetchAll();
    }
}
