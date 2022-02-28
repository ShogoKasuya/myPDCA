<?php
// htmlspecialcharsのfunction化
function html($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

// 
function dbconnect(){
    $db = new mysqli('mysql551.phy.lolipop.lan', 'LA10717358', 'TnrYYo8hKo', 'LA10717358-ke59yr');
    if (!$db) {
        die($db->error);
    }

    return $db;
}
?>