<?php
require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

?>

<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar"><img src="img/avatar-6.jpg" alt="..." class="img-fluid rounded-circle"></div>
        <div class="title">
            <h1 class="h5"><?php echo escape($user->data()->name);?></h1>
            <p><?php echo escape($user->data()->function); ?></p>
        </div>
    </div>
    <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="active"><a href="index.php"> <i class="icon-home"></i>Home </a></li>
        <li><a href="tables.php"> <i class="icon-grid"></i>Users Table</a></li>
        <li><a href="user_data.php"> <i class="icon-grid"></i>Users Data</a></li>
<!--        <li><a href="charts.php"> <i class="fa fa-bar-chart"></i>Data charts </a></li>-->
        <li><a href="#update" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Update data</a>
            <ul id="update" class="collapse list-unstyled ">
                <li><a href="update_database.php"><i class="icon-dashboard"></i>Update database</a></li>
                <li><a href="update_users_profile.php"> <i class="icon-user"></i>Update users </a></li>
            </ul>
        </li>
        <li><a href="#profile" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Profile</a>
            <ul id="profile" class="collapse list-unstyled ">
                <li><a href="alldata.php"><i class="icon-settings"></i>My profile</a></li>
                <li><a href="logout.php"> <i class="icon-logout"></i>Logout </a></li>
            </ul>
        </li>
    </ul>
<!--    <span class="heading">Extras</span>-->
<!--    <ul class="list-unstyled">-->
<!--        <li> <a href="#"> <i class="icon-settings"></i>Demo </a></li>-->
<!--        <li> <a href="#"> <i class="icon-writing-whiteboard"></i>Demo </a></li>-->
<!--        <li> <a href="#"> <i class="icon-chart"></i>Demo </a></li>-->
<!--    </ul>-->
</nav>
