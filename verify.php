<?php

include("db.php");
date_default_timezone_set('Asia/Kolkata');

//Verify certificate
if (isset($_POST['Add'])) {

    $msg = new \stdClass();
    $code = mysqli_real_escape_string($connection, $_POST['code']);

    try {
        $data = mysqli_query(
            $connection,
            "SELECT s.*, w.name as workshopname, 
            DATE_FORMAT(w.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(w.end_date,'%d/%m/%Y') AS end_date,
            w.days,w.type, c.name as collegename 
            FROM student AS s INNER JOIN workshop AS w ON s.workshopid = w.id 
            INNER JOIN college AS c ON w.collegeid = c.id 
            WHERE s.verification_code = '$code' AND s.status != 0"
        );
        if (mysqli_num_rows($data) > 0) {
            $msg->value = 1;
            $msg->data = "This is valid certificate code.";
            $msg->type = "alert alert-success alert-dismissible ";
            $msg->info = mysqli_fetch_all($data, MYSQLI_ASSOC);
        } else {
            $msg->value = 0;
            $msg->data = "No data found code : $code";
            $msg->type = "alert alert-danger alert-dismissible ";
        }
    } catch (Exception $e) {
        $msg->value = 0;
        $msg->data = "Please check code and try again. " . $e->getMessage();
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Verification </title>
    <link rel="icon" href="dist/img/small.png" type="image/x-icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- <link rel="stylesheet" href="bower_components/datatables.net-bs/css/buttons.dataTables.min.css"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        tfoot input {
            width: 50%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <!-- Site wrapper -->
    <div class="wrapper">

        <?php include("certificate_header.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h4>
                        <?= $project ?>
                        <small><?= $slogan ?></small>
                    </h4>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Default box -->
                            <form id="studentverification" action="" method="post" enctype="multipart/form-data">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Certificate Verification </h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->

                                    <div class="box-body ">
                                        <div class="alert " id="addalertclass" style="display: none">
                                            <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                            <p id="addmsg"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Verification Code</label>
                                            <input type="text" class="form-control" id="code" name="code" required placeholder="Enter Verification code">
                                        </div>

                                    </div>
                                    <div class="modal-footer ">
                                        <input type="hidden" name="Add" value="Add">
                                        <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Verify Me </button>
                                        <button type="reset" class="btn pull-right btn-warning">Clear</button>
                                    </div>

                                </div>
                            </form>
                            <!-- /.box-body -->
                            <!-- /.box-footer-->
                        </div>

                        <div class="col-md-6">
                            <div class="box box-primary col-md-6">
                                <div class="box-header with-border">
                                    <h3 class="box-title" id="sendername">Certificate Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" id="messages">
                                    Enter verification code
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
        </div>
        <!-- /.content-wrapper -->

        <?php include("certificate_footer.php"); ?>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Select2 -->
    <script src="bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.buttons.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/jszip.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/pdfmake.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/vfs_fonts.js"></script>
    <script src="bower_components/datatables.net-bs/js/buttons.html5.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/buttons.print.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            //student verification
            $('#studentverification').submit(function(e) {
                $('#add').prop('disabled', true);
                $('#addalertclass').removeClass();
                $('#addmsg').empty();
                $('#messages').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: new FormData(this),
                    enctype: 'multipart/form-data',
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    success: function(response) {
                        console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        $('#add').prop('disabled', false);
                        if (returnedData['value'] == 1) {
                            $('#studentverification')[0].reset();
                            let certificatedata = returnedData['info'][0];
                            // $('#addalertclass').addClass(returnedData['type']);
                            // $('#addmsg').append(returnedData['data']);
                            // $("#addalertclass").show();
                            var html = '<strong>Certificate Code </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.verification_code +'</p>'+                                        
                                        // '<hr>'+
                                        '<strong>Student Name </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.name +'</p>'+                                        
                                        // '<hr>'+
                                        '<strong>College Name </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.college_name +'</p>'+                                        
                                        '<hr>'+
                                        '<strong>Workshop Name </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.workshopname +'</p>'+                                        
                                        // '<hr>'+
                                        '<strong>Days </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.days +'</p>'+                                        
                                        // '<hr>'+
                                        '<strong>Dates </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.start_date +' to '+ certificatedata.end_date+'</p>'+                                        
                                        // '<hr>'+
                                        '<strong>Type </strong>'+
                                        '<p class="text-muted" style="word-wrap: break-word;">'+certificatedata.type +'</p>'+                                        
                                        '<hr>'
                                        ;
                            $('#messages').append(html);
                            
                        } else {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            $('#messages').append(returnedData['data']);
                        }
                    }
                });
            });
        })
    </script>
</body>

</html>