<?php
require('library.php');
$db = dbconnect();

$stmt = $db->prepare('update posts set plan=?, do=?, checking=?, action=? where id=?');
if (!$stmt) {
    die($db->error);
}
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$plan = filter_input(INPUT_POST, 'plan', FILTER_SANITIZE_STRING);
$do = filter_input(INPUT_POST, 'do', FILTER_SANITIZE_STRING);
$checking = filter_input(INPUT_POST, 'checking', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$stmt->bind_param('ssssi', $plan, $do, $checking, $action, $id);
$success = $stmt->execute();
if (!$success) {
    die($db->error);
}

header('Location: detail.php?id=' . $id);
?>