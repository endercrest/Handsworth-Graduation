<html>
<head>
    <meta charset="UTF-8">
    <title>Navbar - Staff</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="../library/Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/Handsworth-Graduation/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="../library/Handsworth-Graduation/css/skins/skin-green-light.min.css" rel="stylesheet" type="text/css"/>
</head>
<body class="skin-green-light sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>HW</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Handsworth</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-info">3</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 3 notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-check-square text-green"></i> Volunteer Hours have been accepted.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-warning text-red"></i> Scholarship forms have been declined!
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-info-circle text-aqua"></i> New blog post.
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../style/Handsworth-Graduation/img/avatar.png" class="user-image" alt="User Image" />
                            <span class="hidden-xs">Marianne Macario</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../style/Handsworth-Graduation/img/avatar.png" class="img-circle" alt="User Image" />
                                <p>
                                    Marianne Macario<br>
                                    Staff<br>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <!-- TODO: Font Styling -->
                <h5 style="font-weight: bold;"><p>Currently Viewing:</p></h5>
                <div class="pull-left image">
                    <img src="../style/Handsworth-Graduation/img/avatar.png" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>Marianne <small>Macario</small></p>
                    <i class="fa fa-circle text-purple"></i> Staff
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..." />
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li>
                    <a href="blog.php">
                        <i class="fa fa-home"></i> <span>Home</span>
                    </a>
                </li>
                <li class="active">
                    <a href="blog.php">
                        <i class="fa fa-bookmark"></i> <span>Blog</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-circle text-blue"></i> <span>Custom Page 1</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-circle text-purple"></i> <span>Custom Page 2</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-circle text-red"></i> <span>Custom Page 3</span>
                    </a>
                </li>
                <li class="header">MY ACCOUNT</li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-tasks"></i> <span>Tasks</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-edit"></i> Manage Tasks</a></li>
                        <li><a href="#"><i class="fa fa-file"></i> Manage Submissions</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-users"></i> <span>Accounts</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-graduation-cap"></i> Manage Students</a></li>
                        <li><a href="#"><i class="fa fa-briefcase"></i> Manage Parents/Guardians</a></li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
</div>

<script src="../library/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="../library/Bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../library/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../library/fastclick/fastclick.js" type="text/javascript"></script>
<script src="../library/Handsworth-Graduation/js/app.min.js" type="text/javascript"></script>
</body>
</html>