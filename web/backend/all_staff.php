<?php
require_once 'core/init.php';
$allStaff = DB::getInstance()->get('cmd_users', $where = [])->results();

?>



<!DOCTYPE html>
<html>
<?php
include 'includes/head.php';
?>
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
                    <h2 class="h5 no-margin-bottom">All staff</h2>
                </div>
            </div>
            <!-- Breadcrumb-->
            <div class="container-fluid">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">All staff</li>
                </ul>
            </div>

            <section>
                <div class="container-fluid">
                    <div class="row">
                        <?php foreach ($allStaff as $staff) {
                            $rating = DB::getInstance()->average('cmd_rating', ['user_id', '=', $staff->id], 'rating')->results();
                            $rating = round(Values::columnValues($rating, 'average'));
                            ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center"><h1 class="avatar avatar-xl mr-3 text-monospace"><?php echo Profile::makeAvatar($staff->name); ?></h1>
                                        <div class="media-body overflow-hidden">
                                            <h3 class="card-text mb-0 text-center" style="color: #9055A2;"><?php echo $staff->name; ?></h3>
                                            <p class="card-text mb-0 text-uppercase font-weight-bold text-secondary text-center"><?php echo Values::columnValue(DB::getInstance()->get('cmd_departments', ['id', '=', $staff->supervisors_id], ['name'])->first()); ?></p>
                                            <p class="card-text mb-3 text-uppercase font-weight-bold text-secondary text-center"><?php echo Values::columnValue(DB::getInstance()->get('cmd_offices', ['id', '=', $staff->offices_id], ['name'])->first()); ?></p>
                                            <p class="card-text mb-0 font-weight-bold text-secondary text-center">Rating</p>
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
                                                    default:
                                                        include 'rating/default.php';
                                                        break;
                                                } ?>
                                                <span class=""><?php echo $rating; ?>/5</span>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="staff_profile.php?id=<?php echo $staff->id; ?>&token=<?php echo Token::generate(); ?> " class="tile-link"></a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <?php
            include 'includes/footer.php';
            ?>
        </div>
    </div>
<!-- JavaScript files-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper.js/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="js/front.js"></script>


</body>
</html>