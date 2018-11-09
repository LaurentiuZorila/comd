<?php
require_once 'core/init.php';

$allLeads = $backendUserProfile->records(Params::TBL_TEAM_LEAD, ['departments_id', '=', $backendUser->departmentId()], ['id', 'name', 'offices_id', 'departments_id']);

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
                    <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'All_staff'); ?></h2>
                </div>
            </div>
            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'All_staff'); ?></li>
                </ul>
            </div>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <?php foreach ($allLeads as $lead) {
                            $rating = $backendUserProfile->rating(['user_id', '=', $lead->id]);
                            ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center"><h1 class="dashtext-1"><?php echo Common::makeAvatar($lead->name); ?></h1>
                                        <div class="media-body overflow-hidden">
                                            <h3 class="card-text mb-0 text-center dashtext-2" style="color: #9055A2;"><?php echo $lead->name; ?></h3>
                                            <p class="card-text mb-0 text-uppercase font-weight-bold text-secondary text-center"><?php echo $backendUserProfile->records(Params::TBL_DEPARTMENT, ['id', '=', $lead->departments_id], ['name'], false)->name; ?></p>
                                            <p class="card-text mb-3 text-uppercase font-weight-bold text-secondary text-center"><?php echo $backendUserProfile->records(Params::TBL_OFFICE, ['id', '=', $lead->offices_id], ['name'], false)->name; ?></p>
                                            <p class="card-text mb-0 font-weight-bold text-secondary text-center"><?php echo Translate::t($lang, 'Rating'); ?></p>
                                            <p class="card-text m-b-0 font-weight-bold text-secondary text-center">
                                                <?php switch ($rating) {
                                                    case '1':
                                                        include 'rating/one_star.php';
                                                        break;
                                                    case '2':
                                                        include 'rating/two_star.php';
                                                        break;
                                                    case '3':
                                                        include 'rating/three_star.php';
                                                        break;
                                                    case '4':
                                                        include 'rating/four_star.php';
                                                        break;
                                                    case '5':
                                                        include 'rating/five_star.php';
                                                        break;
                                                    default:
                                                        include 'rating/default.php';
                                                        break;
                                                } ?>
                                                <span class=""><?php echo $rating; ?>/5</span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="staff_profile.php?office_id=<?php echo $lead->offices_id;?>&lead_id=<?php echo $lead->id; ?>&token=<?php echo ''; ?>&lang=<?= $lang; ?> " class="tile-link"></a>
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