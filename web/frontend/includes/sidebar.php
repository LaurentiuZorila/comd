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
        <li class="active"><a href="<?php echo Config::get('route/home'); ?>"> <i class="icon-home"></i><?php echo Translate::t('Home'); ?></a></li>
        <li><a href="<?php echo Config::get('route/feedback'); ?>"> <i class="fa fa-star-half-full"></i> <?php echo Translate::t('feedback'); ?> </a></li>
        <li><a href="<?php echo Config::get('route/calendar'); ?>"> <i class="fa fa-calendar"></i> <?php echo Translate::t('Calendar'); ?> </a></li>
        <li><a href="<?php echo Config::get('route/updateProfile'); ?>"> <i class="icon-user"></i><?php echo Translate::t('Edit_profile'); ?> </a></li>
        <li><a href="<?php echo Config::get('route/logout'); ?>"> <i class="icon-logout"></i><?php echo Translate::t('logout'); ?> </a></li>
    </ul>
</nav>
