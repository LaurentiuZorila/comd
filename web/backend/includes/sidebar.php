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
            <h1 class="h5"><?php echo $backendUser->name(); ?></h1>
        </div>
    </div>
    <!-- Sidebar Navidation Menus-->
    <ul class="list-unstyled">
        <li class="active"><a href="index.php?r=<?php echo Tokens::getRoute(); ?>" > <i class="icon-home"></i><?php echo Translate::t('Home'); ?> </a></li>
        <li><a href="employees.php?r=<?php echo Tokens::getRoute(); ?>" > <i class="icon-grid"></i><?php echo Translate::t('All_employees'); ?></a></li>
        <li><a href="employees_data.php?r=<?php echo Tokens::getRoute(); ?>"> <i class="icon-list"></i><?php echo Translate::t('employees_data'); ?></a></li>
        <li><a href="all_staff.php?r=<?php echo Tokens::getRoute(); ?>"> <i class="icon-user-1"></i><?php echo Translate::t('All_staff'); ?></a></li>
        <li><a href="#update" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i><?php echo Translate::t('update_data'); ?></a>
            <ul id="update" class="collapse list-unstyled ">
                <li><a href="update_users_profile.php?r=<?php echo Tokens::getRoute(); ?>"> <i class="icon-user"></i><?php echo Translate::t('Update_user_profile'); ?></a></li>
                <li><a href="add_users.php?r=<?php echo Tokens::getRoute(); ?>"> <i class="fa fa-user-plus"></i><?php echo Translate::t('add_user'); ?></a></li>
            </ul>
        </li>
        <li><a href="#profile" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i><?php echo Translate::t('Profile'); ?></a>
            <ul id="profile" class="collapse list-unstyled ">
                <li><a href="update_profile.php?r=<?php echo Tokens::getRoute(); ?>"><i class="icon-settings"></i><?php echo Translate::t('my_profile'); ?></a></li>
                <li><a href="logout.php"> <i class="icon-logout"></i><?php echo Translate::t('logout'); ?> </a></li>
            </ul>
        </li>
    </ul>
</nav>
