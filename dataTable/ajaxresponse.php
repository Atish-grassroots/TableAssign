<?php
include_once '../db/db_conn.php'; 

if (isset($_GET['filename'])) {
    $filename = urldecode($_GET['filename']);

    $SQLSELECT = "SELECT a.*, f.* FROM master AS a JOIN file_info AS f ON a.filename = f.filename WHERE a.filename = ?";
    $stmt = $conn->prepare($SQLSELECT);
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $result_set = $stmt->get_result();

    $data = [];
    while($row = $result_set->fetch_assoc())
    {
        $data[] = $row;
    }
   // $data = array('key' => 'value');

    echo json_encode($data);
}
?>