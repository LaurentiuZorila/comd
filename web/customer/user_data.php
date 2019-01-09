<?php
require_once 'core/init.php';

/** All tables */
$allTables = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['tables'], false);
$allTables = explode(',', trim($allTables->tables));

/** All employees for user */
$allEmployees = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $lead->officesId()]), ['id', 'name']);

/** Data display */
$dataDisplay = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['data_visualisation'], false)->data_visualisation;
$dataDisplay = (array)json_decode($dataDisplay);
foreach ($dataDisplay as $tableData => $v){
    $tblDataDysplay[] = $tableData;
}


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

            /** Conditions for action */
            $where = AC::where([
                ['year', $year],
                ['employees_id', $employeesId],
                ['month', $month]
            ]);
            /** array key => values (keys are tables and values are numbers(quantity column)) */
            foreach ($allTables as $table) {
                $key[]      = $table;
                $values[]   = empty($leadData->records(Params::PREFIX . $table, $where, ['quantity'], false)->quantity) ? 0 : $leadData->records(Params::PREFIX . $table, $where, ['quantity'], false)->quantity;
                $allData    = array_combine($key, $values);
            }

            /** All data for customer */
            $employeesDetails = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $employeesId]), ['name', 'offices_id'], false);

            $name       = $employeesDetails->name;
            $officeName = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $employeesDetails->offices_id]), ['name'], false)->name;
            $initials   = Common::makeAvatar($name);


            // Get all common data
            foreach (Params::TBL_COMMON as $commonTables) {
                $commonDataCollapse[$commonTables] = $leadData->records(Params::PREFIX . $commonTables,
                    AC::where([
                        ['employees_id', $employeesId],
                        ['year', $year]
                    ]), ['year', 'month', 'quantity', 'days', 'employees_id'], true);
            }

            /** Check if exists values */
            if (!Common::checkValues($allData)) {
                Errors::setErrorType('warning', Translate::t('Not_found_data'));
            }
        }
}

/** If get exists and post doesn't exists */
if (Input::existsName('get', 'id') && !Input::exists()) {
    $employeesId    = Input::get('id');
    $year           = date('Y');
    $month          = date('n');

    /** Conditions for action */
    $where = AC::where([
        ['year', $year],
        ['employees_id', $employeesId],
        ['month', $month]
    ]);

    /** array key => values (keys are tables and values are numbers(quantity column)) */
    foreach ($allTables as $table) {
        $key[]      = $table;
        $values[]   = empty($leadData->records(Params::PREFIX . $table, $where, ['quantity'], false)->quantity) ? 0 : $leadData->records(Params::PREFIX . $table, $where, ['quantity'], false)->quantity;
        $allData    = array_combine($key, $values);
    }

    /** All data for customer */
    $employeesDetails   = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $employeesId]), ['name', 'offices_id'], false);
    $officeObj          = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $employeesDetails->offices_id]), ['name'], false);

    $name       = $employeesDetails->name;
    $officeName = $officeObj->name;
    $initials   = Common::makeAvatar($name);

    /** Check if exists values */
    if (Common::checkValues($allData) === true) {
        Errors::setErrorType('info', Translate::t('Not_found_data'));
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
    // LOADING PRELOADER MODAL
    include './../common/includes/preloaders.php';
    ?>
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Employees'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Employees_details'); ?></li>
            </ul>
        </div>
        <?php
        if (Input::exists() && Errors::countAllErrors() || Input::exists('get') && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
                <p>
                    <button class="btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                        <?php echo Translate::t('Filters'); ?>
                    </button>
                </p>
                <div class="<?php if (Input::exists() && !Errors::countAllErrors()) { echo "collapse";} else { echo "collapse show"; } ?>" id="filter">
                <div class="block">
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title"><strong><?php echo Translate::t('Filters'); ?></strong></div>
                            </div>
                            <div class="col-sm-4">
                                <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                    <option value=""><?php echo Translate::t('Select_year'); ?></option>
                                    <?php
                                    foreach (Common::getYearsList() as $year) { ?>
                                        <option><?php echo $year; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                <?php }?>
                            </div>
                            <div class="col-sm-4">
                                <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} ?>">
                                    <option value=""><?php echo Translate::t('Select_month'); ?></option>
                                    <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('month'))) { ?>
                                    <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                <?php }?>
                            </div>
                            <div class="col-sm-4">
                                <select name="employees" class="form-control <?php if (Input::exists() && empty(Input::post('employees'))) {echo 'is-invalid';} ?>">
                                    <option value=""><?php echo Translate::t('Select_Employees'); ?></option>
                                    <?php foreach ($allEmployees as $employees) { ?>
                                        <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('employees'))) { ?>
                                    <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                <?php }?>
                            </div>
                            <div class="col-sm-2 mt-2">
                                <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <?php if (Input::exists('get') && !Input::exists() || Input::exists()) {
            if (!Errors::countAllErrors()) {
                $month = Input::post('month');
                $year  = Input::post('year');
                $month = !empty($month) ? $month : date('n');
                $year  = !empty($year) ? $year : date('Y');
                ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <blockquote class="blockquote mb-0 card-body">
                                <h3><?php echo $name; ?></h3>
                                <footer class="blockquote-footer">
                                    <small class="text-muted"><?php echo $officeName; ?></small>
                                </footer>
                                <footer class="blockquote-footer">
                                    <small class="text-muted"><?php echo Common::numberToMonth($month, $lang) . ' - ' . $year; ?></small>
                                </footer>
                            </blockquote>
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
                            <?php foreach ($allData as $key => $value) { ?>
                                <div class="stats-2 d-flex">
                                    <div class="stats-2-arrow low"><i class="fa <?php echo in_array($key, Params::TBL_COMMON) ? 'fa-info-circle' : 'fa-line-chart'; ?>"></i></div>
                                    <div class="stats-2-content common" id="" data-toggle="collapse" data-target="<?php echo in_array($key, Params::TBL_COMMON) ? $key : ''; ?>" aria-controls="<?php echo in_array($key, Params::TBL_COMMON) ? $key : ''; ?>" <?php echo in_array($key, Params::TBL_COMMON) ? 'style="cursor: pointer;"' : ''; ?> >
                                        <strong class="d-block dashtext-1">
                                            <?php
                                            echo in_array($key, $tblDataDysplay) && $dataDisplay[$key] === 'percentage' ? (!in_array($key, Params::TBL_COMMON) ? $value . '%' : $value) : (in_array($key, Params::TBL_COMMON) ? $value . '<small class="text-small">'  . Translate::t('Days', ['strtolower'=>true]) . '</small>' : $value);
                                            ?>
                                        </strong>
                                        <span class="d-block"><?php echo Translate::t($key, ['strtoupper'=>true]); ?></span>
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
        <?php foreach ($commonDataCollapse as $table => $fields) { ?>
            <section class="no-padding-top collapse allCollapse" id="<?php echo $table; ?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <div class="title"><strong class="dashtext-1"><?php echo Translate::t($table) . ' - ' . $name; ?></strong>
                                    <button type="button" class="btn btn-primary btn-sm float-sm-right closeDiv"><i class="fa fa-close"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th><?php echo Translate::t('Year', ['ucfirst'=>true]); ?></th>
                                            <th><?php echo Translate::t('month',['ucfirst'=>true]); ?></th>
                                            <th><?php echo Translate::t('quantity',['ucfirst'=>true]); ?></th>
                                            <th><?php echo Translate::t('Days',['ucfirst'=>true]); ?></th>
                                            <?php if ($table === 'furlough') { ?>
                                            <th><?php echo Translate::t('actions',['ucfirst'=>true]); ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($fields as $field) { ?>
                                        <tr>
                                            <td><?php echo $field->year; ?></td>
                                            <td><?php echo Common::numberToMonth($field->month, $lang); ?></td>
                                            <td><?php echo $field->quantity; ?> <small><?php echo $field->quantity > 1 ? Translate::t('Days', ['strtolower'=>true]) : Translate::t('Day', ['strtolower'=>true]); ?></small></td>
                                            <td><?php echo $field->days; ?></td>
                                            <?php if ($table === 'furlough') { ?>
                                            <td><a href="print.php?id=<?php echo $field->employees_id;?>&days=<?php echo $field->days;?>" target="_blank"><i class="fa fa-print" style="cursor: pointer;"></i></a></td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
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
if (Input::exists() && !Errors::countAllErrors() || Input::exists('get') && !Errors::countAllErrors()) {
    include './charts/useDataChart.php';
}
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });

    // Click to view common
    $ ( '.common' ).on('click', function () {
        $( '.allCollapse:visible' ).hide(function () {
            $(this).fadeOut(3000);
        });
        var $this = $(this);
        $( '#' + $this.data("target")).show(function () {
            $( '#' + $this.data("target")).fadeIn(3000);
        });
    });

    // Click close div
    $ ( '.closeDiv' ).on('click', function () {
        $( '.collapse:visible' ).hide(function () {
            $(this).fadeOut(3000);
        });
    });
</script>
</body>
</html>