
     <?php
     header('Content-Type: application/json');
     
    //include 'phpcommon/guid.php';
     include 'conn.php';
     if(!$conn){
        echo exit(json_encode(["error" => "Connection Failed"])); 
     }
     
     
     $firmcode=$_POST['firmcode']?? '';
     $username = $_POST['username']?? '';
     $userpass= $_POST['userpassword']?? '';
     
    if (!$firmcode || !$username || !$userpass) {
    //http_response_code(400); //"Bad Request" – the server cannot process the request due to invalid or missing input.
    exit(json_encode(["error" => "Missing input"]));
    }
  
    //Get id,name,firmcode from provider table where firmcode is $_POST['firmcode']  
    //Provider data
    $stmt=$conn->prepare("select id,name,firmcode from provider where firmcode = ?");
    $stmt->bind_param("s",$firmcode);
    $stmt->execute();
    $providerResult=$stmt->get_result(); 
    
    if ($providerResult->num_rows === 0) {
    //http_response_code(401); //401 Unauthorized
    exit(json_encode(["error" => "Invalid firmcode"]));
    }
    
    $provideinfo = $providerResult->fetch_assoc();
   
    $firm_id= $provideinfo['id'];
    
    //Get id, email, downloadrights, deleterights, admin, password from user table where providerid= the id from the previous query and email is $_POST['username']
    // Users Data
    $stmt2=$conn->prepare("select id, email, downloadrights, deleterights, admin, password from user where providerid= ? and username = ?");
    $stmt2->bind_param('is',$firm_id,$username );
    $stmt2->execute();
    $userResult=$stmt2->get_result();
    
    if ($userResult->num_rows === 0) {
    //http_response_code(401); //401 Unauthorized
    exit(json_encode(["error" => "Invalid username"]));
    } 
    
    $userinfo=$userResult->fetch_assoc();  
    $userpassoword=$userinfo['password'];
    
    //use function password_verify to check if $_POST['userpassowrd'] matches with the password from the query above.
    if(!password_verify($userpass, $userpassoword)){
       //http_response_code(401); //401 Unauthorized
    exit(json_encode(["error" => "Invalid Password"]));
    } 

//send back json of object with firmid, firmcode, firmname, userID,username ,email,admin ,downloadrights ,deleterights    
//response
$response = [
    "firmid" => $firm_id,
    "firmcode" => $firmcode,
    "firmname" => $provideinfo['name'],
    "userID" => $userinfo['id'],
    "username" => $username,
    "email" => $userinfo['email'],
    "admin" => $userinfo['admin'],
    "downloadrights" => $userinfo['downloadrights'],
    "deleterights" => $userinfo['deleterights'],
];

  echo json_encode($response); 
//Get a GUID  (include guid.php from phpcommon folder)
include 'phpcommon/guid.php';
$guid=getGUID();

 //Get IP address of request ( $_SERVER['REMOTE_ADDR'] ) 
  $ip = $_SERVER['REMOTE_ADDR'] ?? '';
 
   $userID = $userinfo['id'];
  //insert a record in login table. $guid should be inserted as blob in the parameter
$stmt3 = $conn->prepare("INSERT INTO login (guid,userid, ipaddress, timestamp) VALUES (?,?, ?, CURRENT_TIMESTAMP)");
$stmt3->bind_param("sis",$guid,$userID, $ip);
$stmt3->execute();
$stmt3->close();

//set cookie of guid and userid
// Set cookie for GUID & UserID, expires in 30 days
//setcookie("guid", $guid, time() + (30 * 24 * 60 * 60), "/");
//setcookie("userid", $userID, time() + (30 * 24 * 60 * 60), "/");

setcookie("guid", $guid, time() + (60 * 60), "/");
setcookie("userid", $userID, time() + (60 * 60), "/");



//get statistics ( dcount function of  dcountfunction.php)

//add statistics to the returned json as Unlocked ,Locked ,Processed ,Rejected
?>


















































           <?php
 //        1, 'admin@acme.com',            echo password_hash("acmeadmin@123", PASSWORD_DEFAULT) . "\n";
 //        1, 'user1@acme.com',            echo password_hash("acmeuser@123", PASSWORD_DEFAULT) . "\n";
 //        2, 'admin@medicorp.com',        echo password_hash("mediadmin@123", PASSWORD_DEFAULT) . "\n";
        ?>  
        

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

  