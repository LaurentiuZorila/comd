<?php
require_once 'core/init.php';
$user = new BackendUser();
$data = new BackendProfile();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
/**
 * TODO to modify this file
 */
$allTables = BackendDB::getInstance()->get('departments', array('id', '=', $user->userId()))->results();

foreach (Values::tables($allTables) as $value) {
    $tables[] = trim($value);
}

if (Input::exists()) {
    $year = Input::post('year');
    $month = Input::post('month');
    $table = Input::post('tables');

    $filename = $_FILES['fileToUpload']['tmp_name'];
    $path = $_FILES['fileToUpload']['name'];
    $size = $_FILES['fileToUpload']['size'];
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    // Extensions
    $extensions = array('csv');
    $errors = [];
    $uploadSuccess = [];
    $uploadError = [];
    $extensionError = [];

    if (empty($year) || empty($month) || empty($table)) {
        $errors = [1];
    }
    if (count($errors) == 0) {
    // Check for valid extension
        if (in_array($extension, $extensions) && $size > 0) {
            // Open the file for reading
            if (($h = fopen("{$filename}", "r")) !== FALSE) {
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    BackendDB::getInstance()->insert($table, [
                        'name' => $data[0],
                        'username' => $data[1],
                        'department' => $data[2]
                    ]);
                }
                if (BackendDB::getInstance()->count() > 0) {
                    $uploadSuccess = [1];
                } else {
                    $uploadError = [1];
                }
                // Close the file
                fclose($h);
            }
        } else {
            $extensionError = [1];
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
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Update database </h2>
          </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item active">Update database
            </li>
          </ul>
        </div>
          <section class="no-padding-top no-padding-bottom">
              <div class="col-lg-12">
                  <div class="block">
                      <form method="post" enctype="multipart/form-data">
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="title"><strong>Update your database</strong></div>
                              </div>
                              <div class="col-sm-4">
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
                                  <select name="tables" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('tables'))) {echo 'is-invalid';} ?>">
                                      <option value="">Select table</option>
                                      <?php foreach ($tables as $table) { ?>
                                          <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                  if (Input::exists() && empty(Input::post('tables'))) { ?>
                                      <div class="invalid-feedback">Please select table.</div>
                                  <?php }?>
                              </div>
                              <div class="col-sm-12 pb-3">
                              <input type="file" name="fileToUpload" id="fileToUpload">
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
<!--    <script src="vendor/chart.js/Chart.min.js"></script>-->
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/front.js"></script>
  <!--  Sweet alert   -->
  <script src="sweetalert/dist/sweetalert2.min.js"></script>
  <?php
  if (Input::exists()) {
      if (count($errors) == 0) {
          if (count($extensionError) > 0) {
              include 'notification/uploadExtensionError.php';
          }
          if (count($uploadSuccess) > 0) {
              include 'notification/uploadSuccess.php';
          }
          if (count($uploadError) > 0) {
              include 'notification/uploadError.php';
          }
      } else {
          include 'notification/error.php';
      }
  }
  ?>
  </body>
</html>