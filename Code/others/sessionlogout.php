<?php

include_once '../Database/database.php';
include_once '../User/user.php';

$database = new Database();
$db = $database->getconnection();

$user = new User($db);

$user->token = isset($_GET['token']) ? $_GET['token'] : die();

$stmt = $user->session_logout();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user->status_inactive();
    $user_arr = array(
        "status" => true,
        "message" => "sucessfully Logged Out!",
        "token" => $row['token'],
        "username" =>  $row['username']
    );
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Invalid token!",
    );
}

print_r(json_encode($user_arr));
?>