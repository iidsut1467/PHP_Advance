<DOCTYPE html!>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <link rel="stylesheet" href="PHP_Advance-main/PHP_CSS.css">
    <title>掲示板アプリ</title>
</head>

    
<body>
    
    <? php
    try {
        $pdo = new PDO($BBS, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCE_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        exit('データベースに接続できませんでした。');
    }
    session_start();

    // パスワードの照合に成功した後に実行
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    session_start();

    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        exit('記事を投稿するにはログインが必要です。');
    }
    // 未ログインならログインしてねと促す
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('許可されていないアクセスです。');
    }
    // POST以外の形式だとアクセスを拒否する


    require_once __DIR__ . '/config/database.php';
    
    $article = trim($_POST['article'] ?? '');

    if ($article === '') {
        exit('記事本文を入力してください。');
    }

    $userId = (int)$_SESSION['user_id'];
    // user_idはSESSIONで受け取る
    $sql = 'INSERT INTO posts (article, user_id)
        VALUES (:article, :user_id)';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':article' => $article,
        ':user_id' => $userId,
    ]);
    // SQLはPrepared Statementで受け取るようにする
    header('Location: index.php');
    ?>
    
    <div class = "top">
    <h1>掲示板アプリ</h1>
    <p>掲示板だヨ！！</p>
    </div>
    <!-- idをつかって表示を切り替えたいがこれをCSSでするのかPHPでするのか -->
    <!-- ログイン、ユーザー追加、ユーザー編集、記事投稿画面、コメント画面の5つのリンクを表示してそのページに飛ばすかあるいは他を隠したまま飛ばす -->
    <!-- 
    -->
    <div class="insert" id = "logIn">
     <a>ログイン</a>
     <input type = "email" class = "email"><input>
     <input class = "password"></input>
     <!-- inputで入力させるようにする、PHPで情報をサーバーに送る -->
    </div>

    <div class = "insert" id = "plusUser">
     <a>ユーザー追加</a>
     <imput type = "email" class = "email"></imput>
     <input class = "first_name"></input>
     <input class = "last_name"></input>
     <imput class = "password"></imput>
     <buttom type = "submit" >送信</buttom>
    </div>

    <div class = "insert" id = "editUser">
     <a>ユーザー編集</a>
     <imput type = "email" class = "email"></imput>
     <input class = "first_name"></input>
     <input class = "last_name"></input>
     <imput class = "password"></imput>
    </div>

    <div class = "insert" id = "contents">
     <a>記事投稿画面</a>
     <input class = ""></input>
     <imput class = "password"></imput>
    </div>
    
    <div class = "insert" id = "comments">
     <a>コメント画面</a>
     <input class = "article"></input>
     <imput class = "password"></imput>
    </div>

    <div class = "changeWindow" onclick= "clickcChangeWindow()">
       <ul>
            <button id = "1">ログイン</button>
            <button id = "2">ユーザー追加</button>
            <button id = "3">ユーザー編集</button>
            <button id = "4">記事投稿</button>
            <button id = "5">コメント画面</button>
       </ul>
    </div>
    <div class = "logOut"><h1>ログアウト</h1></div>


</body>
</html>
