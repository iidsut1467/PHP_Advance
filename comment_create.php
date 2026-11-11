<?php

session_start();

// POST以外のアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('許可されていないアクセスです。');
}

// ログインしているか確認
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('レスをするにはログインが必要です。');
}

// データベースに接続
require_once __DIR__ . '/config/database.php';

// どの投稿へのレスか（数値に変換して受け取る）
$postId = (int)($_POST['post_id'] ?? 0);

// レス本文を取得
$comment = trim($_POST['comment'] ?? '');

if ($comment === '') {
    exit('レス本文を入力してください。');
}

// レス先の投稿が本当に存在するか確認
$stmt = $pdo->prepare('SELECT id FROM posts WHERE id = :id');
$stmt->execute([':id' => $postId]);
if (!$stmt->fetch()) {
    http_response_code(404);
    exit('レス先の投稿が見つかりません。');
}

// ログイン中のユーザーIDを取得
$userId = (int)$_SESSION['user_id'];

// レスをデータベースに登録-SQL-
$sql = 'INSERT INTO comments (post_id, user_id, comment)
        VALUES (:post_id, :user_id, :comment)';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':post_id' => $postId,
    ':user_id' => $userId,
    ':comment' => $comment,
]);

// レスした投稿の位置に戻る
header('Location: index.php#post-' . $postId);
exit;
