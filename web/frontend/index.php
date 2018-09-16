<?php
require_once 'core/init.php';
$user = new User();
$profileDetails = new ProfileDetails();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
$tables = $profileDetails->officeDetails($user->officeId(), 'tables');

if (!Input::get('info')) {
    Session::put('InfoAlert', 'Click FILTER button, and search for another data.');
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


if (Input::exists()) {
    $errors = [];
    $year   = trim(Input::post('year'));
    $month  = trim(Input::post('month'));
    $table  = trim(Input::post('table'));


    if (!empty($year) && !empty($month) && !empty($table)) {
        // Conditions for action
        $where = [
            ['employees_id', '=', $user->userId()],
            'AND',
            ['year', '=', $year],
            'AND',
            ['month', '=', $month]
        ];

        // Conditions for COUNT action (total)
        $sum = [
            ['offices_id', '=', $user->officeId()],
            'AND',
            ['year', '=', $year],
            'AND',
            ['month', '=', $month]
        ];

        // Common data for user
        $userFurlough   = $profileDetails->commonDetails($where)['furlough']->quantity;
        $userAbsentees  = $profileDetails->commonDetails($where)['absentees']->quantity;
        $userUnpaidDays = $profileDetails->commonDetails($where)['unpaid']->quantity;

        $totalFurloughs = $profileDetails->allData($sum)['furlough']->total;
        $totalAbsentees = $profileDetails->allData($sum)['absentees']->total;
        $totalUnpaid    = $profileDetails->allData($sum)['unpaid']->total;

        // If form select list is selected "all"
        if (!is_numeric($month)) {
            // Conditions for action
            $whereForChart = [
                ['employees_id', '=', $user->userId()],
                'AND',
                ['year', '=', $year]
            ];

            // Conditions for action
            $where = [
                ['employees_id', '=', $user->userId()],
                'AND',
                ['year', '=', $year]
            ];

            $dataAllMonths      = $profileDetails->arrayMultipleRecords($table, $whereForChart, ['month', 'quantity']);

            $furloughCommon     = $profileDetails->arrayMultipleRecords('furlough', $where, ['month', 'quantity']);
            $absenteesCommon    = $profileDetails->arrayMultipleRecords('absentees', $where, ['month', 'quantity']);
            $unpaidCommon       = $profileDetails->arrayMultipleRecords('unpaid', $where, ['month', 'quantity']);

            // Common charts
            $furloughChartLabel     = Js::toJson(Js::key($furloughCommon));
            $furloughChartValues    = Js::toString(Js::values($furloughCommon));

            $absenteesChartLabel    = Js::toJson(Js::key($absenteesCommon));
            $absenteesChartValues   = Js::toString(Js::values($absenteesCommon));

            $unpaidChartLabel       = Js::toJson(Js::key($unpaidCommon));
            $unpaidChartValues      = Js::toString(Js::values($unpaidCommon));

            // Selected charts
            $chartLabels = Js::toJson(Js::key($dataAllMonths));
            $chartValues = Js::toString(Js::values($dataAllMonths));
        }
    } else {
        $errors = [1];
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
        <?php if (Session::exists('InfoAlert')) { ?>
        <section>
          <div class="row">
              <div class="col-lg-12">
                  <div class="card-body">
                      <div class="alert alert-dismissible fade show badge-info b-l-5" role="alert">
                          <strong class="text-monospace text-dark"> This results are for <?php echo $profileDetails->getMonthsList()[$month]; ?> </strong>
                          <p class="text-dark"> <?php echo Session::flash('InfoAlert'); ?> </p>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                  </div>
              </div>
          </div>
        </section>
        <?php }
        if (Input::exists() && count($errors) > 0) {
            include 'includes/errors.php';
        }
        ?>
        <section>
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg-12">
                      <p>
                          <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                              Filters
                          </button>
                      </p>
                      <div class="<?php if (Input::exists() && count($errors) > 0) {echo 'collapse show'; } else { echo 'collapse'; } ?>" id="collapseExample">
                          <div class="card card-body">
                              <form method="post">
                                  <div class="row">
                                      <div class="col-sm-12">
                                          <div class="title"><strong>Filters</strong></div>
                                      </div>
                                      <div class="col-sm-4">
                                          <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                              <option value="">Select Year</option>
                                              <?php
                                              foreach ($profileDetails->getYearsList() as $year) { ?>
                                                  <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                              <?php } ?>
                                          </select>
                                          <?php
                                          if (Input::exists() && empty(Input::post('year'))) { ?>
                                              <div class="invalid-feedback">Select year!</div>
                                          <?php }?>
                                      </div>

                                      <div class="col-sm-4">
                                          <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                              <option value="">Select Month</option>
                                              <?php foreach ($profileDetails->getMonthsList() as $key => $value) { ?>
                                                  <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
                                              <?php } ?>
                                              <option value="All">ALL</option>
                                          </select>
                                          <?php
                                          if (Input::exists() && empty(Input::post('month'))) { ?>
                                              <div class="invalid-feedback">Select month!</div>
                                          <?php }?>
                                      </div>

                                      <div class="col-sm-4">
                                          <select name="table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                              <option value="">Select Table</option>
                                              <?php foreach ($tables as $table) { ?>
                                                  <option value="<?php echo trim($table); ?>"><?php echo strtoupper($table); ?></option>
                                              <?php } ?>
                                          </select>
                                          <?php
                                          if (Input::exists() && empty(Input::post('table'))) { ?>
                                              <div class="invalid-feedback">Select table!</div>
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
                  </div>
              </div>
          </div>
        </section>
        <?php
        // IF input doesn't exists appear this section
        if (!Input::exists()) { ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
              <div class="row">
                  <?php foreach ($profileDetails->commonDetails($where) as $table => $value) {
                      $table = $table === 'unpaid' ? 'unpaid days' : $table;
                      ?>
                  <div class="col-md-3 col-sm-6">
                      <div class="statistic-block block">
                          <div class="progress-details d-flex align-items-end justify-content-between">
                              <div class="title">
                                  <div class="icon"><i class="icon-list"></i></div><strong><?php echo $table; ?></strong>
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
                                  <div class="icon"><i class="icon-list"></i></div><strong>UNPAID HOURS</strong>
                              </div>
                              <div class="number dashtext-2"><?php echo $profileDetails->unpaidHours($where)->hours; ?>h</div>
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
        if (Input::exists()) { ?>
          <section class="margin-bottom-sm">
              <div class="container-fluid">
                  <div class="row d-flex align-items-stretch">
                      <div class="col-lg-4">
                          <div class="stats-with-chart-1 block">
                              <div class="title"> <strong class="d-block"><?php echo 'Furlough'; ?></strong></div>
                              <div class="row d-flex align-items-end justify-content-between">
                                  <div class="col-4">
                                      <div class="text"><strong class="d-block dashtext-3"><?php echo $userFurlough; ?> <small><?php echo $day = $userFurlough > 1 ? 'days' : 'day'; ?></small></strong>
                                          <span class="d-block"><?php echo $profileDetails->getMonthsList()[$month] . ' ' . Input::post('year'); ?></span>
                                          <small class="d-block">All team furloughs: <?php echo $totalFurloughs; ?></small>
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
                              <div class="title"> <strong class="d-block"><?php echo 'Absentees'; ?></strong></div>
                              <div class="row d-flex align-items-end justify-content-between">
                                  <div class="col-4">
                                      <div class="text"><strong class="d-block dashtext-1"><?php echo $userAbsentees; ?> <small><?php echo $day = $userAbsentees > 1 ? 'days' : 'day'; ?></small></strong>
                                          <span class="d-block"><?php echo $profileDetails->getMonthsList()[$month] . ' ' . Input::post('year'); ?></span>
                                          <small class="d-block">All team absentees: <?php echo $totalAbsentees; ?></small>
                                      </div>
                                  </div>
                                  <div class="col-8">
                                      <div class="bar-chart chart">
                                          <canvas id="absenteesPieChart" style="display: block; width: 194px; height: 157px;" width="194" height="157" class="chartjs-render-monitor"></canvas>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-4">
                          <div class="stats-with-chart-1 block">
                              <div class="title"> <strong class="d-block"><?php echo 'Unpaid'; ?></strong></div>
                              <div class="row d-flex align-items-end justify-content-between">
                                  <div class="col-5">
                                      <div class="text"><strong class="d-block dashtext-2"><?php echo $userUnpaidDays; ?> <small><?php echo $day = $userUnpaidDays > 1 ? 'days' : 'day'; ?></small></strong>
                                          <span class="d-block"><?php echo $profileDetails->getMonthsList()[$month] . ' ' . Input::post('year'); ?></span>
                                          <small class="d-block">All team unpaid: <?php echo $totalUnpaid; ?></small>
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
          <?php if (Input::exists()) {
              if (count($errors) == 0) { ?>
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
          }?>
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
          </div>
        </section>
          <?php
          include 'includes/footer.php';
          ?>
      </div>
    </div>
  <button type="button" data-toggle="collapse" data-target="#style-switch" id="style-switch-button" class="btn btn-primary btn-sm d-none d-md-inline-block" aria-expanded="true">
      <i class="fa fa-cog fa-2x"></i>
  </button>
  <div id="style-switch" class="collapse" style="">
      <h5 class="mb-3">Select theme colour</h5>
          <form class="mb-3">
              <select name="colour" id="colour" class="form-control">
                  <option value="">select colour variant</option>
                  <option value="style.pink">pink</option>
                  <option value="style.red">red</option>
                  <option value="style.green">green</option>
                  <option value="style.violet">violet</option>
                  <option value="style.sea">sea</option>
                  <option value="style.blue">blue</option>
              </select>
          </form>
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
    <!--  Sweet alert   -->
  <script src="sweetalert/dist/sweetalert2.min.js"></script>
  <script>
      $(document).ready(function(){
          $('#colour').change(function(){
              $("#theme-stylesheet").attr("href", "css/" + $(this).val() + ".css");
          });
      });
  </script>

  <?php
  if (Input::exists() && is_numeric(Input::post('month'))) {
      include 'charts/commonDataSingle.php';
  }
  if (Input::exists() && !is_numeric(Input::post('month'))) {
      include 'charts/commonDataMultiple.php';
  }
  ?>

  <script>
      var employees_chart    = $('#employees_chart');
      var employeesChart = new Chart(employees_chart, {
          type: 'bar',
          options: {
              scales: {
                  xAxes: [{
                      display: true,
                      gridLines: {
                          color: 'transparent'
                      }
                  }],
                  yAxes: [{
                      display: true,
                      gridLines: {
                          color: 'transparent'
                      },
                      ticks: {
                          beginAtZero: true
                      }
                  }]
              },
          },
          data: {
              labels: <?php echo $chartLabels; ?>,
              datasets: [
                  {
                      label: "Data Set 1",
                      backgroundColor: "#864DD9",
                      hoverBackgroundColor: "#864DD9",
                      borderColor: "#864DD9",
                      borderWidth: 0.5,
                      data: [<?php echo $chartValues; ?>],
                  }
              ]
          }
      });
</script>


  </body>
</html>