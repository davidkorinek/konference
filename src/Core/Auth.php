<?php
namespace Davca\Konference\Core;

class Auth {
    // vraci udaje o aktualne prihlasenem uzivateli
    public static function user(){
        session_start();
        return $_SESSION['user'] ?? null;
    }

    // overeni zda je uzivatel prihlaseny
    public static function check(){
        session_start();
        return isset($_SESSION['user']);
    }

    // overeni pozadovane role prihlaseneho uzivatele
    public static function requireRole($role){
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            die("Access denied");
        }
    }
}
