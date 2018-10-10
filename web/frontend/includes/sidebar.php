<?php
require_once 'core/init.php';
$user           = new FrontendUser();
$details        = new FrontendProfile();

$name           = $user->name();
$officeName     = $details->records(Params::TBL_OFFICE, ['id', '=', $user->officeId()], ['name'], false);
$departmentName = $details->records(Params::TBL_DEPARTMENT, ['id', '=', $user->departmentId()], ['name'], false);

?>

<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar">
            <img src="../common/img/user.png" alt="..." class="img-fluid rounded-circle">
        </div>
        <div class="title">
            <h1 class="h5"><?php echo escape($name);?></h1>
            <p class="mt-1"><?php echo escape($departmentName->name); ?></p>
            <p><?php echo escape($officeName->name); ?></p>
        </div>
    </div>
    <!-- Sidebar Navidation Menus-->
    <span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="active"><a href="index.php?info=<?php echo Tokens::getToken(); ?>"> <i class="icon-home"></i>Home</a></li>
        <li><a href="feedback.php"> <i class="fa fa-star-half-empty"></i> Feedback </a></li>
        <li><a href="update_profile.php"> <i class="icon-user"></i>Update my profile </a></li>
        <li><a href="logout.php"> <i class="icon-logout"></i>Logout </a></li>
    </ul>
</nav>
