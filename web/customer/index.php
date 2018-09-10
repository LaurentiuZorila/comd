<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

// Names for select list
$users = DB::getInstance()->get('users', array('user_id', '=', $user->userId()))->results();

$furlought = DB::getInstance()->get('furlought', array('user_id', '=', $user->userId()))->results();

$absentees = DB::getInstance()->get('absentees', array('user_id', '=', $user->userId()))->results();

$allTables = DB::getInstance()->get('department', array('user_id', '=', $user->userId()))->results();

foreach (Values::tables($allTables) as $value) {
    $tables[] = trim($value);
}

if (Input::exists()) {
        $year = Input::post('year');
        $month = Input::post('month');
        $user_id = $user->userId();
        $table = Input::post('table');
        $errors = [];
        $quantitySum = [];
        if (empty($month) || empty($year)) {
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
                              <select name="year" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                  <option value="">Select Year</option>
                                  <?php
                                  foreach (Profile::getYearsList() as $year) { ?>
                                      <option><?php echo $year; ?></option>
                                  <?php } ?>
                              </select>
                              <?php
                              if (Input::exists() && empty(Input::post('year'))) { ?>
                                  <div class="invalid-feedback">Please select year.</div>
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
                                    <?php foreach ($tables as $table) { ?>
                                        <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
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
              <div class="col-md-4 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-user-1"></i></div><strong>Total users</strong>
                    </div>
                    <div class="number dashtext-1"><?php echo escape(Values::countUsers($users)); ?></div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-info"></i></div><strong>Total user absentees</strong>
                    </div>
                    <div class="number dashtext-3"><?php echo escape(Values::totalAbsentees($absentees)); ?></div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-list-1"></i></div><strong>Total user furlought</strong>
                    </div>
                    <div class="number dashtext-2"><?php echo escape(Values::totalFurloughts($furlought)); ?></div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
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
<!--                  <section class="no-padding-bottom">-->
<!--                      <div class="container-fluid">-->
<!--                          <div class="row">-->
<!--                              <div class="col-lg-4">-->
<!--                                  <div class="bar-chart block no-margin-bottom">-->
<!--                                      <canvas id="abstentees_chart"></canvas>-->
<!--                                  </div>-->
<!--                                  <div class="bar-chart block">-->
<!--                                      <canvas id="furlought_chart"></canvas>-->
<!--                                  </div>-->
<!--                              </div>-->
<!--                              <div class="col-lg-8">-->
<!--                                  <div class="line-cahrt block">-->
<!--                                      <canvas id="quality_chart"></canvas>-->
<!--                                  </div>-->
<!--                              </div>-->
<!--                          </div>-->
<!--                      </div>-->
<!--                  </section>-->
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
                if (count($errors) ==0) {
                    $x = 1;
                    $year = Input::post('year');
                    $month = Input::post('month');
                    $table = Input::post('table');
                    $names = [];
                    $quantity = [];

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
                                             title="<?php echo ucfirst($table); ?>"><i
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
      if (count($errors) == 0) {
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