<?php
// htmlspecialcharsのfunction化
function html($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

// 
function dbconnect(){
    $db = new mysqli('localhost', 'root', 'root', 'pdca');
    if (!$db) {
        die($db->error);
    }

    return $db;
}
?>