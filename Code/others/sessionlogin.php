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
    $user->status_active_1();
    $user_arr = array(
        "message" => "sucessfully Logged In!",
        "token" => $row['token'],
        "username" =>  $row['username'],
        "password" =>  $row['password'],
    );
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Invalid token!",
    );
}

print_r(json_encode($user_arr));
?>