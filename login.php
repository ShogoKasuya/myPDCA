<?php
session_start();
require('library.php');

$error = [];
$email = [];
$password = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if ($email === '' || $password === ''){
        $error['login'] = 'blank';
    } else {
        // ログインチェック
        $db = dbconnect();
        $stmt = $db->prepare('select id, name, password from members where email=? limit 1');
        if (!$stmt) {
            die ($db->error);
        }

        $stmt->bind_param('s', $email);
        $success = $stmt->execute();
        if (!$success) {
            die($db->error);
        }

        $stmt->bind_result($id, $name, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)){
            // ログイン成功
            session_regenerate_id();
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            header('Location: index.php');
            exit();
        } else {
            $error['login'] = 'failed';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <!-- ヘッダー -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PDCA</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </header>

    <!-- ログインフォーム -->
    <div class="container">
        <div class="row">
            <h2 class="text-center mt-3">ようこそ！</h2>
            <p class="text-center mt-3">既にユーザーの方はこちらからログインしてください。</p>
        </div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-6 offset-3">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">メールアドレス</label>
                        <input type="email" name="email" class="form-control" value="<?php echo html($email); ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <?php if (isset($error['login']) && $error['login'] === 'blank'): ?>
                            <p style="color: red;">* メールアドレスとパスワードを入力してください。</p>
                        <?php endif; ?>
                        <?php if (isset($error['login']) && $error['login'] === 'failed'): ?>
                            <p style="color: red;">* ログインに失敗しました。正しく入力してください。</p>
                        <?php  endif;?>

                        </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">パスワード</label>
                        <input type="password" name="password" class="form-control" value="<?php echo html($password); ?>" id="exampleInputPassword1">
                        <div id="emailHelp" class="form-text">4文字以上の半角英数字</div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">以降アカウント情報を保持する</label>
                    </div>
                    <div class="text-center my-5">
                        <button type="submit" class="btn btn-primary">ログイン</button>
                    </div>
                </form>
                <div class="text-center my-5">
                        <a type="button" class="btn btn-success" href="join/">新規登録</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="navbar navbar-expand-lg navbar-light bg-light" style="height: 80px;"></footer>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>