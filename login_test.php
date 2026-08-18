<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <script>
    
    
    </script>
    <body>
<?php
header('Content-Type: application/json');

include 'phpcommon/guid.php';
include 'phpcommon/dcountfunction.php';
include 'db.php'; // Your DB connection

$firmcode     = $_POST['firmcode'] ?? '';
$username     = $_POST['username'] ?? '';
$userpassword = $_POST['userpassword'] ?? '';

if (!$firmcode || !$username || !$userpassword) {
    http_response_code(400);
    exit(json_encode(["error" => "Missing input"]));
}

// Get provider details
$stmt = $conn->prepare("SELECT id, name, firmcode FROM provider WHERE firmcode = ?");
$stmt->bind_param("s", $firmcode);
$stmt->execute();
$providerResult = $stmt->get_result();

if ($providerResult->num_rows === 0) {
    http_response_code(401); //401 Unauthorized
    exit(json_encode(["error" => "Invalid firmcode"]));
}
$provider = $providerResult->fetch_assoc();
$providerid = $provider['id'];

// Get user details
$stmt = $conn->prepare("SELECT id, email, downloadrights, deleterights, admin, password FROM user WHERE providerid = ? AND email = ?");
$stmt->bind_param("is", $providerid, $username);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows === 0) {
    http_response_code(401);
    exit(json_encode(["error" => "Invalid username"]));
}
$user = $userResult->fetch_assoc();

// Check password
if (!password_verify($userpassword, $user['password'])) {
    http_response_code(401);
    exit(json_encode(["error" => "Invalid password"]));
}

// Login successful
$guid = getGUID(); // returns binary GUID
$ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Insert into login table
$stmt = $conn->prepare("INSERT INTO login (guid, userid, ipaddress) VALUES (?, ?, ?)");
$stmt->bind_param("bis", $guid, $user['id'], $ip);
$stmt->send_long_data(0, $guid); // Send as blob
$stmt->execute();

// Set cookies
setcookie("guid", bin2hex($guid), time() + 3600, "/");
setcookie("userid", $user['id'], time() + 3600, "/");

// Get statistics using dcount
$stats = dcount($user['id']); // Assume this returns assoc array: ['Unlocked' => X, 'Locked' => Y...]

// Build response
$response = [
    "firmid"         => $providerid,
    "firmcode"       => $provider['firmcode'],
    "firmname"       => $provider['name'],
    "userID"         => $user['id'],
    "username"       => $username,
    "email"          => $user['email'],
    "admin"          => $user['admin'],
    "downloadrights" => $user['downloadrights'],
    "deleterights"   => $user['deleterights'],
    "Unlocked"       => $stats['Unlocked'] ?? 0,
    "Locked"         => $stats['Locked'] ?? 0,
    "Processed"      => $stats['Processed'] ?? 0,
    "Rejected"       => $stats['Rejected'] ?? 0
];

echo json_encode($response);
?>

        
        
                
        <?php
 //        1, 'admin@acme.com',            echo password_hash("acmeadmin@123", PASSWORD_DEFAULT) . "\n";
 //        1, 'user1@acme.com',            echo password_hash("acmeuser@123", PASSWORD_DEFAULT) . "\n";
 //        2, 'admin@medicorp.com',        echo password_hash("mediadmin@123", PASSWORD_DEFAULT) . "\n";
        ?>
        
        
        
// forgot_password.php 

<?php

include 'conn.php';
include 'phpcommon/sendmail.php';
include 'phpcommon/sendsms.php';

$firmcode = $_POST['firmcode'];
$mobile = $_POST['mobile'];
$response = [];

if (empty($firmcode) || empty($mobile)) {
    $response['message'] = 'Missing firmcode or mobile';
    echo json_encode($response);
    exit;
}

// Step 1: Get provider ID
$stmt = $conn->prepare('SELECT id FROM provider WHERE firmcode = ?');
$stmt->bind_param('s', $firmcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response['message'] = 'Incorrect FirmCode';
    echo json_encode($response);
    exit;
}
$provider = $result->fetch_assoc();
$provider_id = $provider['id'];
$stmt->close();

// Step 2: Get user by mobile and provider_id
$stmt2 = $conn->prepare('SELECT id, username, admin, email FROM user WHERE mobile = ? AND provider_id = ?');
$stmt2->bind_param('si', $mobile, $provider_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows === 0) {
    $response['message'] = 'User Not Found (Incorrect Mobile Number)';
    echo json_encode($response);
    exit;
}
$user = $result2->fetch_assoc();
$id = $user['id'];
$name = $user['username'];
$admin = $user['admin'];
$email = $user['email'];
$stmt2->close();

// Step 3: Generate random 6-digit PIN
$pin = rand(100000, 999999);

// Step 4: Hash the PIN
$hash_pass = password_hash($pin, PASSWORD_BCRYPT);

// Step 5: Update user password
$stmt3 = $conn->prepare('UPDATE user SET password = ? WHERE id = ?');
$stmt3->bind_param('si', $hash_pass, $id);
$stmt3->execute();
$stmt3->close();

// Step 6: (Optional) Get settings from parameter table
$stmt4 = $conn->prepare('SELECT * FROM parameter WHERE providerid = ?');
$stmt4->bind_param('i', $provider_id);
$stmt4->execute();
$settings = $stmt4->get_result()->fetch_assoc();
$stmt4->close();

// Step 7: Send Email
$subject = "Password Reset - New PIN";
$body = "Hello $name,\n\nYour new login PIN is: $pin\n\nPlease change it after logging in.";
sendMail($email, $subject, $body);

// Step 8: Send SMS
$smsMessage = "Your new login PIN is: $pin";
sendSMS($mobile, $smsMessage);

// Step 9: Send response
$response['message'] = 'done';
echo json_encode($response);
?>
  
//employeelist.php

<?php
include 'conn.php';

header('Content-Type: application/json');

$response = [];

if (!isset($_COOKIE['userid'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$loginUserId = $_COOKIE['userid'];

// Step 2: Get provider_id of logged-in user
$stmt = $conn->prepare("SELECT provider_id FROM user WHERE id = ?");
$stmt->bind_param('i', $loginUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

$row = $result->fetch_assoc();
$providerId = $row['provider_id'];

// Step 3: Get all users with same provider_id
$stmt2 = $conn->prepare("SELECT id, username, email, mobile, admin FROM user WHERE provider_id = ?");
$stmt2->bind_param('i', $providerId);
$stmt2->execute();
$result2 = $stmt2->get_result();

$users = [];

while ($user = $result2->fetch_assoc()) {
    $users[] = $user;
}

echo json_encode($users);
?>

    </body>
</html>
