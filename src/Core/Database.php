<?php
namespace Davca\Konference\Core;

use PDO;
use PDOException;

/* centralni vrstva pro praci s databazi
 *
 * singleton patter - pouze jedno pripojeni k databazi (znovu pouzitelne ve vsech modelech)
 *
 * vyhody:
 *  - nezatezuje server opakovanymi pripojenimi
 *  - snadna centralni konfigurace
 *  - vsechny modely sdileji stejny PDO objekt
 */

class Database{
    private static ?PDO $instance = null;

    /* vraci existujici PDO nebo vytvori nove */
    public static function getInstance(): PDO{
        // pokud pripojeni neexistuje vytvori se
        if(self::$instance === null){
            // nacteni konfiguracnich dat
            $config = require __DIR__ . '/../../config/config.php';

            // vytvoreni dns retezce
            $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset={$config['charset']}";

            try {
                self::$instance = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // OCHRANA PROTI SQL INJECTION
                ]);
            } catch (PDOException $e) {
                // chyba pripojeni k databazi
                die('Database connection error');
            }
        }
        return self::$instance;
    }
}