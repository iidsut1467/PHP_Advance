<?php
// セッションを開始する
session_start();

// POST以外のアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('許可されていないアクセスです。');
}

// データベースに接続
require_once __DIR__ . '/config/database.php';

// フォームから値を受け取る
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// メールアドレスでユーザーを検索
$stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = :email');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// パスワードを照合（入力値をsha256でハッシュ化して、保存済みの値と比べる）
if ($user && hash_equals($user['password'], hash('sha256', $password))) {
    // ログイン成功：セッションIDを作り直してからユーザーIDを保存
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    header('Location: index.php');
    exit;
}

// ユーザーがいない場合もパスワード違いの場合も同じメッセージにする
exit('メールアドレスまたはパスワードが違います。');
