<?php
include_once 'db.php';

if (isset($_POST['sequence']) && isset($_POST['filename'])) {
    $sequence = explode(',', $_POST['sequence']);
    $sequenceLength = count($sequence);
    // $lastFileName = $_POST['filename'];
    $lastFileName = urldecode($_POST['filename']);
    $counter = 0;

    $SQLSELECT = "SELECT * FROM assign WHERE filename = ?";
    $stmt = $conn->prepare($SQLSELECT);
    $stmt->bind_param("s", $lastFileName);
    $stmt->execute();
    $result_set = $stmt->get_result();
    //echo ("Number of rows: " . $result_set->num_rows);

    while ($row = $result_set->fetch_assoc()) {
        $assignVal = $sequence[$counter % $sequenceLength];

        $SQLUPDATE = "UPDATE assign SET assignval = ? WHERE id = ?";
        $stmt = $conn->prepare($SQLUPDATE);
        $stmt->bind_param("si", $assignVal, $row['id']);
        $result = $stmt->execute();


        if ($conn->error) {
            die("SQL error: " . $conn->error);
        }

        $counter++;
    }

    $SQLUPDATE = "UPDATE file_info SET sequence = ? WHERE filename = ?";
    $stmt = $conn->prepare($SQLUPDATE);
    $stmt->bind_param("ss", $_POST['sequence'], $lastFileName);
    $stmt->execute();

    $stmt = $conn->prepare($SQLSELECT);
    $stmt->bind_param("s", $lastFileName);
    $stmt->execute();
    $result_set = $stmt->get_result();

    $data = [];
    while ($row = $result_set->fetch_assoc()) {
        $data[] = $row;
    }
    // $data = array('key' => 'value');

    echo json_encode($data);
} else {
    // Return an error message
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}
