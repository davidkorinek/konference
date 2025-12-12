<?php
namespace Davca\Konference\Models;

use Davca\Konference\Core\Database;
use PDO;

/*
 * odpovida za praci s uzivateli
 *
 * Struktura databaze:
 *   Users.ID_user
 *   UserRoles.ID_user → vazba 1:1 na Users
 *   UserRoles.ID_role → cizí klíč na tabulku Roles
 *
 * Role jsou oddeleny v samostatne tabulce, aby bylo mozne:
 *  - dodrzet normalizaci
 *  - snadno prirazovat a menit role
 *
 * Třída používá PDO připravené dotazy → bezpečná proti SQL injection.
 */
class User{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    // nacteni vsech uzivatelu
    public function getAllUsers(){
        $stmt = $this->db->query("SELECT u.ID_user, u.username, u.email, u.created_at,
            u.blocked, r.role_name FROM Users u
            LEFT JOIN UserRoles ur ON u.ID_user = ur.ID_user
            LEFT JOIN Roles r ON ur.ID_role = r.ID_roles
            ORDER BY u.ID_user ASC");

        return $stmt->fetchAll();
    }

    // aktualizace role a blokace
    public function updateUser($userId, $role, $blocked){
        // najit id role
        $stmt = $this->db->prepare("SELECT ID_roles FROM Roles WHERE role_name = ?");
        $stmt->execute([$role]);
        $roleId = $stmt->fetchColumn();

        // aktualizace blokace
        $stmt = $this->db->prepare("UPDATE Users SET blocked = ? WHERE ID_user = ?");
        $stmt->execute([$blocked, $userId]);

        // aktualizace role
        $stmt = $this->db->prepare("UPDATE UserRoles SET ID_role = ? WHERE ID_user = ?");
        $stmt->execute([$roleId, $userId]);
    }
}
