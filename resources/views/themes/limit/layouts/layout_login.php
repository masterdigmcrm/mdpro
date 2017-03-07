
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masterdigm Pro</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/core.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/components.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="/themes/limit/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="/themes/limit/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/themes/limit/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="/themes/limit/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->


    <!-- Theme JS files -->
    <script type="text/javascript" src="/themes/limit/js/core/app.js"></script>
    <!-- /theme JS files -->

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header" style="padding: 12px 0 0 12px">
        <a href="<?php echo Url('dashboard') ?>">
            <span style="font-size:18px;color:blue">Master</span><span style="font-size:18px;color:white">digm</span>
        </a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>


</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container login-container " >

    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper " >
            <!-- Content area -->
            <div class="content col-lg-4 col-lg-offset-4">
                <!-- Simple login form -->
                <form method="post" id="sForm">
                    <div class="panel panel-body login-form">
                        <?php if( session('error_message') ){ ?>
                            <div class="alert alert-danger">
                                <?php echo session('error_message') ?>
                            </div>
                        <?php } ?>
                        <div class="text-center">
                            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                            <h5 class="content-group">Login to your account</h5>
                        </div>
                        <div class="form-group has-feedback has-feedback-left">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group has-feedback has-feedback-left">
                            <input type="password" name="pass" id="pass" class="form-control" placeholder="Password">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
                        </div>

                        <div class="text-center">
                            <a href="<?php echo Url('forgot_password') ?>">Forgot password?</a>
                        </div>
                    </div>
                    <?php echo csrf_field() ?>
                </form>
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>
