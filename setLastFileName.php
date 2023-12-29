<?php
include_once 'db.php'; 

$SQLSELECT = "SELECT filename FROM assign ORDER BY modified DESC LIMIT 1";
$stmt = $conn->prepare($SQLSELECT);
$stmt->execute();
$result_set = $stmt->get_result();
$fetchResult = $result_set->fetch_assoc();
if ($fetchResult !== null) {
    $lastFileName = $fetchResult['filename'];
} else {
    $lastFileName = '';
}

echo $lastFileName;  
?>