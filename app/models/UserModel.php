<?php

require_once("config/database.php");
require_once("core/Database.php");

class UserModel
{
    private $db;

    public function __construct()
    {
        // Database bağlantısını oluştur
        $this->db = new Database();
    }

    public function getUserById($userId)
    {
        // Veritabanından kullanıcıyı id'ye göre getir
        $query = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
        $result = $this->db->query($query, $params)->fetch();

        return $result;
    }

    public function createUser($userData)
    {
        // Yeni bir kullanıcı oluştur
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $params = [$userData['name'], $userData['email'], $userData['password']];
        $this->db->query($query, $params);

        // Oluşturulan kullanıcının ID'sini döndür
        return $this->db->lastInsertId();
    }

    public function updateUser($userId, $userData)
    {
        // Kullanıcıyı güncelle
        $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $params = [$userData['name'], $userData['email'], $userData['password'], $userId];
        $this->db->query($query, $params);

        // Güncellenen kullanıcının etkilenen satır sayısını döndür
        return $this->db->rowCount();
    }

    public function deleteUser($userId)
    {
        // Kullanıcıyı sil
        $query = "DELETE FROM users WHERE id = ?";
        $params = [$userId];
        $this->db->query($query, $params);

        // Silinen kullanıcının etkilenen satır sayısını döndür
        return $this->db->rowCount();
    }
}
