<?php

namespace App\Manager;

use App\Models\Logger;

class SessionManager
{
    private $sessionTimeout;

    public function __construct($sessionTimeout = 3600)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->sessionTimeout = $sessionTimeout;
        $this->checkExpiration();
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        $_SESSION['session_start'] = time();
    }

    // Get a session variable
    public function get($key)
    {
        $this->checkExpiration(); // Check for expiration
        return $_SESSION[$key] ?? null; // Oturum verisini al
    }

    // Remove a session variable
    public function remove($key)
    {
        unset($_SESSION[$key]); // Oturum verisini sil
    }

    // Destroy the session
    public function destroy()
    {
        session_unset(); // Clear all session variables
        session_destroy(); // Oturumu sonlandır
    }

    // Check if the session is authenticated
    public function isAuthenticated()
    {
        $this->checkExpiration(); // Check for expiration
        return isset($_SESSION['user_id']); // Oturum doğrulaması yap
    }

    // Check if the session has expired
    private function checkExpiration()
    {
        if (isset($_SESSION['session_start']) && (time() - $_SESSION['session_start']) > $this->sessionTimeout) {
            $this->destroy(); // Destroy session if expired
        }
    }

    // Log message for debugging
    public function log($message)
    {
        $logger = new Logger();
        $logger->info($message);
    }
}
