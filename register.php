<?php
// セッションを開始
session_start();

$sql = 'INSERT INTO users
        (first_name, last_name, email, password)
        VALUES (:first_name, :last_name, :email, :password)';

// データベースに接続
require_once __DIR__ . '/config/database.php';
?>
