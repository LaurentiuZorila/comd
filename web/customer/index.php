<?php
require_once 'core/init.php';
$user   = new CustomerUser();
$data   = new CustomerProfile();
$token  = new Token();

if (!$user->isLoggedIn()) {
    CustomerRedirect::to('login.php');
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

if (Input::exists() && $token->checkToken(Input::post('token'))) {
        $year       = Input::post('year');
        $month      = Input::post('month');
        $officeId   = $user->officesId();
        $npTable    = Input::post('table');
        $table      = Params::PREFIX . trim(Input::post('table'));
        $errors = [];
        $quantitySum = [];

        if (empty($month) || empty($year) || empty($table)) {
            $errors = [1];
        }

        if (count($errors) == 0) {
            // Conditions for action
            $where = [
                ['year', '=', $year],
                'AND',
                ['offices_id', '=', $officeId],
                'AND',
                ['month', '=', $month]
            ];

            /** Array with all results for one FTE to use in chart */
            $chartData  = $data->records($table, $where, ['quantity', 'name']);

            foreach ($chartData as $value) {
                $quantitySum[] = $value->quantity;
            }

            /** Charts labels and values */
            $chartNames = Js::toJson(Js::chartLabel($chartData, 'name'));
            $chartValues = Js::chartValues($chartData, 'quantity');

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
      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Dashboard</h2>
          </div>
        </div>
          <?php
          if (Input::exists() && count($errors) > 0) {
              include 'includes/errorRequired.php';
          }

          if (Input::exists() && count($errorNoData) > 0) {
              include 'includes/infoError.php';
          }
          if (Session::exists('configOk')) { ?>
          <section>
              <div class="row">
                  <div class="col-lg-12">
                      <div class="card-body">
                          <div class="alert alert-dismissible fade show badge-info" role="alert">
                              <p class="text-white"> <?php echo Session::flash('configOk'); ?> </p>
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
            <div class="<?php if (Input::exists() && count($errors) == 0 && count($errorNoData) == 0) { echo "collapse";} elseif(!Input::exists()) { echo "collapse"; } else { echo "collapse show"; } ?>" id="filter">
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
                                <input type="hidden" name="token" value="<?php echo $token->getToken(); ?>">
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
                        <div class="number dashtext-3"><?php echo $sumAbsentees; ?></div>
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
                        <div class="number dashtext-3"><?php echo $sumFurlough; ?></div>
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
                            <div class="number dashtext-3"><?php echo $sumUnpaid; ?></div>
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
          <?php if (Input::exists() && count($errors) === 0 && count($errorNoData) === 0) { ?>
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
              <?php } ?>
          <!--        ********************       CHARTS   END      ********************   -->
        <section class="no-padding-bottom">
          <div class="container-fluid">
<!--              FOR BEST OPERATOR-->

<!--            <div class="row">-->
<!--              <div class="col-lg-4">-->
<!--                <div class="user-block block text-center">-->
<!--                  <div class="avatar"><img src="img/avatar-1.jpg" alt="..." class="img-fluid">-->
<!--                    <div class="order dashbg-2">1st</div>-->
<!--                  </div><a href="#" class="user-title">-->
<!--                    <h3 class="h5">Richard Nevoreski</h3><span>@richardnevo</span></a>-->
<!--                  <div class="contributions">Best Operator</div>-->
<!--                  <div class="details d-flex">-->
<!--                    <div class="item"><i class="icon-info"></i><strong>150</strong></div>-->
<!--                    <div class="item"><i class="fa fa-gg"></i><strong>340</strong></div>-->
<!--                    <div class="item"><i class="icon-flow-branch"></i><strong>460</strong></div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--                <div class="col-lg-4">-->
<!--                    <div class="stats-with-chart-1 block" style="height: 91%;">-->
<!--                        <div class="title"> <strong class="d-block">Target</strong><span class="d-block">Lorem ipsum dolor sit</span></div>-->
<!--                        <div class="row d-flex align-items-end justify-content-between">-->
<!--                            <div class="col-5">-->
<!--                                <div class="text"><strong class="d-block dashtext-3">$740</strong><span class="d-block">May 2017</span><small class="d-block">320 Sales</small></div>-->
<!--                            </div>-->
<!--                            <div class="col-7">-->
<!--                                <div class="bar-chart chart">-->
<!--                                    <canvas id="salesBarChart1"></canvas>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-lg-4">-->
<!--                    <div class="stats-with-chart-1 block" style="height: 91%;">-->
<!--                        <div class="title"> <strong class="d-block">Quality</strong><span class="d-block">Lorem ipsum dolor sit</span></div>-->
<!--                        <div class="row d-flex align-items-end justify-content-between">-->
<!--                            <div class="col-4">-->
<!--                                <div class="text"><strong class="d-block dashtext-1">$457</strong><span class="d-block">May 2017</span><small class="d-block">210 Sales</small></div>-->
<!--                            </div>-->
<!--                            <div class="col-8">-->
<!--                                <div class="bar-chart chart">-->
<!--                                    <canvas id="visitPieChart"></canvas>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <?php
            if (Input::exists() && count($errors) === 0 && count($errorNoData) === 0) {
                    $x = 1;
                    $year       = Input::post('year');
                    $month      = Input::post('month');
                    $noPrefTbl  = Input::post('table');
                    $table      = Params::PREFIX . trim($noPrefTbl);
                    $names      = [];
                    $quantity   = [];

                    $where = [
                        ['year', '=', $year],
                        'AND',
                        ['offices_id', '=', $user->officesId()],
                        'AND',
                        ['month', '=', $month]
                    ];

                    $values = $data->records($table, $where, ['name', 'quantity']);
                    foreach ($values as $value) {
                        array_push($quantity, $value->quantity);
                        array_push($names, $value->name);
                        $allData = array_combine($names, $quantity);
                    }

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
                                    <div class="contributions" data-toggle="tooltip" data-placement="top" title="Month"><?php echo escape(ucfirst($noPrefTbl)) . ' - ' . escape(Common::getMonths()[$month]); ?></div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="details d-flex">
                                        <div class="item" data-toggle="tooltip" data-placement="top" title="<?php echo ucfirst($noPrefTbl); ?>"><i class="icon-chart"></i>
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
          include 'includes/footer.php';
          ?>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/charts-home.js"></script>
    <script src="js/front.js"></script>
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
  if (Input::exists() && count($errors) == 0) {
          include 'charts/target_chart.php';
     }
  ?>
  </body>
</html>