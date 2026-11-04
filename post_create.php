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
