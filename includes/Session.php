<?php
class Session
{
    public function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getAuthSession()
    {
        $this->init();
        return isset($_SESSION['email']) ? $_SESSION['email'] : null;
    }

    /**
     * Ends execution for unauthenticated visitors so page data is never sent.
     */
    public function checkSession()
    {
        $this->init();
        if (!isset($_SESSION['email'])) {
            $this->redirectNonAuth();
            exit;
        }
        return true;
    }

    /**
     * Server-side role guard: stops execution unless the logged-in user has
     * one of the given roles. Menu visibility is cosmetic; this is not.
     */
    public function requireRole($roles)
    {
        $this->checkSession();
        $allowed = is_array($roles) ? $roles : array($roles);
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed, true)) {
            http_response_code(403);
            echo "<script>alert('Non autorisé');window.location.href = '../../src/index/index.php'</script>";
            exit;
        }
        return true;
    }

    public function isAdmin()
    {
        $this->init();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    function redirectNonAuth()
    {
        return "<script>alert('Non autorisé');window.location.href = '../../src/index/index.php'</script>";
    }

    public function logout()
    {
        $this->init();
        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        echo $this->redirectNonAuth();
        exit;
    }

    public function redirect($roleId)
    {
        if ($roleId == 1) {
            header('Location:admin.php');
        } else {
            header('Location:home.php');
        }
        exit;
    }
}
