<?php
require_once 'core/init.php';

//if (!Token::check(Input::get('token'))) {
//        Redirect::to('index.php');
//}


$id         = Input::get('id');
$user       = DB::getInstance()->get('cmd_users', ['id', '=', Input::get('id')])->first();
$name       = $user->name;
$allTables  = DB::getInstance()->get('cmd_offices', ['id', '=', $user->offices_id], ['tables'])->first();
$allTables  = Values::toArray(trim($allTables->tables));


if (Input::exists('get')) {
    $id             = Input::get('id');
    $staffProfile   = DB::getInstance()->get('cmd_users', ['id', '=', $id])->first();
    $totalEmployees = DB::getInstance()->get('cmd_employees', ['user_id', '=', $id])->count();
    $departmentName = DB::getInstance()->get('cmd_departments', ['id', '=', $staffProfile->supervisors_id], ['name'])->first();
    $officeName     = DB::getInstance()->get('cmd_offices', ['id', '=', $staffProfile->offices_id], ['name'])->first();

    $prefix         = 'cmd_';
    $commonTables   = ['unpaid', 'furlough', 'absentees'];
    $icon           = ['icon-line-chart', 'icon-dashboard', 'icon-chart'];

    foreach ($commonTables as $table) {
        $data[] = Values::sumAll(DB::getInstance()->get($prefix.$table, ['user_id', '=', $id], ['quantity'])->results(), 'quantity');
    }
    // Array with tables and sum of quantity for each table
    $dataCommonTables = array_combine($commonTables, $data);

}

if (Input::exists()) {
    $user_id = Input::get('id');
    $table = 'cmd_' . trim(Input::post('table'));
    $year = Input::post('year');
    $month = Input::post('month');

    if (empty($month) || empty($year) || empty($table)) {
        $errors = [1];
    }

    if (count($errors) === 0) {
        // Conditions for action
        $where = [
            ['year', '=', $year],
            'AND',
            ['user_id', '=', $user_id],
            'AND',
            ['month', '=', $month]
        ];

        // Array with all results for one FTE
        $chartData = DB::getInstance()->get($table, $where, ['quantity', 'name'])->results();
        $chartNames = Js::toJson(Js::chartLabel($chartData));
        $chartValues = Js::chartValues($chartData);

        foreach ($chartData as $value) {
            $quantitySum[] = $value->quantity;
        }

        if (count($quantitySum) < 1) {
            $errorNoData = [1];
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
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Profile</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Profile </li>
            </ul>
        </div>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <p>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Filters
                            </button>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value="">Select Table</option>
                                                <?php foreach ($allTables as $table) { ?>
                                                    <option value="<?php echo escape(trim($table)); ?>"><?php echo strtoupper($table); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('table'))) { ?>
                                                <div class="invalid-feedback mb-3">Team field are required!</div>
                                            <?php }?>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value="">Select Year</option>
                                                <?php foreach (Profile::getYearsList() as $year) { ?>
                                                <option value="<?php echo  $year; ?>"><?php echo $year; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('year'))) { ?>
                                                <div class="invalid-feedback mb-3">Year field are required!</div>
                                            <?php }?>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value="">Select Month</option>

                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('month'))) { ?>
                                                <div class="invalid-feedback mb-3">Month field are required!</div>
                                            <?php }?>
                                        </div>
                                        <div class="col-sm-12">
                                            <input value="Submit" name="Filter" class="btn btn-outline-primary" type="submit">
                                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-profile">
                            <div class="card-header">
                                <h4 class="text-gray-light text-center"><?php echo $name; ?></h4>
                            </div>
                            <div class="card-body text-center">
                                <p class=""><?php echo escape($departmentName->name) . ' - ' .escape($officeName->name); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon"><i class="icon-user-1"></i></div><strong>Total employees</strong>
                                </div>
                                <div class="number dashtext-2"><?php echo $totalEmployees; ?></div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $x = 0;
                    foreach ($dataCommonTables as $table => $quantity) { ?>
                        <div class="col-md-3 col-sm-3">
                            <div class="statistic-block block">
                                <div class="progress-details d-flex align-items-end justify-content-between">
                                    <div class="title">
                                        <div class="icon"><i class="<?php echo $icon[$x]; ?>"></i></div><strong>Total <?php echo strtoupper($table); ?></strong>
                                    </div>
                                    <div class="number dashtext-2"><?php echo $quantity; ?></div>
                                </div>
                                <div class="progress progress-template">
                                    <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                                </div>
                            </div>
                        </div>
                    <?php
                    $x++;
                    } ?>
                </div>
            </div>
        </section>
        <?php if (Input::exists() && count($errors) === 0) { ?>
        <section>
            <div class="col-md-12">
                <div class="pie-chart chart block">
                    <div class="pie-chart chart margin-bottom-sm"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas id="profile_chart" style="display: block; width: 494px; height: 203px;" width="494" height="203" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </section>
        <?php }
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
include 'includes/ajax_user_profile.php';

if (Input::exists() && count($errors) > 0) {
    include 'notification/error.php';
}

if (Input::exists() && count($errors) === 0) {
    include 'charts/profile_chart.php';
}
?>

</body>
</html>