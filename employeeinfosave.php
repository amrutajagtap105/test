<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'conn.php';
/*get employee info according to  $_POST['employeeid']
File name is employeeinfosave.php */
$employeeid = $_POST['employeeid'];
$name = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$admin = ($_POST['admin'] === 'Yes') ? 1 : 0;
$download = ($_POST['Download-Rights'] === 'Yes') ? 1 : 0;
//$deleteRights = $_POST['employeedeleterights'] ?? 0;
$active = ($_POST['Inactive'] === 'Yes') ? 1 : 0;


//$_COOKIE['userid'] is the loggedinUser
$loggedinUser =$_COOKIE['userid'];
//Get providerid from user table where id is loggedinUser
$sql1 = "Select providerid from user where id=? ";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $loggedinUser);
$stmt1->execute();
$result=$stmt1->get_result();

$row=$result->fetch_assoc();
$providerid=$row['providerid'];

if($employeeid == 0){
    
    // INSERT: First, check if a user with this provider and email already exists
    $checkSql = "SELECT id FROM user WHERE providerid = ? AND email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("is", $providerid, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'User already exists with this email under the provider.']);
        exit;
    }    
    
    //Insert Employee
    $password = $_POST['employeepassword'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO user (providerid, email, username, mobile, admin, downloadrights, Inactive, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiiis", $providerid, $email, $name, $mobile, $admin, $download, $active, $passwordHash);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }   
    
}else
{
    
//Update the record where id is $_POST['employeeid']
$sql = "UPDATE user SET providerid=?, email=?, username=?, mobile=?, admin=?, downloadrights=?,  Inactive=?  WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssiiii",$providerid, $email, $name, $mobile, $admin, $download,  $active, $employeeid);
$stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status'=>'success', 'message' => 'Employee info updated.']);
    } else {   
        echo json_encode(['status'=>'error', 'message'=>'No changes or error']);
           // http_response_code(400);
            //echo json_encode(['status' => 'error', 'message' => 'No changes made.']);
    }


}