<?php
session_start();
require('library.php');

$db = dbconnect();
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

    <!-- PDCA詳細フォーム -->
    <?php
    $stmt = $db->prepare('select p.id, title, theme, plan, do, checking, action, member_id from posts p, members m where p.id=?');
    
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $stmt->bind_param('i', $id);
    if(!$stmt){
        die($db->error);
    }
    $success = $stmt->execute();
    if(!$success){
        die($db->error);
    }

    $stmt->bind_result($id, $title, $theme, $plan, $do, $checking, $action, $member_id);
    $stmt->fetch()
    ?>
    <h2 class="text-center my-5">PDCAの詳細</h2>
    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <p class="text-center"><span  style="font-weight: bold;">PDCA名</span>：<?php echo html($title); ?></p>
                <p class="mb-5 text-center"><span  style="font-weight: bold;">抱えている課題</span>：<?php echo html($theme) ;?></p>
                    <p style="font-weight: bold;">Plan-計画-</p>
                    <div class="m-4 cols-4 border rounded">
                        <p><?php echo html($plan); ?></p>
                    </div>
                        <!-- ここから矢印 -->
                    <div class="mt-5 text-center">
                        <i class="fas fa-angle-down" style="color: red;"></i>
                    </div>
                    <!-- ここまで矢印 -->
                    <p style="font-weight: bold;">Do-実行-</p>
                    <div class="m-4 cols-4 border rounded">
                        <p><?php echo html($do); ?></p>
                    </div>
                    <!-- ここから矢印 -->
                    <div class="mt-5 text-center">
                        <i class="fas fa-angle-down" style="color: red;"></i>
                    </div>
                    <!-- ここまで矢印 -->
                    <p style="font-weight: bold;">Check-評価-</p>
                    <div class="m-4 cols-4 border rounded">
                        <p><?php echo html($checking); ?></p>
                    </div>
                    <!-- ここから矢印 -->
                    <div class="mt-5 text-center">
                        <i class="fas fa-angle-down" style="color: red;"></i>
                    </div>
                    <!-- ここまで矢印 -->
                    <p style="font-weight: bold;">Action-改善-</p>
                    <div class="m-4 cols-4 border rounded">
                        <p><?php echo html($action); ?></p>
                    </div>
                    <?php if ($_SESSION['id'] === $member_id): ?>
                    <form action="edit.php?id=<?php echo $id; ?>" method="post">
                        <div class="text-center my-5">
                            <button type="submit" class="btn btn-primary">PDCAを編集</button>
                        </div>
                    </form>
                    <div class="text-center my-5">
                        <a type="submit" class="btn btn-danger" href="delete.php?id=<?php echo html($id); ?>">PDCAを削除</a>
                    </div>
                    <?php endif; ?>
            </div>
        </div>
    </div>

    
    <footer class="navbar navbar-expand-lg navbar-light bg-light" style="height: 80px;"></footer>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>