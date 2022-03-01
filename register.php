<?php
session_start();
require('library.php');
$db = dbconnect();

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
}
// PDCAの投稿
if ( $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $theme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING);
    $plan = filter_input(INPUT_POST, 'plan', FILTER_SANITIZE_STRING);
    $do = filter_input(INPUT_POST, 'do', FILTER_SANITIZE_STRING);
    $checking = filter_input(INPUT_POST, 'checking', FILTER_SANITIZE_STRING);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $stmt = $db->prepare('insert into posts (title, theme, plan, do, checking, action, member_id) values(?, ?, ?, ?, ?, ?, ?)');
    if (!$stmt) {
        die($db->error);
    }

    $stmt->bind_param('ssssssi', $title, $theme, $plan, $do, $checking, $action, $id,);
    $success = $stmt->execute();
    if(!$success){
        die($db->error);
    }

    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myPDCA</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <!-- ヘッダー -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">myPDCA</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">みんなのPDCA</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <form class="nav-item">
                        <button type="button" class="btn btn-outline-danger" onclick="location.href='logout.php'">ログアウト</button>                
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- PDCA登録フォーム -->
    <h2 class="text-center my-5">枠内の例のようにPDCAを作成しよう！</h2>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1">
                <form action="" method="post">
                    <div class="col-6 m-4">
                        <label class="form-label" for="">PDCAの題名と期限</label>
                        <input class="form-control" name="title" type="text" placeholder="ダイエット　3/31まで">
                    </div>
                    <div class="m-4">
                        <label class="form-label" for="">抱えている課題</label>
                        <textarea class="form-control" name="theme" cols="30" rows="1" placeholder="毎日の間食のせいで1ヶ月で5kg太ってしまった。"></textarea>
                    </div>
                    <div class="d-flex justify-content-around">
                        <div class="m-4">
                            <label class="form-label" for="">Plan-計画-</label>
                            <textarea class="form-control" name="plan" cols="30" rows="7" placeholder="まずは1ヶ月に3kg痩せる。&#13;&#10;脂肪3kg=21,000kcal&#13;&#10;1日700kcalずつ減らす"></textarea>
                        </div>
                        <!-- ここから矢印 -->
                        <div style="margin-top: 130px;">
                            <i class="fas fa-angle-right" style="color: red;"></i>
                        </div>
                        <!-- ここまで矢印 -->
                        <div class="m-4">
                            <label class="form-label" for="">Do-実行-</label>
                            <textarea class="form-control" name="do" cols="30" rows="7" placeholder="毎朝30分のランニング=200kcal消費&#13;&#10;毎日の板チョコ1枚(400kcal)とドーナツ(300kcal)を板チョコ半分にする=500kcal減"></textarea>
                        </div>
                    </div>
                    <!-- ここから矢印 -->
                    <div class="d-flex justify-content-around">
                        <div class="mt-3 me-5">
                            <i class="fas fa-angle-up" style="color: red;"></i>
                        </div>
                        <div class="mt-3 ms-5">
                            <i class="fas fa-angle-down" style="color: red;"></i>
                        </div>
                    </div>
                    <!-- ここまで矢印 -->
                    <div class="d-flex justify-content-around">
                        <div class="m-4">
                            <label class="form-label" for="">Action-改善-</label>
                            <textarea class="form-control" name="action" cols="30" rows="7" placeholder="最終週は10日中3日サボってしまった。あと0.6kgで目標達成だった。来月は寒くなくなるのでランニング時間を10分増(トータル1980kcal)。"></textarea>
                        </div>
                        <!-- ここから矢印 -->
                        <div style="margin-top: 130px;">
                            <i class="fas fa-angle-left" style="color: red;"></i>
                        </div>
                        <!-- ここまで矢印 -->
                        <div class="m-4">
                            <label class="form-label" for="">Check-評価-</label>
                            <textarea class="form-control" name="checking" cols="30" rows="7" placeholder="〜3/7:3日サボった(2800kcal消費)&#13;&#10;〜3/14:1日サボった(4200kcal消費)&#13;&#10;〜3/21:毎日できた(4900kcal消費)&#13;&#10;あと9100kcal消費！"></textarea>
                        </div>
                    </div>
                    <div class="text-center my-5">
                        <button type="submit" class="btn btn-primary">PDCAを登録</button>
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