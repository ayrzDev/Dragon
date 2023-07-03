<?php

class UserController
{
    public function index()
    {
        // Kullanıcı listeleme işlemleri
    }

    public function create()
    {
        // Yeni kullanıcı oluşturma işlemleri
    }

    public function edit($id)
    {
        // Kullanıcı düzenleme işlemleri
    }


    public function profile($username)
    {
        echo "Kullanıcı profilini göster: $username";
    }
}
