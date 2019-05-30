<?php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html>
<!--HEAD-->
<head>
    <?php
    include '../common/includes/head.php';
    ?>
</head>
<!--BODY-->
<body>
<?php
include 'includes/navbar.php';
?>
    <div class="d-flex align-items-stretch">
        <!-- Sidebar Navigation-->
        <?php
        include 'includes/sidebar.php';
        ?>
        <!-- Sidebar Navigation end-->
        <div class="page-content">
            <!-- Page Header-->
            <div class="page-header no-margin-bottom">
                <div class="container-fluid">
                    <h2 class="h5 no-margin-bottom"><?php echo Translate::t('All_staff'); ?></h2>
                </div>
            </div>
            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo Translate::t('All_staff'); ?></li>
                </ul>
            </div>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <?php foreach ($backendUserProfile->leadData() as $lead) {
                            $rating = $backendUserProfile->rating(AC::where(['user_id', $lead->id]));
                            ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center"><h1 class="dashtext-1"><?php echo Common::makeAvatar($lead->name); ?></h1>
                                        <div class="media-body overflow-hidden">
                                            <h3 class="card-text mb-0 text-center text-secondary" style="color: #9055A2;"><?php echo $lead->name; ?></h3>
                                            <p class="card-text mb-0 text-uppercase font-weight-bold text-secondary text-center"><?php echo $backendUserProfile->leadDepartName($lead->id); ?></p>
                                            <p class="card-text mb-3 text-uppercase font-weight-bold text-secondary text-center"><?php echo $backendUserProfile->leadOfficeName($lead->id); ?></p>
                                            <p class="card-text mb-0 font-weight-bold text-secondary text-center"><?php echo Translate::t('Rating'); ?></p>
                                            <p class="card-text m-b-0 font-weight-bold text-secondary text-center">
                                                <?php
                                                for ($i=1;$i<6;$i++) {
                                                    if ($i <= $rating) { ?>
                                                        <a class="dashtext-2" href="#"><span class="fa fa-star checked"></span></a>
                                                    <?php } else { ?>
                                                        <a class="text-secondary" href="#"><span class="fa fa-star"></span></a>
                                                    <?php }
                                                }
                                                ?>
                                                <span class=""><?php echo $rating; ?>/5</span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="<?php echo Config::get('route/staffProfile');?>?office_id=<?php echo $lead->offices_id;?>&lead_id=<?php echo $lead->id; ?>&token=<?php echo ''; ?>&lang=<?php echo $lang; ?> " class="tile-link"></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <?php
            include '../common/includes/footer.php';
            ?>
        </div>
    </div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";
?>


</body>
</html>