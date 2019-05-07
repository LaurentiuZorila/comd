<?php
require_once 'core/init.php';

$tables = $frontProfile->records(Params::TBL_OFFICE, AC::where(['id', $frontUser->officeId()]), ['tables'], false);
$tables = explode(',', $tables->tables);

// Get all common data
foreach (Params::TBL_COMMON as $commonTables) {
    $commonData[$commonTables] = $frontProfile->records(Params::PREFIX . $commonTables, AC::where([['employees_id', $frontUser->userId()], ['year', date('Y')]]), ['*'], true);
}


if (!Input::get('info')) {
    Session::put('InfoAlert', Translate::t('Click_filter_for_data'));
}

if (!Input::exists()) {
    //Current year
    $year = date('Y');
    $whereIndexPage = AC::where([
        ['employees_id', $frontUser->userId()],
        ['year', $year],
    ]);
}

if (Input::noPost() && Input::existsName('get', 'lastData')) {
    $lastData = new LastData();
    $lang = $frontUser->language();
    $lastDataChartLabel = $lastData->chartData('key', $lang);
    $lastDataChartKey   = $lastData->chartData('label', $lang);
}

if (Input::exists() && Tokens::tokenVerify()) {
    Session::delete('InfoAlert');
    $validate   = new Validate();
    $validation = $validate->check($_POST, [
        'year'  => ['required'  => true],
        'month' => ['required'  => true],
        'table' => ['required'  => true]
    ]);

    if ($validate->passed()) {
    $year       = trim(Input::post('year'));
    $month      = trim(Input::post('month'));
    $table      = trim(Input::post('table'));
    $prefixTbl  = Params::PREFIX . $table;
    $alfaMonth  = is_numeric($month) ? Common::numberToMonth($month, $lang) : '';

    if (is_numeric($month)) {
        // Conditions for action
        $where = AC::where([
            ['employees_id', $frontUser->userId()],
            ['year', $year],
            ['month', $month]
        ]);

        // Conditions for COUNT action (total)
        $whereSum = AC::where([
            ['offices_id', $frontUser->officeId()],
            ['year', $year],
            ['month', $month]
        ]);

        /**  One record if is selected one month form form */
        $data = $frontProfile->records($prefixTbl, $where, ['quantity'], false)->quantity;

        if ($data == '') {
            Session::put('selected_month', $alfaMonth);
            Session::put('selected_year', $year);
            Errors::setErrorType('info', Translate::t('Not_found_data'));
            $data = 0;
        }

        /** Common data for user */
        $userFurlough     = $frontProfile->commonDetails($where, ['quantity'])['furlough']->quantity;
        $userAbsentees    = $frontProfile->commonDetails($where, ['quantity'])['absentees']->quantity;
        $userUnpaidDays   = $frontProfile->commonDetails($where, ['quantity'])['unpaid']->quantity;
        $userMedicalLeave = $frontProfile->commonDetails($where, ['quantity'])['medical']->quantity;

        /** Total data for common tables */
        $totalFurloughs     = $frontProfile->sumAllCommonData($whereSum, 'quantity')['furlough']->total;
        $totalAbsentees     = $frontProfile->sumAllCommonData($whereSum, 'quantity')['absentees']->total;
        $totalUnpaid        = $frontProfile->sumAllCommonData($whereSum, 'quantity')['unpaid']->total;
        $totalMedicalLeave  = $frontProfile->sumAllCommonData($whereSum, 'quantity')['medical']->total;
    }

        /** If user search data for all months */
        if (!is_numeric($month)) {
            // Conditions for action
            $where = AC::where([
                ['employees_id', $frontUser->userId()],
                ['year', $year]
            ]);

            // Conditions for COUNT action (total)
            $sumCommonDataAll = AC::where([
                ['offices_id', $frontUser->officeId()],
                ['year', $year]
            ]);

            /** Selected chart */
            $dataAllMonths = $frontProfile->arrayMultipleRecords($prefixTbl, $where, ['month', 'quantity'], $lang);

            /** Common charts */
            $furloughCommon        = $frontProfile->arrayMultipleRecords(Params::TBL_FURLOUGH, $where, ['month', 'quantity'], $lang);
            $absenteesCommon       = $frontProfile->arrayMultipleRecords(Params::TBL_ABSENTEES, $where, ['month', 'quantity'], $lang);
            $unpaidCommon          = $frontProfile->arrayMultipleRecords(Params::TBL_UNPAID, $where, ['month', 'quantity'], $lang);
            $medicalLeaveCommon    = $frontProfile->arrayMultipleRecords(Params::TBL_MEDICAL, $where, ['month', 'quantity'], $lang);

            /** Total data for all employees from common charts */
            $totalFurloughs = $frontProfile->sumAllCommonData($sumCommonDataAll, 'quantity')['furlough']->total;
            $totalAbsentees = $frontProfile->sumAllCommonData($sumCommonDataAll, 'quantity')['absentees']->total;
            $totalUnpaid    = $frontProfile->sumAllCommonData($sumCommonDataAll, 'quantity')['unpaid']->total;
            $totalMedical   = $frontProfile->sumAllCommonData($sumCommonDataAll, 'quantity')['medical']->total;

            /** User total data for all months */
            $userFurlough   = $frontProfile->sumAllCommonData($where, 'quantity')['furlough']->total;
            $userAbsentees  = $frontProfile->sumAllCommonData($where, 'quantity')['absentees']->total;
            $userUnpaidDays = $frontProfile->sumAllCommonData($where, 'quantity')['unpaid']->total;
            $userMedical    = $frontProfile->sumAllCommonData($where, 'quantity')['medical']->total;

            /** Common charts */
            $furloughChartLabel     = Js::key($furloughCommon);
            $furloughChartValues    = Js::values($furloughCommon);
            $countFurlough          = count($furloughCommon);

            $absenteesChartLabel    = Js::key($absenteesCommon);
            $absenteesChartValues   = Js::values($absenteesCommon);
            $countAbsentees         = count($absenteesCommon);

            $unpaidChartLabel       = Js::key($unpaidCommon);
            $unpaidChartValues      = Js::values($unpaidCommon);
            $countUnpaid            = count($unpaidCommon);

            $medicalChartLabel      = Js::key($medicalLeaveCommon);
            $medicalChartValues     = Js::values($medicalLeaveCommon);
            $countMedical           = count($medicalLeaveCommon);

            /** Get colors for pie charts */
            $furloughColors     = ' \' ' . implode('\',\'', array_slice(Params::CHART_COLORS_VIOLET, 0, $countFurlough)) .' \' ';
            $absenteesColors    = ' \' ' . implode('\',\'', array_slice(Params::CHART_COLORS_RED, 0, $countAbsentees)) .' \' ';
            $unpaidColors       = ' \' ' . implode('\',\'', array_slice(Params::CHART_COLORS_VIOLET, 0, $countUnpaid)) .' \' ';
            $medicalColors      = ' \' ' . implode('\',\'', array_slice(Params::CHART_COLORS_RED, 0, $countMedical)) .' \' ';

            /** Selected table chart */
            if (!empty($dataAllMonths)) {
                $chartLabels = Js::key($dataAllMonths);
                $chartValues = Js::values($dataAllMonths);
            } else {
                Errors::setErrorType('info', Translate::t('Not_found_data'));
            }
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
    // LOADING PRELOADER MODAL
    include './../common/includes/preloaders.php';
    ?>
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Dashboard'); ?></h2>
            </div>
        </div>
        <?php
        if (Input::exists() && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        if (Input::noPost() && Input::existsName('get', 'lastData') && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <p>
                            <button class="btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <?php echo Translate::t('Filters'); ?>
                            </button>
                        </p>
                        <div class="<?php if (Input::exists() && !Errors::countAllErrors()) { echo "collapse";} else { echo "collapse show"; } ?>" id="collapseExample">
                            <div class="card card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="title"><strong><?php echo Translate::t('Filters'); ?></strong></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                                <option value="<?php echo Input::exists() && !empty(Input::post('year')) ? Input::post('year') : ''; ?>"><?php echo Input::exists() && !empty(Input::post('year')) ? Input::post('year') : Translate::t('Select_year', ['ucfirst']); ?></option>
                                                <?php
                                                foreach (Common::getYearsList() as $year) { ?>
                                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('year'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) { echo 'is-invalid'; } else { echo 'mb-3';} ?>">
                                                <option value="<?php echo Input::exists() && !empty(Input::post('month')) ? Input::post('month') : ''; ?>"><?php echo Input::exists() && !empty(Input::post('month')) && is_numeric(Input::post('month')) ? Common::numberToMonth(Input::post('month'), Session::get('lang')) : Translate::t('Select_month', ['ucfirst']); ?></option>
                                                <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>"><?php echo ucfirst($value); ?></option>
                                                <?php } ?>
                                                    <option value="All">All</option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('month'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <?php if (Input::existsName('post', 'submitFilters')) { ?>
                                                    <option value="<?php echo Input::post('table'); ?>"><?php echo strtoupper(Input::post('table')); ?></option>
                                                <?php } else { ?>
                                                    <option value=""><?php echo Translate::t('Select_table', ['ucfirst'=>true]); ?></option>
                                                <?php }
                                                foreach ($tables as $table) { ?>
                                                    <option value="<?php echo trim($table); ?>"><?php echo Translate::t($table, ['strtoupper']); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php if (Input::exists() && empty(Input::post('table'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="col-sm-2">
                                            <button name="submitFilters" id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                            <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
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
        // IF input doesn't exists, show this section
        if (!Input::exists()) { ?>
            <section class="no-padding-top no-padding-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <?php foreach ($frontProfile->sumAllCommonData($whereIndexPage, 'quantity') as $table => $value) {
                            $table = $table === Translate::t('unpaid') ? Translate::t('unpaid_days') : $table;
                            $col = $table == 'furlough' ? "col-sm-4 col-sm-4" : "col-sm-2 col-sm-2";
                            ?>
                            <div class="col-sm-2 col-sm-2">
                                <div class="statistic-block block">
                                    <div class="progress-details d-flex align-items-end justify-content-between">
                                        <div class="title">
                                            <div class="icon"><i class="icon-list"></i></div><strong><?php echo Translate::t($table); ?></strong>
                                        </div>
                                        <div class="number dashtext-2"><?php echo !empty($value->total) ? $value->total : 0; ?></div>
                                    </div>
                                    <div class="progress progress-template">
                                        <div role="progressbar" style="width: 100%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn-sm btn-outline-secondary col-sm-6 common" type="button" data-toggle="collapse" data-target="<?php echo $table; ?>" id="">
                                            <?php echo Translate::t('show'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>

<!--            Collapse user common details-->
        <?php foreach ($commonData as $key => $values) { ?>
            <section class="no-padding-top collapse allCollapse" id="<?php echo $key; ?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong><?php echo Translate::t($key); ?></strong>
                                    <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv"><i class="fa fa-close"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo Translate::t('Name'); ?></th>
                                            <th><?php echo Translate::t('month'); ?></th>
                                            <th><?php echo Translate::t('quantity'); ?></th>
                                            <th><?php echo Translate::t('days'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 1;
                                            foreach ($values as $v) { ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $frontUser->name(); ?></td>
                                                <td><?php echo Common::numberToMonth($v->month, $lang); ?></td>
                                                <td><?php echo $v->quantity; ?></td>
                                                <td><?php echo $v->days; ?></td>
                                            </tr>
                                            <?php
                                            $x++; } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
        }
        if (Input::exists() && !Errors::countAllErrors()) {
            if (is_numeric(Input::post('month'))) { ?>
                <section class="no-padding-top no-padding-bottom">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 col-sm-6">
                                <div class="statistic-block block">
                                    <div class="progress-details d-flex align-items-end justify-content-between">
                                        <div class="title">
                                            <div class="icon"><i class="icon-info"></i></div>
                                            <strong><?php echo Translate::t(Input::post('table'), ['strtoupper' => true]) . ' - ' . Common::numberToMonth($month, $lang) . ' - ' . Input::post('year'); ?></strong>
                                        </div>
                                        <div class="number dashtext-1"><?php echo $data; ?></div>
                                    </div>
                                    <div class="progress progress-template">
                                        <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
            <section class="margin-bottom-sm">
                <div class="container-fluid">
                    <div class="row d-flex align-items-stretch">
                        <?php if (Input::exists() && !Errors::countAllErrors() && $userFurlough > 0) { ?>
                        <div class="col-lg-6">
                            <div class="stats-with-chart-1 block">
                                <div class="title mb-0"><strong class="d-block"><?php echo Translate::t('furlough'); ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5 align-self-center">
                                        <div class="text">
                                            <strong class="d-block dashtext-3"><?php echo $userFurlough; ?>
                                                <small><?php echo $day = $userFurlough > 1 ? Translate::t('Days') : Translate::t('Day'); ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t('Team_furlough') . ' ' . $totalFurloughs; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="furloughChart" style="display: block; width: 70px; height: 60px;" width="70" height="60" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }
                        if (Input::exists() && !Errors::countAllErrors() && $userMedical > 0) {
                        ?>
<!--                        MEDICAL LEAVE-->
                        <div class="col-lg-6">
                            <div class="stats-with-chart-1 block">
                                <div class="title mb-0"><strong class="d-block"><?php echo Translate::t('medical'); ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5 align-self-center">
                                        <div class="text"><strong
                                                    class="d-block dashtext-3"><?php echo $userMedical; ?>
                                                <small><?php echo $day = $userMedical > 1 ? Translate::t('Days') : Translate::t('Day'); ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t('Team_medical') . ' ' . $totalMedical; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="medicalChart" style="display: block; width: 70px; height: 60px;" width="70" height="60" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
<!--                        ABSENTEES-->
                        <?php if (Input::exists() && !Errors::countAllErrors() && $userAbsentees > 0) { ?>
                        <div class="col-lg-6">
                            <div class="stats-with-chart-1 block">
                                <div class="title mb-0"><strong class="d-block"><?php echo Translate::t('absentees');  ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5 align-self-center">
                                        <div class="text"><strong class="d-block dashtext-1"><?php echo $userAbsentees; ?>
                                                <small><?php echo $day = $userAbsentees > 1 ? Translate::t('Days') : Translate::t('Day'); ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t('Team_absentees') . ': ' . $totalAbsentees; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="absenteesPieChart" style="display: block; width: 70px; height: 60px;" width="70" height="60" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!--UNPAID LEAVE-->
                        <?php if (Input::exists() && !Errors::countAllErrors() && $userUnpaidDays > 0) { ?>
                        <div class="col-lg-6">
                            <div class="stats-with-chart-1 block">
                                <div class="title mb-0"><strong class="d-block"><?php echo Translate::t('unpaid'); ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5 align-self-center">
                                        <div class="text"><strong
                                                    class="d-block dashtext-2"><?php echo $userUnpaidDays; ?>
                                                <small><?php echo $day = $userUnpaidDays > 1 ? Translate::t('Days') : Translate::t('Day');  ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t('Team_absentees') . ': ' . $totalUnpaid; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="unpaidChart" style="display: block; width: 70px; height: 60px;" width="70" height="60" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        <?php } ?>
        <!--        ********************       CHARTS         ********************   -->
        <?php if (Input::exists() && !is_numeric(Input::post('month')) && !Errors::countAllErrors()) { ?>
                <section class="no-padding-bottom">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="drills-chart block">
                                    <canvas id="employees_chart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
        <?php }
        if (Input::noPost() && Input::existsName('get', 'lastData') && !Errors::countAllErrors()) { ?>
            <section class="no-padding-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="drills-chart block">
                                <canvas id="lastDataChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
        <!--        ********************       CHARTS   END      ********************   -->
        <?php
        include '../common/includes/footer.php';
        ?>
    </div>
</div>
<?php
if (Input::exists() && is_numeric(Input::post('month')) && !Errors::countAllErrors()) {
    include 'charts/commonDataSingle.php';
}
if (Input::exists() && !is_numeric(Input::post('month')) && !Errors::countAllErrors()) {
    include 'charts/commonDataMultiple.php';
}
if (Input::noPost() && Input::existsName('get', 'lastData') && !Errors::countAllErrors()) {
    include 'charts/last_data_chart.php';
}
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
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
</script>
<?php
include './includes/js/markAsRead.php';
?>
</body>
</html>