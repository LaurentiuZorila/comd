<?php
require_once 'core/init.php';
$backendUser        = new BackendUser();
$backendUserProfile = new BackendProfile();

if (!$backendUser->isLoggedIn()) {
    Redirect::to('login.php');
}

// All users and staffs for one department
$offices        = $backendUserProfile->records(Params::TBL_OFFICE, ['departments_id', '=', $backendUser->departmentId()], ['id', 'name']);
$allUsers       = $backendUserProfile->records(Params::TBL_EMPLOYEES, ['departments_id', '=', $backendUser->userId()], ['offices_id', 'departments_id', 'supervisors_id', 'name']);

$countEmployees = $backendUserProfile->count(Params::TBL_EMPLOYEES, ['departments_id', '=', $backendUser->userId()]);
$countStaff     = $backendUserProfile->count(Params::TBL_TEAM_LEAD, ['supervisors_id', '=', $backendUser->userId()]);
$countOffices   = $backendUserProfile->count(Params::TBL_OFFICE, ['departments_id', '=', $backendUser->departmentId()]);


$where = [
        'departments_id', '=', $backendUser->userId()
];

// Total common tables for all department
$furlough   = $backendUserProfile->records(Params::TBL_FURLOUGH, $where, ['quantity']);
$absentees  = $backendUserProfile->records(Params::TBL_ABSENTEES, $where, ['quantity']);
$unpaid     = $backendUserProfile->records(Params::TBL_UNPAID, $where, ['quantity']);

if (Input::exists()) {
        $year           = Input::post('year');
        $month          = Input::post('month');
        $officeId       = Input::post('teams');
        $table          = strtolower(trim(Input::post('table')));
        $table          = Params::PREFIX.$table;
        $quantitySum    = [];

        if (empty($month) || empty($year)) {
            $errors = [1];
        }

        if (count($errors) === 0) {
            // Conditions for action
            $where = [
                ['year', '=', $year],
                'AND',
                ['offices_id', '=', $officeId],
                'AND',
                ['month', '=', $month]
            ];

            // Array with all results for one FTE
            $chartData      = $backendUserProfile->records($table, $where, ['quantity', 'name']);
            // Total furlough , absentees, unpaid for selected user
            $countFurlough  = $backendUserProfile->sum(Params::TBL_FURLOUGH, $where, 'quantity');
            $countAbsentees = $backendUserProfile->sum(Params::TBL_ABSENTEES, $where, 'quantity');
            $countUnpaid    = $backendUserProfile->sum(Params::TBL_UNPAID, $where, 'quantity');

            foreach ($chartData as $value) {
                $quantitySum[] = $value->quantity;
            }

            $chartNames     = Js::toJson(Js::chartLabel($chartData, 'name'));
            $chartValues    = Js::chartValues($chartData, 'quantity');
            $pieCommonData  = $countFurlough . ', ' . $countAbsentees . ', ' . $countUnpaid;

            if (count($quantitySum) < 1) {
                $errorNoData[] = 1;
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
          <?php if (Input::exists() && count($errors) > 0) {
              include 'includes/errors.php';
          } elseif (Input::exists() && count($errorNoData) > 0) {
              include 'includes/infoError.php';
          }
         ?>
          <section class="no-padding-top no-padding-bottom">
              <div class="col-lg-12">
                  <p>
                      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#filter" aria-expanded="false" aria-controls="filter">
                          Filters
                      </button>
                  </p>
                  <div class="<?php if (Input::exists() && count($errors) == 0 && count($errorNoData) == 0) { echo "collapse";} else { echo "collapse show"; } ?>" id="filter">
                  <div class="block">
                      <form method="post">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title"><strong>Filters</strong></div>
                            </div>
                         <div class="col-sm-3">
                                <select name="teams" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select team</option>
                                    <?php foreach ($offices as $office) { ?>
                                        <option value="<?php echo $office->id; ?>"><?php echo $office->name; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('table'))) { ?>
                                    <div class="invalid-feedback">Select team!</div>
                                <?php }?>
                          </div>
                          <div class="col-sm-3">
                                <select name="table" id="#table" class="form-control <?php if (Input::exists() && empty(Input::post('table'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                    <option value="">Select Table</option>
                                </select>
                                <?php
                                if (Input::exists() && empty(Input::post('table'))) { ?>
                                    <div class="invalid-feedback">Select table!</div>
                                <?php }?>
                          </div>
                          <div class="col-sm-3">
                              <select name="year" class="form-control <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                  <option value="">Select Year</option>
                                  <?php
                                  foreach (Common::getYearsList() as $year) { ?>
                                      <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                  <?php } ?>
                              </select>
                              <?php
                              if (Input::exists() && empty(Input::post('year'))) { ?>
                                  <div class="invalid-feedback">Select year!</div>
                              <?php }?>
                          </div>
                          <div class="col-sm-3">
                              <select name="month" class="form-control <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} else { echo 'mb-3';} ?>">
                                  <option value="">Select Month</option>
                                  <?php foreach (Common::getMonths() as $key => $value) { ?>
                                  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                  <?php } ?>
                              </select>
                              <?php
                              if (Input::exists() && empty(Input::post('month'))) { ?>
                                  <div class="invalid-feedback">Select month!</div>
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
                      <div class="col-md-4 col-sm-3">
                          <div class="statistic-block block">
                              <div class="progress-details d-flex align-items-end justify-content-between">
                                  <div class="title">
                                      <div class="icon"><i class="icon-list"></i></div><strong>Offices</strong>
                                  </div>
                                  <div class="number dashtext-1"><?php echo $countOffices; ?></div>
                              </div>
                              <div class="progress progress-template">
                                  <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                              </div>
                              <a href="all_staff.php" class="tile-link"></a>
                          </div>
                      </div>
                      <div class="col-md-4 col-sm-3">
                          <div class="statistic-block block">
                              <div class="progress-details d-flex align-items-end justify-content-between">
                                  <div class="title">
                                      <div class="icon"><i class="icon-user"></i></div><strong>All staffs</strong>
                                  </div>
                                  <div class="number dashtext-1"><?php echo $countStaff; ?></div>
                              </div>
                              <div class="progress progress-template">
                                  <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                              </div>
                              <a href="all_staff.php" class="tile-link"></a>
                          </div>
                      </div>
                      <div class="col-md-4 col-sm-3">
                          <div class="statistic-block block">
                              <div class="progress-details d-flex align-items-end justify-content-between">
                                  <div class="title">
                                      <div class="icon"><i class="icon-user-1"></i></div><strong>All employees</strong>
                                  </div>
                                  <div class="number dashtext-2"><?php echo $countEmployees; ?></div>
                              </div>
                              <div class="progress progress-template">
                                  <div role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                              </div>
                              <a href="employees.php" class="tile-link"></a>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
        <?php if (Input::exists() && count($errors) === 0 && count($errorNoData) === 0) { ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-4 col-sm-3">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-info"></i></div><strong>Total user absentees</strong>
                    </div>
                    <div class="number dashtext-3"><?php echo $countAbsentees; ?></div>
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
                      <div class="icon"><i class="icon-list-1"></i></div><strong>Total user furlough</strong>
                    </div>
                    <div class="number dashtext-3"><?php echo $countFurlough; ?></div>
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
                            <div class="number dashtext-3"><?php echo $countUnpaid; ?></div>
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
        <section>
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg-8">
                      <div class="bar-chart block chart">
                          <ul class="nav nav-pills card-header-pills">
                              <li class="nav-item"><button class="btn btn-primary mr-1 bar" id="bar" type="button">Bar</button></li>
                              <li class="nav-item"><button class="btn btn-outline-primary line" id="line" type="button">Line</button></li>
                          </ul>
                          <div class="drills-chart block">
                              <canvas id="backendIndexBarChart" height="150"></canvas>
                              <canvas id="backendIndexLineChart" height="150" style="display: none;"></canvas>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-4">
                      <div class="bar-chart block chart">
                          <div class="drills-chart block">
                              <canvas id="totalCommonTables" height="408"></canvas>
                          </div>
                      </div>
                  </div>
<!--                  <div class="col-lg-8">-->
<!--                      <div class="bar-chart block chart">-->
<!--                          <ul class="nav nav-pills card-header-pills">-->
<!--                              <li class="nav-item"><button class="btn btn-primary mr-1 bar" id="bar" type="button">Bar</button></li>-->
<!--                              <li class="nav-item"><button class="btn btn-outline-primary line" id="line" type="button">Line</button></li>-->
<!--                          </ul>-->
<!--                          <div class="bar-chart chart"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor">-->
<!--                                  <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">-->
<!--                                      <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>-->
<!--                                  </div>-->
<!--                                  <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">-->
<!--                                      <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>-->
<!--                                  </div>-->
<!--                              </div>-->
<!--                              <canvas id="backendIndexBarChart" style="display: block; width: 682px; height: 375px;" width="682" height="375" class="chartjs-render-monitor"></canvas>-->
<!--                              <canvas id="backendIndexLineChart" style="display: none; width: 682px; height: 370px;" width="682" height="370" class="chartjs-render-monitor"></canvas>-->
<!--                          </div>-->
<!--                      </div>-->
<!--                  </div>-->
<!--                  <div class="col-md-4">-->
<!--                      <div class="pie-chart chart block">-->
<!--                          <div class="title">-->
<!--                              <strong>All absentees</strong>-->
<!--                          </div>-->
<!--                          <div class="pie-chart chart margin-bottom-sm"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>-->
<!--                              <canvas id="totalCommonTables" style="display: block; width: 494px; height: 542px;" width="494" height="542" class="chartjs-render-monitor"></canvas>-->
<!--                          </div>-->
<!--                      </div>-->
<!--                  </div>-->
              </div>
          </div>
        </section>
        <?php } ?>
          <!--        ********************       CHARTS   END      ********************   -->
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
<script src="js/charts-custom.js"></script>
  <?php
  if (Input::exists() && count($errors) === 0) {
      include 'charts/index_charts.php';
  }
  ?>
<script>
  $( "select[name='teams']" ).change(function () {
      var officeId = $(this).val();
      if(officeId) {
          $.ajax({
              url: "includes/staff_tables.php",
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

</script>

  </body>
</html>