<?php

$host = 'localhost';
$dbname = 'BBS';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password
    );

    // PDOのエラーを例外として扱う
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    exit('データベース接続に失敗しました。');
}

// データベースに接続
require_once __DIR__ . '/config/database.php';

?>
