<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $result = mysqli_query(
        $connection,
        "SELECT s.*, w.name as workshopname, c.name as collegename FROM
        student AS s INNER JOIN workshop AS w ON s.workshopid = w.id
        INNER JOIN college AS c ON w.collegeid = c.id 
        ORDER BY s.id DESC"
    );
    $data->studentlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "SELECT w.*, c.name as collegename 
                                        FROM `workshop` as w INNER JOIN college AS c 
                                        ON w.collegeid = c.id");
    $data->workshoplist = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
    exit();
}


//Add student
if (isset($_POST['Add'])) {

    $msg = new \stdClass();

    $workshopid = mysqli_real_escape_string($connection, $_POST['workshopid']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $collegename = mysqli_real_escape_string($connection, $_POST['collegename']);
    $class = mysqli_real_escape_string($connection, $_POST['class']);
    $date = date("Y-m-d");
    $uniqueKey = strtoupper(substr(sha1(microtime()), rand(0, 5), 9));
    $uniqueKey  = implode("-", str_split($uniqueKey, 3));

    try {

        $data = mysqli_query($connection, "SELECT * FROM `student` WHERE `workshopid` = '$workshopid' AND `email` = '$email'");
        if (mysqli_num_rows($data) == 0) {

            $res = mysqli_query(
                $connection,
                "INSERT INTO `student`(`workshopid`, `name`, `email`, `mobile`, `college_name`, `class`, `status`, `registration_date`, `verification_code`)
                            VALUES('$workshopid','$name','$email','$mobile','$collegename','$class','1','$date','$uniqueKey')"
            );
            if ($res > 0) {
                $msg->value = 1;
                $msg->data = "Registration completed successfully";
                $msg->type = "alert alert-success alert-dismissible ";
            } else {
                $msg->value = 0;
                $msg->data = "Please check your info and try again.";
                $msg->type = "alert alert-danger alert-dismissible ";
            }
        } else {
            $msg->value = 0;
            $msg->data = "You already registered for workshop. Please contact trainer for more details.";
            $msg->type = "alert alert-danger alert-dismissible ";
        }
    } catch (Exception $e) {
        $msg->value = 0;
        $msg->data = "Please check your info and try again. " . $e->getMessage();
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//approve student Status
if (isset($_POST['approve'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $email = array();

    $studentid = mysqli_real_escape_string($connection, trim(strip_tags($_POST['studentid'])));
    $status = mysqli_real_escape_string($connection, trim(strip_tags($_POST['status'])));

    $data = mysqli_query($connection, "SELECT s.*, w.name as workshopname, c.name as collegename FROM
                                        student AS s INNER JOIN workshop AS w ON s.workshopid = w.id
                                        INNER JOIN college AS c ON w.collegeid = c.id  
                                        WHERE s.id = '$studentid'");
    if (mysqli_num_rows($data) == 1) {

        $data = mysqli_fetch_assoc($data);

        $body =  "Dear " . $data['name'] . "  ,  <br/>            
          Your certificate is approve and ready for download.<br/>
          Event Name : ".$data['workshopname']."<br/> 
          Attended at : ".$data['collegename']."<br/>
          Verification code : ".$data['verification_code']."<br/><br/>

          Download from : https://certificates.svlautomations.in/download.php <br/><br/>
          OR<br/>
          Click on : https://certificates.svlautomations.in/certificate.php?".$data['verification_code']."<br/><br/>

          We thank you for connecting with us.<br/><br/>
                            
          
          Regards,<br/>
          " . $project . "           
          ";

        $subject = "SVL Automations : Certificate Download ";
        $email[] = $data['email'];

        $mailstatus = mailsend($email, $body, $subject, $project);

        if ($mailstatus == 'Success') {
            $data = mysqli_query($connection, "Update student SET status = '2' WHERE id = '$studentid'");
            $msg->value = 1;
            $msg->data = "Certificate approve successfully.";
            $msg->type = "alert alert-success alert-dismissible ";
        } else {
            $msg->value = 0;
            $msg->data = "Email not sent. Please Try after sometime!!!";
            $msg->type = "alert alert-danger alert-dismissible ";
        }
    } else {
        $msg->value = 0;
        $msg->data = "Student details not found. Please check info and try again. ";
        $msg->type = "alert alert-danger alert-dismissible ";
    }    

    echo json_encode($msg);
    exit();
}

//reject student Status
if (isset($_POST['reject'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8mb4");
    $email = array();

    $studentid = mysqli_real_escape_string($connection, trim(strip_tags($_POST['studentid'])));
    $status = mysqli_real_escape_string($connection, trim(strip_tags($_POST['status'])));

    $data = mysqli_query($connection, "SELECT s.*, w.name as workshopname, c.name as collegename FROM
                                        student AS s INNER JOIN workshop AS w ON s.workshopid = w.id
                                        INNER JOIN college AS c ON w.collegeid = c.id  
                                        WHERE s.id = '$studentid'");
    if (mysqli_num_rows($data) == 1) {       
            $data = mysqli_query($connection, "Update student SET status = '0' WHERE id = '$studentid'");
            $msg->value = 1;
            $msg->data = "Certificate reject successfully.";
            $msg->type = "alert alert-warning alert-dismissible ";       
    } else {
        $msg->value = 0;
        $msg->data = "Student details not found. Please check info and try again. ";
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
    <title><?= $project ?> : Student Details </title>
    <link rel="icon" href="../../dist/img/small.png" type="image/x-icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/buttons.dataTables.min.css"> -->

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

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <?php include("header.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h4>
                    <?= $project ?>
                    <small><?= $slogan ?></small>
                </h4>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#"> Student </a></li>
                    <li class="active"> Add / Update </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Student Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Student" data-toggle="modal" data-target="#modaladdstudent"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="alert " id="alertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                                <p id="msg"></p>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body  table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Name </th>
                                            <th class='text-center'>Workshop Name </th>
                                            <th class='text-center'>Email </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>College </th>
                                            <th class='text-center'>Class </th>
                                            <th class='text-center'>Code </th>
                                            <th class='text-center'>Status </th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Name </th>
                                            <th class='text-center'>Workshop Name </th>
                                            <th class='text-center'>Email </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>College </th>
                                            <th class='text-center'>Class </th>
                                            <th class='text-center'>Code </th>
                                            <th class='text-center'>Status </th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <!-- /.box-footer-->
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Add student modal -->
        <form id="addstudent" action="" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="modaladdstudent" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add student details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Workshop Name</label>
                                <select class="form-control select2 select3 " style="width: 100%;" required name="workshopid" id="workshopid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Student Name</label>
                                <input type="text" class="form-control" id="name" name="name" required pattern="[a-zA-Z\s.]+" placeholder="Name will appear on the certificate">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="The certificate will receive on email">

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" class="form-control" id="mobile" name="mobile" required placeholder="Enter mobile number" pattern="[0-9]{10}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">College Name</label>
                                <input type="text" class="form-control" id="collegename" name="collegename" required placeholder="Enter college name">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Class</label>
                                <select class="form-control select2 " style="width: 100%;" required name="class" id="class">
                                    <option value="">Select class</option>
                                    <option value="First Year">First Year</option>
                                    <option value="Second Year">Second Year</option>
                                    <option value="Third Year">Third Year</option>
                                    <option value="Fourth Year">Fourth Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success" disabled>Add </button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add student modal -->

        <?php include("footer.php"); ?>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.buttons.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/jszip.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/pdfmake.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/vfs_fonts.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.html5.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.print.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();


            //display data table
            function tabledata() {
                $('.select3').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        //  console.log(response); 
                        var returnedData = JSON.parse(response);
                        //  console.log(returnedData);
                        var srno = 0;
                        $.each(returnedData['studentlist'], function(key, value) {
                            srno++;
                            let approve = '';
                            let reject = '';

                            if (value.status == 1) {
                                approve = '<button type="submit" name="Approve" id="Approve" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-success approve-button" style= "margin:5px" title="Approve" ><i class="fa fa-check"></i></button>';
                                reject = '<button type="submit" name="Reject" id="Reject" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-danger reject-button" style= "margin:5px" title="Reject" ><i class="fa fa-close"></i></button>';
                            } else if (value.status == 0) {
                                approve = '<button type="submit" name="Approve" id="Approve" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-success approve-button" style= "margin:5px" title="Approve" ><i class="fa fa-check"></i></button>';
                            } else {
                                reject = '<button type="submit" name="Reject" id="Reject" ' +
                                    'data-editid="' + value.id + '" data-status="' + value.status +
                                    '" class="btn btn-xs btn-danger reject-button" style= "margin:5px" title="Reject" ><i class="fa fa-close"></i></button>';
                            }

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.name + '</td>' +
                                '<td class="text-center">' + value.workshopname + '( ' + value.collegename + ')</td>' +
                                '<td class="text-center">' + value.email + '</td>' +
                                '<td class="text-center">' + value.mobile + '</td>' +
                                '<td class="text-center">' + value.college_name + '</td>' +
                                '<td class="text-center">' + value.class + '</td>' +
                                '<td class="text-center">' + value.verification_code + '</td>' +
                                '<td class="text-center">' + value.status + '</td>' +
                                '<td class="text-center">' + approve + reject + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('.select3').append(new Option("Select workshop", ""));
                        $.each(returnedData['workshoplist'], function(key, value) {
                            $('.select3').append(new Option(value.name, value.id));
                        });

                        //Initialize Select2 Elements
                        $('.select2').select2()


                        $('#example1').DataTable({
                            stateSave: true,
                            destroy: true,
                        });
                    }
                });
            }

            tabledata();

            //add student
            $('#addstudent').submit(function(e) {
                $('#add').prop('disabled', true);
                $('#addalertclass').removeClass();
                $('#addmsg').empty();

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
                            $('#addstudent')[0].reset();
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        } else {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                        }
                    }
                });
            });

            //approve student status
            $(document).on("click", ".approve-button", function(e) {

                $('#alertclass').removeClass();
                $('#msg').empty();
                $(this).prop('disabled', true);

                e.preventDefault();
                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'approve': 'approve',
                        'studentid': $(this).data('editid'),
                        'status': $(this).data('status')
                    },
                    success: function(response) {
                        //console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();                            
                        } else {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                        }
                        tabledata();
                        $(this).prop('disabled', false);
                    }
                });
            });

            //Reject student status
            $(document).on("click", ".reject-button", function(e) {

                $('#alertclass').removeClass();
                $('#msg').empty();
                $(this).prop('disabled', true);

                e.preventDefault();
                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'reject': 'reject',
                        'studentid': $(this).data('editid'),
                        'status': $(this).data('status')
                    },
                    success: function(response) {
                        //console.log(response);
                        returnedData = JSON.parse(response);
                        if (returnedData['value'] == 1) {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();                            
                        } else {
                            $('#alertclass').addClass(returnedData['type']);
                            $('#msg').append(returnedData['data']);
                            $("#alertclass").show();
                        }
                        $(this).prop('disabled', false);
                        tabledata();
                    }
                });
            });


        })
    </script>
</body>

</html>