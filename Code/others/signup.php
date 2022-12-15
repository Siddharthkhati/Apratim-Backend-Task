<?php

include_once '../Database/database.php';
include_once '../User/user.php';

$database = new Database();
$db = $database->getconnection();

$user = new User($db);

$user->username = $_POST['username'];
$user->college = $_POST['college'];
$user->email = $_POST['email'];
$user->password = base64_encode($_POST['password']);
$user->created = date('Y-m-d H:i:s');

if($user->signup()) {
    $user_arr=array(
        "status" => true,
        "message" => "sucessfully Signup!",
        "username" => $user->username
    );
}
else {
    $user_arr=array(
        "status" => false,
        "message" => "Username already exist!"
    );
}

print_r(json_encode($user_arr));

?>