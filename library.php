<?php
// htmlspecialcharsのfunction化
function html($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}

// 
function dbconnect(){
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $url['dbname'] = ltrim($url['path'], '/');
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $db = new mysqli($server, $username, $password, $db);

    return $db;
}
?>