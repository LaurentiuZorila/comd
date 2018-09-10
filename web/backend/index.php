<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}

// All users and staf for one department
$allStaff = DB::getInstance()->get('cmd_users', $where = ['supervisors_id', '=', $user->userId()])->results();
$allUsers = DB::getInstance()->get('cmd_employes', $where = ['departments_id', '=', $user->userId()])->results();

// Total for all department
$furlought  = DB::getInstance()->get('cmd_furlought', array('departments_id', '=', $user->userId()))->results();
$absentees  = DB::getInstance()->get('cmd_absentees', array('departments_id', '=', $user->userId()))->results();
$unpaid     = DB::getInstance()->get('cmd_unpaid', ['departments_id', '=', $user->userId()])->results();

if (Input::exists()) {
        $year = Input::post('year');
        $month = Input::post('month');
        $user_id = Input::post('teams');
        $table = strtolower(Input::post('table'));
        $table = 'cmd_'.$table;
        $errors = [];
        $quantitySum = [];

        if (empty($month) || empty($year)) {
            $errors = [1];
        }

        if (count($errors) === 0) {
            // Conditions for action
            $where = [
                ['year', '=', $year],
                'AND',
                ['user_id', '=', $user_id],
                'AND',
                ['month', '=', $month]
            ];

            // Array with all results for one FTE
            $chartData      = DB::getInstance()->get($table, $where, ['quantity', 'name'])->results();
            // Total furlought , absentees, unpaid for selected user
            $userFurlought  = DB::getInstance()->get('cmd_furlought', $where)->results();
            $userAbsentees  = DB::getInstance()->get('cmd_absentees', $where)->results();
            $userUnpaid     = DB::getInstance()->get('cmd_unpaid', $where)->results();

            foreach ($chartData as $value) {
                $quantitySum[] = $value->quantity;
            }
            $chartNames = Js::toJson(Js::chartLabel($chartData));
            $chartValues = Js::chartValues($chartData);

            if (count($quantitySum) < 1) {
                $errorNoData = true;
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
                         <div class="col-sm-3">
                                <select name="teams" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Team</option>
                                    <?php foreach ($allStaff as $staff) { ?>
                                        <option value="<?php echo $staff->offices_id; ?>"><?php echo $staff->name; ?> (<small><?php echo $staff->department; ?></small>)</option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('table'))) { ?>
                                    <div class="invalid-feedback">Please select table.</div>
                                <?php }?>
                          </div>
                          <div class="col-sm-3">
                                <select name="table" id="#table" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} ?>">
                                    <option value="">Select Table</option>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('table'))) { ?>
                                    <div class="invalid-feedback">Please select table.</div>
                                <?php }?>
                          </div>
                          <div class="col-sm-3">
                              <select name="year" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                  <option value="">Select Year</option>
                                  <?php
                                  foreach (Profile::getYearsList() as $year) { ?>
                                      <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                  <?php } ?>
                              </select>
                              <?php
                              if (Input::exists() && empty(Input::post('year'))) { ?>
                                  <div class="invalid-feedback">Please select year.</div>
                              <?php }?>
                          </div>
                          <div class="col-sm-3">
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
              <div class="col-md-6 col-sm-3">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-user-1"></i></div><strong>Total staff</strong>
                    </div>
                    <div class="number dashtext-1"><?php echo escape(Values::countAll($allStaff)); ?></div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-3">
                <div class="statistic-block block">
                    <div class="progress-details d-flex align-items-end justify-content-between">
                        <div class="title">
                            <div class="icon"><i class="icon-user-1"></i></div><strong>Total users</strong>
                        </div>
                        <div class="number dashtext-2"><?php echo escape(Values::countAll($allUsers)); ?></div>
                    </div>
                    <div class="progress progress-template">
                        <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                    </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-3">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-info"></i></div><strong>Total user absentees</strong>
                    </div>
                    <div class="number dashtext-3"><?php echo escape(Values::totalCommonTables($absentees)); ?></div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-3">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-list-1"></i></div><strong>Total user furlought</strong>
                    </div>
                    <div class="number dashtext-3"><?php echo escape(Values::totalCommonTables($furlought)); ?></div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                  </div>
                </div>
              </div>
                <div class="col-md-4 col-sm-3">
                    <div class="statistic-block block">
                        <div class="progress-details d-flex align-items-end justify-content-between">
                            <div class="title">
                                <div class="icon"><i class="icon-list-1"></i></div><strong>Total user unpaid leave</strong>
                            </div>
                            <div class="number dashtext-3"><?php echo escape(Values::totalCommonTables($unpaid)); ?></div>
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
          <?php if ( Input::exists() && count($errors) == 0) { ?>
          <section>
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-lg-8">
                          <div class="bar-chart block chart">
                              <div class="title"><strong><?php echo Input::post('table'); ?></strong></div>
                              <div class="bar-chart chart"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                  <canvas id="charts" style="display: block; width: 682px; height: 341px;" width="682" height="341" class="chartjs-render-monitor"></canvas>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="pie-chart chart block">
                              <div class="title"><strong>All absentees</strong></div>
                              <div class="pie-chart chart margin-bottom-sm"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                  <canvas id="totalCommonTables" style="display: block; width: 494px; height: 503px;" width="494" height="503" class="chartjs-render-monitor"></canvas>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <?php } ?>
          <!--        ********************       CHARTS   END      ********************   -->
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
    <script src="js/charts-custom.js"></script>
    <!--  Sweet alert   -->
  <script src="sweetalert/dist/sweetalert2.min.js"></script>
  <script>
      $( "select[name='teams']" ).change(function () {
          var userID = $(this).val();
          if(userID) {
              $.ajax({
                  url: "includes/staff_tables.php",
                  dataType: 'Json',
                  data: {'id':userID},
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

      $(document).ready(function(){
          $('#colour').change(function(){
              $("#theme-stylesheet").attr("href", "css/" + $(this).val() + ".css");
          });
      });
</script>
<script>
      var BARCHARTEXMPLE    = $('#charts');
      var barChartExample = new Chart(BARCHARTEXMPLE, {
          type: 'bar',
          options: {
              scales: {
                  xAxes: [{
                      display: true,
                      gridLines: {
                          color: 'transparent'
                      },
                      ticks: {
                          autoSkip: false
                      }
                  }],
                  yAxes: [{
                      display: true,
                      gridLines: {
                          color: 'transparent'
                      }
                  }]
              },
          },
          data: {
              labels: <?php echo $chartNames; ?>,
              datasets: [
                  {
                      label: "<?php echo Profile::getMonthsList()[Input::post('month')]; ?>",
                      backgroundColor: "#864DD9",
                      hoverBackgroundColor: "#864DD9",
                      borderColor: "#864DD9",
                      borderWidth: 0.5,
                      data: [<?php echo $chartValues; ?>],
                  },
              ]
          }
      });
</script>
<script>
      var PIECHARTEXMPLE    = $('#totalCommonTables');
      var pieChartExample = new Chart(PIECHARTEXMPLE, {
          type: 'pie',
          options: {
              legend: {
                  display: true,
              }
          },
          data: {
              labels: [
                  "Furlought",
                  "Absentees",
                  "Unpaid"
              ],
              datasets: [
                  {
                      data: [<?php echo Values::totalCommonTables($userFurlought). ', ' . Values::totalCommonTables($userAbsentees). ', ' . Values::totalCommonTables($userUnpaid); ?>],
                      borderWidth: 0,
                      backgroundColor: [
                          "#864DD9",
                          '#723ac3',
                          "#9762e6"
                      ],
                      hoverBackgroundColor: [
                          "#864DD9",
                          '#723ac3',
                          "#9762e6"
                      ]
                  }]
          }
      });

      var pieChartExample = {
          responsive: true
      };

</script>

  </body>
</html>