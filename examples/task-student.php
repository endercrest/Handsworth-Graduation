<html>
<head>
    <meta charset="UTF-8">
    <title>Task - Student</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="../library/Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/Handsworth-Graduation/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/FontAwesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../library/Ionicons/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="../library/Handsworth-Graduation/css/skins/skin-green-light.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/Handsworth-Graduation/css/HandsworthGrad.css" rel="stylesheet" type="text/css"/>
    <link href="../library/select2/select2.min.css" rel="stylesheet" type="text/css" />
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
                    <!-- Tasks: style can be found in dropdown.less -->
                    <!-- TODO Un-submitted task status needed -->
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-tasks"></i>
                            <span class="label label-danger">12</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 12 tasks</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Scholarship Forms
                                                <small class="pull-right" style="font-size: 11px;">Declined</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-red" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">100%</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Task 2
                                                <small class="pull-right" style="font-size: 11px;">Pending</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-yellow" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">100%</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Volunteer Hours
                                                <small class="pull-right" style="font-size: 11px;">Accepted</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-green" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">100%</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../style/Handsworth-Graduation/img/avatar.png" class="user-image" alt="User Image" />
                            <span class="hidden-xs">Thomas Cordua-von Specht</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../style/Handsworth-Graduation/img/avatar.png" class="img-circle" alt="User Image" />
                                <p>
                                    Thomas Cordua-von Specht<br>
                                    Student<br>
                                    <small>Graduation Student of 2016</small>
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
                    <!-- TODO Use this to show which user a parent or staff member may be viewing -->
                    <p>Thomas <small>Cordua-von Specht</small></p>
                    <i class="fa fa-circle text-primary"></i> Student
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
                <li>
                    <a href="#">
                        <i class="fa fa-tasks"></i> <span>Tasks</span>
                        <small class="label pull-right bg-red">9</small>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Task <small>Volunteer Hours</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="tasks-student.php"><i class="fa fa-tasks"></i> Tasks</a></li>
                <li><a href="task-student.php"><i class="fa fa-pencil"></i> Task - Volunteer Hours</a></li>
            </ol>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="row">
                <div class="col-md-9">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Information</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <h4 style="font-weight: bold">Task Name:</h4>
                                    Volenteer Hours

                                    <h4 style="font-weight: bold">Description:</h4>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque dignissim commodo quam, ac vestibulum arcu iaculis vel. Nullam lacinia pretium tempor. Pellentesque dapibus velit vel arcu euismod pretium. Donec volutpat nisi nibh, nec dapibus odio sodales id. Fusce ac viverra odio. Suspendisse mi ante, lobortis in tortor vitae, accumsan sodales quam. Quisque dignissim nibh dolor, quis fermentum justo molestie nec.

                                    <br><br>
                                    <h4 style="font-weight: bold">Teacher Comments:</h4>
                                    Everything seems to be in order. Thank you for submitting ahead of the due date.
                                </div>
                                <div class="col-md-3">
                                    <h4 style="font-weight: bold">Status:</h4>
                                    <span class="label label-success" style="font-size: 12px;">Accepted</span>

                                    <h4 style="font-weight: bold">Due Date:</h4>
                                    January 5, 2015
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Upload</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 style="margin-top: 0;">Uploaded Files</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="well well-sm">
                                                <div class="row">
                                                    <div class="col-xs-2"><i class="fa fa-3x fa-file-pdf-o"></i></div>
                                                    <div class="col-xs-10">
                                                        <a class="pull-right fa fa-times"></a>
                                                        volenteerhours-1.pdf<br>
                                                        <small class="text-muted">File Size: 2.1MB</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="well well-sm">
                                                <div class="row">
                                                    <div class="col-xs-2"><i class="fa fa-3x fa-file-word-o"></i></div>
                                                    <div class="col-xs-10">
                                                        <a class="pull-right fa fa-times"></a>
                                                        volenteerhours-2.docx<br>
                                                        <small class="text-muted">File Size: 4.2MB</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h4 style="margin-top: 0;">Upload File</h4>
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-9">
                                                <input type="file" id="exampleInputFile" class="center-block"/>
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                <button type="button" class="btn btn-primary btn-sm center-block">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Task Files</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="well well-sm">
                                <a href="#">
                                <div class="row">
                                    <div class="col-xs-2 text-black">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-file-o fa-stack-2x"></i>
                                            <i class="fa fa-arrow-down fa-stack-1x" style="top: 3px;"></i>
                                        </span>
                                    </div>
                                    <div class="col-xs-10 text-black">
                                        Volunteer Hours template<br>
                                        <small class="text-muted">Form to be filled out per volunteer assignment.</small>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="well well-sm">
                                <a href="#">
                                    <div class="row">
                                        <div class="col-xs-2 text-black">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-file-o fa-stack-2x"></i>
                                            <i class="fa fa-arrow-down fa-stack-1x" style="top: 3px;"></i>
                                        </span>
                                        </div>
                                        <div class="col-xs-10 text-black">
                                            Special Form<br>
                                            <small class="text-muted">Special form only some students may need</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>
        </section>
    </div>
</div>

<script src="../library/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="../library/Bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../library/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../library/fastclick/fastclick.js" type="text/javascript"></script>
<script src="../library/Handsworth-Graduation/js/app.min.js" type="text/javascript"></script>
<script src="../library/select2/select2.full.min.js" type="text/javascript"></script>
<script>
    //Enable the select2 Elements on the page
    $(".select2").select2();
</script>
</body>
</html>