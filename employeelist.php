<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'conn.php';

$response=[];
if(!isset($_COOKIE['userid'])){
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
//1. Get id of logged in user from the cookie 
$loginUserId = $_COOKIE['userid'];

//2. Get the providerid from user table of the logged in user.
$stmt=$conn->prepare("select providerid from user where id = ? ");
$stmt->bind_param('i',$loginUserId);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    echo json_encode(["error"=>"User Not Found"]);
    exit();
}
$row=$result->fetch_assoc();
$providerId = $row['providerid'];

//3. Get all records of users whose providerid is the providerid you got in step 2
$stmt2=$conn->prepare("SELECT id, username, email, mobile, admin,downloadrights, deleterights, Inactive FROM user WHERE providerid = ?");
$stmt2->bind_param('i',$providerId);
$stmt2->execute();
$result2 = $stmt2->get_result();

$users=[];
//$users['providerid']=$providerId;
while($user=$result2->fetch_assoc()){
    $users[]=$user;
}

$response = [
    "providerid" => $providerId,
    "users" => $users
];
//4. Send the result back as a json array
echo json_encode($response);

//Display employee data in datatable format and in action column get password icon,edit icon,delete icon and top of table get add button