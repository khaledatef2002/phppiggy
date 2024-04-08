<?php

include __DIR__ . '/src/Framework/DB.php';

use Framework\DB;

$db = new DB('mysql', [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'phpiggy'
], 'root', '');

$sqlFile = file_get_contents('database.sql');

$db->query($sqlFile);
