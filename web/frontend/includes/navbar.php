<?php

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
                <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink1" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link messages-toggle"><i class="fa fa-envelope"></i><span class="badge dashbg-1">1</span></a>
                    <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages">
                        <a href="#" class="dropdown-item message d-flex align-items-center">
                            <div class="profile">
                                <div class="status online"></div>
                            </div>
                            <div class="content"><strong class="d-block"><?php echo $user->name(); ?></strong><span class="d-block"><?php echo Translate::t($lang, 'navNotification'); ?></span>
                                <small class="date d-block"><?php echo date("h:i A"); ?></small>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item text-center message"><strong><?php echo Translate::t($lang, 'navEmplData'); ?><i class="fa fa-angle-right"></i></strong></a>
                        <a href="#" class="dropdown-item text-center message"><strong><?php echo Translate::t($lang, 'navStaffData'); ?><i class="fa fa-angle-right"></i></strong></a>
                    </div>
                </div>
                <!-- Tasks-->

                <!-- Tasks end-->
                <!-- Megamenu-->
                <div class="list-inline-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="nav-link">Mega <i class="fa fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu megamenu">
                        <div class="row">
                            <div class="col-lg-3 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'last_month_data_title', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><a href="#"><?php echo Translate::t($lang, 'last_month_data_content'); ?></a></li>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'give_feedback', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><?php echo Translate::t($lang, 'nav_give_feedback'); ?></li>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'my_profile', ['strtoupper' => true]); ?></strong>
                                <ul class="list-unstyled mb-3">
                                    <li><?php echo Translate::t($lang, 'nav_update_profile'); ?></li>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6"><strong class="text-uppercase"><?php echo Translate::t($lang, 'logout'); ?></strong>
                            </div>
                        </div>
                        <div class="row megamenu-buttons text-center">
                            <div class="col-lg-3 col-md-4"><a href="index.php?officeId=<?php echo $user->officeId(); ?>&userId=<?php echo $user->userId(); ?>&lastData=<?php echo Tokens::getRoute(); ?>" class="d-block megamenu-button-link dashbg-1"><i class="fa fa-bar-chart-o"></i><strong><?php echo Translate::t($lang, 'view_data')?></strong></a></div>
                            <div class="col-lg-3 col-md-4"><a href="feedback.php" class="d-block megamenu-button-link dashbg-4"><i class="fa fa-star-half-full"></i><strong><?php echo Translate::t($lang, 'feedback'); ?></strong></a></div>
                            <div class="col-lg-3 col-md-4"><a href="update_profile.php" class="d-block megamenu-button-link dashbg-2"><i class="icon-user"></i><strong><?php echo Translate::t($lang, 'my_profile'); ?></strong></a></div>
                            <div class="col-lg-3 col-md-4"><a href="logout.php" class="d-block megamenu-button-link dashbg-3"><i class="icon-logout"></i><strong><?php echo Translate::t($lang, 'logout'); ?></strong></a></div>
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
                    <?php } elseif (is_null($lang)) { ?>
                        <a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle">
                            <img src="./../common/img/flag/_england.png" alt="English"><span class="d-none d-sm-inline-block"><?php echo Translate::t($lang, 'english'); ?></span>
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
