<?php
session_start();
require('../library.php');

if (isset($_SESSION['form'])){
    $form = $_SESSION['form'];
} else {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = dbconnect();
    $stmt = $db->prepare('insert into members (name, email, password, picture) VALUES (?, ?, ?, ?) ');
    if (!$stmt) {
        die($db->error);
    }
    $password = password_hash($form['password'], PASSWORD_DEFAULT);
    $stmt->bind_param('ssss', $form['name'], $form['email'], $password, $form['image']);
    $success = $stmt->execute();
    if (!$success) {
        die($db->error);
    }

    unset($_SESSION['form']);
    header('Location: thanks.php');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <!-- ヘッダー -->
    <header>
        <nav class="text-center navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid justify-content-center">
                <h1>入力情報の確認</h1>
            </div>
        </nav>
    </header>
    
    <!-- 確認フォーム -->
    <div class="container">
        <div class="row">
            <p class="text-center mt-3">ご確認の上、「登録する」ボタンをクリックしてください。</p>
        </div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-6 offset-3">
                <form action="" method="post">
                    <dl>
                        <dt>ユーザー名</dt>
                        <dd><?php echo html($form['name']); ?></dd>
                        <dt>メールアドレス</dt>
                        <dd><?php echo html($form['email']); ?></dd>
                        <dt>パスワード</dt>
                        <dd>
                            【表示されません】
                        </dd>
                        <dt>プロフィール画像</dt>
                        <dd>
                            <img src="../member_picture/<?php echo html($form['image']); ?>" width="100" height="100">
                        </dd>
                    </dl>
                    <div class="d-flex justify-content-around my-5">
                        <a type="submit" class="btn btn-success" href="index.php?action=rewrite">変更する</a>
                        <button type="submit" class="btn btn-primary">登録する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="navbar navbar-expand-lg navbar-light bg-light" style="height: 80px;"></footer>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>