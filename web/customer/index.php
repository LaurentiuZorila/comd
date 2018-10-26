<?php
require_once 'core/init.php';
$user   = new CustomerUser();
$data   = new CustomerProfile();
$best   = new Best($user->officesId());

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

/** Count all employees */
$countUsers = $data->count(Params::TBL_EMPLOYEES, ['offices_id', '=', $user->officesId()]);

/** Data for common tables */
$sumFurlough   = $data->sum(Params::TBL_FURLOUGH, ['offices_id', '=', $user->officesId()], 'quantity');
$sumAbsentees  = $data->sum(Params::TBL_ABSENTEES, ['offices_id', '=', $user->officesId()], 'quantity');
$sumUnpaid     = $data->sum(Params::TBL_UNPAID, ['offices_id', '=', $user->officesId()], 'quantity');

/** tables for user */
$allTables  = $data->records(Params::TBL_OFFICE, ['id', '=', $user->officesId()], ['tables'], false);
$allTables  = explode(',', trim($allTables->tables));

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
        $officeId   = $user->officesId();
        $npTable    = Input::post('table');
        $table      = Params::PREFIX . trim(Input::post('table'));
        $quantitySum = [];


        /** Conditions for action */
        $where = [
            ['year', '=', $year],
            'AND',
            ['offices_id', '=', $officeId],
            'AND',
            ['month', '=', $month]
        ];

        /** Array with all results for one FTE to use in chart */
        $chartData  = $data->records($table, $where, ['quantity', 'employees_id']);

        /** Chart names */
        foreach ($chartData as $chartNames) {
            $names[] = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $chartNames->employees_id], ['name'], false)->name;
        }

        /** Quantity data */
        foreach ($chartData as $datas) {
            $quantity[] = $datas->quantity;
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
            Errors::setErrorType('warning', 'Please select other values and try again!');
        }
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
      <!-- Sidebar Navigation end-->
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Dashboard</h2>
          </div>
        </div>
          <?php
                  if (Input::exists() && Errors::countAllErrors()) {
                      include './../common/errors/errors.php';
          ?>
          <section>
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-body">
                          <div class="alert alert-dismissible fade show badge-warning" role="alert">
                              <strong class="text-white"> Your profile is configured.  </strong>
                              <p class="text-white mb-0"> <?php echo Session::flash('configOk'); ?> </p>
                              <p class="text-white mb-0">Click this <a href="update_database.php">link</a> to update your database.</p>
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <?php } ?>
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                    Filters
                </button>
            </p>
            <div class="<?php if (Input::exists() && !!Errors::countAllErrors()) { echo "collapse";} elseif(!Input::exists()) { echo "collapse"; } else { echo "collapse show"; } ?>" id="filter">
              <div class="block">
                  <form method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="title"><strong>Filters</strong></div>
                        </div>
                            <div class="col-sm-4">
                                <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                    <option value="">Select Year</option>
                                    <?php
                                    foreach (Common::getYearsList() as $year) { ?>
                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback">Year field are required!</div>
                                <?php }?>
                            </div>

                            <div class="col-sm-4">
                              <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) { echo 'is-invalid'; } else { echo 'mb-3';} ?>">
                                  <option value="">Select Month</option>
                                  <?php foreach (Common::getMonths() as $key => $value) { ?>
                                  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                  <?php } ?>
                              </select>
                              <?php
                              if (Input::exists() && empty(Input::post('month'))) { ?>
                                  <div class="invalid-feedback">Month field are required!</div>
                              <?php }?>
                            </div>

                            <div class="col-sm-4">
                                <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Table</option>
                                    <?php foreach ($tables as $key => $table) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo strtoupper($table); ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('table'))) { ?>
                                    <div class="invalid-feedback">Table field are required!</div>
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
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="statistic-block block">
                      <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                          <div class="icon"><i class="icon-user-1"></i></div><strong>Total employees</strong>
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
                          <div class="icon"><i class="icon-info"></i></div><strong>Total absentees</strong>
                        </div>
                        <div class="number dashtext-3"><?php echo $sumAbsentees > 0 ? $sumAbsentees : 0; ?></div>
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
                          <div class="icon"><i class="icon-list-1"></i></div><strong>Total furlough</strong>
                        </div>
                        <div class="number dashtext-3"><?php echo $sumFurlough > 0 ? $sumFurlough : 0; ?></div>
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
                                <div class="icon"><i class="icon-list-1"></i></div><strong>Total unpaid</strong>
                            </div>
                            <div class="number dashtext-3"><?php echo $sumUnpaid > 0 ? $sumUnpaid : 0; ?></div>
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
                                          <button class="btn btn-primary bar" id="bar" type="button">Bar</button>
                                          <button class="btn btn-outline-primary line" id="line" type="button">Line</button>
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

        <section class="no-padding-bottom">
          <div class="container-fluid">
              <!--              FOR BEST OPERATOR-->
            <div class="row">
              <div class="col-lg-2">
                <div class="user-block block text-center">
                  <div class="avatar"><img src="./../common/img/user.png" alt="..." class="img-fluid">
                    <div class="order dashbg-2">1st</div>
                  </div><a href="#" class="user-title mb-0"><h3 class="h5"><?php echo $best->getBestEmployeesName(); ?></h3></a>
                  <div class="contributions mb-2">Best Operator</div>
                      <?php
                      foreach ($best->getCommonData() as $key => $commonData) { ?>
                          <p class="text-primary mb-0"><small><?php echo strtoupper($key) . ' - ' . $commonData; ?></small></p>
                      <?php } ?>
                </div>
              </div>
                <div class="col-lg-5">
                    <div class="stats-with-chart-1 block" style="height: 91%;">
                        <div class="title"> <strong class="d-block"><?php echo strtoupper($best->getFirstPriorityTbl()) . ' AVERAGE'; ?></strong></div>
                        <div class="row d-flex align-items-end justify-content-between">
                            <div class="col-12">
                                <div class="bar-chart chart">
                                    <canvas id="bestFirst" style="display: block; width: 400px; height: 250px;" width="400" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="stats-with-chart-1 block" style="height: 91%;">
                        <div class="title"> <strong class="d-block"><?php echo strtoupper($best->getSecondPriorityTbl()) . ' AVERAGE'; ?></strong></div>
                        <div class="row d-flex align-items-end justify-content-between">
                            <div class="col-12">
                                <div class="bar-chart chart">
                                    <canvas id="bestSecond" style="display: block; width: 400px; height: 250px;" width="400" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              <!--              BEST OPERATOR END  -->
              <?php
                    $x = 1;
                    foreach ($allData as $key => $value) {
                        ?>
                        <div class="public-user-block block">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-4 d-flex align-items-center">
                                    <div class="order"><?php echo $x; ?></div>
                                    <div class="avatar"></div>
                                    <a href="#" class="name" data-toggle="tooltip" data-placement="top" title="Name">
                                        <strong class="d-block"><?php echo $key; ?></strong>
                                        <span class="d-block"></span>
                                    </a>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="contributions" data-toggle="tooltip" data-placement="top" title="Month"><?php echo escape(ucfirst($npTable)) . ' - ' . escape(Common::getMonths()[$month]); ?></div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="details d-flex">
                                        <div class="item" data-toggle="tooltip" data-placement="top" title="<?php echo ucfirst($npTable); ?>"><i class="icon-chart"></i>
                                            <strong><?php echo $value; ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $x++;
                    }
                }
            ?>
          </div>
        </section>
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
      include 'charts/bestChart.php';
      include 'charts/target_chart.php';
  }
  ?>
  </body>
</html>