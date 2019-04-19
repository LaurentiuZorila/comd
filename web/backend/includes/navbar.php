<?php
$notificationCount = $backendDB->get(Params::TBL_NOTIFICATION, AC::where([['departments_id', $backendUser->departmentId()], ['supervisors_view', false]]))->count();
$notificationData = $backendUserProfile->records(Params::TBL_NOTIFICATION, AC::where([['departments_id', $backendUser->departmentId()], ['supervisors_view', false]]), ['supervisors_message','user_id', 'id', 'date'], true, ['ORDER BY' => 'date DESC']);
if (Input::existsName('get', 'notificationId')) {
    $id = Input::get('notificationId');
    if ($id == 0) {
        $backendDB->update(Params::TBL_NOTIFICATION,
            [
                'supervisors_view' => 1
            ], [
                'departments_id'   => $backendUser->departmentId()
            ]);
    } else {
        $backendDB->update(Params::TBL_NOTIFICATION,
            [
                'supervisors_view' => 1
            ], [
                'id'   => $id
            ]);
    }
}
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
                <!-- Navbar Header--><a href="<?php echo Config::get('route/home', true); ?>" class="navbar-brand">
                    <div class="brand-text brand-big visible text-uppercase"><strong class="text-primary">Cmd</strong><strong>Dashbord</strong></div>
                    <div class="brand-text brand-sm"><strong class="text-primary">D</strong><strong>A</strong></div></a>
                <!-- Sidebar Toggle Btn-->
                <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
            </div>
            <div class="right-menu list-inline no-margin-bottom">
                <!--                <div class="list-inline-item"><a href="#" class="search-open nav-link"><i class="icon-magnifying-glass-browser"></i></a></div>-->
                <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink1" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link messages-toggle"><i class="fa fa-envelope"></i><span class="badge dashbg-1"><?php echo $notificationCount > 0 ? $notificationCount : ''; ?></span></a>
                    <?php if ($notificationCount > 4) { ?>
                    <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages" style="height:419px; overflow-y: scroll;">
                        <?php } else { ?>
                        <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages">
                            <?php } ?>
                            <?php if ($notificationCount > 0) {
                                foreach ($notificationData as $notification) { ?>
                                    <a href="<?php echo Config::get('route/emplData'); ?>?status=2&notificationId=<?php echo $notification->id; ?>" class="dropdown-item message d-flex align-items-center">
                                        <div class="profile"><img src="./../common/img/user.png" alt="..." class="img-fluid">
                                            <div class="status online"></div>
                                        </div>
                                        <div class="content">
                                            <span class="d-block"><?php echo Translate::t($notification->supervisors_message, ['ucfirst']); ?></span>
                                            <?php if ($notification->user_id > 0) { ?>
                                            <small class="date d-block"><?php echo Translate::t('employee', ['ucfirst']) . ': ' . $backendUserProfile->records(Params::TBL_EMPLOYEES, AC::where(['id', $notification->user_id]), ['name'], false)->name; ?></small>
                                            <small class="date d-block"><?php echo Translate::t('Date', ['ucfirst']) .': ' . $notification->date; ?></small>
                                            <?php } ?>
                                        </div>
                                    </a>
                                <?php } ?>
                                <a href="?notificationId=<?php echo 0; ?>" class="dropdown-item text-center message"> <strong><?php echo Translate::t('mark_as_read', ['ucfirst']);?> <i class="fa fa-flag-checkered dashtext-1"></i></strong></a>
                                <?php
                            } else { ?>
                                <a href="javascript:;" class="dropdown-item text-center message"><strong><?php echo Translate::t('notification_not_found', ['ucfirst']); ?></strong></a>
                            <?php } ?>
                        </div>
                    </div>
                <!-- Megamenu-->
                <div class="list-inline-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="nav-link">Mega <i class="fa fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu megamenu">
                        <div class="row">
                            <div class="col-lg-2 col-md-4"><strong class="text-uppercase"><?php echo Translate::t('view_employees', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('view_employees_nav_details'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-4"><strong class="text-uppercase"><?php echo Translate::t('navEmplData', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('view_employees_nav_data_details'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-4"><strong class="text-uppercase"><?php echo Translate::t('view_staff', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('view_staff_nav_details'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('Update_user_profile', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('move_staff_nav'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('my_profile', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('move_staff_nav'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('logout', ['strtoupper']); ?></strong>
                            </div>
                        </div>
                        <div class="row megamenu-buttons text-center">
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/employees', true); ?>" class="d-block megamenu-button-link dashbg-1"><i class="icon-list" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/emplData', true); ?>" class="d-block megamenu-button-link dashbg-4"><i class="icon-chart" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/allStaff', true); ?>" class="d-block megamenu-button-link dashbg-2"><i class="icon-user-1" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/updateStaffProfile', true); ?>" class="d-block megamenu-button-link dashbg-3"><i class="icon-new-file" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/updateProfile', true); ?>" class="d-block megamenu-button-link dashbg-1"><i class="icon-settings" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/logout'); ?>" class="d-block megamenu-button-link dashbg-4"><i class="icon-logout" style="font-size: 20px;"></i></a></div>
                        </div>
                    </div>
                </div>
                <!-- Megamenu end     -->
                <!-- Languages dropdown    -->
                <div class="list-inline-item dropdown">
                    <?php
                    if ($lang === 'en') { ?>
                    <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                        <img src="./../common/img/flag/_england.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t('english'); ?></span>
                    </a>
                    <?php } elseif ($lang === 'ro') {  ?>
                    <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                        <img src="/../common/img/flag/RO.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t('romanian'); ?></span>
                    </a>
                    <?php } elseif ($lang === 'it') { ?>
                    <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                        <img src="/../common/img/flag/IT.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t('italian'); ?></span>
                    </a>
                    <?php } ?>
                    <div aria-labelledby="languages" class="dropdown-menu">
                        <a rel="nofollow" href="language.php?lang=2" class="dropdown-item">
                            <img src="/../common/img/flag/IT.png" alt="English" class="mr-2"><span><?php echo Translate::t('italian'); ?><small> (not present)</small></span>
                        </a>
                        <a rel="nofollow" href="language.php?lang=3" class="dropdown-item">
                            <img src="/../common/img/flag/RO.png" alt="English" class="mr-2"><span><?php echo Translate::t('romanian'); ?><small> (not present)</small></span>
                        </a>
                        <a rel="nofollow" href="language.php?lang=1" class="dropdown-item">
                            <img src="/../common/img/flag/_england.png" alt="English" class="mr-2"><span><?php echo Translate::t('english'); ?><small></small></span>
                        </a>
                    </div>
                </div>
                <!-- Log out               -->
                <div class="list-inline-item logout">
                    <a id="logout" href="<?php echo Config::get('route/logout'); ?>" class="nav-link"><?php echo Translate::t('logout'); ?> <i class="icon-logout"></i></a>
                </div>
            </div>
        </div>
    </nav>
</header>
