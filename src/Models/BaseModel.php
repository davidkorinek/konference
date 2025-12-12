<?php
namespace App\Models;

use Davca\Konference\Core\Database;
use PDO;

// spolecny rodic pro vsechny modely - centralizace pristupu k databazi
class BaseModel {
    protected PDO $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }
}