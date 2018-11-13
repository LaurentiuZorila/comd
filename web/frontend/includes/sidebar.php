<?php
require_once 'core/init.php';
$frontUser           = new FrontendUser();
$details        = new FrontendProfile();

$name           = $frontUser->name();
$officeName     = $details->records(Params::TBL_OFFICE, ['id', '=', $frontUser->officeId()], ['name'], false);
$departmentName = $details->records(Params::TBL_DEPARTMENT, ['id', '=', $frontUser->departmentId()], ['name'], false);

?>

<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar">
            <img src="../common/img/user.png" alt="..." class="img-fluid rounded-circle">
        </div>
        <div class="title">
            <h1 class="h5"><?php echo escape($name);?></h1>
            <p class="mt-1"><?php echo strtoupper(escape($departmentName->name)); ?></p>
            <p><?php echo strtoupper(escape($officeName->name)); ?></p>
        </div>
    </div>
    <!-- Sidebar Navidation Menus-->
    <span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="active"><a href="index.php"> <i class="icon-home"></i><?php echo Translate::t($lang, 'Home'); ?></a></li>
        <li><a href="feedback.php"> <i class="fa fa-star-half-full"></i> <?php echo Translate::t($lang, 'feedback'); ?> </a></li>
        <li><a href="calendar.php"> <i class="fa fa-calendar"></i> <?php echo Translate::t($lang, 'Calendar'); ?> </a></li>
        <li><a href="update_profile.php"> <i class="icon-user"></i><?php echo Translate::t($lang, 'Edit_profile'); ?> </a></li>
        <li><a href="logout.php"> <i class="icon-logout"></i><?php echo Translate::t($lang, 'logout'); ?> </a></li>
    </ul>
</nav>
