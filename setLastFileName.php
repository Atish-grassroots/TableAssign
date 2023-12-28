<?php
session_start();

if (isset($_POST['filename'])) {
    $_SESSION['lastFileName'] = $_POST['filename'];
    echo "Last filename set successfully.";
} else {
    echo "No filename provided.";
}
?>