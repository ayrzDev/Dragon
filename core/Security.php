<?php
// core/Security.php

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

    public static function validateCsrfToken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token'])) {
            $postedToken = $_POST['csrf_token'];
            $sessionToken = $_SESSION['csrf_token'];

            return $postedToken === $sessionToken;
        }

        return false;
    }
}
