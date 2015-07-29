<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="../library/Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../library/Handsworth-Graduation/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/Handsworth-Graduation/css/HandsworthGrad.css" rel="stylesheet" type="text/css"/>
    <link href="../library/iCheck/square/green.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="blur"></div>
    <div class="register-box-fix">
        <div class="register-logo">
            <a href="../index.php">Handsworth</a>
        </div>
        <div class="register-box-body">
            <div class="register-box-msg">
                Register with your school information.
            </div>
            <!-- TODO Add action -->
            <form method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Student ID" />
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Date of birth" />
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Re-type Password" />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Agree to the <a href="#">terms of use</a>
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat">Register</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <div class="row" style="margin-top: 50px;">
                <div class="col-xs-3"></div>
                <div class="col-xs-6"><a href="login.php" class="btn bg-maroon btn-block btn-sm btn-flat">Already Have Account</a></div>
                <div class="col-xs-3"></div>
            </div>
        </div>
    </div>

    <footer style="position: absolute;bottom:0px;background-color: white;padding: 10px;">
        <strong>Photo by Sara Freyvogel
    </footer>

    <script src="../library/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <script src="../library/Bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../library/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
        $(function(){
            $("input").iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: "iradio_square-green",
                increaseArea: '20%'
            });
        });
    </script>
</body>
</html>