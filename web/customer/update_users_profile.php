<?php
require_once 'core/init.php';
$users = new User();
$allUsers = DB::getInstance()->get('users', ['user_id', '=', $users->userId()], ['name', 'customer_id', 'user_id', 'department'])->results();
$departments = DB::getInstance()->get('department', $where= [], ['user_id', 'name'])->results();
$userTables = DB::getInstance()->get('department', ['user_id', '=', $users->userId()], ['tables'])->results();


if (Input::exists()) {
    $user = Input::post('user');
    $department = Input::post('department');
    $errors = [];
    $newDepartmentName = DB::getInstance()->get('department', $where= ['user_id', '=', $department], ['name'])->results();

    if (empty($user) && empty($department)) {
        $errors[] = 1;
    }

    if (count($errors) == 0) {
        foreach (Values::tables($userTables) as $values) {
            $tables[] = trim($values);
        }

        $users->update('users', [
            'user_id' => $department,
            'department' => Profile::name($newDepartmentName)
        ], [
            'customer_id' => $user
        ]);

        foreach ($tables as $table) {
            $users->update($table, [
                'user_id' => $department,
                'department' => Profile::name($newDepartmentName)
            ], [
                'customer_id' => $user
            ]);
        }
    }
}


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
                <h2 class="h5 no-margin-bottom">Update user profile </h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a>
                </li>
                <li class="breadcrumb-item active">Update user profile
                </li>
            </ul>
        </div>
        <section>
            <section class="no-padding-top">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Form Elements -->
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong>Update user</strong></div>
                                <div class="block-body">
                                    <form class="form-horizontal" method="post">
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Select user</label>
                                            <div class="col-sm-9">
                                                <select name="user" class="form-control mb-3 mb-3">
                                                    <option value="">Select user</option>
                                                    <?php
                                                    foreach ($allUsers as $user) { ?>
                                                        <option value="<?php echo $user->customer_id; ?>"><?php echo $user->name; ?><small> (<?php echo  $user->department;?>)</small></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Select where to move</label>
                                            <div class="col-sm-9">
                                                <select name="department" class="form-control mb-3 mb-3">
                                                    <option value="">Select department</option>
                                                    <?php
                                                    foreach ($departments as $department) { ?>
                                                    <option value="<?php echo $department->user_id; ?>"><?php echo $department->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="col-sm-9 ml-auto">
                                            <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
<script src="vendor/popper.js/umd/popper.min.js"> </script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
<!--    <script src="vendor/chart.js/Chart.min.js"></script>-->
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="js/front.js"></script>
<!--  Sweet alert   -->
<script src="sweetalert/dist/sweetalert2.min.js"></script>

</body>
</html>