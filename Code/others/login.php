<?php

include_once '../Database/database.php';
include_once '../User/user.php';

$database = new Database();
$db = $database->getconnection();

$user = new User($db);

$user->username = isset($_GET['username']) ? $_GET['username'] : die();
$user->password = base64_encode(isset($_GET['password']) ? $_GET['password'] : die());

$stmt = $user->login();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user->session_token();
    $user->status_active();
    $user_arr = array(
        "status" => true,
        "message" => "sucessfully Login!",
        "username" => $row['username'],
        "token" => $user->token
    );
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Invalid Username or Password!",
    );
}

print_r(json_encode($user_arr));

?>