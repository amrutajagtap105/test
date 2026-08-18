<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include '../conn.php';
//
if (!isset($_COOKIE['userid'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}
$userid=$_COOKIE['userid'];
    // Step 1: Get logged-in username
    $userStmt = $conn->prepare("SELECT username FROM user WHERE id = ?");
    $userStmt->bind_param('i', $userid);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $userRow = $userResult->fetch_assoc();
    $loggedInUsername = $userRow ? $userRow['username'] : 'Unknown';

if($_POST['key']=='employeeReport'){
    
$todate=$_POST['to'];
$fromdate =$_POST['from'];

$stmt=$conn->prepare('Select first_name, last_name, department_id,salary,job from employees where join_date between ? AND ?');
$stmt->bind_param('ss',$fromdate,$todate);
$stmt->execute();
$result=$stmt->get_result();

$emp_record=[];
 $total_salary = 0;
while ($row = $result->fetch_assoc()) {
        $emp_record[] = $row;
        $total_salary += (float) $row['salary']; // Convert to float to ensure accurate summation
    }

    $response = [
        'username' => $loggedInUsername,
        'employees' => $emp_record,
        'total_salary' => $total_salary
    ];
    echo json_encode($response);

}
elseif ($_POST['key']=='clientReport') {
    
    $fromdate =$_POST['from'];
    $todate=$_POST['to'];

    $stmt=$conn->prepare('Select name, providerid, active,contact,date from client where date between ? AND ?');
    $stmt->bind_param('ss',$fromdate,$todate);
    $stmt->execute();
    $result=$stmt->get_result();

    $client_record=[];
    while($row=$result->fetch_assoc()){
        $client_record[]=$row;
        //print_r($emp_record) ;
    }
    $response = [
        'client' => $client_record,
        'username' => $loggedInUsername
    ];

    echo json_encode($response);
}
elseif ($_POST['key'] == 'userSummary') {
    $from = $_POST['from'];
    $to = $_POST['to'];


    // Step 2: Query login counts by provider and login type
//$stmt = $conn->prepare("
//    SELECT 
//        COALESCE(p.name, 'Unknown Provider') AS provider_name, 
//        l.logintype,
//        COUNT(*) AS count
//    FROM login l
//    LEFT JOIN user u ON l.logintype != 'client' AND l.userid = u.id
//    LEFT JOIN client c ON l.logintype = 'client' AND l.userid = c.id
//    LEFT JOIN provider p ON 
//        (l.logintype != 'client' AND u.providerid = p.id) OR 
//        (l.logintype = 'client' AND c.providerid = p.id)
//    WHERE l.timestamp BETWEEN ? AND ?
//    GROUP BY provider_name, l.logintype
//    ORDER BY provider_name, l.logintype
//");

//$stmt = $conn->prepare("
//SELECT 
//    u.providerid,
//    CASE 
//        WHEN u.admin = 1 THEN 'admin'
//        ELSE 'employee'
//    END AS user_type,
//    COUNT(*) AS login_count
//FROM login l
//JOIN user u ON l.userid = u.id
//WHERE l.timestamp BETWEEN ? AND ?  -- put your date range here
//GROUP BY u.providerid, user_type
//ORDER BY u.providerid, user_type;
//");    
//
//    
//    $stmt->bind_param('ss', $from, $to);
//    $stmt->execute();
//    $result = $stmt->get_result();
//
//    // Step 3: Prepare summary
//    $summary = [];
//
//    while ($row = $result->fetch_assoc()) {
//        $provider = $row['provider_name'];
//        $type = $row['logintype'];
//
//        if (!isset($summary[$provider])) {
//            $summary[$provider] = [
//                'data' => [],
//                'total' => 0
//            ];
//        }
//
//        $summary[$provider]['data'][$type] = (int)$row['count'];
//        $summary[$provider]['total'] += (int)$row['count'];
//    }
//
//    // Step 4: Final output
//    $response = [
//        'summary' => $summary,
//        'username' => $loggedInUsername
//    ];
//
//    echo json_encode($response);
    $stmt = $conn->prepare("
    SELECT 
        p.name AS provider_name,
        CASE 
            WHEN u.admin = 1 THEN 'admin'
            ELSE 'employee'
        END AS user_type,
        COUNT(*) AS login_count
    FROM login l
    JOIN user u ON l.userid = u.id
    LEFT JOIN provider p ON u.providerid = p.id
    WHERE l.timestamp BETWEEN ? AND ?
    GROUP BY p.name, user_type
    ORDER BY p.name, user_type;
");    

$stmt->bind_param('ss', $from, $to);
$stmt->execute();
$result = $stmt->get_result();

$summary = [];

while ($row = $result->fetch_assoc()) {
    $provider = $row['provider_name'] ?: 'Unknown Provider';  // Handle NULL provider names
    $type = $row['user_type'];

    if (!isset($summary[$provider])) {
        $summary[$provider] = [
            'data' => [],
            'total' => 0
        ];
    }

    $summary[$provider]['data'][$type] = (int)$row['login_count'];
    $summary[$provider]['total'] += (int)$row['login_count'];
}

$response = [
    'summary' => $summary,
    'username' => $loggedInUsername
];

echo json_encode($response);

}
