<?php
require_once 'core/init.php';
$user  = new BackendUser();
$data  = new BackendProfile();
$token = new Token();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
$allLeads = $data->records(Params::TBL_TEAM_LEAD, ['departments_id', '=', $user->departmentId()], ['id', 'name', 'offices_id', 'departments_id']);

?>



<!DOCTYPE html>
<html>
<?php
include '../common/includes/head.php';
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
                        <?php foreach ($allLeads as $lead) {
                            $rating = $data->rating(['user_id', '=', $lead->id]);
                            ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media align-items-center"><h1 class="avatar avatar-xl mr-3 text-monospace"><?php echo Common::makeAvatar($lead->name); ?></h1>
                                        <div class="media-body overflow-hidden">
                                            <h3 class="card-text mb-0 text-center" style="color: #9055A2;"><?php echo $lead->name; ?></h3>
                                            <p class="card-text mb-0 text-uppercase font-weight-bold text-secondary text-center"><?php echo $data->records(Params::TBL_DEPARTMENT, ['id', '=', $lead->departments_id], ['name'], false)->name; ?></p>
                                            <p class="card-text mb-3 text-uppercase font-weight-bold text-secondary text-center"><?php echo $data->records(Params::TBL_OFFICE, ['id', '=', $lead->offices_id], ['name'], false)->name; ?></p>
                                            <p class="card-text mb-0 font-weight-bold text-secondary text-center">Rating</p>
                                            <p class="card-text m-b-0 font-weight-bold text-secondary text-center">
                                                <?php switch ($rating) {
                                                    case '0':
                                                        include 'rating/default.php';
                                                        break;
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
                                    <a href="staff_profile.php?office_id=<?php echo $lead->offices_id;?>&lead_id=<?php echo $lead->id; ?>&token=<?php echo $token->getToken(); ?> " class="tile-link"></a>
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