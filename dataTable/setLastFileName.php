<?php
include_once '../db/db_conn.php'; 

$SQLSELECT = "SELECT filename, sequence FROM file_info ORDER BY id  DESC LIMIT 1";
$stmt = $conn->prepare($SQLSELECT);
$stmt->execute();
$result_set = $stmt->get_result();
$fetchResult = $result_set->fetch_assoc();
if ($fetchResult !== null) {
    $lastFileName = $fetchResult['filename'];
    $sequence = $fetchResult['sequence'];
} else {
    $lastFileName = '';
}

echo $lastFileName . ',' . $sequence;   
?>