<?php
session_start();
require_once __DIR__ . '/config/database.php';

// ログインしているか確認
$isLoggedIn = isset($_SESSION['user_id']);

// 投稿一覧を新しい順に取得（投稿者名も一緒に取得する）
$sql = 'SELECT posts.id, posts.article, posts.created_at,
               users.first_name, users.last_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC';
$posts = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// 表示用のエスケープ関数（XSS対策）
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="PHP_CSS.css">
    <title>掲示板アプリ</title>
</head>
<body>
    <div class="top">
        <h1>掲示板アプリ</h1>
        <p>掲示板だヨ！！</p>
    </div>

    <!-- 画面切り替えボタン：ログイン状態によって出すボタンをPHPで変える -->
    <div class="changeWindow">
        <?php if (!$isLoggedIn): ?>
            <button type="button" data-target="logIn">ログイン</button>
            <button type="button" data-target="plusUser">ユーザー追加</button>
        <?php else: ?>
            <button type="button" data-target="contents">記事投稿</button>
        <?php endif; ?>
    </div>

    <?php if (!$isLoggedIn): ?>
        <!-- ログイン -->
        <div class="insert" id="logIn">
            <h2>ログイン</h2>
            <form action="login.php" method="post">
                <input type="email" name="email" class="email" placeholder="メールアドレス" required>
                <input type="password" name="password" class="password" placeholder="パスワード" required>
                <button type="submit">ログイン</button>
            </form>
        </div>

        <!-- ユーザー追加 -->
        <div class="insert" id="plusUser">
            <h2>ユーザー追加</h2>
            <form action="register.php" method="post">
                <input type="email" name="email" class="email" placeholder="メールアドレス" required>
                <input type="text" name="first_name" class="first-name" placeholder="名" required>
                <input type="text" name="last_name" class="last-name" placeholder="姓" required>
                <input type="password" name="password" class="password" placeholder="パスワード" required>
                <button type="submit">登録</button>
            </form>
        </div>
    <?php else: ?>
        <!-- 記事投稿 -->
        <div class="insert" id="contents">
            <h2>記事投稿</h2>
            <form action="post_create.php" method="post">
                <textarea name="article" placeholder="ここに記事を書いてね" required></textarea>
                <button type="submit">投稿する</button>
            </form>
        </div>

        <!-- ログアウト -->
        <div class="logOut">
            <form action="logout.php" method="post">
                <button type="submit">ログアウト</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- 投稿一覧 -->
    <div class="posts">
        <?php if (count($posts) === 0): ?>
            <p>まだ投稿はありません。</p>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <p><?= nl2br(h($post['article'])) ?></p>
                <small>
                    <?= h($post['last_name'] . ' ' . $post['first_name']) ?>
                    ／ <?= h($post['created_at']) ?>
                </small>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    // ボタンを押したら、対応するフォームだけを表示する（is-activeクラスの付け外し）
    document.querySelectorAll('.changeWindow button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.insert, .changeWindow button').forEach(function (el) {
                el.classList.remove('is-active');
            });
            btn.classList.add('is-active');
            document.getElementById(btn.dataset.target).classList.add('is-active');
        });
    });
    </script>
</body>
</html>
