<?php
include 'conn.php';

$newpassword =$_POST['newpassword'];
$confirmpassword=$_POST['confirmpassword'];
$clientid= $_POST['clientid'];

if(empty($newpassword)){
    echo json_encode(["message"=>"New Password is Empty!"]);
    exit();
}

if($newpassword != $confirmpassword ){
    echo json_encode(["message"=>"new Password and confirm password is not match"]);
    exit();
}

$hash_pass=password_hash($newpassword, PASSWORD_BCRYPT);

$stmt3=$conn->prepare('update client set password = ? where id= ?');
$stmt3->bind_param('si',$hash_pass,$clientid);
$stmt3->execute();
$stmt3->close();

echo json_encode(["message"=>"done"]);