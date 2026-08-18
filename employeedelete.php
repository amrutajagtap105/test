<?php
include 'conn.php';


$loggedinUser = $_COOKIE['userid'] ?? 0;

// Get the logged-in user's delete rights
$sql = "SELECT deleterights FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loggedinUser);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['deleterights'] != 1) {
    echo json_encode(['status' => 'Error ', 'message' => 'You do not have permission to delete records.']);
    exit;
}

//admin with delete rights
$userid = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
$stmt->bind_param('i', $userid);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully.']);
} else {
    echo json_encode(['status' => 'Error ', 'message' => 'No record deleted (maybe ID not found).']);
}
