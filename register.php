<?php
// セッションを開始
session_start();

// POST以外のアクセスを拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('許可されていないアクセスです。');
}

// データベースに接続
require_once __DIR__ . '/config/database.php';

// フォームから値を受け取る
$firstName = trim($_POST['first_name'] ?? '');
$lastName  = trim($_POST['last_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';   // パスワードはtrimしない

// 入力チェック
if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
    exit('すべての項目を入力してください。');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('メールアドレスの形式が正しくありません。');
}

// SQLを準備して実行
$sql = 'INSERT INTO users
        (first_name, last_name, email, password)
        VALUES (:first_name, :last_name, :email, :password)';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':first_name' => $firstName,
    ':last_name'  => $lastName,
    ':email'      => $email,
    ':password'   => hash('sha256', $password),   // パスワードはsha256でハッシュ化して保存
]);

// 登録が終わったらトップページへ戻る
header('Location: index.php');
exit;

?>