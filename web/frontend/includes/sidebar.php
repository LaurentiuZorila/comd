<?php
require_once 'core/init.php';
$user = new User();
$details = new ProfileDetails();
$office = $details->officeDetails($user->officeId(), 'name');
$department = $details->departmentDetails($user->departmentId(), 'name');

?>

<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar"><img src="img/avatar-6.jpg" alt="..." class="img-fluid rounded-circle"></div>
        <div class="title">
            <h1 class="h5"><?php echo escape($user->name());?></h1>
            <p><?php echo escape($department); ?></p>
            <p><?php echo escape($office); ?></p>
        </div>
    </div>
    <!-- Sidebar Navidation Menus-->
    <span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="active"><a href="index.php?info=<?php echo Token::generate(); ?>"> <i class="icon-home"></i>Home </a></li>
        <li><a href="user_data.php"> <i class="icon-grid"></i>MyData</a></li>
        <li><a href="alldata.php"><i class="icon-settings"></i>My profile</a></li>
        <li><a href="up_employees_profile.php"> <i class="icon-user"></i>Update my profile </a></li>
        <li><a href="logout.php"> <i class="icon-logout"></i>Logout </a></li>
    </ul>
<!--    <span class="heading">Extras</span>-->
<!--    <ul class="list-unstyled">-->
<!--        <li> <a href="#"> <i class="icon-settings"></i>Demo </a></li>-->
<!--        <li> <a href="#"> <i class="icon-writing-whiteboard"></i>Demo </a></li>-->
<!--        <li> <a href="#"> <i class="icon-chart"></i>Demo </a></li>-->
<!--    </ul>-->
</nav>
