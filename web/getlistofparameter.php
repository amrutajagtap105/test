<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include '../conn.php';

if (isset($_POST['phpfileid'])) {
    $phpfileid = $_POST['phpfileid'];
    
 $stmt=$conn->prepare("select* from php_file_parameters where php_file_id=?");
 $stmt->bind_param('i',$phpfileid);
 $stmt->execute();
 $result=$stmt->get_result();
 $parametersList=[];
 
        while($row=$result->fetch_assoc()){
            $parametersList[]=$row;
        }
        
 
    echo json_encode($parametersList);
}