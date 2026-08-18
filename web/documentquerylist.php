<?php
include '../conn.php'; // Use your DB connection file

//header('Content-Type: application/json');

// 1. Read POST parameters
$searchdocfrom = $_POST['searchdocfrom'] ?? '';
$searchdocto = $_POST['searchdocto'] ?? '';
$selectdocstatus = $_POST['selectdocstatus'] ?? '0';
$clientname = $_POST['clientname'] ?? '';
$selectdocemployee = $_POST['selectdocemployee'] ?? '0';

// 2. Get current user ID from cookie
$userid = $_COOKIE['userid'] ?? null;
if (!$userid) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

// 3. Get providerid for current user
$stmt = $conn->prepare("SELECT providerid FROM user WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$providerid = $result->fetch_column();

if (!$providerid) {
    echo json_encode(['error' => 'Provider not found']);
    exit;
}

// 4. Build SQL query
$sql = "
SELECT 
    d.id AS docid,
    d.status,
    d.clientid,
    HEX(d.fileguid) AS fileguid,
    HEX(d.accessguid) AS accessguid,
    IFNULL(u.username, '') AS username,
    DATE_FORMAT(d.datetime, '%e/%c/%Y') AS docdate,
    IFNULL(DATE_FORMAT(d.lockedon, '%e/%c/%Y'), '') AS lockedon,
    IFNULL(d.lockedby, '') AS lockedby,
    IFNULL(DATE_FORMAT(d.processedon, '%e/%c/%Y'), '') AS processed,
    IFNULL(d.processedby, '') AS processedby,
    IFNULL(DATE_FORMAT(d.rejectedon, '%e/%c/%Y'), '') AS rejected,
    IFNULL(d.rejectedby, '') AS rejectedby,
    IFNULL(d.rejectionreason, '-') AS rejectionreason,
    IFNULL(DATE_FORMAT(d.deletedon, '%e/%c/%Y'), '') AS deleted,
    IFNULL(d.deletedby, '') AS deletedby,
    d.tallyid,
    DATE_FORMAT(d.tallydate, '%e/%c/%Y') AS tallydate
FROM document d
JOIN client c ON d.clientid = c.id
LEFT JOIN user u ON d.lockedby = u.id
WHERE 
    c.providerid = ?
    AND d.datetime BETWEEN ? AND ?
";

// 5. Add filters
$params = [$providerid, $searchdocfrom, $searchdocto];
$types = "iss";

if ($selectdocstatus != '0' AND $selectdocstatus != '') {
    $sql .= " AND d.status = ?";
    $params[] = $selectdocstatus;
    $types .= "i";
}

//returns document that locked by current logged user.
if ($selectdocemployee != '0' AND $selectdocemployee != '' ) {
    $sql .= " AND d.lockedby = ?";
    $params[] = $selectdocemployee;
    $types .= "i";
}

if (!empty($clientname)) {
    $sql .= " AND c.name LIKE ?";
    $params[] = '%' . $clientname . '%';
    $types .= "s";
}

// 6. Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// 7. Fetch and return data
$documents = [];
while ($row = $result->fetch_assoc()) {
    $documents[] = $row;
}

echo json_encode($documents);
