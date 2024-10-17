<?php

namespace App\Controllers;

use App\Controllers\SessionManager;
use App\Models\UserModel;
use Core\Helpers;

class UserController
{
    public function profile($username)
    {
        $decodedUsername = urldecode($username);
        echo "Kullanıcı profilini göster: " . htmlspecialchars($decodedUsername);
    }

    public $user_name;
    public $user_address;

    public function logout()
    {
        session_destroy();
    }


    public function getLogged()
    {
        if (isset($_SESSION["getLogged"])) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserName()
    {
        return $this->user_name = $_SESSION["username"] ?? null;
    }

    public function getEmail()
    {
        return $_SESSION["email"] ?? null;
    }

    public function getUserFullName()
    {
        return $_SESSION["full_name"] ?? null;
    }

    public function getUserSurName()
    {
        return $_SESSION["sur_name"] ?? null;
    }

    public function getUserAddress()
    {
        return $_SESSION["userAddress"]["address"] ?? null;
    }

    public function getCountry()
    {
        return $_SESSION["userAddress"]["country"] ?? null;
    }

    public function getProvince()
    {
        return $_SESSION["userAddress"]["province"] ?? null;
    }


    public function getDistrict()
    {
        return $_SESSION["userAddress"]["district"] ?? null;
    }

    public function getUserCreatedDate()
    {
        return $_SESSION["created_date"] ?? null;
    }


    public static function getUserId()
    {
        return $_SESSION["userId"] ?? null;
    }

    public static function isAdmin()
    {
        return $_SESSION["admin"] ?? false;
    }

    public function usersCount()
    {
        $userModel = new UserModel();
        $session =  new SessionManager();
        $result = null;
        if ($session->get("userCount")) {
            $result["count"] =  $session->get("userCount");
            $result["type"] = "session";
        } else {
            $result["count"] =  $userModel->usersCount();
            $result["type"] = "db";
        }
        return $result;
    }

    public function customerCount()
    {
        $userModel = new UserModel();
        $session =  new SessionManager();
        $result = null;
        if ($session->get("customerCount")) {
            $result["count"] =  $session->get("customerCount");
            $result["type"] = "session";
        } else {
            $count = $userModel->customerCount();
            $session->set("customerCount", $count);
            $result["count"] = $count;
            $result["type"] = "db";
        }
        return $result;
    }
}
