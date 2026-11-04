<?php

session_start();

require_once __DIR__ . '/config/database.php';

$sql = 'INSERT INTO users
        (first_name, last_name, email, password)
        VALUES (:first_name, :last_name, :email, :password)';

?>
