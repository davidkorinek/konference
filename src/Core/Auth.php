<?php
namespace Davca\Konference\Core;

class Auth {
    public static function user(){
        session_start();
        return $_SESSION['user'] ?? null;
    }

    public static function check(){
        session_start();
        return isset($_SESSION['user']);
    }

    public static function requireRole($role){
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            die("Access denied");
        }
    }
}
