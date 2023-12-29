<?php

include_once 'db.php';

if (!empty($_GET['status'])) {
    switch ($_GET['status']) {
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Something went wrong, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid Excel file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

</head>

<body>

    <!-- Display status message -->
    <?php if (!empty($statusMsg)) { ?>
        <div class="col-xs-12 p-3">
            <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
        </div>
    <?php } ?>

    <div class="row p-3">
        <div class="col-md-12 head">
            <div class="float-end">
                <div class="row">
                    <div class="col">
                        <!-- <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import Excel</a> -->
                        <form class="row g-3" action="importData.php" method="post" enctype="multipart/form-data">
                            <div class="col-auto">
                                <label for="fileInput" class="visually-hidden">File</label>
                                <input type="file" class="form-control" name="file" id="fileInput" />

                            </div>
                            <div class="col-auto">
                                <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Upload">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12" id="importFrm" style="display: none;">
            <form class="row g-3" action="importData.php" method="post" enctype="multipart/form-data">
                <div class="col-auto">
                    <label for="fileInput" class="visually-hidden">File</label>
                    <input type="file" class="form-control" name="file" id="fileInput" />
                    <!-- <input type="file" class="form-control" name="file" id="fileInput" /> -->
                </div>
                <div class="col-auto">
                    <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Upload">
                </div>
            </form>
            <!-- </div>
                <div class="col-md-12 offset-md-10"> -->
        </div>

        <!-- Data list table -->
        <div>
            <div class="row">
                <div class="col-12 col-md-8" style="box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;  padding: 10px;">

                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sno</th>
                                <th>Call Date</th>
                                <th>Date Data</th>
                                <th>Agent Name</th>
                                <th>Phone</th>
                                <th>Sentiment Score</th>
                                <th>Duration</th>
                                <th>Assign Val</th>
                            </tr>
                        </thead>
                        <tbody>
                    </table>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                    <div>
                        <form id="myForm" class="row g-3" method="post">
                            <!-- <input type="hidden" name="filename" value="<?php echo isset($_GET['filename']) ? $_GET['filename'] : ''; ?>"> -->
                            <textarea id="sequence" name="sequence" title="Enter your sequence here, separated by commas" placeholder="Enter sequence separated by comma" rows="12" cols="50"><?php echo isset($_POST['sequence']) ? $_POST['sequence'] : ''; ?></textarea>
                            <input type="hidden" name="filename" id="filename">

                            <!-- <input type="file" class="form-control" name="file" id="fileInput" onchange="updateSequence()" /> -->
                            <input type="submit" id="mySubmit" name="submitSequence" title="Click to submit your sequence" value="Submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $.ajax({
            url: 'fetchData.php',
            type: 'GET',
            success: function(response) {
                var data = JSON.parse(response);
                if ($.fn.DataTable.isDataTable('#example')) {
                    $('#example').DataTable().destroy();
                }

                $("#example tbody").empty();
                var sno = 1;
                $.each(data, function(i, item) {
                    var row = $("<tr>");
                    row.append($("<td>").text(sno++));
                    row.append($("<td>").text(item.Calldate));
                    row.append($("<td>").text(item.Datedata));
                    row.append($("<td>").text(item.Agentname));
                    row.append($("<td>").text(item.phone));
                    row.append($("<td>").text(item.Sentiment_Score));
                    row.append($("<td>").text(item.duration));
                    row.append($("<td>").text(item.assignval));
                    $("#example tbody").append(row);

                });
                $('#example').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "dom": 'Bfrtip',
                    "buttons": [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        });


        $(".dropdown-item").click(function(e) {
            e.preventDefault();
            var filename = $(this).attr('href').split('=')[1];
            $('#filename').val(filename);
            $.ajax({
                url: 'ajaxresponse.php',
                type: 'get',
                data: {
                    filename: filename
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#sequence').val(data.sequence);
                    if ($.fn.DataTable.isDataTable('#example')) {
                        $('#example').DataTable().destroy();
                    }

                    $("#example tbody").empty();
                    $('textarea[name="sequence"]').val(data[0].sequence);
                    //console.log(data[0].sequence);
                    var sno = 1;
                    $.each(data, function(i, item) {
                        var row = $("<tr>");
                        row.append($("<td>").text(sno++));
                        row.append($("<td>").text(item.Calldate));
                        row.append($("<td>").text(item.Datedata));
                        row.append($("<td>").text(item.Agentname));
                        row.append($("<td>").text(item.phone));
                        row.append($("<td>").text(item.Sentiment_Score));
                        row.append($("<td>").text(item.duration));
                        row.append($("<td>").text(item.assignval));
                        $("#example tbody").append(row);

                    });
                    $('#example').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "dom": 'Bfrtip',
                        "buttons": [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ]
                    });
                    $.post(window.location.href, {
                        filename: filename
                    });
                }
            });
        });


        $('#myForm').on('submit', function(e) {
            e.preventDefault();

            var sequence = $('#sequence').val();
            var filename = $('#filename').val();

            if (!filename) {
                filename = sessionStorage.getItem('uploadedFileName');

                updateData(sequence, filename);
                //console.log('if' + filename)
            } else {
                updateData(sequence, filename);
                console.log('else' + filename)
            }
            //console.log('fun' + filename);
        });

        function updateData(sequence, filename) {
            console.log('update' + filename);
            $.ajax({
                url: 'updateData.php',
                type: 'post',
                data: {
                    sequence: sequence,
                    filename: filename
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    //console.log(response);
                    if ($.fn.DataTable.isDataTable('#example')) {
                        $('#example').DataTable().destroy();
                    }

                    $("#example tbody").empty();
                    var sno = 1;
                    $.each(data, function(i, item) {
                        var row = $("<tr>");
                        row.append($("<td>").text(sno++));
                        row.append($("<td>").text(item.Calldate));
                        row.append($("<td>").text(item.Datedata));
                        row.append($("<td>").text(item.Agentname));
                        row.append($("<td>").text(item.phone));
                        row.append($("<td>").text(item.Sentiment_Score));
                        row.append($("<td>").text(item.duration));
                        row.append($("<td>").text(item.assignval));
                        $("#example tbody").append(row);
                    });
                    $('#example').DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "dom": 'Bfrtip',
                        "buttons": [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ]
                    });
                }
            });
        }


        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#example')) {
                table = $('#example').DataTable({
                    "paging": true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                });
            }
        });

        function updateSequence() {
            var fileInput = document.getElementById('fileInput');
            var filename = fileInput.value.split('\\').pop();
            $.ajax({
                url: 'updateSequence.php',
                type: 'post',
                data: {
                    filename: filename
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }

        window.onload = function() {
            var sequenceTextarea = document.getElementById('sequence');
            var storedSequence = sessionStorage.getItem('sequence');
            if (storedSequence) {
                sequenceTextarea.value = storedSequence;
            }
        };

        document.querySelector('#myForm').addEventListener('submit', function() {
            var sequenceTextarea = document.getElementById('sequence');
            sessionStorage.setItem('sequence', sequenceTextarea.value);
            });

        document.querySelector('#fileInput').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                // sessionStorage.removeItem('sequence');
                var encodedFileName = encodeURIComponent(file.name);
                sessionStorage.setItem('uploadedFileName', encodedFileName);

                var newSequence = '';
               
                if (newSequence) {
                    var sequenceTextarea = document.getElementById('sequence');
                    sequenceTextarea.value = newSequence;
                    sessionStorage.setItem('sequence', newSequence);
                }
            }
        });

       // console.log(sessionStorage.getItem('uploadedFileName'));
    </script>
    <?php include 'footer.php'; ?>

</html>