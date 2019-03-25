<?php
require_once 'core/init.php';
$rating = $leadData->rating($lead->customerId());
?>
<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar">
            <img src="../../common/img/user.png" alt="..." class="img-fluid rounded-circle">
        </div>
        <div class="title">
            <h1 class="h5 mb-4"><?php echo escape($lead->name());?></h1>
            <div class="contributions text-monospace text-center">
                <?php
                for ($i=1;$i<6;$i++) {
                    if ($i <= $rating) { ?>
                        <a class="text-primary" href="#"><span class="fa fa-star checked"></span></a>
                    <?php } else { ?>
                        <a class="text-secondary" href="#"><span class="fa fa-star"></span></a>
                    <?php }
                } ?>
                <a class="text-white-50" href="#"><?php echo $rating . '/5'; ?></a>
            </div>
        </div>
    </div>
    <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="active"><a href="<?php echo Config::get('route/home'); ?>"> <i class="icon-home"></i><?php echo Translate::t('Home', ['ucfirst']); ?> </a></li>
        <li><a href="<?php echo Config::get('route/allUsers'); ?>"> <i class="icon-user"></i><?php echo Translate::t('All_employees', ['ucfirst']); ?></a></li>
        <li><a href="<?php echo Config::get('route/uData'); ?>"> <i class="icon-chart"></i><?php echo Translate::t('Employees_details', ['ucfirst']); ?></a></li>
        <li><a href="<?php echo Config::get('route/bestEmployee'); ?>"> <i class="icon-user-1"></i><?php echo Translate::t('Best_operator', ['ucfirst']); ?></a></li>

<!--        <li><a href="charts.php"> <i class="fa fa-bar-chart"></i>Data charts </a></li>-->
        <li><a href="#update" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i><?php echo Translate::t('Update_db', ['ucfirst']); ?></a>
            <ul id="update" class="collapse list-unstyled ">
                <li><a href="<?php echo Config::get('route/updateDb'); ?>"><i class="icon-dashboard"></i><?php echo Translate::t('Update_db', ['ucfirst']); ?></a></li>
                <li><a href="<?php echo Config::get('route/updateUProf'); ?>"> <i class="icon-user"></i><?php echo Translate::t('Update_employees_profile', ['ucfirst']); ?> </a></li>
                <li><a href="<?php echo Config::get('route/employeeStatus'); ?>"> <i class="icon-form-1"></i><?php echo Translate::t('change_status', ['ucfirst']); ?> </a></li>
                <li><a href="<?php echo Config::get('route/addEmployees'); ?>"> <i class="icon-user-outline"></i><?php echo Translate::t('add_employees', ['ucfirst']); ?> </a></li>
                <li><a href="<?php echo Config::get('route/addUser'); ?>"> <i class="icon-user-1"></i><?php echo Translate::t('add_user', ['ucfirst']); ?> </a></li>
            </ul>
        </li>
        <li><a href="#profile" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i><?php echo Translate::t('Profile', ['ucfirst']); ?> </a>
            <ul id="profile" class="collapse list-unstyled ">
                <li><a href="<?php echo Config::get('route/updateMyProfile'); ?>"><i class="icon-settings"></i><?php echo Translate::t('my_profile', ['ucfirst']); ?></a></li>
                <li><a href="<?php echo Config::get('route/logout'); ?>"> <i class="icon-logout"></i><?php echo Translate::t('logout', ['ucfirst']); ?> </a></li>
            </ul>
        </li>
        <li><a href="<?php echo Config::get('route/calendar'); ?>"> <i class="fa fa-calendar"></i><?php echo Translate::t('calendar', ['ucfirst']); ?></a></li>
    </ul>
<!--    <span class="heading">Extras</span>-->
<!--    <ul class="list-unstyled">-->
<!--        <li> <a href="#"> <i class="icon-settings"></i>Demo </a></li>-->
<!--        <li> <a href="#"> <i class="icon-writing-whiteboard"></i>Demo </a></li>-->
<!--        <li> <a href="#"> <i class="icon-chart"></i>Demo </a></li>-->
<!--    </ul>-->
</nav>
