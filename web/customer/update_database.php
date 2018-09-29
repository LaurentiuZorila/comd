<?php
require_once 'core/init.php';
$user = new CustomerUser();
$data = new CustomerProfile();

if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
// User data
$userData = CustomerDB::getInstance()->get('cmd_users', ['id', '=', $user->customerId()])->first();

// All tables
$allTables = $data->records(Params::TBL_OFFICE, ['id', '=', $user->officesId()], ['tables'], false);
$allTables = explode(',', $allTables->tables);

foreach ($allTables as $value) {
    $prefix                   = Params::PREFIX;
    $tables[$prefix . $value] = trim($value);
}


if (Input::exists()) {
    $year   = Input::post('year');
    $month  = Input::post('month');
    $table  = trim(Input::post('tables'));
    $table  = Params::PREFIX . $table;

    $filename   = $_FILES['fileToUpload']['tmp_name'];
    $path       = $_FILES['fileToUpload']['name'];
    $size       = $_FILES['fileToUpload']['size'];
    $extension  = pathinfo($path, PATHINFO_EXTENSION);
    // Extensions
    $extensions     = Params::EXTENSIONS;
    $errors         = [];
    $uploadSuccess  = [];
    $uploadError    = [];
    $extensionError = [];

    if (empty($year) || empty($month) || empty($table)) {
        $errors = [1];
    }
    if (count($errors) === 0) {
    // Check for valid extension
        if (in_array($extension, $extensions) && $size > 0) {
            // Open the file for reading
            if (($h = fopen("{$filename}", "r")) !== FALSE) {
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    CustomerDB::getInstance()->insert($table, [
                        'name' => $data[0],
                        'username' => $data[1],
                        'department' => $data[2]
                    ]);
                }
                if (CustomerDB::getInstance()->count() > 0) {
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
          <?php
          if (Input::exists() && count($uploadSuccess) > 0) {
              include 'includes/uploadSuccess.php';
          } elseif (Input::exists() && count($uploadError) > 0) {
              include 'includes/uploadError.php';
          } elseif (Input::exists() && count($extensionError) > 0) {
              include 'includes/extensionError.php';
          } elseif (Input::exists() && count($errors) > 0) {
              include 'includes/errorRequired.php';
          }
          ?>
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
                                      foreach (Common::getYearsList() as $year) { ?>
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
                                      <?php foreach (Common::getMonths() as $key => $value) { ?>
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
                                      <?php foreach ($tables as $key => $table) { ?>
                                          <option value="<?php echo $key; ?>"><?php echo strtoupper($table); ?></option>
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

  </body>
</html>