<?php
require_once 'core/init.php';
$leadId         = Input::get('lead_id');
$officeId       = Input::get('office_id');

/** Lead data */
$lead       = $backendUserProfile->records(Params::TBL_TEAM_LEAD, AC::where(['id', $leadId]), ['name', 'id', 'offices_id', 'supervisors_id'], false);
/** Lead name */
$leadName   = $lead->name;
/** All tables */
$allTables  = $backendUserProfile->records(Params::TBL_OFFICE, AC::where(['id', $lead->offices_id]), ['tables'], false)->tables;
$allTables  = explode(',', $allTables);


/** Sum common data if get exist and if submit button is clicked */
if (Input::existsName('get', 'lead_id') && Input::existsName('get', 'office_id') && !Input::existsName('post', Tokens::getInputName())) {
    $year = date('Y');
    foreach (Params::PREFIX_TBL_COMMON as $table) {
        $commonData[] = $backendUserProfile->sum($table, AC::where([['offices_id', $officeId], ['year',$year]]), 'quantity');
    }
    /** Array with tables and sum of quantity for each table */
    $dataCommonTables = array_combine(Params::TBL_COMMON, $commonData);
} elseif (Input::existsName('post', Tokens::getInputName())) {
    $year = Input::post('year');
    foreach (Params::PREFIX_TBL_COMMON as $table) {
        $commonData[] = $backendUserProfile->sum($table, AC::where([['offices_id', $officeId], ['year',$year]]), 'quantity');
    }
    /** Array with tables and sum of quantity for each table */
    $dataCommonTables = array_combine(Params::TBL_COMMON, $commonData);
}


if (Input::existsName('get', 'lead_id') && Input::existsName('get', 'office_id')) {
    $leadId             = Input::get('lead_id');
    $officeId           = Input::get('office_id');
    $language           = Input::get('lang');
    $year               = date('Y');

    /** All employees for one lead */
    $allEmployees = $backendUserProfile->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $officeId]), ['offices_id', 'departments_id', 'name', 'id']);

    foreach ($allEmployees as $employees) {
        // Array with employees id
        $employeesId[$employees->id] = $employees->name;
    }

    /** Common data for lead employees  */
    foreach ($employeesId as $employeeId => $employeesName) {
        $employeesFurlough[]  = ['name' =>$employeesName,'avg' => $backendUserProfile->sum(Params::TBL_FURLOUGH, AC::condition(['employees_average_id', $employeeId], true), 'quantity'), 'id' => $employeeId];
        $employeesAbsentees[] = ['name' =>$employeesName, 'avg' => $backendUserProfile->sum(Params::TBL_ABSENTEES, AC::condition(['employees_average_id', $employeeId . '_' . date('Y')]), 'quantity'), 'id' => $employeeId];
        $employeesUnpaid[]    = ['name' =>$employeesName, 'avg' => $backendUserProfile->sum(Params::TBL_UNPAID, AC::condition(['employees_average_id', $employeeId . '_' . date('Y')]), 'quantity'), 'id' => $employeeId];
    }

    /** Staff details */
    $leadProfile        = $backendUserProfile->records(Params::TBL_TEAM_LEAD, ['id', '=', $leadId],['name', 'id', 'supervisors_id', 'offices_id'], false);

    /** Count employees */
    $totalEmployees     = $backendUserProfile->count(Params::TBL_EMPLOYEES, ['offices_id', '=', $officeId]);

    /** Department name */
    $departmentName     = $backendUserProfile->records(Params::TBL_DEPARTMENT, ['id', '=', $leadProfile->supervisors_id], ['name'], false)->name;

    /** Office name */
    $officeName         = $backendUserProfile->records(Params::TBL_OFFICE, ['id', '=', $leadProfile->offices_id], ['name'], false)->name;

    /** Icons for tables */
    $icon               = ['icon-line-chart', 'icon-dashboard', 'icon-chart'];
}

if (Input::existsName('post', Tokens::getInputName()) && Tokens::tokenVerify()) {
    /** Instantiate validate class */
    $validate = new Validate();

    /** Validate fields */
    $validation = $validate->check($_POST, [
        'table'     => ['required'  => true],
        'year'      => ['required'  => true],
        'month'     => ['required'  => true]
    ]);

    /** Check if validation passed */
    if ($validation->passed()) {
        $officeId   = Input::get('office_id');
        $table      = Params::PREFIX . trim(Input::post('table'));
        $year       = Input::post('year');
        $month      = Input::post('month');
        $tableForChart  = Translate::t(strtolower(Input::post('table')), ['ucfirst' => true]);

        /** Conditions for action */
        $where = AC::where([
            ['year', $year],
            ['offices_id', $officeId],
            ['month', $month]
        ]);

        /** Array with all results for one FTE */
        $chartData      = $backendUserProfile->records($table, $where, ['quantity', 'employees_id']);

        /** Employees names */
        foreach ($chartData as $chartNames) {
            $names[] = $backendUserProfile->records(Params::TBL_EMPLOYEES, ['id', '=', $chartNames->employees_id], ['name'], false)->name;
        }

        /** Employees chart names */
        $chartNames     = Js::toJson($names);
        /** Employees chart values */
        $chartValues    = Js::chartValues($chartData, 'quantity');

        foreach ($chartData as $value) {
            $quantitySum[] = $value->quantity;
        }

        /** Chech if exist values for options selected */
        if (count($quantitySum) < 1) {
            Errors::setErrorType('warning', Translate::t('Not_found_data'));
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
    <link rel="stylesheet" href="./../common/css/spiner/style.css">
    <script src="./../common/vendor/chart.js/Chart.min.js"></script>
</head>
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Profile'); ?></h2>
            </div>
        </div>
        <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade hide">
            <div class="loader loader-3">
                <div class="dot dot1"></div>
                <div class="dot dot2"></div>
                <div class="dot dot3"></div>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Profile'); ?> </li>
            </ul>
        </div>
        <?php
        if (Input::exists() && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <p>
                            <button class="btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                                <?php echo Translate::t('Filters'); ?>
                            </button>
                        </p>
                        <div class="<?php if (Input::exists() && Errors::countAllErrors()) { echo "collapse show";} else { echo "collapse"; } ?>" id="filter">
                            <div class="card card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value=""><?php echo Translate::t('Select_table'); ?></option>
                                                <?php foreach ($allTables as $table) { ?>
                                                    <option value="<?php echo escape(trim($table)); ?>"><?php echo Translate::t($table, ['ucfirst'=>true]); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('table'))) { ?>
                                                <div class="invalid-feedback mb-3"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value=""><?php echo Translate::t('Select_year'); ?></option>
                                                <?php foreach (Common::getYearsList() as $year) { ?>
                                                <option value="<?php echo  $year; ?>"><?php echo $year; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('year'))) { ?>
                                                <div class="invalid-feedback mb-3"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value=""><?php echo Translate::t('Select_month'); ?></option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('month'))) { ?>
                                                <div class="invalid-feedback mb-3"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="col-sm-12 mb-0">
                                            <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                            <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <blockquote class="blockquote mb-0 card-body">
                                <h3><?php echo $leadName; ?></h3>
                                <footer class="blockquote-footer">
                                    <small class="text-muted"><?php echo strtoupper($departmentName); ?></small>
                                </footer>
                                <footer class="blockquote-footer">
                                    <small class="text-muted"><?php echo strtoupper($officeName); ?></small>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="statistic-block block pb-1">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon"><i class="icon-user-1"></i></div><strong><?php echo Translate::t('Total_employees'); ?></strong>
                                </div>
                                <div class="number dashtext-2"><?php echo $totalEmployees; ?></div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                            </div>
                            <div class="mt-2 mb-1">
                                <button class="btn-sm btn-outline-secondary col-sm-2" type="button" data-toggle="collapse" data-target="4"  aria-expanded="false" aria-controls="filter" id="employeeTable">
                                    <?php echo Translate::t('show'); ?> <i class="fa fa-user-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                    $x = 0;
                    foreach ($dataCommonTables as $table => $quantity) { ?>
                        <div class="col-md-3 col-sm-3">
                            <div class="statistic-block block pb-1">
                                <div class="progress-details d-flex align-items-end justify-content-between">
                                    <div class="title">
                                        <div class="icon"><i class="<?php echo $icon[$x]; ?>"></i></div><strong><?php echo Translate::t('Total') . ' ' . Translate::t($table); ?></strong>
                                    </div>
                                    <div class="number <?php echo Params::DASH['text'][$x]; ?>"><?php echo $quantity; ?></div>
                                </div>
                                <div class="progress progress-template">
                                    <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template <?php echo Params::DASH['bg'][$x]; ?>"></div>
                                </div>
                                <div class="mt-2 mb-1">
                                    <button class="btn-sm btn-outline-secondary col-sm-6 common" type="button" data-toggle="collapse" data-target="<?= $x; ?>" id="">
                                        <?php echo Translate::t('show'); ?> <i class="<?php echo $icon[$x]; ?>"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php
                    $x++;
                    } ?>
                </div>
            </div>
        </section>

        <!--        Collapse Employees table -->
        <section class="no-padding-top collapse allCollapse" id="4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t('All_employees'); ?></strong>
                                <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv"><i class="fa fa-close"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo Translate::t('Name'); ?></th>
                                        <th><?php echo Translate::t('Team'); ?></th>
                                        <th><?php echo Translate::t('Depart'); ?></th>
                                        <th><?php echo Translate::t('Action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($allEmployees as $employees) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employees->id; ?>"><?php echo $employees->name; ?></a></td>
                                            <td><?php echo $backendUserProfile->records(Params::TBL_OFFICE, ['id', '=', $employees->offices_id], ['name'], false)->name; ?></td>
                                            <td><?php echo $backendUserProfile->records(Params::TBL_DEPARTMENT, ['id', '=', $employees->departments_id], ['name'], false)->name ;?></td>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employees->id; ?>"><i class="fa fa-user"></i></a></td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--        Collapse furlough  -->
        <section class="no-padding-top collapse allCollapse" id="0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t('furlough'); ?></strong>
                                <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv"><i class="fa fa-close"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo Translate::t('Name'); ?></th>
                                        <th><?php echo Translate::t('Team_furlough'); ?></th>
                                        <th><?php echo Translate::t('furlough'); ?></th>
                                        <th><?php echo Translate::t('percentage', ['ucfirst' => true]); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($employeesFurlough as $employeesData) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employeesData['id']; ?>"><?php echo $employeesData['name']; ?></a></td>
                                            <td><?php echo (int)$dataCommonTables['furlough'] . ' ' .  Translate::t('Days', ['strtolower'=>true]); ?></td>
                                            <td><?php echo $employeesData['avg'] . ' ' .  Translate::t('Days', ['strtolower'=>true]);?></td>
                                            <td><?php echo Common::percentage($dataCommonTables['furlough'], $employeesData['avg']); ?></td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--        Collapse absentees -->
        <section class="no-padding-top collapse allCollapse" id="1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t('absentees'); ?></strong>
                                <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv"><i class="fa fa-close"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo Translate::t('Name'); ?></th>
                                        <th><?php echo Translate::t('Team_absentees'); ?></th>
                                        <th><?php echo Translate::t('absentees'); ?></th>
                                        <th><?php echo Translate::t('percentage', ['ucfirst' => true]); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($employeesAbsentees as $employeesData) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employeesData['id']; ?>"><?php echo $employeesData['name']; ?></a></td>
                                            <td><?php echo (int)$dataCommonTables['absentees'] . ' ' .  Translate::t('Days', ['strtolower'=>true]); ?></td>
                                            <td><?php echo $employeesData['avg'] . ' ' .  Translate::t('Days', ['strtolower'=>true]);?></td>
                                            <td><?php echo Common::percentage($dataCommonTables['absentees'], $employeesData['avg']); ?></td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--        Collapse unpaid -->
        <section class="no-padding-top collapse allCollapse" id="2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t('unpaid'); ?></strong>
                                <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv""><i class="fa fa-close"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo Translate::t('Name'); ?></th>
                                        <th><?php echo Translate::t('Team_unpaid'); ?></th>
                                        <th><?php echo Translate::t('unpaid'); ?></th>
                                        <th><?php echo Translate::t('percentage', ['ucfirst' => true]); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($employeesUnpaid as $employeesData) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employeesData['id']; ?>"><?php echo $employeesData['name']; ?></a></td>
                                            <td><?php echo (int)$dataCommonTables['unpaid'] . ' ' .  Translate::t('Days', ['strtolower'=>true]); ?></td>
                                            <td><?php echo $employeesData['avg'] . ' ' .  Translate::t('Days', ['strtolower'=>true]); ?></td>
                                            <td><?php echo Common::percentage($dataCommonTables['unpaid'], $employeesData['avg']); ?></td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--        Collapse medical -->
        <section class="no-padding-top collapse allCollapse" id="3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t('medical'); ?></strong>
                                <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv""><i class="fa fa-close"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo Translate::t('Name'); ?></th>
                                        <th><?php echo Translate::t('Team_medical'); ?></th>
                                        <th><?php echo Translate::t('medical'); ?></th>
                                        <th><?php echo Translate::t('percentage', ['ucfirst' => true]); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($employeesUnpaid as $employeesData) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employeesData['id']; ?>"><?php echo $employeesData['name']; ?></a></td>
                                            <td><?php echo (int)$dataCommonTables['medical'] . ' ' .  Translate::t('Days', ['strtolower'=>true]); ?></td>
                                            <td><?php echo $employeesData['avg'] . ' ' . Translate::t('Days', ['strtolower'=>true]); ?></td>
                                            <td><?php echo Common::percentage($dataCommonTables['medical'], $employeesData['avg']); ?></td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        if (Input::exists() && !Errors::countAllErrors()) { ?>
        <section>
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-header pt-2">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item"><button class="btn-sm btn-primary mr-1 bar" id="bar" type="button"><?php echo Translate::t('Bar'); ?></button></li>
                            <li class="nav-item"><button class="btn-sm btn-outline-primary line" id="line" type="button"><?php echo Translate::t('Line'); ?></button></li>
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
        include './../common/includes/footer.php';
        ?>
    </div>
</div>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });

    // Click to view table employees
    $ ( '#employeeTable' ).on('click', function () {
        $( '.allCollapse:visible' ).hide();
        var $this = $(this);
        $( '#' + $this.data("target")).show(function () {
            $( '#' + $this.data("target")).fadeIn(3000);
        });
    });

    // Click to view common
    $ ( '.common' ).on('click', function () {
        $( '.allCollapse:visible' ).hide();
        var $this = $(this);
        $( '#' + $this.data("target")).show(function () {
            $( '#' + $this.data("target")).fadeIn(3000);
        });
    });

    // Click close div
    $ ( '.closeDiv' ).on('click', function () {
        $( '.allCollapse:visible' ).hide(function () {
            $(this).fadeOut(3000);
        });
    });


    $("#bar").click(function(){
        $('.line').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.bar').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#profile_line_chart").hide();
        $("#profile_bar_chart").show();
    });

    $("#line").click(function(){
        $('.bar').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.line').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#profile_bar_chart").hide();
        $("#profile_line_chart").show();
    });
</script>
<?php
include 'includes/js/ajax_user_profile.php';

if (Input::exists() && !Errors::countAllErrors()) {
    include 'charts/profile_chart.php';
}
?>

</body>
</html>