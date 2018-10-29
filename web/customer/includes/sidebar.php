<?php
require_once 'core/init.php';


?>

<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar">
            <img src="../../common/img/user.png" alt="..." class="img-fluid rounded-circle">
        </div>
        <div class="title">
            <h1 class="h5"><?php echo escape($user->name());?></h1>
        </div>
    </div>
    <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="active"><a href="index.php"> <i class="icon-home"></i><?php echo Translate::t($lang, 'Home'); ?> </a></li>
        <li><a href="tables.php"> <i class="icon-grid"></i><?php echo Translate::t($lang, 'All_employees'); ?></a></li>
        <li><a href="user_data.php"> <i class="icon-grid"></i><?php echo Translate::t($lang, 'Employees_details'); ?></a></li>
<!--        <li><a href="charts.php"> <i class="fa fa-bar-chart"></i>Data charts </a></li>-->
        <li><a href="#update" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i><?php echo Translate::t($lang, 'Update_db'); ?></a>
            <ul id="update" class="collapse list-unstyled ">
                <li><a href="update_database.php"><i class="icon-dashboard"></i><?php echo Translate::t($lang, 'Update_db'); ?></a></li>
                <li><a href="update_users_profile.php"> <i class="icon-user"></i><?php echo Translate::t($lang, 'Update_employees_profile'); ?> </a></li>
            </ul>
        </li>
        <li><a href="#profile" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i><?php echo Translate::t($lang, 'Profile'); ?> </a>
            <ul id="profile" class="collapse list-unstyled ">
                <li><a href="alldata.php"><i class="icon-settings"></i><?php echo Translate::t($lang, 'my_profile'); ?></a></li>
                <li><a href="logout.php"> <i class="icon-logout"></i><?php echo Translate::t($lang, 'logout'); ?> </a></li>
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
