<?php
require_once 'core/init.php';
$user = new User();

$allUsersDetails = DB::getInstance()->get('cmd_employees', ['supervisors_id', '=', $user->userId()])->results();

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
                <h2 class="h5 no-margin-bottom">Tables</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">All users</li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong>All Employees</strong></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Team-Leader</th>
                                        <th>Team</th>
                                        <th>Department</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($allUsersDetails as $usersDetail) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $usersDetail->id; ?>"><?php echo $usersDetail->name; ?></a></td>
                                            <td><?php echo Tables::getDetails('cmd_users', ['id', '=', $usersDetail->user_id], 'name'); ?></td>
                                            <td><?php echo Tables::getDetails('cmd_offices', ['id', '=', $usersDetail->offices_id], 'name'); ?></td>
                                            <td><?php echo Tables::getDetails('cmd_departments', ['id', '=', $usersDetail->departments_id], 'name') ;?></td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
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
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="js/front.js"></script>

</body>
</html>