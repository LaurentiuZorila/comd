<?php
require_once 'core/init.php';

/** Count all employees */
$countUsers = $leadData->count(Params::TBL_EMPLOYEES, ['offices_id', '=', $lead->officesId()]);


// If input don't exist sum data for current year else sum for selected year
if (!Input::existsName('post', Tokens::getInputName())) {
    /** Data for common tables */
    $where = AC::where([
        ['offices_id', $lead->officesId()],
        ['year', date('Y')]
    ]);
} elseif (Input::existsName('post', Tokens::getInputName())) {
    /** Data for common tables */
    $where = AC::where([
        ['offices_id', $lead->officesId()],
        ['year', Input::post('year')]
    ]);
}

$sumFurlough   = $leadData->sum(Params::TBL_FURLOUGH, $where, 'quantity');
$sumAbsentees  = $leadData->sum(Params::TBL_ABSENTEES, $where, 'quantity');
$sumUnpaid     = $leadData->sum(Params::TBL_UNPAID, $where, 'quantity');
$sumMedical    = $leadData->sum(Params::TBL_MEDICAL, $where, 'quantity');


/** tables for user */
$allTables  = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['tables'], false);
$allTables  = explode(',', trim($allTables->tables));

/** Data display */
$dataDisplay = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['data_visualisation'], false)->data_visualisation;
$dataDisplay = (array)json_decode($dataDisplay);
foreach ($dataDisplay as $tableData => $v){
    $tblDataDysplay[] = $tableData;
}

/** Array with tables $k => $v */
foreach ($allTables as $table) {
    $tables[trim($table)] = trim($table);
}


/** If form is submitted */
if (Input::exists()) {
    /** Instantiate validation class */
    $validate = new Validate();
    // Check if all fields are filed
    $validation = $validate->check($_POST, [
        'year'  => ['required' => true],
        'month' => ['required' => true],
        'table' => ['required' => true]
    ]);

    /** If validation passed */
    if ($validation->passed()) {
        $year       = Input::post('year');
        $month      = Input::post('month');
        $officeId   = $lead->officesId();
        $npTable    = Translate::t(strtolower(Input::post('table')), ['strtolower']);
        $table      = Params::PREFIX . trim(Input::post('table'));
        $quantitySum = [];


        /** Conditions for action */
        $where = AC::where([
            ['year', $year],
            ['offices_id', $officeId],
            ['month', $month]
        ]);

        /** Array with all results for one FTE to use in chart */
        $chartData  = $leadData->records($table, $where, ['quantity', 'employees_id']);
        
        /** Chart names */
        foreach ($chartData as $chartNames) {
            $names[] = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $chartNames->employees_id]), ['name'], false)->name;
        }


        /** Quantity data */
        foreach ($chartData as $data) {
            $quantity[] = is_null($data->quantity) ? 0 : $data->quantity;
        }

        // Insert returned values in array
        foreach ($chartData as $value) {
            $quantitySum[] = $value->quantity;
        }

        /** Check if submitted form return values */
        if (count($quantitySum) > 1) {
            // Assoc array with names => quantity
            $allData = array_combine($names, $quantity);

            // Charts labels and values
            $chartNames = Js::toJson($names);
            $chartValues = Js::chartValues($chartData, 'quantity');
        } else {
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
    <link rel="stylesheet" href="../common/vendor/dataTables/dataTables.bootstrap4.min.css">
    <script src="../common/vendor/dataTables/datatables.min.js"></script>
    <script src="../common/vendor/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="./../common/vendor/chart.js/Chart.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#employeesTable').DataTable();
        });
    </script>
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
          ?>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
            <p>
                <button class="btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
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
                              <option value="<?php echo Input::exists() && !empty(Input::post('month')) ? Input::post('month') : ''; ?>"><?php echo Input::exists() && !empty(Input::post('month')) ? Common::numberToMonth(Input::post('month'), Session::get('lang')) : Translate::t('Select_month', ['ucfirst']); ?></option>
                              <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                              <option value="<?php echo $key; ?>"><?php echo ucfirst($value); ?></option>
                              <?php } ?>
                          </select>
                          <?php
                          if (Input::exists() && empty(Input::post('month'))) { ?>
                              <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                          <?php }?>
                        </div>

                        <div class="col-sm-4">
                            <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                <option value="<?php echo Input::exists() && !empty(Input::post('table')) ? Input::post('table') : ''; ?>"><?php echo Input::exists() && !empty(Input::post('table')) ? Translate::t(strtolower(Input::post('table')), ['ucfirst']) : Translate::t('Select_table', ['ucfirst']); ?></option>
                                <?php foreach ($tables as $key => $table) {
                                    if (in_array($table, Params::TBL_COMMON)) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo Translate::t($table, ['ucfirst']); ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $key; ?>"><?php echo ucfirst($table); ?></option>
                                    <?php }
                                } ?>
                            </select>
                            <?php
                            if (Input::exists() && empty(Input::post('table'))) { ?>
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
                <div class="col-md-12 col-sm-12">
                    <div class="statistic-block block">
                      <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                          <div class="icon"><i class="icon-user-1"></i></div><strong><?php echo Translate::t('All_employees'); ?></strong>
                        </div>
                        <div class="number dashtext-1"><?php echo $countUsers; ?></div>
                      </div>
                      <div class="progress progress-template">
                        <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                      <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                          <div class="icon"><i class="icon-info"></i></div><strong><?php echo Translate::t('Total_user_absentees'); ?></strong>
                        </div>
                        <div class="number dashtext-3">
                            <h5 class="mb-1"><?php echo $sumAbsentees > 0 ? $sumAbsentees . '<small>' . Translate::t('Days', ['strtolower'=>true]) . '</small>' : 0 . '<small>' . Translate::t('Day', ['strtolower'=>true]) . '</small>'; ?></h5>
                        </div>
                      </div>
                      <div class="progress progress-template">
                        <div role="progressbar" style="width: 100%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                      <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                          <div class="icon"><i class="icon-list-1"></i></div><strong><?php echo Translate::t('Total_user_furlough'); ?></strong>
                        </div>
                        <div class="number dashtext-3">
                            <h5 class="mb-1"><?php echo $sumFurlough > 0 ? $sumFurlough . '<small>' . Translate::t('Days', ['strtolower'=>true]) . '</small>' : 0 . '<small>' . Translate::t('Day', ['strtolower'=>true]) . '</small>'; ?></h5>
                        </div>
                      </div>
                      <div class="progress progress-template">
                        <div role="progressbar" style="width: 100%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                      </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                        <div class="progress-details d-flex align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"><i class="icon-list-1"></i></div><strong><?php echo Translate::t('Total_user_unpaid'); ?></strong>
                            </div>
                            <div class="number dashtext-3">
                                <h5 class="mb-1"><?php echo $sumUnpaid > 0 ? $sumUnpaid . '<small>' . Translate::t('Days', ['strtolower'=>true]) . '</small>' : 0 . '<small>' . Translate::t('Day', ['strtolower'=>true]) . '</small>'; ?></h5>
                            </div>
                        </div>
                        <div class="progress progress-template">
                            <div role="progressbar" style="width: 100%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                        <div class="progress-details d-flex align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"><i class="icon-list-1"></i></div><strong><?php echo Translate::t('Total_user_medical'); ?></strong>
                            </div>
                            <div class="number dashtext-3">
                                <h5 class="mb-1"><?php echo $sumMedical > 0 ? $sumMedical . '<small>' . Translate::t('Days', ['strtolower'=>true]) . '</small>' : 0 . '<small>' . Translate::t('Day', ['strtolower'=>true]) . '</small>'; ?></h5>
                            </div>
                        </div>
                        <div class="progress progress-template">
                            <div role="progressbar" style="width: 100%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </section>
<!--        ********************       CHARTS         ********************   -->
          <?php
          /** IF FORM IS SUBMITTED */
          if (Input::exists() && !Errors::countAllErrors()) { ?>
          <section class="no-padding-bottom">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="bar-chart block chart">
<!--                                      <ul class="nav nav-pills card-header-pills">-->
<!--                                          <li class="nav-item"><button class="btn btn-primary mr-1 bar" id="bar" type="button">Bar</button></li>-->
<!--                                          <li class="nav-item"><button class="btn btn-outline-primary line" id="line" type="button">Line</button></li>-->
<!--                                      </ul>-->
                              <div class="btn-group btn-group-sm float-sm-right" role="group" aria-label="Charts type">
                                  <button class="btn-sm btn-primary bar" id="bar" type="button"><?php echo Translate::t('Bar'); ?></button>
                                  <button class="btn-sm btn-outline-primary line" id="line" type="button"><?php echo Translate::t('Line'); ?></button>
                              </div>
                              <div class="drills-chart block">
                                  <canvas id="target_customer_chart_bar" height="150" style="display: block;"></canvas>
                                  <canvas id="target_customer_chart_line" height="150" style="display: none;"></canvas>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <!--        ********************       CHARTS   END      ********************   -->

          <section class="no-padding-top">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="block margin-bottom-sm">
                                <div class="table-responsive">
                                  <table class="table" id="employeesTable">
                                      <thead>
                                      <tr>
                                          <th>#</th>
                                          <th><?php echo Translate::t('Name', ['strtoupper']); ?></th>
                                          <th><?php echo Translate::t('Data', ['strtoupper']); ?></th>
                                          <th><?php echo Translate::t(strtolower(Input::post('table')), ['strtoupper']); ?></th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr>
                                          <?php
                                          $x = 1;
                                          foreach ($allData as $key => $value) {
                                          ?>
                                          <th scope="row"><?php echo $x; ?></th>
                                          <td><?php echo $key; ?></td>
                                          <td><?php echo Common::getMonths($lang)[$month] . ' - ' . Input::post('year'); ?></td>
                                          <td><?php echo in_array(strtolower($npTable), $tblDataDysplay) && $dataDisplay[strtolower($npTable)] == 'percentage' ? $value . '%' : $value; ?></td>
                                      </tr>
                                      <?php $x++; } ?>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <?php }
          include '../common/includes/footer.php';
          ?>
      </div>
    </div>
<script>

    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });

    $("#bar").click(function(){
        $('.line').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.bar').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#target_customer_chart_bar").show();
        $("#target_customer_chart_line").hide();
    });

    $("#line").click(function(){
        $('.bar').removeClass('btn-primary').addClass('btn-outline-primary');
        $('.line').removeClass('btn-outline-primary').addClass('btn-primary');
        $("#target_customer_chart_line").show();
        $("#target_customer_chart_bar").hide();
    });
</script>
  <?php
  /** BEST CHART and Form Chart */
  if (Input::exists() && !Errors::countAllErrors()) {
      include 'charts/target_chart.php';
  }
  ?>
  </body>
</html>