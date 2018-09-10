<?php
require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
//  Get all tables for department
$allTables = DB::getInstance()->get('department', array('user_id', '=', $user->userId()))->results();

// All users for customer logged
$users = DB::getInstance()->get('users', ['user_id', '=', $user->userId()])->results();


if (Input::exists()) {
    if (Token::check(Input::post('token'))) {
        $customer_id   = Input::post('customer');
        $year          = Input::post('year');
        $month         = Input::post('month');
        $errors        = [];
        $errorsNoData  = [];

        if (empty($customer_id) || empty($year) || empty($month)) {
            $errors = [1];
        }
// Conditions for action
            $where = [
                ['year', '=', $year],
                'AND',
                ['customer_id', '=', $customer_id],
                'AND',
                ['month', '=', $month]
            ];

            foreach (Values::tables($allTables) as $value) {
                $tables[] = trim($value);
            }

            foreach ($tables as $table) {
                $key[] = $table;
                $values[] = Profile::value(DB::getInstance()->get($table, $where, ['quantity'])->results());
                // assoc array key => values (keys are tables and values are numbers(quantity column))
                $allData = array_combine($key, $values);
            }
// All data for customer
        $user_profile = DB::getInstance()->get('users', array('customer_id', '=', $customer_id))->results();
        foreach ($user_profile as $profile) {
            $initials = Profile::makeAvatar($profile->name);
            $name = $profile->name;
            $department = $profile->department;
        }

// Check if exists values
        if (!Js::ifExistsValues($allData)) {
            $errorsNoData = [1];
        }
    }
}

if (!empty(Input::get('customer_id')) && !Input::exists()) {
    $customer_id = Input::get('customer_id');
    $year = date('Y');
    $month = date('n');
    $errorsNoData = [];

    // Conditions for action
    $where = [
        ['year', '=', $year],
        'AND',
        ['customer_id', '=', $customer_id],
        'AND',
        ['month', '=', $month]
    ];

    foreach (Values::tables($allTables) as $value) {
        $tables[] = trim($value);
    }

    foreach ($tables as $table) {
        $key[] = $table;
        $values[] = Profile::value(DB::getInstance()->get($table, $where, ['quantity'])->results());
        // assoc array key => values (keys are tables and values are numbers(quantity column))
        $allData = array_combine($key, $values);
    }

    // All data for customer
    $user_profile = DB::getInstance()->get('users', array('customer_id', '=', $customer_id))->results();
    foreach ($user_profile as $profile) {
        $initials = Profile::makeAvatar($profile->name);
        $name = $profile->name;
        $department = $profile->department;
    }

    // Check if exists values
    if (!Js::ifExistsValues($allData)) {
        $errorsNoData = [1];
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
                            <div class="col-sm-4">
                                <select name="year" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Year</option>
                                    <?php
                                    foreach (Profile::getYearsList() as $year) { ?>
                                        <option><?php echo $year; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback">Please select year.</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-4">
                                <select name="month" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Month</option>
                                    <?php foreach (Profile::getMonthsList() as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('month'))) { ?>
                                    <div class="invalid-feedback">Please select month.</div>
                                <?php }?>
                            </div>
                            <div class="col-sm-4">
                                <select name="customer" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('customer'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select fte</option>
                                    <?php foreach ($users as $value) { ?>
                                        <option value="<?php echo $value->customer_id; ?>"><?php echo $value->name; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('customer'))) { ?>
                                    <div class="invalid-feedback">Please select fte.</div>
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
                                        <?php foreach ($allData as $key => $value) { ?>
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
            labels: <?php echo Js::key($allData); ?>,
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
                    data: [<?php echo Js::values($allData); ?>],
                    spanGaps: false
                }
            ]
        }
    });
</script>
<?php
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
if (Input::exists() && Input::exists('get')) {
    if (count($errorsNoData) > 0 && count($errors) == 0) {
        include 'notification/post_not_found.php';
    }
}



?>

</body>
</html>