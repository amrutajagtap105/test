<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

include 'conn.php';

/*$_POST will have
employeeid
newpassword
confirmpassword*/

$employeeid =$_POST['ChangePasswordEmployeeID'];
$newpassword=$_POST['NewPassword'];
$confirmpassword=$_POST['ReenterNewPassword'];

//Check if length of newpassword is greater than 0. Use strlen function. If not give error message and exit
if(strlen($newpassword)===0){
  echo json_encode(["message"  => "Password cannot be empty"]);
  exit();
}

//Check if newpassword is same as confirmpassword. If not  give error message and exit
if($newpassword !== $confirmpassword){
  echo json_encode(["message"  => "Password do not match"]);
  exit();
}

//Get password_hash of newpassword
$hash_pass=password_hash($newpassword, PASSWORD_BCRYPT);

//update password field in user table for id=employeeid
$stmt3=$conn->prepare('update user set password = ? where id= ?');
$stmt3->bind_param('si',$hash_pass,$employeeid);
$stmt3->execute();
$stmt3->close();

/*echo object with message=done*/

echo json_encode(["message"=>"done"]);