<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'conn.php';
$employeeid=$_POST['employeeid'];
$activatedeactivate=$_POST['activatedeactivate'];
$isChecked = $_POST['isChecked'];

//Update the user table and set column inactive to the value of activatedeactivate for the record where id is employeeid
$stmt=$conn->prepare("Update user set inactive = ? where id = ?");
$stmt->bind_param("ii",$activatedeactivate,$employeeid);
$stmt->execute();

if($stmt){
    echo json_encode(["status"=>"ok","ischeked"=>"$isChecked"]);
}