<?php
require_once 'core/init.php';


$tables = $records->records(Params::TBL_OFFICE, ['id', '=', $user->officeId()], ['tables'], false);
$tables = explode(',', $tables->tables);


if (!Input::get('info')) {
    Session::put('InfoAlert', Translate::t($lang, 'Click_filter_for_data'));
}

if (!Input::exists()) {
    //Current year
    $year = date('Y');
    // Current month
    //date('n') - 1;
    $month = 1;
    $where = [
        ['employees_id', '=', $user->userId()],
        'AND',
        ['year', '=', $year],
        'AND',
        ['month', '=', $month]
    ];
}

if (Input::noPost() && Input::existsName('get', 'lastData')) {
    $lastData = new LastData();
    $lang = $user->language();
    $lastDataChartLabel = $lastData->chartData('key', $lang);
    $lastDataChartKey   = $lastData->chartData('label', $lang);
}

if (Input::exists()) {
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
        $where = [
            ['employees_id', '=', $user->userId()],
            'AND',
            ['year', '=', $year],
            'AND',
            ['month', '=', $month]
        ];

        // Conditions for COUNT action (total)
        $whereSum = [
            ['offices_id', '=', $user->officeId()],
            'AND',
            ['year', '=', $year],
            'AND',
            ['month', '=', $month]
        ];

        /**  One record if is selected one month form form */
        $data = $records->records($prefixTbl, $where, ['quantity'])->quantity;
        if ($data == '') {
            Errors::setErrorType('warning', Translate::t($lang, 'Not_found_data'));
            $data = 0;
        }

        /** Common data for user */
        $userFurlough = $records->commonDetails($where, ['quantity'])['furlough']->quantity;
        $userAbsentees = $records->commonDetails($where, ['quantity'])['absentees']->quantity;
        $userUnpaidDays = $records->commonDetails($where, ['quantity'])['unpaid']->quantity;

        /** Total data for common tables */
        $totalFurloughs = $records->sumAllCommonData($whereSum, 'quantity')['furlough']->total;
        $totalAbsentees = $records->sumAllCommonData($whereSum, 'quantity')['absentees']->total;
        $totalUnpaid = $records->sumAllCommonData($whereSum, 'quantity')['unpaid']->total;
    }

        /** If user search data for all months */
        if (!is_numeric($month)) {
            // Conditions for action
            $where = [
                ['employees_id', '=', $user->userId()],
                'AND',
                ['year', '=', $year]
            ];

            // Conditions for COUNT action (total)
            $sumCommonDataAll = [
                ['offices_id', '=', $user->officeId()],
                'AND',
                ['year', '=', $year]
            ];

            /** Selected chart */
            $dataAllMonths = $records->arrayMultipleRecords($prefixTbl, $where, ['month', 'quantity'], $lang);

            /** Common charts */
            $furloughCommon   = $records->arrayMultipleRecords(Params::TBL_FURLOUGH, $where, ['month', 'quantity'], $lang);
            $absenteesCommon  = $records->arrayMultipleRecords(Params::TBL_ABSENTEES, $where, ['month', 'quantity'], $lang);
            $unpaidCommon     = $records->arrayMultipleRecords(Params::TBL_UNPAID, $where, ['month', 'quantity'], $lang);

            /** Total data for all employees from common charts */
            $totalFurloughs = $records->sumAllCommonData($sumCommonDataAll, 'quantity')['furlough']->total;
            $totalAbsentees = $records->sumAllCommonData($sumCommonDataAll, 'quantity')['absentees']->total;
            $totalUnpaid    = $records->sumAllCommonData($sumCommonDataAll, 'quantity')['unpaid']->total;

            /** User total data for all months */
            $userFurlough   = $records->sumAllCommonData($where, 'quantity')['furlough']->total;
            $userAbsentees  = $records->sumAllCommonData($where, 'quantity')['absentees']->total;
            $userUnpaidDays = $records->sumAllCommonData($where, 'quantity')['unpaid']->total;

            /** Common charts */
            $furloughChartLabel     = Js::key($furloughCommon);
            $furloughChartValues    = Js::values($furloughCommon);

            $absenteesChartLabel    = Js::key($absenteesCommon);
            $absenteesChartValues   = Js::values($absenteesCommon);

            $unpaidChartLabel       = Js::key($unpaidCommon);
            $unpaidChartValues      = Js::values($unpaidCommon);

            /** Selected table chart */
            if (!empty($dataAllMonths)) {
                $chartLabels = Js::key($dataAllMonths);
                $chartValues = Js::values($dataAllMonths);
            } else {
                Errors::setErrorType('warning', 'Not_found_data');
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
    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Dashboard'); ?></h2>
            </div>
        </div>
        <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade hide">
            <div class="loader loader-3">
                <div class="dot dot1"></div>
                <div class="dot dot2"></div>
                <div class="dot dot3"></div>
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
                                <?php echo Translate::t($lang, 'Filters'); ?>
                            </button>
                        </p>
                        <div class="<?php if (Input::exists() && !Errors::countAllErrors()) { echo "collapse";} else { echo "collapse show"; } ?>" id="collapseExample">
                            <div class="card card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="title"><strong><?php echo Translate::t($lang, 'Filters'); ?></strong></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                                <option value=""><?php echo Translate::t($lang, 'Select_year'); ?></option>
                                                <?php
                                                foreach (Common::getYearsList() as $year) { ?>
                                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('year'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value=""><?php echo Translate::t($lang, 'Select_month'); ?></option>
                                                <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
                                                <?php } ?>
                                                <option value="All">ALL</option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('month'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="col-sm-4">
                                            <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                                <option value=""><?php echo Translate::t($lang, 'Select_table'); ?></option>
                                                <?php foreach ($tables as $table) { ?>
                                                    <option value="<?php echo trim($table); ?>"><?php echo Translate::t($lang, $table, ['ucfirst' => true]); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('table'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="col-sm-2">
                                            <button id="Submit" value="<?php echo Translate::t($lang, 'Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t($lang, 'Submit'); ?></button>
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
                        <?php foreach ($records->commonDetails($where, ['quantity']) as $table => $value) {
                            $table = $table === Translate::t($lang, 'unpaid') ? Translate::t($lang, 'unpaid_days') : $table;
                            ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="statistic-block block">
                                    <div class="progress-details d-flex align-items-end justify-content-between">
                                        <div class="title">
                                            <div class="icon"><i class="icon-list"></i></div><strong><?php echo Translate::t($lang, $table); ?></strong>
                                        </div>
                                        <div class="number dashtext-2"><?php echo $value->quantity; ?></div>
                                    </div>
                                    <div class="progress progress-template">
                                        <div role="progressbar" style="width: 100%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="statistic-block block">
                                <div class="progress-details d-flex align-items-end justify-content-between">
                                    <div class="title">
                                        <div class="icon"><i class="icon-list"></i></div><strong><?php echo Translate::t($lang, 'Unpaid_h'); ?></strong>
                                    </div>
                                    <div class="number dashtext-2"><?php echo $records->unpaidHours($where)->hours; ?>h</div>
                                </div>
                                <div class="progress progress-template">
                                    <div role="progressbar" style="width: 100%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        <?php }
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
                                            <strong><?php echo Translate::t($lang, 'target') . ' - ' . Common::numberToMonth($month, $lang) . ' - ' . Input::post('year'); ?></strong>
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
                        <div class="col-lg-4">
                            <div class="stats-with-chart-1 block">
                                <div class="title"><strong class="d-block"><?php echo Translate::t($lang, 'furlough'); ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5">
                                        <div class="text"><strong
                                                    class="d-block dashtext-3"><?php echo $userFurlough; ?>
                                                <small><?php echo $day = $userFurlough > 1 ? Translate::t($lang, 'Days') : Translate::t($lang, 'Day'); ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t($lang, 'Total_user_furlough') . ' ' . $totalFurloughs; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="furloughChart" style="display: block; width: 166px; height: 157px;" width="166" height="157" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="stats-with-chart-1 block">
                                <div class="title"><strong class="d-block"><?php echo Translate::t($lang, 'absentees');  ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5">
                                        <div class="text"><strong
                                                    class="d-block dashtext-1"><?php echo $userAbsentees; ?>
                                                <small><?php echo $day = $userAbsentees > 1 ? Translate::t($lang, 'Days') : Translate::t($lang, 'Day'); ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t($lang, 'Team_absentees') . ': ' . $totalAbsentees; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="absenteesPieChart" style="display: block; width: 166px; height: 157px;" width="166" height="157" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="stats-with-chart-1 block">
                                <div class="title"><strong class="d-block"><?php echo Translate::t($lang, 'Total_user_unpaid'); ?></strong></div>
                                <div class="row d-flex align-items-end justify-content-between">
                                    <div class="col-5">
                                        <div class="text"><strong
                                                    class="d-block dashtext-2"><?php echo $userUnpaidDays; ?>
                                                <small><?php echo $day = $userUnpaidDays > 1 ? Translate::t($lang, 'Days') : Translate::t($lang, 'Day');  ?></small>
                                            </strong>
                                            <span class="d-block"><?php echo Common::getMonths($lang)[$month] . ' ' . Input::post('year'); ?></span>
                                            <small class="d-block"><?php echo Translate::t($lang, 'Team_absentees') . ': ' . $totalUnpaid; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="bar-chart chart">
                                            <canvas id="unpaidChart" style="display: block; width: 166px; height: 157px;" width="166" height="157" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";

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
</script>
</body>
</html>