<?php

namespace App\Models;

use App\Api\Sql;
use App\Controllers\UserController;
use App\Manager\SessionManager;
use Exception;

class UserModel
{
    private $sessionManager, $db;
    public function __construct()
    {
        $db = new Sql();
        $this->db = $db->getDb();
        $this->sessionManager = new SessionManager(3600);
    }
    public function login($response)
    {
        $usernameOrEmail = $response['usernameOrEmail'];
        $password = $response['password'];

        $query = "SELECT * FROM accounts WHERE (username = ? OR email = ?) AND password_hash = ?";
        $cryt = md5($password);
        $params = [$usernameOrEmail, $usernameOrEmail, $cryt];

    
        $result = $this->db->query($query, $params)->fetch();

        if ($result) {
            // Giriş başarılı
            $response = [
                'success' => true,
                'message' => 'Giriş başarılı!',
                'color' => 'green'
            ];

            $_SESSION["getLogged"] = true;
            $_SESSION["username"] = $result["username"];
            $_SESSION["full_name"] = $result["full_name"];
            $_SESSION["sur_name"] = $result["sur_name"];
            $_SESSION["email"] = $result["email"];

            if ($result["permission"] === 1) {
                $_SESSION["admin"] = true;
            } else {
                $_SESSION["admin"] = false;
            }
        } else {
            // Giriş başarısız
            $response = [
                'success' => false,
                'message' => 'Geçersiz kullanıcı adı veya şifre!',
                'color' => 'red'
            ];
        }

        return $response;
    }


    public function usersCount()
    {
        $count = $this->sessionManager->get('users_count');
        if ($count === null) {
            $query = "SELECT COUNT(username) as count FROM accounts";
            $result = $this->db->query($query)->fetch();

            $count = $result ? $result['count'] : 0;
            $this->sessionManager->set('users_count', $count);

            // Log data source
            $this->sessionManager->log('Fetched usersCount from database');
        } else {
            // Log data source
            $this->sessionManager->log('Fetched usersCount from session');
        }
        return $count;
    }


    public function ordersCount()
    {
        $count = $this->sessionManager->get('orders_Count');
        if ($count === null) {
            $query = "SELECT COUNT(id) as count FROM orders";
            $result = $this->db->query($query)->fetch();

            $count = $result ? $result['count'] : 0;
            $this->sessionManager->set('orders_Count', $count);

            // Log data source
            $this->sessionManager->log('Fetched usersCount from database');
        } else {
            // Log data source
            $this->sessionManager->log('Fetched usersCount from session');
        }
        return $count;
    }

    public function totalKazanc()
    {
        $count = $this->sessionManager->get('totalKazanc');
        if ($count === null) {
            $query = "SELECT SUM(total_price) AS total_earnings
            FROM (
                SELECT oi.order_id, SUM(oi.quantity * p.price) AS total_price
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                GROUP BY oi.order_id
            ) AS order_totals;
            ";
            $result = $this->db->query($query)->fetch();

            $count = $result ? $result['total_earnings'] : 0;
            $this->sessionManager->set('totalKazanc', $count);

            // Log data source
            $this->sessionManager->log('Fetched usersCount from database');
        } else {
            // Log data source
            $this->sessionManager->log('Fetched usersCount from session');
        }
        return $count;
    }

    public function customerCount()
    {
        $count = $this->sessionManager->get('customer_count');
        if ($count === null) {
            $query = "SELECT COUNT(company) as count FROM customer";
            $result = $this->db->query($query)->fetch();

            $count = $result ? $result['count'] : 0;
            $this->sessionManager->set('customer_count', $count);

            // Log data source
            $this->sessionManager->log('Fetched customerCount from database');
        } else {
            // Log data source
            $this->sessionManager->log('Fetched customerCount from session');
        }
        return $count;
    }



    public function changeProfile($getData)
    {
        $userId = UserController::getUserId();
        $data = array_filter($getData, function ($value, $key) {
            return !empty($value);
        }, ARRAY_FILTER_USE_BOTH);

        $whereClause = 'customer_id = ?';
        $whereParams = [$userId];

        $result = $this->db->update('users', $data, $whereClause, $whereParams);

        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Giriş başarılı!',
                'color' => 'green'
            ];

            $_SESSION["getLogged"] = true;
            $_SESSION["username"] = $result["user_name"];
            $_SESSION["full_name"] = $result["full_name"];
            $_SESSION["sur_name"] = $result["sur_name"];
            $_SESSION["email"] = $result["email"];
        } else {
            $response = [
                'success' => false,
                'message' => 'Geçersiz kullanıcı adı veya şifre!',
                'color' => 'red'
            ];
        }

        return $response;
    }

    public function getAllCustomers()
    {
        $result = $this->db->select("customer", "*", "")->fetchAll();
        if ($result) {
            $response = [
                'success' => true,
                'data' => $result
            ];
        } else {
            // Sonuç başarısızsa
            $response = [
                'success' => false,
                'message' => 'bulunamadı.'
            ];
        }

        return $response; // Sonucu döndür
    }



    public function getCustomerDetails($id)
    {
        $query = "SELECT u.company, u.full_name, u.sur_name,u.phone, a.district, a.country, a.province, a.address_detail
        FROM customer AS u
        LEFT JOIN addresses AS a ON u.address = a.address_id
        WHERE u.customer_id = ?";

        $params = [$id];

        // Veritabanı bağlantısını sağla ve sorguyu çalıştır
        $result = $this->db->query($query, $params)->fetch();

        // Sonuç başarılıysa
        if ($result) {
            $response = [
                'success' => true,
                'data' => $result
            ];
        } else {
            // Sonuç başarısızsa
            $response = [
                'success' => false,
                'message' => 'Kullanıcı bulunamadı.'
            ];
        }

        return $response; // Sonucu döndür
    }

    public function newUser($responseData)
    {
        $this->db->beginTransaction();

        try {
            $data = [
                "username" => $responseData["username"],
                "email" => $responseData["email"],
                "password_hash" => md5($responseData["password"]),
                "full_name" => $responseData["full_name"],
                "sur_name" => $responseData["sur_name"],
                "permission" => 1
            ];

            $this->db->insert("accounts", $data);
            $this->db->commit();

            $response = [
                'success' => true,
                'message' => 'Yetkili başarıyla eklendi.',
                'color' => 'green'
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error inserting customer: ' . $e->getMessage());
            $response = [
                'success' => false,
                'message' => 'Yetkili eklenirken hata oluştu: ' . $e->getMessage(),
                'color' => 'red'
            ];
        }
        return $response;
    }
}
