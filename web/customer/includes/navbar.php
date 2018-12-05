<?php

$notificationCount = $leadData->count(Params::TBL_NOTIFICATION, ActionCond::where([['lead_id', $lead->customerId()], ['response_status', false]]));
$notificationData = $leadData->records(Params::TBL_NOTIFICATION, ActionCond::where([['lead_id', $lead->customerId()], ['response_status', false]]), ['message', 'user_id']);

?>

<header class="header">
    <nav class="navbar navbar-expand-lg">
        <!--        <div class="search-panel">-->
        <!--            <div class="search-inner d-flex align-items-center justify-content-center">-->
        <!--                <div class="close-btn">Close <i class="fa fa-close"></i></div>-->
        <!--                <form id="searchForm" action="#">-->
        <!--                    <div class="form-group">-->
        <!--                        <input type="search" name="search" placeholder="What are you searching for...">-->
        <!--                        <button type="submit" class="submit">Search</button>-->
        <!--                    </div>-->
        <!--                </form>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="navbar-header">
                <!-- Navbar Header--><a href="index.php" class="navbar-brand">
                    <div class="brand-text brand-big visible text-uppercase"><strong class="text-primary">Cmd</strong><strong>Dashbord</strong></div>
                    <div class="brand-text brand-sm"><strong class="text-primary">D</strong><strong>A</strong></div></a>
                <!-- Sidebar Toggle Btn-->
                <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
            </div>
            <div class="right-menu list-inline no-margin-bottom">
                <!--                <div class="list-inline-item"><a href="#" class="search-open nav-link"><i class="icon-magnifying-glass-browser"></i></a></div>-->
                <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink1" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link messages-toggle"><i class="fa fa-envelope"></i><span class="badge dashbg-1"><?php echo $notificationCount > 0 ?: ''; ?></span></a>
                    <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages">
                        <?php if ($notificationCount > 0) {
                            foreach ($notificationData as $notification) { ?>
                                <a href="calendar.php" class="dropdown-item message d-flex align-items-center">
                                    <div class="profile">
                                        <div class="status online"></div>
                                    </div>
                                    <div class="content">
                                        <strong class="d-block text-primary">
                                            <?php echo Translate::t($lang, [$notification->message, 'from'], ['ucfirst'=>true]) . ' ' . $leadData->records(Params::TBL_EMPLOYEES, ActionCond::where(['id', $notification->user_id]), ['name'], false)->name; ?>    <i class="fa fa-angle-right"></i>
                                        </strong>
                                    </div>
                                </a>
                            <?php }
                        } else { ?>
                            <a href="calendar.php" class="dropdown-item text-center message"><strong><?php echo Translate::t($lang, 'notification_not_found', ['ucfirst' => true]); ?></strong></a>
                        <?php } ?>

                    </div>
                </div>
                <!-- Tasks-->

                <!-- Tasks end-->
                <!-- Megamenu-->
                <div class="list-inline-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="nav-link">Mega <i class="fa fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu megamenu">
                        <div class="row">
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'All_employees', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'view_employees_nav_details', ['ucfirst' => true]); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'employees_data', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'view_employees_nav_data_details', ['ucfirst' => true]); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'update_data', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'update_db_navbar', ['ucfirst' => true]); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'Update_user_profile', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'move_staff_nav', ['ucfirst' => true]); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'my_profile', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'update_profile_nav', ['ucfirst' => true]); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'calendar', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'nav_give_calendar', ['ucfirst' => true]); ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row megamenu-buttons text-center">
                            <div class="col-lg-2 col-md-4"><a href="tables.php" class="d-block btn-dark"><i class="icon-user" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="user_data.php" class="d-block btn-dark"><i class="icon-chart" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="update_database.php" class="d-block btn-dark"><i class="icon-three-cells" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="update_users_profile.php" class="d-block btn-dark"><i class="icon-flow-branch" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="update_profile.php" class="d-block btn-dark"><i class="icon-settings" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="calendar.php" class="d-block btn-dark"><i class="icon-contract" style="font-size: 20px;"></i></a></div>
                        </div>
                    </div>
                </div>
                <!-- Megamenu end     -->
                <!-- Languages dropdown    -->
                <div class="list-inline-item dropdown">
                    <?php
                    if ($lang === 'en') { ?>
                        <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                            <img src="./../common/img/flag/_england.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t($lang, 'english'); ?></span>
                        </a>
                    <?php } elseif ($lang === 'ro') {  ?>
                        <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                            <img src="/../common/img/flag/RO.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t($lang, 'romanian'); ?></span>
                        </a>
                    <?php } elseif ($lang === 'it') { ?>
                        <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                            <img src="/../common/img/flag/IT.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t($lang, 'italian'); ?></span>
                        </a>
                    <?php } ?>
                    <div aria-labelledby="languages" class="dropdown-menu">
                        <a rel="nofollow" href="language.php?lang=2" class="dropdown-item">
                            <img src="/../common/img/flag/IT.png" alt="English" class="mr-2"><span><?php echo Translate::t($lang, 'italian'); ?><small> (not present)</small></span>
                        </a>
                        <a rel="nofollow" href="language.php?lang=3" class="dropdown-item">
                            <img src="/../common/img/flag/RO.png" alt="English" class="mr-2"><span><?php echo Translate::t($lang, 'romanian'); ?><small> (not present)</small></span>
                        </a>
                        <a rel="nofollow" href="language.php?lang=1" class="dropdown-item">
                            <img src="/../common/img/flag/_england.png" alt="English" class="mr-2"><span><?php echo Translate::t($lang, 'english'); ?><small></small></span>
                        </a>
                    </div>
                </div>
                <!-- Log out               -->
                <div class="list-inline-item logout">
                    <a id="logout" href="logout.php" class="nav-link"><?php echo Translate::t($lang, 'logout'); ?> <i class="icon-logout"></i></a>
                </div>
            </div>
        </div>
    </nav>
</header>
