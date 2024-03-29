<?php
require_once 'core/init.php';

/** If form is submitted */
if (Input::exists() && Tokens::tokenVerify()) {
    /** Instantiate validation class */
    $validate = new Validate();

    /** Validate inputs */
    $validation = $validate->check($_POST, [
            'year'  => ['required'  => true],
            'month' => ['required'  => true],
            'teams' => ['required'  => true],
            'table' => ['required'  => true]
    ]);

    /** Check if validation is passed */
    if ($validation->passed()) {
        $year           = Input::post('year');
        $month          = Input::post('month');
        $officeId       = Input::post('teams');
        $tbl            = strtolower(trim(Input::post('table')));
        $table          = $backendUserProfile->getAssocTables($officeId, $tbl);

        /** Conditions for action */
        $where = AC::where([['year', $year], ['offices_id', $officeId], ['month', $month]]);

        /** Array with all results for one FTE */
        $chartData      = $backendUserProfile->records($table, $where, ['employees_id', 'quantity']);

        foreach ($chartData as $record) {
            $name = $backendUserProfile->records(Params::TBL_EMPLOYEES, AC::where(['id', $record->employees_id]), ['name'], false)->name;
            $records[$name] = $record->quantity;
        }

        if (!empty($records)) {
            $chartNames     = Js::toJson(array_keys($records));
            $chartValues    = implode(',',$records);
            $pieCommonData  = implode(',', $backendUserProfile->getSumFormCommonTables($where));
            $chartLabel     = in_array($table, Params::PREFIX_TBL_COMMON) ? Translate::t($tbl, ['ucfirst']) : ucfirst($tbl);
            $pieLabel       = '"' . implode('","',Params::TBL_COMMON_TRANSLATED) . '"';
            $pieBgColors    = $backendUserProfile->pieBgColors();
        } else {
            Session::put('selected_month', Common::numberToMonth($month, $backendUser->language()));
            Session::put('selected_year', $year);
            Errors::setErrorType('info', Translate::t('Not_found_data'));
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
        <?php if (Input::exists() && Errors::countAllErrors()) {
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
                                    <div class="title"><strong>Filters</strong></div>
                                </div>
                                <div class="col-sm-3">
                                    <select name="teams" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                        <option value=""><?php echo Translate::t('Select_team'); ?></option>
                                        <?php foreach ($backendUserProfile->getOffices(['id', 'name']) as $office) { ?>
                                            <option value="<?php echo $office->id; ?>"><?php echo $office->name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php
                                    if (Input::exists() && empty(Input::post('table'))) { ?>
                                        <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                    <?php }?>
                                </div>
                                <div class="col-sm-3">
                                    <select name="table" id="#table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                        <option value=""><?php echo Translate::t('Select_table'); ?></option>
                                    </select>
                                    <?php
                                    if (Input::exists() && empty(Input::post('table'))) { ?>
                                        <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                    <?php }?>
                                </div>
                                <div class="col-sm-3">
                                    <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                        <option value=""><?php echo Translate::t('Select_year'); ?></option>
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
                                <div class="col-sm-3">
                                    <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
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
                                <div class="col-sm-2">
                                    <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                    <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </section>
        <section class="no-padding-top no-padding-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-sm-3">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon"><i class="icon-list"></i></div><strong><?php echo Translate::t('Offices'); ?></strong>
                                </div>
                                <div class="number dashtext-2"><?php echo $backendUserProfile->countOffices(); ?></div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                            </div>
                            <a href="<?php echo Config::get('route/allStaff'); ?>" class="tile-link"></a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-3">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon"><i class="icon-user"></i></div><strong><?php echo Translate::t('All_staff'); ?></strong>
                                </div>
                                <div class="number dashtext-2"><?php echo $backendUserProfile->countStaff(); ?></div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                            </div>
                            <a href="<?php echo Config::get('route/allStaff'); ?>" class="tile-link"></a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-3">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon"><i class="icon-user-1"></i></div><strong><?php echo Translate::t('All_employees'); ?></strong>
                                </div>
                                <div class="number dashtext-2"><?php echo $backendUserProfile->countEmployees(); ?></div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                            </div>
                            <a href="<?php echo Config::get('route/employees'); ?>" class="tile-link"></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php if (Input::exists() && !Errors::countAllErrors()) { ?>
            <section class="no-padding-top no-padding-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <?php foreach ($backendUserProfile->getSumFormCommonTables($where, true) as $table => $records) { ?>
                        <div class="col-md-2 col-sm-3">
                            <div class="statistic-block block">
                                <div class="progress-details d-flex align-items-end justify-content-between">
                                    <div class="title">
                                        <div class="icon"><i class="icon-info"></i></div><strong style="color: <?php echo Params::BACKEND_ASSOC_PREFIX_TBL[$table]['pie_chart_color']; ?>;"><?php echo Translate::t($backendUserProfile->forTranslate[$table]); ?></strong>
                                    </div>
                                    <div class="number" style="color: <?php echo Params::BACKEND_ASSOC_PREFIX_TBL[$table]['pie_chart_color']; ?>;"><?php echo $records; ?></div>
                                </div>
                                <div class="progress progress-template">
                                    <div role="progressbar" style="width: 100%; background: <?php echo Params::BACKEND_ASSOC_PREFIX_TBL[$table]['pie_chart_color']; ?>;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template"></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <!--        ********************       CHARTS         ********************   -->
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="bar-chart block chart">
                                <ul class="nav nav-pills card-header-pills">
                                    <li class="nav-item"><button class="btn-sm btn-primary mr-1 bar" id="bar" type="button"><?php echo Translate::t('Bar'); ?></button></li>
                                    <li class="nav-item"><button class="btn-sm btn-outline-primary line" id="line" type="button"><?php echo Translate::t('Line'); ?></button></li>
                                </ul>
                                <div class="drills-chart block">
                                    <canvas id="backendIndexBarChart" style="display: block; width: 494px; height: 250px;" width="494" height="250"></canvas>
                                    <canvas id="backendIndexLineChart" style="display: none; width: 494px; height: 247px;" width="494" height="247"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="bar-chart block chart">
                                <div class="drills-chart block">
                                    <canvas id="totalCommonTables" style="height: 280px;" height="280"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
        include '../common/includes/footer.php';
        ?>
        <!--        ********************       CHARTS   END      ********************   -->
    </div>
</div>
<?php
if (Input::exists() && !Errors::countAllErrors()) {
    include 'charts/index_charts.php';
}
?>
<script>
    $( "select[name='teams']" ).change(function () {
        var officeId = $(this).val();
        if(officeId) {
            $.ajax({
                url: "ajax/staff_tables.php",
                dataType: 'Json',
                data: {'office_id':officeId},
                success: function(data) {
                    $('select[name="table"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="table"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        }else{
            $('select[name="table"]').empty();
        }
    });

    $("#bar").click(function(){
        $('.line').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.bar').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#backendIndexBarChart").show();
        $("#backendIndexLineChart").hide();
    });

    $("#line").click(function(){
        $('.bar').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.line').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#backendIndexLineChart").show();
        $("#backendIndexBarChart").hide();
    });

    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>

</body>
</html>