<?php
require_once 'core/init.php';
$user = new CustomerUser();
$data = new CustomerProfile();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

// All tables
$allTables = $data->records(Params::TBL_OFFICE, ['id', '=', $user->officesId()], ['tables'], false);
$allTables = explode(',', trim($allTables->tables));

// All employees for user
$allEmployees = $data->records($data::TBL_EMPLOYEES, ['offices_id', '=', $user->officesId()], ['id', 'name']);


if (Input::exists() && Token::check(Input::post('token'))) {
        $employeesId    = Input::post('employees');
        $year           = Input::post('year');
        $month          = Input::post('month');
        $errors         = [];
        $errorNoData    = [];

        if (empty($employeesId) || empty($year) || empty($month)) {
            $errors = [1];
        }

        if (count($errors) === 0) {
            // Conditions for action
            $where = [
                ['year', '=', $year],
                'AND',
                ['employees_id', '=', $employeesId],
                'AND',
                ['month', '=', $month]
            ];

            //array key => values (keys are tables and values are numbers(quantity column))
            foreach ($allTables as $table) {
                $prefix = $data::PREFIX;
                $key[] = $table;
                $values[] = Common::columnValues($data->records($prefix . $table, $where, ['quantity']), 'quantity');
                $allData = array_combine($key, $values);
            }

            // All data for customer
            $employeesDetails = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $employeesId], ['name', 'offices_id'], false);
            $officeObj = $data->records(Params::TBL_OFFICE, ['id', '=', $employeesDetails->offices_id], ['name'], false);

            $name = $employeesDetails->name;
            $officeName = $officeObj->name;
            $initials = $data::makeAvatar($name);

            // Check if exists values
            if (!Common::checkValues($allData)) {
                $errorNoData = [1];
            }
        }
}

// If get exists
if (Input::exists('get') && !Input::exists()) {
    $employeesId    = Input::get('id');
    $year           = date('Y');
    $month          = date('n');
    $errorsNoData   = [];

    // Conditions for action
    $where = [
        ['year', '=', $year],
        'AND',
        ['employees_id', '=', $employeesId],
        'AND',
        ['month', '=', $month]
    ];

    //array key => values (keys are tables and values are numbers(quantity column))
    foreach ($allTables as $table) {
        $prefix     = $data::PREFIX;
        $key[]      = $table;
        $values[]   = Common::columnValues($data->records($prefix . $table, $where, ['quantity']), 'quantity');
        $allData    = array_combine($key, $values);
    }

    // All data for customer
    $employeesDetails   = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $employeesId], ['name', 'offices_id'], false);
    $officeObj          = $data->records(Params::TBL_OFFICE, ['id', '=', $employeesDetails->offices_id], ['name'], false);

    $name = $employeesDetails->name;
    $officeName = $officeObj->name;
    $initials = $data::makeAvatar($name);

// Check if exists values
    if (!Common::checkValues($allData)) {
        $errorNoData = [1];
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
        <?php
        if (Input::exists() && count($errors) > 0) {
            include 'includes/errorRequired.php';
        }

        if (Input::exists() && count($errorNoData) > 0) {
            include 'includes/infoError.php';
        }
        ?>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                        Filters
                    </button>
                </p>
                <div class="<?php if (Input::exists() && count($errors) == 0 && count($errorNoData) == 0) { echo "collapse";} else { echo "collapse show"; } ?>" id="filter">
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
            if (count($errors) == 0 && count($errorNoData) == 0) {  ?>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-profile text-center">
                                <div class="card-header"><?php echo $officeName; ?>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="mb-3 text-gray-light"><?php echo $name; ?></h4>
                                    <p class="mb-4"></p>
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
                                <canvas id="all_data" style="display: block; width: 494px; height: 247px;" width="494" height="147" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="stats-2-block block d-flex">
                                <?php foreach ($allData as $key => $value) { ?>
                                    <div class="stats-2 d-flex">
                                        <div class="stats-2-arrow low"><i class="fa fa-line-chart"></i></div>
                                        <div class="stats-2-content"><strong
                                                    class="d-block dashtext-1"><?php echo $value; ?></strong><span
                                                    class="d-block"><? echo strtoupper($key); ?></span>
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
    }?>
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
    var target_chart   = $('#all_data');
    var target = new Chart(target_chart, {
        type: 'line',
        options: {
            legend: {labels:{fontColor:"#777", fontSize: 16}},
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        color: 'transparent'
                    },
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        color: 'transparent'
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
        },
        data: {
            labels: <?php echo Js::key($allData); ?>,
            datasets: [
                {
                    label: "<?php echo Common::getMonths()[$month]; ?>",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "rgba(134, 77, 217, 0.88)",
                    borderColor: "rgba(134, 77, 217, 088)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: "rgba(134, 77, 217, 0.88)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(134, 77, 217, 0.88)",
                    pointHoverBorderColor: "rgba(134, 77, 217, 0.88)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 3,
                    pointHitRadius: 10,
                    data: [<?php echo Js::values($allData); ?>],
                    spanGaps: false
                }
            ]
        }
    });

</script>

</body>
</html>