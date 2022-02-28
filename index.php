<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $member_id = $_SESSION['id'];
    $name = $_SESSION['name'];
} else {
    header('Location: login.php');
    exit();
}

require('library.php');
$db = dbconnect();
?>
<!DOCTYPE html>ω
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDCA</title>

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

    <!-- タスク一覧 -->
        <div class="m-5 text-center">
            <p style="font-size: x-large;"><?php echo html($name); ?>さん、ようこそ！</p>
        </div>
    <div class="m-5 text-center">
        <button type="button" class="btn btn-primary" onclick="location.href='register.php'">PDCAを作成</button>
    </div>

    <!-- 一覧表示 -->
    <?php
    $stmt = $db->prepare('select p.id, title, theme, plan, do, checking, action, member_id from posts p, members m where m.id=p.member_id order by p.id desc');
    if(!$stmt){
        die($db->error);
    }
    $success = $stmt->execute();

    $stmt->bind_result($id, $title, $theme, $plan, $do, $checking, $action, $member_id);
    ?>
    <?php while ($stmt->fetch()):?>
    <?php if ($_SESSION['id'] === $member_id): ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="m-5 border rounded" style="height: 300px; position: relative;">
                    <p class="m-2">【<?php echo html($title); ?>】</p>
                    <p class="mt-4 ms-2">抱えている課題：</p>
                    <p class="ps-2 m-2"><?php echo html($theme); ?></p>
                    <p class="mt-4 ms-2">Do：</p>
                    <p class="ps-2 ms-2"><?php echo html($do) ?></p>
                    <form action="detail.php?=<?php echo $id; ?>" method="get">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="m-2" style="position: absolute; right: 0; bottom: 0;">
                            <button type="submit" class="btn btn-success">詳しくみる</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endwhile; ?>

    <footer class="navbar navbar-expand-lg navbar-light bg-light" style="height: 80px;"></footer>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>