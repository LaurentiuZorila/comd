<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

// Count all employees
$countUsers = DB::getInstance()->get('cmd_employees', ['user_id', '=', $user->userId()])->count();
// User data
$userData   = DB::getInstance()->get('cmd_users', ['id', '=', $user->userId()])->first();

// Quantity for common tables
$furlough  = DB::getInstance()->get('cmd_furlough', ['user_id', '=', $user->userId()], ['quantity'])->results();
$absentees  = DB::getInstance()->get('cmd_absentees', ['user_id', '=', $user->userId()], ['quantity'])->results();
$unpaid     = DB::getInstance()->get('cmd_unpaid', ['user_id', '=', $user->userId()], ['quantity'])->results();


// tables for user
$allTables  = DB::getInstance()->get('cmd_offices', ['id', '=', $userData->offices_id], ['tables'])->first();
$prefix     = 'cmd_';

// Array with tables for user
foreach (Values::table($allTables) as $value) {
    $tables[$prefix . $value] = trim($value);
}


if (Input::exists()) {
        $year       = Input::post('year');
        $month      = Input::post('month');
        $user_id    = $user->userId();
        $table      = trim(Input::post('table'));
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
                ['user_id', '=', $user_id],
                'AND',
                ['month', '=', $month]
            ];

            // Array with all results for one FTE
            $chartData = DB::getInstance()->get($table, $where, ['quantity'])->results();
            foreach ($chartData as $value) {
                $quantitySum[] = $value->quantity;
            }

            $chartNames = Js::toJson(Js::chartLabel($chartData));
            $chartValues = Js::chartValues($chartData);

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
        <section class="no-padding-top no-padding-bottom">
            <div class="col-lg-12">
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
                                    foreach (Profile::getYearsList() as $year) { ?>
                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('year'))) { ?>
                                    <div class="invalid-feedback">Select year!</div>
                                <?php }?>
                            </div>

                            <div class="col-sm-4">
                              <select name="month" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} ?>">
                                  <option value="">Select Month</option>
                                  <?php foreach (Profile::getMonthsList() as $key => $value) { ?>
                                  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                  <?php } ?>
                              </select>
                              <?php
                              if (Input::exists() && empty(Input::post('month'))) { ?>
                                  <div class="invalid-feedback">Please select month.</div>
                              <?php }?>
                            </div>

                            <div class="col-sm-4">
                                <select name="table" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Table</option>
                                    <?php foreach ($tables as $key => $table) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo strtoupper($table); ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('table'))) { ?>
                                    <div class="invalid-feedback">Please select table.</div>
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
                          <div class="icon"><i class="icon-user-1"></i></div><strong>Total users</strong>
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
                        <div class="number dashtext-3"><?php echo escape(Values::totalAbsentees($absentees)); ?></div>
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
                        <div class="number dashtext-3"><?php echo escape(Values::totalFurloughs($furlough)); ?></div>
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
                            <div class="number dashtext-3"><?php echo escape(Values::totalFurloughs($unpaid)); ?></div>
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
          <?php if (Input::exists()) {
              if (count($errors) == 0) { ?>
                  <section class="no-padding-bottom">
                      <div class="container-fluid">
                          <div class="row">
                              <div class="col-lg-12">
                                  <div class="drills-chart block">
                                      <canvas id="target_chart" height="100"></canvas>
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
            <?php
            if (Input::exists()) {
                if (count($errors) === 0) {
                    $x = 1;
                    $year       = Input::post('year');
                    $month      = Input::post('month');
                    $noPrefTbl  = Input::post('table');
                    $table      = Input::post('table');
                    $table      = 'cmd_' . $table;
                    $names      = [];
                    $quantity   = [];

                    $where = [
                        ['year', '=', $year],
                        'AND',
                        ['user_id', '=', $user->userId()],
                        'AND',
                        ['month', '=', $month]
                    ];

                    $values = DB::getInstance()->get($table, $where)->results();
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
                                    <a href="#" class="name" data-toggle="tooltip" data-placement="top"
                                       title="Name"><strong
                                                class="d-block"><?php echo $key; ?></strong><span
                                                class="d-block"></span></a>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="contributions" data-toggle="tooltip" data-placement="top"
                                         title="Month"><?php echo Profile::getMonthsList()[$month]; ?></div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="details d-flex">
                                        <div class="item" data-toggle="tooltip" data-placement="top"
                                             title="<?php echo ucfirst($noPrefTbl); ?>"><i
                                                    class="icon-chart"></i><strong><?php echo $value; ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
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
  if (Input::exists()) {
      if (count($errors) === 0) {
          include 'charts/target_chart.php';
      } else {
          include 'notification/error.php';
      }
      if (count($errorNoData) > 0) {
          include 'notification/post_not_found.php';
      }
  }
  ?>
  </body>
</html>