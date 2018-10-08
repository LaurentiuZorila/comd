<?php
require_once 'core/init.php';
$user   = new FrontendUser();
$token  = new Token();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
// User data
$userData   = BackendDB::getInstance()->get('cmd_users', ['id', '=', $user->userId()])->first();

//  Get all tables for department
$allTables = BackendDB::getInstance()->get('cmd_offices', ['id', '=', $userData->offices_id])->first();

// All employees for user
$allEmployees = BackendDB::getInstance()->get('cmd_employees', ['user_id', '=', $user->userId()], ['id', 'name'])->results();


if (Input::exists()) {
    if ($token->getToken(Input::post('token'))) {
        $id = Input::post('employees');
        $year = Input::post('year');
        $month = Input::post('month');
        $errors = [];
        $errorsNoData = [];

        if (empty($id) || empty($year) || empty($month)) {
            $errors = [1];
        }
// Conditions for action
        $where = [
            ['year', '=', $year],
            'AND',
            ['employees_id', '=', $id],
            'AND',
            ['month', '=', $month]
        ];

        foreach (Values::table($allTables) as $value) {
            $tables[] = trim($value);
        }

        //array key => values (keys are tables and values are numbers(quantity column))
        foreach ($tables as $table) {
            $prefix = 'cmd_';
            $key[] = $table;
            $values[] = Values::value(BackendDB::getInstance()->get($prefix . $table, $where, ['quantity'])->results());
            $sumAllCommonData = array_combine($key, $values);
        }

// All data for customer
        $user_profile = BackendDB::getInstance()->get('cmd_employees', ['id', '=', $id], ['name', 'offices_id'])->first();
        $officeObj = BackendDB::getInstance()->get('cmd_offices', ['id', '=', $user_profile->offices_id], ['name'])->first();

        $name = $user_profile->name;
        $officeName = $officeObj->name;
        $initials = Profile::makeAvatar($user_profile->name);

// Check if exists values
        if (!Js::ifExistsValues($sumAllCommonData)) {
            $errorsNoData = [1];
        }
    }
}


// If get exists
if (!empty(Input::get('customer_id')) && !Input::exists()) {
    $customer_id = Input::get('customer_id');
    $year = date('Y');
    $month = date('n');
    $errorsNoData = [];

    // Conditions for action
    $where = [
        ['year', '=', $year],
        'AND',
        ['employees_id', '=', $id],
        'AND',
        ['month', '=', $month]
    ];

    foreach (Values::table($allTables) as $value) {
        $tables[] = trim($value);
    }

    //array key => values (keys are tables and values are numbers(quantity column))
    foreach ($tables as $table) {
        $prefix     = 'cmd_';
        $key[]      = $table;
        $values[]   = Values::value(BackendDB::getInstance()->get($prefix . $table, $where, ['quantity'])->results());
        $sumAllCommonData    = array_combine($key, $values);
    }

// All data for customer
    $user_profile   = BackendDB::getInstance()->get('cmd_employees', ['id', '=', $id], ['name', 'offices_id'])->first();
    $officeObj      = BackendDB::getInstance()->get('cmd_offices', ['id', '=', $user_profile->offices_id], ['name'])->first();

    $name           = $user_profile->name;
    $officeName     = $officeObj->name;
    $initials       = Profile::makeAvatar($user_profile->name);

// Check if exists values
    if (!Js::ifExistsValues($sumAllCommonData)) {
        $errorsNoData = [1];
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
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Employees data</li>
            </ul>
        </div>
        <?php
        // If selected option not return data, alert
        if (Input::exists()) {
            if (count($errors) > 0) {
                include './../common/errors/errorRequired.php';
            }
        }


        // If get not return data, alert
        if (Input::exists('get') && !Input::exists()) {
            if (count($errorsNoData) > 0 && count($errors) == 0) {
                include './../common/errors/infoNoDataError.php';
            }
        }


        // If search not return data, alert
        if (Input::exists() || Input::exists('get')) {
            if (count($errorsNoData) > 0 && count($errors) == 0) {
                include './../common/errors/infoNoDataError.php';
            }
        }
        ?>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
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
                                <input type="hidden" name="token" value="<?php echo $token->getToken(); ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php if (Input::exists('customer_id') || Input::exists()) {
            if (count($errors) == 0 && count($errorsNoData) == 0) {  ?>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-profile">
                                <div class="card-header"><?php echo $department; ?>
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
                                        <canvas id="all_data" style="display: block; width: 494px; height: 247px;"
                                                width="494" height="147" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="stats-2-block block d-flex">
                                        <?php foreach ($sumAllCommonData as $key => $value) { ?>
                                            <div class="stats-2 d-flex">
                                                <div class="stats-2-arrow low"><i class="fa fa-caret-down"></i></div>
                                                <div class="stats-2-content"><strong
                                                            class="d-block"><?php echo $value; ?></strong><span
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
    include '../common/includes/footer.php';
    ?>
</div>
</div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";
?>
<script>
    var LINECHART1 = $('#all_data');
    var myLineChart = new Chart(LINECHART1, {
        type: 'line',
        options: {
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    display: false,
                    gridLines: {
                        display: false
                    }
                }]
            },
            legend: {
                display: true
            }
        },
        data: {
            labels: <?php echo Js::key($sumAllCommonData); ?>,
            datasets: [
                {
                    label: "<?php echo Profile::getMonthsList()[$month]; ?>",
                    fill: true,
                    lineTension: 0.3,
                    backgroundColor: "transparent",
                    borderColor: '#EF8C99',
                    pointBorderColor: '#EF8C99',
                    pointHoverBackgroundColor: '#EF8C99',
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 2,
                    pointBackgroundColor: "#EF8C99",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 0,
                    pointRadius: 1,
                    pointHitRadius: 0,
                    data: [<?php echo Js::values($sumAllCommonData); ?>],
                    spanGaps: false
                }
            ]
        }
    });
</script>
</body>
</html>