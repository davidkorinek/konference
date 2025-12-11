<?php
namespace Davca\Konference\Controllers;

use Davca\Konference\Core\Database;
use PDOException;

class AuthController {
    public function showRegister(){
        require __DIR__ . '/../View/register.php';
    }

    // registrace noveho uzivatele
    public function register(){
        session_start();

        // povinna pole
        if (empty($_POST['username']) || empty($_POST['email']) ||
            empty($_POST['password1']) || empty($_POST['password2'])) {

            $_SESSION['register_error'] = "Vyplňte všechna povinná pole.";
            header("Location: /konference/public/register");
            exit;
        }

        $username  = trim($_POST['username']);
        $email     = trim($_POST['email']);
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        // kontrola hesla
        if ($password1 !== $password2) {
            $_SESSION['register_error'] = "Hesla se neshodují.";
            header("Location: /konference/public/register");
            exit;
        }

        // kontrola delky hesla
        if (strlen($password1) < 8) {
            $_SESSION['register_error'] = "Heslo musí mít alespoň 8 znaků.";
            header("Location: /konference/public/register");
            exit;
        }

        $passwordHash = password_hash($password1, PASSWORD_BCRYPT);

        try {
            $db = Database::getInstance();

            // 1. vytvorit uzivatele
            $stmt = $db->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $passwordHash]);

            $userId = $db->lastInsertId();

            // 2. automaticka role author
            $stmt = $db->prepare("
                INSERT INTO UserRoles (ID_user, ID_role)
                VALUES (?, (SELECT ID_roles FROM Roles WHERE role_name = 'author'))
            ");
            $stmt->execute([$userId]);

            // 3. automaticky prihlasit
            $_SESSION['user'] = [
                'id'       => $userId,
                'username' => $username,
                'role'     => 'author',
                'blocked'  => 0
            ];

            $_SESSION['register_success'] = "Registrace proběhla úspěšně. Jste přihlášen.";

            header("Location: /konference/public/author/files");
            exit;

        // chyby
        } catch (PDOException $e) {

            if ($e->getCode() === "23000") {
                $_SESSION['register_error'] = "Uživatel s tímto jménem nebo e-mailem již existuje.";
            } else {
                $_SESSION['register_error'] = "Chyba registrace.";
            }

            header("Location: /konference/public/register");
            exit;
        }
    }

    // prihlaseni uzivatele
    public function login(){
        session_start();
        $db = Database::getInstance();

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // nacteni "blocked"
        $stmt = $db->prepare("
            SELECT u.ID_user, u.username, u.password, u.blocked, r.role_name
            FROM Users u
            JOIN UserRoles ur ON ur.ID_user = u.ID_user
            JOIN Roles r ON r.ID_roles = ur.ID_role
            WHERE u.username = ?
            LIMIT 1
        ");
        $stmt->execute([$username]);

        $user = $stmt->fetch();

        // chybne heslo
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['login_error'] = "Neplatné přihlašovací údaje.";
            header("Location: /konference/public/");
            exit;
        }

        // blokovany ucet
        if ($user['blocked'] == 1) {
            $_SESSION['login_error'] = "Účet je zablokován administrátorem.";
            header("Location: /konference/public/");
            exit;
        }

        // uspesne prihlaseni
        $_SESSION['user'] = [
            'id'       => $user['ID_user'],
            'username' => $user['username'],
            'role'     => $user['role_name'],
            'blocked'  => $user['blocked']
        ];

        // presmerovani podle role
        switch ($user['role_name']) {
            case 'author':
                header("Location: /konference/public/author/files");
                break;
            case 'reviewer':
                header("Location: /konference/public/reviewer/tasks");
                break;
            case 'admin':
            case 'superadmin':
                header("Location: /konference/public/admin");
                break;
            default:
                header("Location: /konference/public/");
        }

        exit;
    }

    // odhlasovani uzivaele
    public function logout(){
        session_start();
        session_destroy();
        header("Location: /konference/public/");
        exit;
    }

    public function clearLoginError(){
        session_start();
        unset($_SESSION['login_error']);
        http_response_code(204);
    }
}
