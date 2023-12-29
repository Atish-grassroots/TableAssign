<?php
include_once 'db.php';

$action = $_POST['action'];

switch ($action) {
    case 'get_last_filename':
        $SQLSELECT = "SELECT filename FROM assign ORDER BY modified DESC LIMIT 1";
        $stmt = $conn->prepare($SQLSELECT);
        $stmt->execute();
        $result_set = $stmt->get_result();
        $fetchResult = $result_set->fetch_assoc();
        if ($fetchResult !== null) {
            echo $fetchResult['filename'];
        } else {
            echo '';
        }
        break;
    case 'get_assign_data':
        $filename = $_POST['filename'];
        $SQLSELECT = "SELECT * FROM assign WHERE filename = ?";
        $stmt = $conn->prepare($SQLSELECT);
        $stmt->bind_param("s", $filename);
        $stmt->execute();
        $result_set = $stmt->get_result();
        $data = $result_set->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
        break;
        case 'update_assign':
            $assignVal = $_POST['assignVal'];
            $id = $_POST['id'];
            $SQLUPDATE = "UPDATE assign SET assignval = ? WHERE id = ?";
            $stmt = $conn->prepare($SQLUPDATE);
            $stmt->bind_param("si", $assignVal, $id);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo "Error: " . $stmt->error;
            }
            break;
    case 'update_file_info':
        $sequence = $_POST['sequence'];
        $filename = $_POST['filename'];
        $SQLUPDATE = "UPDATE file_info SET sequence = ? WHERE filename = ?";
        $stmt = $conn->prepare($SQLUPDATE);
        $stmt->bind_param("ss", $sequence, $filename);
        $stmt->execute();
        echo 'success';
        break;
}
?>