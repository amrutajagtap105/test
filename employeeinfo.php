
<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'conn.php';
/*
On clicking edit button, you have to call employeeinfo.php. In the data, you have to send employeeid. You will receive back 
id
name
email
mobile
admin  0 No, 1 Yes
Download Rights  0 No, 1 Yes
Inactive 0 No, 1 Yes

These have to be shown in a form. Except id. id will be in a hidden input.

There will be save and cancel button in the form. On cancel, you will hide the form and show the dashboard
*/
if(isset($_POST['employeeid'])){
$employeeid= $_POST['employeeid'];
}
else{
    echo json_encode(["message"=>"Id Not found"]);
   exit();
}

$stmt=$conn->prepare('SELECT providerid,email,username,mobile,admin,downloadrights,Inactive from user where id=?');
$stmt->bind_param('i',$employeeid);
$stmt->execute();
$result = $stmt->get_result();
$data=$result->fetch_assoc();

$providerid=$data['providerid'];
$email =$data['email'];
$name=$data['username'];
//$pass=$data['password'];
$mobile=$data['mobile'];
$admin  =$data['admin'];
$Download =$data['downloadrights'];
$Inactive =$data['Inactive'];

$response = array_merge(["message" => "success"], $data);

echo json_encode($response);
?>
