<?php
namespace Davca\Konference\Core;

use PDO;
use PDOException;

class Database{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO{
        if(self::$instance === null){
            $config = require __DIR__ . '/../../config/config.php';

            $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset={$config['charset']}";

            try {
                self::$instance = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // OCHRANA PROTI SQL INJECTION
                ]);
            } catch (PDOException $e) {
                die('Database connection error');
            }
        }
        return self::$instance;
    }
}