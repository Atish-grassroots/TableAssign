<?php
include_once 'db.php'; 

if(isset($_POST['sequence']) && isset($_POST['filename'])) {
    $sequence = $_POST['sequence'];
    $filename = $_POST['filename'];
    $sequenceArray = explode(',', $sequence); 
    $sequenceLength = count($sequenceArray);

    // Update sequence in file_info table
    $SQLUPDATE = "UPDATE file_info SET sequence = ? WHERE filename = ?";
    $stmt = $conn->prepare($SQLUPDATE);
    if ($stmt === false) {
        die("Failed to prepare SQLUPDATE: " . $conn->error);
    }
    $stmt->bind_param("ss", $sequence, $filename);
    if (!$stmt->execute()) {
        die("Failed to execute SQLUPDATE: " . $stmt->error);
    }

    // Update assignval in assign table
    $SQLSELECT = "SELECT * FROM assign WHERE filename = ?";
    $stmt = $conn->prepare($SQLSELECT);
    if ($stmt === false) {
        die("Failed to prepare SQLSELECT: " . $conn->error);
    }
    $stmt->bind_param("s", $filename);
    if (!$stmt->execute()) {
        die("Failed to execute SQLSELECT: " . $stmt->error);
    }
    $result_set = $stmt->get_result();

    $counter = 0;
    while($row = $result_set->fetch_assoc()) {
        $assignVal = $sequenceArray[$counter % $sequenceLength];

        $SQLUPDATE = "UPDATE assign SET assignval = ? WHERE id = ?";
        $stmt = $conn->prepare($SQLUPDATE);
        if ($stmt === false) {
            die("Failed to prepare SQLUPDATE: " . $conn->error);
        }
        $stmt->bind_param("si", $assignVal, $row['id']);
        if (!$stmt->execute()) {
            die("Failed to execute SQLUPDATE: " . $stmt->error);
        }

        $counter++;
    }

    echo "sequence and assignval updated successfully.";
} else {
    echo "Invalid request.";
}
?>