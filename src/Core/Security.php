<?php
// core/Security.php
namespace App\Core;
class Security
{
    public static function generateCsrfToken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }


    public static function storeCsrfToken($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['csrf_token'] = $token;
    }

    public static function validateCsrfToken($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($token) && isset($_SESSION['token'])) {
            $postedToken = $token;
            $sessionToken = $_SESSION['token'];

            return hash_equals($sessionToken, $postedToken);
        }

        return false;
    }
}
