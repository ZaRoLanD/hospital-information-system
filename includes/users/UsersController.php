<?php
include "../../includes/DBconn.php";
require_once "../../includes/Session.php";

if (isset($_POST['type'])) :
    $user = new User();
    switch ($_POST['type']) {

        case "login":
            echo $user->login($_POST['email'], $_POST['password']);
            break;
        case "addUser":
            echo $user->addUser($_POST['in_Email'], $_POST['in_Password'], $_POST['in_Role']);
            break;
    }
endif;


class User
{
    function login($username, $password)
    {
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        $query = $dbConn->prepare("SELECT email, password, role
                        FROM users
                        WHERE email = :email
                        LIMIT 1");
        $query->execute(array(':email' => $username));
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // Transparent rehash when the default algorithm or cost changes
            if (password_needs_rehash($result['password'], PASSWORD_DEFAULT)) {
                $rehash = $dbConn->prepare("UPDATE users SET password = :password WHERE email = :email");
                $rehash->execute(array(
                    ':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':email' => $result['email']
                ));
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            session_regenerate_id(true); // prevents session fixation

            $_SESSION['email'] = $result['email'];
            $_SESSION['role'] = $result['role'];
            return "success";
        }

        return "E-mail ou mot de passe incorrect";
    }

    public function addUser($email, $password, $role)
    {
        // Server-side guard: hiding the menu item is not access control
        $session = new Session();
        $session->requireRole('admin');

        if (!in_array($role, array('admin', 'user'), true)) {
            return "Rôle invalide";
        }
        if ($password === '' || strlen($password) < 4) {
            return "Mot de passe trop court (4 caractères minimum)";
        }

        $db = new DBconnection();
        $dbConn = $db->getConnection();
        $sql = "INSERT INTO users (email,password,role) VALUES (:email,:password,:role)";
        $query = $dbConn->prepare($sql);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query->bindparam(':email', $email);
        $query->bindparam(':password', $hash);
        $query->bindparam(':role', $role);
        try {
            $query->execute();
            return "success";
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }


    function viewAllUsers()
    {
        $db = new DBconnection();
        $dbConn = $db->getConnection();
        // Never select the password column for display purposes
        return $dbConn->query("SELECT user_id, email, role FROM users ORDER BY user_id DESC");
    }
}
