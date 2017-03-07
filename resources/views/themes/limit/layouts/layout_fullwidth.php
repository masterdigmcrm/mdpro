<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo env('COMPANY_NAME') ?></title>

    <!-- Global stylesheets -->
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">-->

    <link href="/themes/limit/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">

    <link href="/themes/limit/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/core.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/components.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/colors.css" rel="stylesheet" type="text/css">
    <link href="/css/plugins/fa/css/font-awesome.css" rel="stylesheet" type="text/css">
    <?php echo \Helpers\Layout::instance()->renderPageStyles() ?>
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/js/core/libraries/bootstrap.min.js"></script>
    <!--
    <script type="text/javascript" src="/plugins/vue/vue.1.0.26.min.js"></script>
    -->
    <script type="text/javascript" src="/plugins/vue/vue.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->

    <!-- /theme JS files -->

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-inverse" style="background-color: red">
    <div class="pull-right" style="padding: 12px">
        <a href="<?php echo Url('logout') ?>"><i class="icon-switch2" style="color: white"></i></a>

    </div>
    <div class="navbar-header">
        <a class="navbar-brand" href=""><img src="/themes/limit/images/logo_light.png" alt=""></a>
        <!--
        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a href="<?php echo Url('logout') ?>"><i class="icon-switch2"></i></a></li>
        </ul>
        -->
    </div>
</div>
<!-- /main navbar -->

<!-- Second navbar -->
<div class="navbar navbar-default" id="navbar-second">
    <?php //echo \App\Http\Controllers\NavBarsController::render( $user_type ); ?>
</div>
<!-- /second navbar -->

<!-- Page container -->
<div class="page-container" style="padding: 0;margin-top:8px;">

<!-- Page content -->
<div class="page-content">
    <?php echo $content ?>
</div>
<!-- /page content -->

</div>
<!-- /page container -->


<!-- Footer -->

<!-- /footer -->
<?php echo \Helpers\Layout::instance()->renderPageScripts(); ?>

</body>
</html>


