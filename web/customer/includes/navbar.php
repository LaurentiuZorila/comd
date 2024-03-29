<?php

$notificationCount = $leadData->count(Params::TBL_NOTIFICATION, AC::where([['lead_id', $lead->officesId()], ['response_status', false], ['view', 0]]));
$notificationData = $leadData->records(Params::TBL_NOTIFICATION, AC::where([['lead_id', $lead->officesId()], ['response_status', false], ['view', 0]]), ['message', 'user_id', 'date', 'id', 'title', 'days', 'form_supervisors'], true, ['ORDER BY' => 'date DESC']);
if (Input::existsName('get', 'notificationId')) {
    $id = Input::get('notificationId');
    $leadDb->update(Params::TBL_NOTIFICATION,
        [
            'view' => 1
        ], [
            'id'   => $id
        ]);
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
                <!-- Navbar Header--><a href="<?php echo Config::get('route/home'); ?>" class="navbar-brand">
                    <div class="brand-text brand-big visible text-uppercase">
                        <strong class="text-primary">Crud</strong><strong>Dashbord</strong>
                    </div>
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
                            foreach ($notificationData as $notification) {
                                $days = explode(',', $notification->days); ?>
                                <a href="<?php echo Config::get('route/calendar'); ?>?status=2&notificationId=<?php echo $notification->id; ?>" class="dropdown-item message d-flex align-items-center">
                                    <div class="profile"><img src="./../common/img/user.png" alt="..." class="img-fluid">
                                        <div class="status online"></div>
                                    </div>
                                    <div class="content">
                                        <?php if ($notification->form_supervisors) { ?>
                                            <span class="d-block"><?php echo Translate::t($notification->message, ['ucfirst']); ?></span>
                                            <small class="date d-block"><?php echo 'Added: ' . $notification->date; ?></small>
                                        <?php } else { ?>
                                            <span class="d-block"><?php echo Translate::t([$notification->message, 'from'], ['ucfirst']) . ' ' . $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $notification->user_id]), ['name'], false)->name; ?></span>
                                            <small class="date d-block text-white"><?php echo Translate::t(strtolower($notification->title)) . ': ' . current($days) . ' - ' . end($days); ?></small>
                                            <small class="date d-block"><?php echo 'Added: ' . $notification->date; ?></small>
                                        <?php } ?>
                                    </div>
                                </a>
                            <?php } ?>
                            <a href="javascript:;" class="dropdown-item text-center message markasread" data-lead="<?php echo $lead->officesId(); ?>"> <strong><?php echo Translate::t('mark_as_read', ['ucfirst']);?> <i class="fa fa-flag-checkered dashtext-1"></i></strong></a>
                            <?php
                        } else { ?>
                            <a href="<?php echo Config::get('route/calendar'); ?>" class="dropdown-item text-center message"><strong><?php echo Translate::t('notification_not_found', ['ucfirst']); ?></strong></a>
                        <?php } ?>
                        </div>
                    </div>

                <!-- Megamenu-->
                <div class="list-inline-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="nav-link">Mega <i class="fa fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu megamenu">
                        <div class="row">
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('All_employees', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('view_employees_nav_details', ['ucfirst']); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('employees_data', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('view_employees_nav_data_details', ['ucfirst']); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('update_data', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('update_db_navbar', ['ucfirst']); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('Update_user_profile', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('move_staff_nav', ['ucfirst']); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('my_profile', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('update_profile_nav', ['ucfirst']); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-6"><strong class="text-uppercase"><?php echo Translate::t('calendar', ['strtoupper']); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t('nav_give_calendar', ['ucfirst']); ?></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row megamenu-buttons text-center">
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/allUsers'); ?>" class="d-block megamenu-button-link dashbg-1"><i class="icon-user-1" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/uData'); ?>" class="d-block megamenu-button-link dashbg-4"><i class="icon-chart" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/updateDb'); ?>" class="d-block megamenu-button-link dashbg-2"><i class="icon-three-cells" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/updateUProf'); ?>" class="d-block megamenu-button-link dashbg-3"><i class="icon-flow-branch" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/updateMyProfile'); ?>" class="d-block megamenu-button-link dashbg-1"><i class="icon-settings" style="font-size: 20px;"></i></a></div>
                            <div class="col-lg-2 col-md-4"><a href="<?php echo Config::get('route/calendar'); ?>" class="d-block megamenu-button-link dashbg-4"><i class="icon-contract" style="font-size: 20px;"></i></a></div>
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
                        <a rel="nofollow" href="javascript:;" class="dropdown-item">
                            <img src="/../common/img/flag/IT.png" alt="English" class="mr-2"><span><?php echo Translate::t('italian'); ?><small> (not present)</small></span>
                        </a>
                        <a rel="nofollow" href="javascript:;" class="dropdown-item">
                            <img src="/../common/img/flag/RO.png" alt="English" class="mr-2"><span><?php echo Translate::t('romanian'); ?><small> (not present)</small></span>
                        </a>
                        <a rel="nofollow" href="javascript:;" class="dropdown-item">
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
