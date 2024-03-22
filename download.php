<?php

include("db.php");
date_default_timezone_set('Asia/Kolkata');

//Download certificate
if (isset($_POST['Add'])) {

    $msg = new \stdClass();
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    try {
        $data = mysqli_query(
            $connection,
            "SELECT s.*, w.name as workshopname, 
            DATE_FORMAT(w.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(w.end_date,'%d/%m/%Y') AS end_date,
            w.days,w.type, c.name as collegename 
            FROM student AS s INNER JOIN workshop AS w ON s.workshopid = w.id 
            INNER JOIN college AS c ON w.collegeid = c.id 
            WHERE s.email = '$email' AND s.status = 2 "
        );
        if (mysqli_num_rows($data) > 0) {
            $msg->value = 1;
            $msg->workshoplist = mysqli_fetch_all($data, MYSQLI_ASSOC);
        } else {
            $msg->value = 0;
            $msg->data = "No data found for email : $email";
            $msg->type = "alert alert-danger alert-dismissible ";
        }
    } catch (Exception $e) {
        $msg->value = 0;
        $msg->data = "Please check email and try again. " . $e->getMessage();
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
                                        <h3 class="box-title">Certificate Downloads </h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->

                                    <div class="box-body ">
                                        <div class="alert " id="addalertclass" style="display: none">
                                            <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                            <p id="addmsg"></p>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email address used for registration">
                                        </div>

                                    </div>
                                    <div class="modal-footer ">
                                        <input type="hidden" name="Add" value="Add">
                                        <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Search </button>
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
                                <div class="box-body  table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class='text-center'>Download </th>
                                                <th class='text-center'>Name </th>
                                                <th class='text-center'>Code </th>
                                                <th class='text-center'>Type </th>
                                                <th class='text-center'>Location </th>
                                                <th class='text-center'>Days </th>
                                                <th class='text-center'>Start Date </th>
                                                <th class='text-center'>End Date </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class='text-center'>Download </th>
                                                <th class='text-center'>Name </th>
                                                <th class='text-center'>Code </th>
                                                <th class='text-center'>Type </th>
                                                <th class='text-center'>Location </th>
                                                <th class='text-center'>Days </th>
                                                <th class='text-center'>Start Date </th>
                                                <th class='text-center'>End Date </th>
                                            </tr>
                                        </tfoot>
                                    </table>
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

            $('#example1').DataTable({
                stateSave: true,
                destroy: true,
            });

            //student verification
            $('#studentverification').submit(function(e) {
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();
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
                        var srno = 0;

                        if (returnedData['value'] == 0) {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            $('#add').prop('disabled', false);
                        } else {

                            $.each(returnedData['workshoplist'], function(key, value) {
                                srno++;
                                let button1 = '';
                                button1 = '<a target="_blank" class="btn btn-xs btn-primary" style= "margin:5px" title=" Download" href= certificate.php?code=' + value.verification_code + '><i class="fa fa-download"></i></a>';


                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + button1 + '</td>' +
                                    '<td class="text-center">' + value.name + '</td>' +
                                    '<td class="text-center">' + value.verification_code + '</td>' +
                                    '<td class="text-center">' + value.type + '</td>' +
                                    '<td class="text-center">' + value.collegename + '</td>' +
                                    '<td class="text-center">' + value.days + '</td>' +
                                    '<td class="text-center">' + value.start_date + '</td>' +
                                    '<td class="text-center">' + value.end_date + '</td>' +
                                    '</tr>';
                                $('#example1 tbody').append(html);
                                // alert(html);
                            });
                            
                        }
                        $('#add').prop('disabled', false);
                            $('#example1').DataTable({
                                stateSave: true,
                                destroy: true,
                            });
                    }

                });
            });
        })
    </script>
</body>

</html>