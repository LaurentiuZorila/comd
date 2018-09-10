<?php
require_once 'core/init.php';
$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
// All users and staf for one department
$allStaff = DB::getInstance()->get('cmd_users', $where = ['supervisors_id', '=', $user->userId()])->results();
$allUsers = DB::getInstance()->get('cmd_employees', $where = ['departments_id', '=', $user->userId()])->results();


if (Input::exists()) {
//    if (Token::check(Input::post('token'))) {
        // Tables prefix
//        $prefix         = DB::getInstance()->get('cmd_prefix', $where=[], ['prefix'])->results();
//        $prefix         = trim($prefix[0]->prefix);
        $prefix         = 'cmd_';
        $customer_id    = Input::post('customer');
        $year           = Input::post('year');
        $month          = Input::post('month');
        $id             = Input::post('employees');
        $team           = Input::post('teams');
        $table          = $prefix . strtolower(Input::post('tables'));
        $errors         = [];
        $errorsNoData   = [];

        if (empty($year) || empty($month) || empty($id) || empty($team)) {
            $errors = [1];
        }

        if (count($errors) == 0) {
            // All tables
            $employeesDetails    = DB::getInstance()->get('cmd_employees', ['id', '=', $id], ['offices_id', 'name', 'user_id'])->first();
            $allOfficesData     = DB::getInstance()->get('cmd_offices', array('id', '=', $employeesDetails->offices_id))->first();
//            $teamLeader         = DB::getInstance()->get('cmd_users', ['id', '=', $employeesDetails->user_id], ['name'])->first();
//            $teamLeaderName     = $teamLeader->name;
            $teamLeaderName     = Tables::getDetails('cmd_users', ['id', '=', $employeesDetails->user_id], 'name');
            $employeesName       = $employeesDetails->name;
            $monthName          = Profile::getMonthsList()[$month];

            // arrays with tables (with and without prefix)
            foreach (Values::toArray($allOfficesData->tables) as $value) {
                $noPrefixTables[] = $value;
                $tables[] = $prefix . trim($value);
            }

            // Conditions for action
            $where = [
                ['year', '=', $year],
                'AND',
                ['customer_id', '=', $id],
                'AND',
                ['month', '=', $month]
            ];

            // array with tables without prefix
            foreach ($noPrefixTables as $noPrefixTable) {
                $key[]  = $noPrefixTable;
            }

            foreach ($tables as $table) {
                // quantity for all tables
                $values[] = Values::columnValues(DB::getInstance()->get($table, $where, ['quantity'])->results(), 'quantity');
            }

            // Array with tables and values(quantity)
            $allData = array_combine($key, $values);

            if (!Values::checkValues($allData)) {
                $errorsNoData = [1];
            }
        }
//    }
}


// FOR GET
if (!empty(Input::get('customer_id')) && !Input::exists()) {

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
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Users</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ul>
        </div>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
                <div class="block">
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title"><strong>Filters</strong></div>
                            </div>
                            <div class="col-sm-6">
                                <select name="teams" class="form-control <?php if (Input::exists() && empty(Input::post('teams'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Team</option>
                                    <?php foreach ($allStaff as $staff) { ?>
                                        <option value="<?php echo $staff->id; ?>"><?php echo $staff->name; ?> (<small><?php echo DB::getInstance()->get('cmd_offices', ['id', '=', $staff->offices_id])->first()->name; ?></small>)</option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('teams'))) { ?>
                                    <div class="invalid-feedback mb-3">Please select team.</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-6">
                                <select name="employees" id="#employees" class="form-control <?php if (Input::exists() && empty(Input::post('employees'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Employees</option>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('employees'))) { ?>
                                    <div class="invalid-feedback mb-3 ">Please select employees.</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-6">
                                <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Year</option>

                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback mb-3">Please select year.</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-6">
                                <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Month</option>
                                    <?php foreach (Profile::getMonthsList() as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('month'))) { ?>
                                    <div class="invalid-feedback mb-3">Please select month.</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-2">
                                <input value="Submit" class="btn btn-outline-secondary" type="submit">
                                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
<?php if (Input::exists()) {
    if (count($errorsNoData) == 0 && count($errors) == 0) { ?>
        <section>
            <div class="col-md-12">
                <div class="card text-white bg-dark">
                    <div class="card-header card-header-transparent text-center">Team
                        Leader: <?php echo $teamLeaderName; ?></div>
                    <div class="card-body">
                        <h5 class="card-title text-center"><?php echo $employeesName; ?></h5>
                        <p class="card-text text-center"><?php echo 'Data for month ' . $monthName . ', year ' . Input::post('year'); ?></p>
                    </div>
                </div>
            </div>
        </section>
        <section class="no-padding-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="drills-chart block">
                            <div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"
                                 class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand"
                                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                     style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas id="all_data" style="display: block; width: 494px; height: 247px;"
                                    width="494" height="147" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="stats-2-block block d-flex">
                            <?php foreach ($allData as $key => $value) {
                                $value = (empty($value) ? 0 : $value); ?>
                                <div class="stats-2 d-flex">
                                    <div class="stats-2-arrow low"><i class="fa fa-caret-down"></i></div>
                                    <div class="stats-2-content"><strong
                                                class="d-block"><?php echo $value; ?></strong><span
                                                class="d-block"><?php echo strtoupper($key); ?></span>
                                        <div class="progress progress-template progress-small">
                                            <div role="progressbar" style="width: <?php echo $value; ?>%;"
                                                 aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                                                 class="progress-bar progress-bar-template progress-bar-small dashbg-2"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
?>
    </div>
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
<script src="js/charts-home.js"></script>
<script src="js/front.js"></script>
<!--  Sweet alert   -->
<script src="sweetalert/dist/sweetalert2.min.js"></script>
<?php
include 'includes/ajax.php';

if (Input::exists() && count($errors) == 0 && count($errorsNoData) == 0) {
    include 'charts/employees_data_chart.php';
}
// If selected option not return data, alert
if (Input::exists()) {
    if (count($errors) > 0) {
        include 'notification/error.php';
    }
}

// If get not return data, alert
if (Input::exists('get') && !Input::exists()) {
    if (count($errorsNoData) > 0 && count($errors) == 0) {
        include 'notification/get_not_found.php';
    }
}

// If search not return data, alert
if (Input::exists()) {
    if (count($errorsNoData) > 0 && count($errors) == 0) {
        include 'notification/post_not_found.php';
    }
}

?>

</body>
</html>