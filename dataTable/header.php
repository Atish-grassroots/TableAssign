<?php 

include_once '../db/db_conn.php'; 


$SQLSELECT = "SELECT filename FROM file_info ORDER BY filename DESC LIMIT 15";
$stmt = $conn->prepare($SQLSELECT);
$stmt->execute();
$result_set = $stmt->get_result();
$filenames = $result_set->fetch_all(MYSQLI_ASSOC);

?>
<head>
<meta charset="utf-8">
<title>Excel File Data</title>



<link rel="stylesheet" href="assets/bootstrap.min.css">
<link rel="stylesheet" href="assets/style.css">

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.semanticui.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">  


<script>
// function formToggle(ID){
//     var element = document.getElementById(ID);
//     if(element.style.display === "none"){
//         element.style.display = "block";
//     }else{
//         element.style.display = "none";
//     }
// }
</script>
<nav class="navbar navbar-expand-md navbar-dark bg-success fixed-top">
      <a class="navbar-brand" href="#"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only"></span></a>
          </li>
          <li class="nav-item">
            <!-- <a class="nav-link" href="#">Link</a> -->
          </li>
          <li class="nav-item">
            <!-- <a class="nav-link disabled" href="#">Disabled</a> -->
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Files</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
                <?php foreach ($filenames as $file): ?>
                    <input type="hidden" name="filename" value="<?php echo urlencode($file['filename']); ?>">
                    <a class="dropdown-item" href="?filename=<?php echo urlencode($file['filename']); ?>"><?php echo $file['filename']; ?></a>
                <?php endforeach; ?>
            </div>
          </li>
        
          
          <!-- <form id="filenameForm" method="POST" action="index.php">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Files</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
            <?php foreach ($filenames as $file): ?>
                <input type="hidden" name="filename" value="<?php echo urlencode($file['filename']); ?>">
                <a class="dropdown-item" href="#" onclick="submitForm('<?php echo urlencode($file['filename']); ?>')"><?php echo $file['filename']; ?></a>
            <?php endforeach; ?>
        </div>
    </li>
</form> -->
        </ul>
        <div class="ml-auto" style="display: flex; align-items: center;">
    <h1 style="margin-right: 15px; color: white;"> <?php echo $_SESSION['name']; ?></h1>
    <a href="../logout.php" style="float: right; background: #555; padding: 10px 15px; color: #fff; border-radius: 5px; margin-right: 10px; border: none; text-decoration: none;">Logout</a></div>
    </div>
      </div>
    </nav>