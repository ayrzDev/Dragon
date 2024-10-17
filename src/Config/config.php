<?php

// Kök dizin yolunu tanımla
define('ROOT_DIR', dirname(__DIR__, 2)); // public dizininin iki üstü

// Public dizin yolunu tanımla
define('PUBLIC_DIR', dirname(__DIR__));

// Autoload dosyasının yolu
define('AUTOLOAD_PATH', ROOT_DIR . '/vendor/autoload.php');
