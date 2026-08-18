<?php
include 'conn.php';
$userid=$_COOKIE['userid'];

//from user table get providerid for the record where id=$userid and save to variable $providerid
$stmt=$conn->prepare('select providerid from user where id=? ');
$stmt->bind_param('i',$userid);
$stmt->execute();
$result=$stmt->get_result();
$row = $result->fetch_assoc(); 
$providerid=$row['providerid'];

//after that get id, name,active from client  table where  providerid = ? and active=1 
$stmt2=$conn->prepare('select id, name ,active,contact from client where providerid=? AND active=?  ');
$active = 1;
$stmt2->bind_param('ii',$providerid,$active);
$stmt2->execute();
$result2=$stmt2->get_result();

$clientlist=[];
while($row2=$result2->fetch_assoc()){
  $clientlist[]=$row2;  
}

echo json_encode($clientlist);
//and display client list in datatable format
//columns like name ,active,contact,phone,action

