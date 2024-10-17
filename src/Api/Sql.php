<?php

namespace App\Api;

use App\Api\Sql\FlexSQL;
use App\Config\App;

class Sql {
    public static $db;

    public function __construct(){
        self::$db = new FlexSQL(App::DB_HOST, App::DB_NAME, App::DB_USER, App::DB_PASSWORD);
    }

    public static function getDb(){
        return self::$db;
    }
}
