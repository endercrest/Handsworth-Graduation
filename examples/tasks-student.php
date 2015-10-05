<html>
<head>
    <meta charset="UTF-8">
    <title>Tasks - Student</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="../library/Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/Handsworth-Graduation/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
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
            <h1>Tasks<small>Steps towards graduation!</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
                <li class="active"><a href="tasks-student.php"><i class="fa fa-tasks"></i> Tasks</a></li>
            </ol>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="row">
                <div class="col-md-9">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Tasks</h3>
                            <div class="box-tools">
                                <ul class="pagination pagination-sm no-margin pull-right">
                                    <li><a href="#">&laquo;</a></li>
                                    <li><a href="#">1</a></li>
                                    <li class="active"><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">&raquo;</a></li>
                                </ul>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th width="20%">Task</th>
                                    <th width="10%">Due Date</th>
                                    <th width="50%">Description</th>
                                    <th width="10%">Status</th>
                                    <th width="10%"></th>
                                </tr>
                                <tr>
                                    <td><a href="#">Volunteer Hours</a></td>
                                    <td>11-7-2015</td>
                                    <td>The mandatory hours of volunteering needed for graduation.</td>
                                    <td><span class="label label-success">Accepted</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 2</a></td>
                                    <td>11-15-2015</td>
                                    <td>This is just an example task displaying for task 2</td>
                                    <td><span class="label label-warning">Pending</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Scholarship Forms</a></td>
                                    <td>11-21-2015</td>
                                    <td>Forms needed to receive scholarships</td>
                                    <td><span class="label label-danger">Declined</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 3</a></td>
                                    <td>12-18-2015</td>
                                    <td>This is just an example task displaying for task 3</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 4</a></td>
                                    <td>12-25-2015</td>
                                    <td>This is just an example task displaying for task 4</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                                <tr>
                                    <td><a href="#">Task 5</a></td>
                                    <td>01-12-2016</td>
                                    <td>This is just an example task displaying for task 5</td>
                                    <td><span class="label label-primary">Assigned</span></td>
                                    <td><a href="#">Details <i class="fa fa-arrow-right"></i></a></td>
                                </tr>
                            </table>
                            <span class="pull-right" style="padding:5px;">Showing 19 of 46</span>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-aqua-gradient">
                        <span class="info-box-icon-fix"><i style="position: relative;top:22px;" class="fa fa-graduation-cap"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">GRADUATION PROGRESS</span>
                            <span class="info-box-number">32/46 Task Complete</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 70%"></div>
                            </div>
                              <span class="progress-description">
                                70% Complete
                              </span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Reports</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label>Types</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select a report type">
                                    <option>Complete Overview</option>
                                    <option>Missing Tasks</option>
                                    <option>Upcoming Tasks</option>
                                    <option>Some other report</option>
                                    <option>Report type 2</option>
                                    <option>Report</option>
                                </select>
                            </div><!-- /.form-group -->
                            <button type="submit" class="btn btn-success btn-block btn-flat">Generate</button>
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