<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
// Names for select list
$users = DB::getInstance()->get('users', array('user_id', '=', $user->userId()))->results();

$furlough = DB::getInstance()->get('furlough', array('user_id', '=', $user->userId()))->results();

$absentees = DB::getInstance()->get('absentees', array('user_id', '=', $user->userId()))->results();

$allTables = DB::getInstance()->get('departments', array('id', '=', $user->userId()))->results();

foreach (Values::tables($allTables) as $value) {
    $tables[] = trim($value);
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
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Charts</h2>
          </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Charts            </li>
          </ul>
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
        <section>
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="line-chart block chart">
                  <div class="title"><strong>Line Chart Example</strong></div>
                  <canvas id="lineChartCustom1"></canvas>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="chart block">
                  <div class="title"> <strong>Bar Chart Example</strong></div>
                  <div class="bar-chart chart margin-bottom-sm">
                    <canvas id="barChartCustom1"></canvas>
                  </div>
                  <div class="bar-chart chart">
                    <canvas id="barChartCustom2"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="bar-chart block chart">
                  <div class="title"><strong>Bar Chart Example</strong></div>
                  <div class="bar-chart chart">
                    <canvas id="barChartCustom3"></canvas>
                  </div>
                </div>
              </div>
<!--              <div class="col-lg-6">-->
<!--                <div class="pie-chart chart block">-->
<!--                  <div class="title"><strong>Pie Chart Example</strong></div>-->
<!--                  <div class="pie-chart chart margin-bottom-sm">-->
<!--                    <canvas id="pieChartCustom1"></canvas>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
<!--              <div class="col-lg-6">-->
<!--                <div class="doughnut-chart chart block">-->
<!--                  <div class="title"><strong>Pie Chart Example</strong></div>-->
<!--                  <div class="doughnut-chart chart margin-bottom-sm">-->
<!--                    <canvas id="doughnutChartCustom1"></canvas>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->
            </div>
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
    <script src="js/charts-custom.js"></script>
    <script src="js/front.js"></script>
  </body>
</html>