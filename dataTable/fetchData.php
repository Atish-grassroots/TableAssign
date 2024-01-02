<?php 

include_once '../db/db_conn.php'; 

$sno = 1; 
$counter = 0; 

$SQLSELECT = "SELECT filename FROM master ORDER BY modified DESC LIMIT 1";
$stmt = $conn->prepare($SQLSELECT);
$stmt->execute();
$result_set = $stmt->get_result();
$fetchResult = $result_set->fetch_assoc();
if ($fetchResult !== null) {
    $lastFileName = $fetchResult['filename'];
} else {
    $lastFileName = '';
}

$SQLSELECT = "SELECT * FROM master WHERE filename = ?";
$stmt = $conn->prepare($SQLSELECT);
$stmt->bind_param("s", $lastFileName);
$stmt->execute();
$result_set = $stmt->get_result();

if ($conn->error) {
    die("SQL error: " . $conn->error);
}

$data = [];
while($row = $result_set->fetch_assoc())
{
    $row['sno'] = $sno++;
    $data[] = $row;
}

echo json_encode($data);

?>