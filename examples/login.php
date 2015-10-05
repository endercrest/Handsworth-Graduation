<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="../library/Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../library/Handsworth-Graduation/css/HandsworthGrad.css" rel="stylesheet" type="text/css"/>
    <link href="../library/Handsworth-Graduation/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="../library/iCheck/square/green.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="blur"></div>

    <div class="login-box-fix">
        <div class="login-logo">
            <a href="../index.php">Handsworth</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to access your information</p>
            <form method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Student ID" />
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" />
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>

            <div class="row" style="margin-top: 50px;">
                <div class="col-xs-4"></div>
                <div class="col-xs-4"><a href="register.php" class="btn bg-navy btn-block btn-sm btn-flat">Register</a></div>
                <div class="col-xs-4"></div>
            </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-xs-3"></div>
                <div class="col-xs-6"><a href="recover.php" class="btn bg-red btn-block btn-sm btn-flat">Forget Password?</a></div>
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