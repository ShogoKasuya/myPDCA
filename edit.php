<?php
session_start();
require('library.php');

$db = dbconnect();

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $member_id = $_SESSION['id'];
}

?>
<!DOCTYPE html>
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

    <!-- PDCA編集フォーム --> 
    <?php
    $stmt = $db->prepare('select p.id, title, theme, plan, do, checking, action, member_id from posts p, members m where p.id=? and member_id=?');
    
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $stmt->bind_param('ii', $id, $member_id);
    if(!$stmt){
        die($db->error);
    }
    $success = $stmt->execute();
    if(!$success){
        die($db->error);
    }
    
    $stmt->bind_result($id, $title, $theme, $plan, $do, $checking, $action, $member_id);
    $stmt->fetch();


    ?>
    <h2 class="text-center my-5">PDCAを更新してブラッシュアップしよう！</h2>  
    <div class="container">
        <div class="row"> 
            <div class="col-8 offset-2">  
                <p class="text-center">PDCA名:<?php echo html($title); ?></p>
                <p class="text-center">抱えている課題:<?php echo html($theme); ?></p>
                <form action="edit_do.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="d-flex justify-content-around">
                        <div class="m-4"> 
                            <label class="form-label" for="">Plan-計画-</label>
                            <textarea class="form-control" name="plan" id="" cols="30" rows="7"><?php echo html($plan); ?></textarea>
                        </div>
                        <!-- ここから矢印 --> 
                        <div style="margin-top: 130px;">  
                            <i class="fas fa-angle-right"></i>
                        </div>
                        <!-- ここまで矢印 --> 
                        <div class="m-4"> 
                            <label class="form-label" for="">Do-実行-</label>
                            <textarea class="form-control" name="do" id="" cols="30" rows="7"><?php echo html($do); ?></textarea>
                        </div>
                    </div>
                    <!-- ここから矢印 --> 
                    <div class="d-flex justify-content-around">
                        <div class="mt-3 me-5">
                            <i class="fas fa-angle-up"></i>
                        </div>
                        <div class="mt-3 ms-5">
                            <i class="fas fa-angle-down"></i> 
                        </div>
                    </div>
                    <!-- ここまで矢印 --> 
                    <div class="d-flex justify-content-around">
                        <div class="m-4"> 
                            <label class="form-label" for="">Action-改善-</label>
                            <textarea class="form-control" name="action" id="" cols="30" rows="7"><?php echo html($action); ?></textarea>
                        </div>
                        <!-- ここから矢印 --> 
                        <div style="margin-top: 130px;">  
                            <i class="fas fa-angle-left"></i> 
                        </div>
                        <!-- ここまで矢印 --> 
                        <div class="m-4"> 
                            <label class="form-label" for="">Check-評価-</label>
                            <textarea class="form-control" name="checking" id="" cols="30" rows="7"><?php echo html($checking); ?></textarea>
                        </div>
                    </div>
                    <div class="text-center my-5">
                        <button type="submit" class="btn btn-primary">PDCAを更新</button>
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