<?php
include_once 'db.php'; 

if(isset($_POST['sequence']) && isset($_POST['filename'])) {
    $sequence = $_POST['sequence'];
    $filename = $_POST['filename'];
    $sequence = explode(',', $_POST['sequence']); 
    $sequenceLength = count($sequence); 
   // $filename = $_POST['filename'];
    $SQLSELECT = "SELECT * FROM assign WHERE filename = ?";
    $stmt = $conn->prepare($SQLSELECT);
    $stmt->bind_param("s", $lastFileName);
   // echo($lastFileName);
    $stmt->execute();
    $result_set = $stmt->get_result();

   // echo "<script>$('#example tbody').empty();</script>";

    while($row = $result_set->fetch_assoc())
    {
        $assignVal = $sequence[$counter % $sequenceLength];

        $SQLUPDATE = "UPDATE assign SET assignval = ? WHERE id = ?";
        $stmt = $conn->prepare($SQLUPDATE);
        $stmt->bind_param("si", $assignVal, $row['id']);
        $stmt->execute();

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

    echo "sequence and assignval updated successfully.";
} else {
    echo "Invalid request.";
}
?>