<?php

session_start();

require_once __DIR__ . '/config/database.php';

// POST以外のアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('許可されていないアクセスです。');
}

// ログインしているか確認
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('記事を投稿するにはログインが必要です。');
}

// データベースに接続
require_once __DIR__ . '/config/database.php';

// 記事本文を取得
$article = trim($_POST['article'] ?? '');

if ($article === '') {
    exit('記事本文を入力してください。');
}

// ログイン中のユーザーIDを取得
$userId = (int)$_SESSION['user_id'];

// 記事をデータベースに登録
$sql = 'INSERT INTO posts (article, user_id)
        VALUES (:article, :user_id)';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':article' => $article,
    ':user_id' => $userId,
]);

header('Location: index.php');
exit;

?>
