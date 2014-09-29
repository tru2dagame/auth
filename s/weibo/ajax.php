<?php

include_once ('../../config.php');

$name = isset($_POST['name']) ? addslashes($_POST['name']) : '';
$email = isset($_POST['email']) ? addslashes($_POST['email']) : '';
$phone = isset($_POST['phone']) ? intval($_POST['phone']) : '0';
$position = isset($_POST['position']) ? addslashes($_POST['position']) : '';
$company = isset($_POST['company']) ? addslashes($_POST['company']) : '';
$comment = isset($_POST['comment']) ? addslashes($_POST['comment']) : '';

$result = array('code' => 'false');

$sql = "SELECT COUNT(*) FROM `user_info` WHERE `email` = '{$email}'";
$count = $mysql->query($sql, '1');
if($count) {
    $result['type'] = 'email';
    $result['message'] = 'Email has been registered.';
    echo json_encode($result, TRUE);
    exit;
}

$sql = "SELECT COUNT(*) FROM `user_info` WHERE `phone` = '{$phone}'";
$count = $mysql->query($sql, '1');
if($count) {
    $result['type'] = 'phone';
    $result['message'] = 'The phone number has been registered.';
    echo json_encode($result, TRUE);
    exit;
}

$insert_sql = "INSERT INTO `user_info` (`name`, `email`, `phone`, `company`, `position`, `comment`)
        VALUES ('{$name}', '{$email}', '{$phone}', '{$company}', '{$position}', '{$comment}'); ";
try {
    $mysql->query($insert_sql);
    $result['code'] = 'true';
    echo json_encode($result, TRUE);
    exit;
} catch(Exception $e) {
    echo $e->getMessage();
}