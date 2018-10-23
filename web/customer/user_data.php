<?php
require_once 'core/init.php';
$user   = new CustomerUser();
$data   = new CustomerProfile();


if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

/** All tables */
$allTables = $data->records(Params::TBL_OFFICE, ['id', '=', $user->officesId()], ['tables'], false);
$allTables = explode(',', trim($allTables->tables));

/** All employees for user */
$allEmployees = $data->records($data::TBL_EMPLOYEES, ['offices_id', '=', $user->officesId()], ['id', 'name']);


if (Input::exists()) {
    /** Instantiate validation class */
    $validate = new Validate();
    /** Validate  inputs */
    $validation = $validate->check($_POST, [
        'employees' => ['required'  => true],
        'year'      => ['required'  => true],
        'month'     => ['required'  => true]
    ]);

    /** If validation is passed */
    if ($validation->passed()) {
    /** Inputs */
        $employeesId    = Input::post('employees');
        $year           = Input::post('year');
        $month          = Input::post('month');
        $errorNoData    = [];

            /** Conditions for action */
            $where = [
                ['year', '=', $year],
                'AND',
                ['employees_id', '=', $employeesId],
                'AND',
                ['month', '=', $month]
            ];

            /** array key => values (keys are tables and values are numbers(quantity column)) */
            foreach ($allTables as $table) {
                $prefix     = $data::PREFIX;
                $key[]      = $table;
                $values[]   = $data->records($prefix . trim($table), $where, ['quantity'], false)->quantity;
                $allData    = array_combine($key, $values);
            }

            /** All data for customer */
            $employeesDetails = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $employeesId], ['name', 'offices_id'], false);

            $name       = $employeesDetails->name;
            $officeName = $data->records(Params::TBL_OFFICE, ['id', '=', $employeesDetails->offices_id], ['name'], false)->name;
            $initials   = Common::makeAvatar($name);

            /** Check if exists values */
            if (!Common::checkValues($allData)) {
                $errorNoData = [1];
            }
        }
}

/** If get exists and post doesn't exists */
if (Input::exists('get') && !Input::exists()) {
    $employeesId    = Input::get('id');
    $year           = date('Y');
    $month          = date('n');
    $errorsNoData   = [];

    /** Conditions for action */
    $where = [
        ['year', '=', $year],
        'AND',
        ['employees_id', '=', $employeesId],
        'AND',
        ['month', '=', $month]
    ];

    /** array key => values (keys are tables and values are numbers(quantity column)) */
    foreach ($allTables as $table) {
        $prefix     = $data::PREFIX;
        $key[]      = $table;
        $values[]   = Common::columnValues($data->records($prefix . $table, $where, ['quantity']), 'quantity');
        $allData    = array_combine($key, $values);
    }

    /** All data for customer */
    $employeesDetails   = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $employeesId], ['name', 'offices_id'], false);
    $officeObj          = $data->records(Params::TBL_OFFICE, ['id', '=', $employeesDetails->offices_id], ['name'], false);

    $name       = $employeesDetails->name;
    $officeName = $officeObj->name;
    $initials   = Common::makeAvatar($name);

    /** Check if exists values */
    if (!Common::checkValues($allData)) {
        $errorNoData = [1];
    }
}


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
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Employees data</li>
            </ul>
        </div>
        <?php
        if (Input::exists() && $validation->countErrors()) {
            include './../common/errors/validationErrors.php';
        }

        if (Input::exists() && count($errorNoData) > 0) {
            include './../common/errors/infoError.php';
        }
        ?>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                        Filters
                    </button>
                </p>
                <div class="<?php if (Input::exists() && !$validation->countErrors() && count($errorNoData) == 0) { echo "collapse";} else { echo "collapse show"; } ?>" id="filter">
                <div class="block">
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title"><strong>Filters</strong></div>
                            </div>
                            <div class="col-sm-4">
                                <select name="year" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Year</option>
                                    <?php
                                    foreach (Common::getYearsList() as $year) { ?>
                                        <option><?php echo $year; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback">Select year!</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-4">
                                <select name="month" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Month</option>
                                    <?php foreach (Common::getMonths() as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('month'))) { ?>
                                    <div class="invalid-feedback">Select month!</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-4">
                                <select name="employees" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('employees'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select employees</option>
                                    <?php foreach ($allEmployees as $employees) { ?>
                                        <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('employees'))) { ?>
                                    <div class="invalid-feedback">Select employees!</div>
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
        <?php if (Input::exists('get') || Input::exists()) {
            if (!$validation->countErrors() && count($errorNoData) == 0) {
                $month = Input::post('month');
                ?>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-profile text-center">
                                <div class="card-header"><?php echo $officeName; ?>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="mb-3 text-gray-light"><?php echo $name; ?></h4>
                                    <p class="mb-4"><?php echo Common::numberToMonth($month) . ' - ' . Input::post('year'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="no-padding-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="drills-chart block">
                                <div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                        <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                        <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                    </div>
                                </div>
                                <canvas id="all_data" style="display: block; width: 494px; height: 247px;" width="494" height="147" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="stats-2-block block d-flex">
                                <?php foreach ($allData as $key => $value) { ?>
                                    <div class="stats-2 d-flex">
                                        <div class="stats-2-arrow low"><i class="fa fa-line-chart"></i></div>
                                        <div class="stats-2-content">
                                            <strong class="d-block dashtext-1"><?php echo $value == '' || $value == null ? 0 : $value; ?></strong>
                                            <span class="d-block"><? echo strtoupper($key); ?></span>
                                            <div class="progress progress-template progress-small">
                                                <div role="progressbar" style="width: <?php echo $value; ?>%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template progress-bar-small dashbg-2"></div>
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
    }?>
    </div>
    <?php
    include '../common/includes/head.php';
    ?>
</div>
</div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";
if (Input::exists() && !$validate->countErrors() && count($errorNoData) == 0) {
    include './charts/useDataChart.php';
}
?>

</body>
</html>