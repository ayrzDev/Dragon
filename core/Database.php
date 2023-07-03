<?php

require_once 'vendor/flexsql.php';
require_once 'config/database.php';

class Database
{
    private $db;

    public function __construct()
    {
        $this->db = new flexsql(db_host, db_name, db_user, db_password);
    }

}