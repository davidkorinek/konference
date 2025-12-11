<?php

namespace Davca\Konference\Models;

use Davca\Konference\Core\Database;

class Review {
    private $db;
    public function __construct(){
        $this->db = Database::getInstance();
    }

    // seznam clanku recenzenta
    public function getAssignedFiles($reviewerId){
        $sql = "SELECT ra.ID_assignment, f.ID_file, f.title, f.authors, f.filename, f.upload_date,
                fs.status_name, r.ID_decision AS my_decision, r.score1, r.score2, r.score3,
                (SELECT AVG((rv.score1 + rv.score2 + rv.score3) / 3) FROM Reviews rv
                JOIN ReviewAssignments ra2 ON ra2.ID_assignment = rv.ID_assignment
                WHERE ra2.ID_file = f.ID_file) AS avg_score
                FROM ReviewAssignments ra 
                JOIN Files f ON ra.ID_file = f.ID_file
                JOIN FileStatuses fs ON f.ID_status = fs.ID_status
                LEFT JOIN Reviews r ON r.ID_assignment = ra.ID_assignment
                WHERE ra.ID_reviewer = ?
                AND fs.status_name NOT IN ('approved','rejected')
                ORDER BY f.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewerId]);
        return $stmt->fetchAll();
    }

    // detail clanku pri recenzi
    public function getFileForReview($fileId, $reviewerId){
        $sql = " SELECT ra.ID_assignment, f.*, fs.status_name FROM ReviewAssignments ra
            JOIN Files f ON ra.ID_file = f.ID_file 
            JOIN FileStatuses fs ON f.ID_status = fs.ID_status
            WHERE f.ID_file = ? AND ra.ID_reviewer = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fileId, $reviewerId]);
        return $stmt->fetch();
    }

    // vrati existujci recenzi
    public function getReviewByAssignment($assignmentId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM Reviews WHERE ID_assignment = ?
        ");
        $stmt->execute([$assignmentId]);
        return $stmt->fetch();
    }

    // ulozeni recenze
    public function saveReview($assignmentId, $decision, $comment, $score1, $score2, $score3){
        // desetina mista (pulhvezdy)
        $score1 = $score1 !== null ? (float)$score1 : null;
        $score2 = $score2 !== null ? (float)$score2 : null;
        $score3 = $score3 !== null ? (float)$score3 : null;

        // najit decision id
        $dStmt = $this->db->prepare("SELECT ID_decision FROM ReviewDecisions WHERE decision_name = ?");
        $dStmt->execute([$decision]);
        $decisionId = $dStmt->fetchColumn();

        // existuje recenze
        $existing = $this->getReviewByAssignment($assignmentId);

        if ($existing) {
            $sql = "UPDATE Reviews SET ID_decision = ?, comment = ?, score1 = ?, score2 = ?,
                   score3 = ? WHERE ID_assignment = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$decisionId, $comment, $score1, $score2, $score3, $assignmentId]);

        } else {
            $sql = "INSERT INTO Reviews (ID_assignment, ID_decision, comment, score1, score2,
                     score3) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$assignmentId, $decisionId, $comment, $score1, $score2, $score3]);
        }
    }

    // recenze pro admina
    public function getReviewsForFile($fileId)
    {
        $sql = "SELECT u.username, d.decision_name, r.score1, r.score2, r.score3, r.comment
        FROM ReviewAssignments ra
        LEFT JOIN Reviews r ON r.ID_assignment = ra.ID_assignment
        LEFT JOIN ReviewDecisions d ON d.ID_decision = r.ID_decision
        JOIN Users u ON u.ID_user = ra.ID_reviewer WHERE ra.ID_file = ? ORDER BY u.username";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fileId]);
        return $stmt->fetchAll();
    }
}
