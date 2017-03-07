
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masterdigm PRO</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/core.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/components.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/colors.css" rel="stylesheet" type="text/css">
    <link href="/themes/limit/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/css/fa/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <?php echo \Helpers\Layout::instance()->renderPageStyles() ?>

</head>

<body class="">

<!-- Main navbar -->
<div class="navbar navbar-inverse">

    <div class="navbar-header" style="padding: 12px 0 0 12px">
        <a href="<?php echo Url('dashboard') ?>">
            <span style="font-size:18px;color:blue">Master</span><span style="font-size:18px;color:white">digm</span>
        </a>
        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img src="/themes/limit/images/image.png" alt="">
                    <span><?php echo \App\Models\Users\UserEntity::me()->name ?></span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
                    <li><a href="#"><span class="badge badge-warning pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                    <li><a href="<?php echo Url('logout') ?>"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

<!-- Page content -->
<div class="page-content">

<!-- Main sidebar -->
<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <a href="#" class="media-left">
                        <img src="<?php echo \App\Models\Users\UserEntity::me()->imagePath ? \App\Models\Users\UserEntity::me()->imagePath  :
                            '/themes/limit/images/image.png' ?>" class="img-circle img-sm" alt="">
                    </a>
                    <div class="media-body">
                        <span class="media-heading text-semibold"><?php echo \App\Models\Users\UserEntity::me()->name ?></span>
                    </div>

                    <div class="media-right media-middle">
                        <ul class="icons-list">
                            <li>
                                <a href="#"><i class="icon-cog3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <?php echo $sidebar ?>
        <!-- /main navigation -->

    </div>
</div>
<!-- /main sidebar -->


<!-- Main content -->
<div class="content-wrapper">

<!-- Page header -->
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo Url('/dashboard') ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <!--
            <li><a href="2_col.html">Starters</a></li>
            <li class="active">2 columns</li>
            -->
        </ul>

        <ul class="breadcrumb-elements">
            <li><a href="#"><i class="icon-comment-discussion position-left"></i>Messages</a></li>
            <li><a href="#"><i class="icon-earth position-left"></i>Notifications</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

<?php echo $content ?>

</div>
<!-- /content area -->

</div>
<!-- /main content -->

</div>
<!-- /page content -->

</div>
<!-- /page container -->
<!-- Core JS files -->
<script type="text/javascript" src="/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/plugins/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="/plugins/vue/vue-2.1.9.js"></script>
<script type="text/javascript" src="/plugins/toastr/toastr.min.js"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script type="text/javascript" src="/js/limit.min.js"></script>
<!-- /theme JS files -->
<?php echo \Helpers\Layout::instance()->renderPageScripts() ?>
</body>
</html>


