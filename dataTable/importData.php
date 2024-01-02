<?php 

include_once '../db/db_conn.php'; 

require_once 'spreadsheet/vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
use PhpOffice\PhpSpreadsheet\Reader\Csv;

if(isset($_POST['importSubmit'])){ 

    $excelMimes = array('text/xls', 'text/xlsx', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
    $csvMimes = array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/vnd.ms-excel', 'text/anytext', 'application/octet-stream', 'application/txt');
    
    if(!empty($_FILES['file']['name']) && (in_array($_FILES['file']['type'], $excelMimes) || in_array($_FILES['file']['type'], $csvMimes))){ 

        if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
            $reader = in_array($_FILES['file']['type'], $excelMimes) ? new Xlsx() : new Csv();
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
            $worksheet = $spreadsheet->getActiveSheet();  
            $worksheet_arr = $worksheet->toArray(); 

            unset($worksheet_arr[0]); 

            $filename = $_FILES['file']['name'];
            $total_count = count($worksheet_arr);
            $date = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("INSERT INTO file_info (filename, total_count, date) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $filename, $total_count, $date);
            $stmt->execute();
 
            foreach($worksheet_arr as $row){ 
             
                $Calldate = $row[0]; 
                $Datedata = $row[1]; 
                $Agentname = $row[2]; 
                $phone = $row[3]; 
                $Sentiment_Score = $row[4]; 
                $Duration = $row[5];
               
 
                $prevQuery = "SELECT id FROM master WHERE Calldate = '".$Calldate."' AND filename = '".$filename."'"; 
                $prevResult = $conn->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    $conn->query("UPDATE master SET Calldate = '".$Calldate."', Datedata = '".$Datedata."', Agentname = '".$Agentname."', phone = '".$phone."', Sentiment_Score = '".$Sentiment_Score."', Duration = '".$Duration."', modified = NOW(), filename = '".$filename."' WHERE Agentname = '".$Agentname."' AND filename = '".$filename."'"); 
                }else{ 
                    $conn->query("INSERT INTO master (Calldate, Datedata, Agentname, phone, Sentiment_Score, Duration,  created, modified, filename) VALUES ('".$Calldate."', '".$Datedata."', '".$Agentname."', '".$phone."', '".$Sentiment_Score."', '".$Duration."', NOW(), NOW(), '".$filename."')"); 
                } 
            } 
             
            $qstring = '?status=succ'; 
            header("Location: index.php", true, 303);
            exit;
        }else{ 
             $qstring = '?status=err'; 
        } 
    }else{ 
        $qstring = '?status=invalid_file'; 
    } 
    
} 

header("Location: index.php".$qstring); 

?>