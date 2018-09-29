<?php
require_once 'core/init.php';
$user   = new BackendUser();
$data   = new BackendProfile();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}


$leadId         = Input::get('lead_id');
$officeId       = Input::get('office_id');
// Lead data
$lead       = $data->records($data::TBL_TEAM_LEAD, ['id', '=', $leadId], ['name', 'id', 'offices_id', 'supervisors_id'], false);
// Lead name
$leadName   = $lead->name;
// All tables
$allTables  = $data->records($data::TBL_OFFICE, ['id', '=', $lead->offices_id], ['tables'], false)->tables;
$allTables  = explode(',', $allTables);


if (Input::exists('get')) {
    $leadId             = Input::get('lead_id');
    $officeId           = Input::get('office_id');
    // Staff details
    $leadProfile        = $data->records($data::TBL_TEAM_LEAD, ['id', '=', $leadId],['name', 'id', 'supervisors_id', 'offices_id'], false);
    // Count employees
    $totalEmployees     = $data->count($data::TBL_EMPLOYEES, ['offices_id', '=', $officeId]);
    // Department name
    $departmentName     = $data->records($data::TBL_DEPARTMENT, ['id', '=', $leadProfile->supervisors_id], ['name'], false)->name;
    // Office name
    $officeName         = $data->records($data::TBL_OFFICE, ['id', '=', $leadProfile->offices_id], ['name'], false)->name;
    // Icons for tables
    $icon               = ['icon-line-chart', 'icon-dashboard', 'icon-chart'];


    foreach (BackendProfile::TBL_COMMON_PREFIX as $table) {
        $commonData[] = $data->sum($table, ['offices_id', '=', $officeId], 'quantity');
    }

    // Array with tables and sum of quantity for each table
    $dataCommonTables = array_combine($data::TBL_COMMON, $commonData);


}

if (Input::exists()) {
    $officeId   = Input::get('office_id');
    $table      = $data::PREFIX . trim(Input::post('table'));
    $year       = Input::post('year');
    $month      = Input::post('month');

    if (empty($month) || empty($year) || empty($table)) {
        $errors = [1];
    }

    if (count($errors) === 0) {
        // Conditions for action
        $where = [
            ['year', '=', $year],
            'AND',
            ['offices_id', '=', $officeId],
            'AND',
            ['month', '=', $month]
        ];

        // Array with all results for one FTE
        $chartData      = $data->records($table, $where, ['quantity', 'name']);
        $chartNames     = Js::toJson(Js::chartLabel($chartData, 'name'));
        $chartValues    = Js::chartValues($chartData, 'quantity');

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
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                Filters
                            </button>
                        </p>
                        <div class="<?php if (Input::exists() && count($errors) == 0 && count($errorNoData) == 0) { echo "collapse";} else { echo "collapse show"; } ?>" id="filter">
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
                                                <?php foreach (Common::getYearsList() as $year) { ?>
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
                                <h4 class="text-gray-light text-center"><?php echo $leadName; ?></h4>
                            </div>
                            <div class="card-body text-center">
                                <p class=""><?php echo escape($departmentName) . ' - ' .escape($officeName); ?></p>
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
                            <canvas id="profile_bar_chart" style="display: block; width: 494px; height: 213px;" width="494" height="213" class="chartjs-render-monitor"></canvas>
                            <canvas id="profile_line_chart" style="display: none; width: 494px; height: 211px;" width="494" height="211" class="chartjs-render-monitor"></canvas>
                        </div>
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
<script>
    $("#bar").click(function(){
        $('.line').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.bar').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#profile_bar_chart").show();
        $("#profile_line_chart").hide();
    });

    $("#line").click(function(){
        $('.bar').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.line').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#profile_line_chart").show();
        $("#profile_bar_chart").hide();
    });
</script>
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