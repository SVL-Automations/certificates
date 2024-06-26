<header class="main-header">
  <!-- Logo -->
  <a href="index.php" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini " style="font-size: 13px !important"><?= $officename1 ?></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg " style="font-size: 15px !important"><?= $smallproject ?> </span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="../../dist/img/avatar5.png" class="user-image" alt="User Image">
            <span class="hidden-xs"><?= $_SESSION['name'] ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="../../dist/img/avatar5.png" class="img-circle" alt="User Image">
              <p>
                <?= $_SESSION['name'] ?>
                <small><?=$project?> <?= $officename ?></small>
              </p>
            </li>
            <!-- Menu Body -->
            <li class="user-body">
              <div class="row">
                <div class="col-xs-4 text-center">
                  <a href="profile.php">Profile</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="changepassword.php">Change Password</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="logout.php">Sign out</a>
                </div>
              </div>
              <!-- /.row -->
            </li>
            <li class="user-body">
              <div class="row">
                <div class="text-center">
                  <span ><i class="fa fa-user"></i> Designed by : SVL Automations</span>
                </div>
                <div class="text-center">
                  <span ><i class="fa fa-mobile"></i>  : 88 5335 4141 </span>
                </div> 
                <div class="text-center">
                  <span ><i class="fa fa-internet-explorer"></i>  : info@svlautomations.in</span>
                </div>               
              </div>
              <!-- /.row -->
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>

<!-- =============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="../../dist/img/small.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p style="white-space: normal;"><?= $_SESSION['name'] ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

      <li>
        <a href="index.php">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>      

      <li class="treeview">
        <a href="#">
          <i class="fa fa-certificate"></i> <span>Workshops</span> <i class="fa  fa-hand-o-down pull-right"></i>
        </a>
        <ul class="treeview-menu">          
          <li><a href="workshop.php"><i class="fa fa-certificate"></i> View Workshops </a></li>          
        </ul>
      </li>      

      <li class="treeview">
        <a href="#">
          <i class="fa fa-child"></i> <span>Student List</span> <i class="fa  fa-hand-o-down pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li><a href="students.php"><i class="fa fa-child"></i> Add / Update Students </a></li>                    
        </ul>
      </li>      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<!-- =============================================== -->