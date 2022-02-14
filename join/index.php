<?php
session_start();
require('../library.php');

if (isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])) {
    $form = $_SESSION['form'];
} else {
    $form = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];
}
$error =[];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    if ($form['name'] === ''){
        $error['name'] = 'blank';
    }
    $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($form['email'] === ''){
        $error['email'] = 'blank';
    } else {
        $db = dbconnect();
        $stmt = $db->prepare('select count(*) from members where email=?');
        if(!$stmt) {
            die($db->error);
        }
        $stmt->bind_param('s', $form['email']);        
        $success = $stmt->execute();
        if(!$success) {
            die($db->error);
        }

        $stmt->bind_result($cnt);
        $stmt->fetch();

        if ($cnt > 0) {
            $error['email'] = 'duplicate';
        }
    }
    $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if ($form['password'] === ''){
        $error['password'] = 'blank';
    } else if (strlen($form['password']) < 4) {
        $error['password'] = 'length';
    }
    
    $image = $_FILES['image'];
    if ($image['name'] !== '' && $image['error'] === 0) {
        $type = mime_content_type($image['tmp_name']);
        if ($type !== 'image/png' && $type !== 'image/jpeg'){
            $error['image'] = 'type';
        }
    }
    
    if (empty($error)) {
        $_SESSION['form'] = $form;
        
        if ($image['name'] !== '') {
            $filename = date('YmdHis') . '_' . $image['name'];
            if (!move_uploaded_file($image['tmp_name'], '../member_picture/' . $filename)) {
                die('ファイルのアップロードに失敗しました');
            }
            $_SESSION['form']['image'] = $filename;
        } else {
            $_SESSION['form']['image'] = '';
        }
        
        header('Location: check.php');
        exit();
    }
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
                <h1>アカウント作成</h1>
            </div>
        </nav>
    </header>
    
    <!-- 登録フォーム -->
    <div class="container">
        <div class="row">
            <p class="text-center mt-3">初めての方は以下に必要事項を記入してください。</p>
        </div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-6 offset-3">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">ユーザー名</label>
                        <input type="text" name="name" class="form-control" value="<?php echo html($form['name']); ?>" id="exampleInputName1">
                        <?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
                            <p style="color: red;">* ユーザー名を入力してください</p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">メールアドレス</label>
                        <input type="email" name="email" class="form-control" value="<?php echo html($form['email']); ?>" id="exampleInputEmail1">
                        <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
                            <p style="color: red;">* メールアドレスを入力してください</p>
                        <?php endif; ?>
                        <?php if (isset($error['email']) && $error['email'] === 'duplicate'): ?>
                            <p style="color: red;">* 既に登録されたメールアドレスです。</p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">パスワード</label>
                        <input type="password" name="password" class="form-control" placeholder="4文字以上" value="<?php echo html($form['password']); ?>" id="exampleInputPassword1" aria-describedby="passwordHelp">
                        <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
                            <p style="color: red;">* パスワードを入力してください</p>
                        <?php endif;?>
                        <?php if (isset($error['password']) && $error['password'] === 'length'): ?>
                            <p style="color: red;">* 4文字以上で入力してくだい</p>
                        <?php endif;?>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">プロフィール画像（任意）</label>
                        <input type="file" name="image" class="form-control" id="exampleInputUserImage1">
                        <?php if (isset($error['image']) && $error['image'] === 'type'): ?>
                            <p style="color: red;">* 画像は「.png」または「.jpg」の画像を指定してください</p>
                        <?php endif;?>
                        <p style="color: red;">* 恐れ入りますが、画像を改めて指定してください</p>
                    </div>
                    <div class="text-center my-5">
                        <a type="submit" class="mx-5 btn btn-success" href="../login.php">戻る</a>
                        <button type="submit" class="mx-5 btn btn-primary">入力内容を確認する</button>
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