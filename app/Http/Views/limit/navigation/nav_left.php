<?php
/**
 * Created by PhpStorm.
 * User: Dennis
 * Date: 7/27/2015
 * Time: 9:44 AM
 */
?>
<div class="page-sidebar navbar-collapse collapse">
<!-- BEGIN SIDEBAR MENU -->
<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<ul class="page-sidebar-menu page-sidebar-menu-light" data-keep-expanded="true" data-auto-scroll="true" data-slide-speed="200">
<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
<li class="sidebar-toggler-wrapper">
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <div class="sidebar-toggler">
    </div>
    <!-- END SIDEBAR TOGGLER BUTTON -->
</li>
<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->


<!--<li class="start active open">-->
<li class="start">
    <a href="javascript:;">
        <i class="icon-bulb"></i>
        <span class="title">Dashboard</span>
        <span class="selected"></span>
        <span class="arrow open"></span>
    </a>

</li>
<li class="start">
    <a href="<?php echo Url('leads') ?>">
        <i class="icon-users"></i>
        <span class="title">Leads</span>
    </a>
</li>
<li class="start">
    <a href="<?php echo Url('properties') ?>">
        <i class="icon-home"></i>
        <span class="title">Properties</span>
    </a>
</li>
<li class="start">
    <a href="<?php echo Url('settings') ?>">
        <i class="icon-wrench"></i>
        <span class="title">Tools and Settings</span>
    </a>
</li>
<li class="start">
    <a href="<?php echo Url('logout') ?>">
        <i class="icon-wrench"></i>
        <span class="title">Logout</span>
    </a>
</li>
</ul>
<!-- END SIDEBAR MENU -->
</div>