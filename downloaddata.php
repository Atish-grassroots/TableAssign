<?php
ob_start(); 
require 'spreadsheet/vendor/autoload.php';
include_once 'db.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$format = $_POST['format']; 
$lastFileName = '';


if (isset($_POST['submit'])) {
    $SQLSELECT = "SELECT filename FROM assign ORDER BY modified DESC LIMIT 1";
    $stmt = $conn->prepare($SQLSELECT);
    $stmt->execute();
    $result_set = $stmt->get_result();
    $lastFileName = $result_set->fetch_assoc()['filename'];
} else if (isset($_POST['filename'])) {
    $lastFileName = $_POST['filename'];
}

$SQLSELECT = "SELECT * FROM assign WHERE filename = ?";
$stmt = $conn->prepare($SQLSELECT);
$stmt->bind_param("s", $lastFileName);
$stmt->execute();
$result_set = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Call Date');
$sheet->setCellValue('B1', 'Date Data');
$sheet->setCellValue('C1', 'Agent Name');
$sheet->setCellValue('D1', 'Phone');
$sheet->setCellValue('E1', 'Sentiment Score');
$sheet->setCellValue('F1', 'Duration');
$sheet->setCellValue('G1', 'Assign Val');

$rowCounter = 2;
while($row = $result_set->fetch_assoc())
{
    $sheet->setCellValue('A'.$rowCounter, $row['Calldate']);
    $sheet->setCellValue('B'.$rowCounter, $row['Datedata']);
    $sheet->setCellValue('C'.$rowCounter, $row['Agentname']);
    $sheet->setCellValue('D'.$rowCounter, $row['phone']);
    $sheet->setCellValue('E'.$rowCounter, $row['Sentiment_Score']);
    $sheet->setCellValue('F'.$rowCounter, $row['duration']);
    $sheet->setCellValue('G'.$rowCounter, $row['assignval']); 
    $rowCounter++;
    $counter++;
}

$fileName = 'table_data';
if ($format == 'xlsx') {
    $writer = new Xlsx($spreadsheet);
    $fileName .= '.xlsx';
    $contentType = "application/vnd.ms-excel";
} else if ($format == 'csv') {
    $writer = new Csv($spreadsheet);
    $fileName .= '.csv';
    $contentType = "text/csv";
}

$writer->save($fileName);
ob_end_clean();
header("Content-Type: $contentType");
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Pragma: no-cache"); 
header("Expires: 0");
readfile($fileName);
exit;
?>