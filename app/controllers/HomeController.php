<?php

class HomeController
{
    public function index()
    {
        require_once 'app/views/home/index.php';
    }

    public function about()
    {
        require_once 'app/views/home/about-us.php';
    }
}
