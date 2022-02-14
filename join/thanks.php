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
                <h1>会員登録が完了しました。</h1>
            </div>
        </nav>
    </header>
    
    <!-- 登録完了画面 -->
    <div class="container">
        <div class="row">
            <p class="text-center mt-3">下のボタンからログインをお願いします。</p>
        </div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-6 offset-3">
                    <div class="text-center my-5">
                        <button type="submit" class="btn btn-primary" onclick="location.href='../login.php'">ログインページへ移動する</button>
                    </div>
            </div>
        </div>
    </div>

    <footer class="navbar navbar-expand-lg navbar-light bg-light" style="height: 80px;"></footer>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>