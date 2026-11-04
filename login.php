<?php
//セッションを開始する
session_start();

//ログイン時にidを比較する
session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];

//データベースに接続
require_once __DIR__ . '/config/database.php';

?>
