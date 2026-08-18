<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'conn.php';
include 'phpcommon/sendmail.php';


$firmcode=$_POST['firmcode'];
$mobile=$_POST['mobile'];

$response=[];

if(empty($firmcode) || empty($mobile)){
    $response['message']='missing Firmcode or mobile';
    echo json_encode($response);
    exit;
}

//Get the provider id from the provider table with firmcode given in the POST.
$stmt=$conn->prepare('select id from provider  where firmcode = ? ');
$stmt->bind_param('s',$firmcode);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows === 0){
    $response['message']='Incorrect FirmCode';
    echo json_encode($response);
    exit;    
}
$provider=$result->fetch_assoc();
$provider_id=$provider['id'];
$stmt->close();


//Get the record from the user table where mobile number is in POST and provider id is from the above. The columns required are id,name,admin,email
$stmt2=$conn->prepare('select id, username, admin, email from user where mobile = ? and providerid= ?');
$stmt2->bind_param('si',$mobile,$provider_id);
$stmt2->execute();
$result2=$stmt2->get_result();

if($result2->num_rows === 0){
     $response['message']='User Not Found (Incorrect Mobile Number)';
     echo json_encode($response);
     exit;      
}

$user= $result2->fetch_assoc();
$id=$user['id'];
$name=$user['username'];
$admin=$user['admin'];
$email=$user['email'];
$stmt2->close();

//Create a random 6 digit pin
$pin = rand(100000, 999999);

//Get the password hash of the pin
$hash_pass=password_hash($pin, PASSWORD_BCRYPT);

// user table set password to the password hash
$stmt3=$conn->prepare('update user set password = ? where id= ?');
$stmt3->bind_param('si',$hash_pass,$id);
$stmt3->execute();
$stmt3->close();


//get settings from parameter table where providerid is what we got in step 2 


//Send email of new pin to user (include sendmail.php in phpcommon folder. Use the function defined in it)
$to = $email;
$subject = "Password Reset - New PIN";
$message = "Hello $name,\n\nYour new login PIN is: $pin.";
//$headers = "From: amrutajagtap@rtac.in";
$mailresult=sendmail($to, $subject, $message); 

//Send sms of new pin to user (include sendsms.php in phpcommon folder. Use the function defined in it)


if($mailresult == 'ok'){
//Create an object with message = done and echo it
$response['message'] = 'done';
$response['mail'] = $mailresult;
$response['pin'] = $pin;
echo json_encode($response);
}
else{
    $response['mail'] = $mailresult;
    echo json_encode($response);
}
?>