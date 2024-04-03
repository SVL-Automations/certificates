<?php

include("db.php");
include("mail/mail.php");
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $result = mysqli_query(
        $connection,
        "SELECT c.name AS collegename, w.*  FROM 
                            `workshop` AS w INNER JOIN `college` AS c 
                            ON w.collegeid = c.id 
                            WHERE w.status = 1
                            ORDER BY w.`start_date` DESC"
    );
    $data->workshoplist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "SELECT * FROM `college` ORDER BY `name`");
    $data->collegelist = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
    exit();
}


//Add Student
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

        $data = mysqli_query($connection, "SELECT * FROM `student` WHERE `workshopid` = '$workshopid' AND `email` = '$email' AND status != 0");
        if (mysqli_num_rows($data) == 0) {

            $res = mysqli_query(
                $connection,
                "INSERT INTO `student`(`workshopid`, `name`, `email`, `mobile`, `college_name`, `class`, `status`, `registration_date`, `verification_code`)
                            VALUES('$workshopid','$name','$email','$mobile','$collegename','$class','1','$date','$uniqueKey')"
            );
            if ($res > 0) {

                $body =  "Dear " . $name . "  ,  <br/>            
                        Your registration is successfully completed for workshop.<br/>                        
                        Verification code : " . $uniqueKey . "<br/><br/>

                        You can able to download a certificate once it approved. You will get email notification for same. Downlaod links as follow<br/>
                        Download from : https://certificates.svlautomations.in/download.php <br/><br/>
                        OR<br/>
                        Click on : https://certificates.svlautomations.in/certificate.php?" . $uniqueKey . "<br/><br/>

                        We thank you for connecting with us.<br/><br/>                                            
                        
                        Regards,<br/>
                        " . $project . "           
                        ";

                $subject = "SVL Automations : Workshop Registration ";                

                $mailstatus = mailsend($email, $body, $subject, $project);
                $msg->mailstatus = $mailstatus;
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

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Registration </title>
    <link rel="icon" href="dist/img/small.png" type="image/x-icon">    
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta content="Thing in Everything" name="description">
    <meta content="IOT,Web Developement, Trainings" name="keywords">
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
                            <form id="studentregistration" action="" method="post" enctype="multipart/form-data">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Workshop Registration </h3>

                                    </div>
                                    <div class="alert " id="alertclass" style="display: none">
                                        <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                                        <p id="msg"></p>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->

                                    <div class="box-body ">
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
                                        <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Register Me </button>
                                        <button type="reset" class="btn pull-right btn-warning">Clear</button>
                                    </div>

                                </div>
                            </form>
                            <!-- /.box-body -->
                            <!-- /.box-footer-->
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


            //display data table
            function tabledata() {
                $('.select3').empty();

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

                        $('.select3').append(new Option("Select workshop", ""));
                        $.each(returnedData['workshoplist'], function(key, value) {
                            $('.select3').append(new Option(value.name, value.id));
                        });

                        //Initialize Select2 Elements
                        $('.select2').select2();
                    }
                });
            }

            tabledata();

            //student registration
            $('#studentregistration').submit(function(e) {
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
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        $('#add').prop('disabled', false);
                        if (returnedData['value'] == 1) {
                            $('#studentregistration')[0].reset();
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
        })
    </script>
</body>

</html>