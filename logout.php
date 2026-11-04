<?php

//セッションを開始する
session_start();

// ログアウトするためにデータベースと接続を切り、かつセッションも切る


// POST以外のアクセスを拒否→URLでのログアウトを防ぐ
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('許可されていないアクセスです。');
}

// セッションの中身を削除→セッションを切ることに繋がりログインが解除される
$_SESSION = [];

// セッションを終了→ログアウト処理と同義
session_destroy();

// 掲示板のトップページへ戻る
header('Location: index.php');
exit;

?>
