<?php
require_once 'core/init.php';
$user   = new BackendUser();
$data   = new BackendProfile();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

$user_id        = $user->userId();
$department_id  = $user->departmentId();

// All users and staf for one department
$allStaff   = $data->records(Params::TBL_TEAM_LEAD, ['supervisors_id', '=', $user_id], ['id', 'name', 'offices_id', 'supervisors_id']);
$offices    = $data->records(Params::TBL_OFFICE, ['departments_id', '=', $department_id], ['id', 'name']);
$allUsers   = $data->records(Params::TBL_EMPLOYEES, ['supervisors_id', '=', $user_id]);

if (Input::exists()) {
    $prefix         = Params::PREFIX;
    $customer_id    = Input::post('customer');
    $year           = Input::post('year');
    $month          = Input::post('month');
    $id             = Input::post('employees');
    $team           = Input::post('teams');
    $errors         = [];
    $errorsNoData   = [];

    if (empty($year) || empty($month) || empty($id) || empty($team)) {
        $errors = [1];
    }

    if (count($errors) == 0) {
        //Employees details
        $employeesData  = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $id], ['offices_id', 'name'], false);
        // Offices data
        $allOfficesData = $data->records(Params::TBL_OFFICE, ['id', '=', $employeesData->offices_id], ['name', 'tables'], false);
        //All Leads for selected office details
        $allLeads       = $data->records(Params::TBL_TEAM_LEAD, ['offices_id', '=', $employeesData->offices_id], ['name']);

        //Team Leads names
        foreach ($allLeads as $leads) {
            $leadsName[] = $leads->name;
        }
        // Employee name
        $employeesName  = $employeesData->name;
        //Month name
        $monthName      = Common::numberToMonth($month);

        // arrays with tables
        $tables = explode(',', trim($allOfficesData->tables));

        // array with tables without prefix
        foreach ($tables as $table) {
            $key[] = trim($table);
        }

        // Tables with prefix
        foreach ($tables as $table) {
            $prefixTables[] = Params::PREFIX . trim($table);
        }

        // Conditions for action
        $where = [
            ['year', '=', $year],
            'AND',
            ['employees_id', '=', $id],
            'AND',
            ['month', '=', $month]
        ];

        foreach ($prefixTables as $table) {
            // quantity for all tables
            if (is_null($data->records($table, $where, ['quantity'], false)->quantity)) {
                $values[] = 0;
            } else {
                $values[] = $data->records($table, $where, ['quantity'], false)->quantity;
            }
        }

        // Array with tables and values(quantity)
        $allData = array_combine($key, $values);

        $chartLabels = Js::key($allData);
        $chartValues = Js::values($allData);

        // Check if exists values for selected options
        if (!Common::checkValues($allData)) {
            $errorsNoData = [1];
        }
    }
}


// FOR GET
if (!empty(Input::get('employees_id')) && !Input::exists()) {

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
                <li class="breadcrumb-item active">Employees data</li>
            </ul>
        </div>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                        Filters
                    </button>
                </p>
                <div class="<?php if (Input::exists() && count($errors) == 0 && count($errorsNoData) == 0) { echo "collapse";} else { echo "collapse show"; } ?>" id="filter">
                    <div class="card card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title"><strong>Filters</strong></div>
                            </div>
                            <div class="col-sm-6">
                                <select name="teams" class="form-control <?php if (Input::exists() && empty(Input::post('teams'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Team</option>
                                    <?php foreach ($offices as $office) { ?>
                                        <option value="<?php echo $office->id; ?>"><?php echo $office->name; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('teams'))) { ?>
                                    <div class="invalid-feedback mb-3">Team field are required!</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-6">
                                <select name="employees" id="#employees" class="form-control <?php if (Input::exists() && empty(Input::post('employees'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Employees</option>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('employees'))) { ?>
                                    <div class="invalid-feedback mb-3 ">Employees field are required!</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-6">
                                <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Year</option>

                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback mb-3">Year field are required!</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-6">
                                <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Month</option>
                                    <?php foreach (Common::getMonths() as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('month'))) { ?>
                                    <div class="invalid-feedback mb-3">Month field are required!</div>
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
            </div>
        </section>
<?php
if (Input::exists() && count($errorsNoData) == 0 && count($errors) == 0) { ?>
        <section>
            <div class="col-md-12">
                <div class="card text-white bg-dark">
                    <div class="card-header card-header-transparent text-center"><?php echo $employeesName; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center"><?php echo $allOfficesData->name; ?></h5>
                        <p class="card-text text-center text-white-50"><?php echo 'Data for ' . $monthName . ', ' . Input::post('year'); ?></p>
                        <p class="card-text text-center text-white-50">Leads:
                            <?php
                            foreach ($leadsName as $leadName) {
                                if (count($leadsName) > 1) {
                                        $leadNames[] = $leadName . ' ';
                                } elseif (count($leadsName) < 2) {
                                    $leadNames[] = $leadName;
                                }
                            }
                            echo implode(', ', $leadsName);
                            ?>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-header pt-2">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item"><button class="btn btn-primary mr-1 bar" id="bar" type="button">Bar</button></li>
                            <li class="nav-item"><button class="btn btn-outline-primary line" id="line" type="button">Line</button></li>
                        </ul>
                    </div>
                    <div class="pie-chart chart block">
                        <div class="pie-chart chart margin-bottom-sm">
                            <div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas id="all_data_bar" style="display: block; width: 494px; height: 147px;" width="494" height="147" class="chartjs-render-monitor"></canvas>
                            <canvas id="all_data_line" style="display: none; width: 494px; height: 145px;" width="494" height="145" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="no-padding-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="stats-2-block block d-flex">
                            <?php foreach ($allData as $key => $value) {
                                $value = (empty($value) ? 0 : $value); ?>
                                <div class="stats-2 d-flex">
                                    <div class="stats-2-arrow low"><i class="fa fa-line-chart"></i></div>
                                    <div class="stats-2-content">
                                        <strong class="d-block dashtext-1"><?php echo $value; ?></strong>
                                        <span class="d-block"><?php echo strtoupper($key); ?></span>
                                        <div class="progress progress-template progress-small">
                                            <div role="progressbar" style="width: <?php echo $value; ?>%;"aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template progress-bar-small dashbg-2"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
            </div>
        </section>
    <?php } ?>
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
<script>
    $("#bar").click(function(){
        $('.line').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.bar').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#all_data_bar").show();
        $("#all_data_line").hide();
    });

    $("#line").click(function(){
        $('.bar').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.line').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#all_data_line").show();
        $("#all_data_bar").hide();
    });
</script>
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