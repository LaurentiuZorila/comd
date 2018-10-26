<?php
require_once 'core/init.php';
require_once '../vendor/league/csv/autoload.php';

use League\Csv\Reader;

$user   = new CustomerUser();
$data   = new CustomerProfile();


if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}
// User data
$userData = CustomerDB::getInstance()->get('cmd_users', ['id', '=', $user->customerId()])->first();

// All tables
$allTables = $data->records(Params::TBL_OFFICE, ['id', '=', $user->officesId()], ['tables'], false);
$allTables = explode(',', $allTables->tables);
array_map('trim', $allTables);


if (Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'year'      => ['required' => true],
        'month'     => ['required' => true],
        'tables'    => ['required' => true]
    ]);

    /** Check if validation is passed */
    if ($validation->passed()) {
        $year   = Input::post('year');
        $month  = Input::post('month');
        $table  = trim(Input::post('tables'));
        $table  = Params::PREFIX . $table;

        $filename   = $_FILES['fileToUpload']['tmp_name'];
        $path       = $_FILES['fileToUpload']['name'];
        $size       = $_FILES['fileToUpload']['size'];
        $extension  = pathinfo($path, PATHINFO_EXTENSION);
        /** Allowed extensions for file */
        $extensions = Params::EXTENSIONS;

        // Check for valid extension
        if (in_array($extension, $extensions) && $size > 0) {
            // Open the file for reading
            if (($h = fopen("{$filename}", "r")) !== FALSE) {
                // Escape first line of file
                fgetcsv($h);
                // Read file
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    $user->insert($table, [
                        'offices_id' => $user->officesId(),
                        'departments_id' => $user->departmentId(),
                        'year' => $year,
                        'month' => $month,
                        'employees_id' => $data[0],
                        'employees_average_id' => $data[0] . '_' . $year,
                        'quantity' => $data[2]
                    ]);
                }
                if ($user->success()) {
                    Errors::setErrorType('success', 'Your DB is successfully updated.');
                } else {
                    Errors::setErrorType('danger', 'Something is going wrong, please try again.');
                }
                // Close the file
                fclose($h);
            }
        } else {
            Errors::setErrorType('warning', 'Your file must have CSV extension.');
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
          <?php if (Input::exists() && Errors::countAllErrors()) {
              include './../common/errors/errors.php';
          }
          ?>
          <section class="no-padding-top no-padding-bottom">
              <div class="col-lg-12">
                  <div class="block">
                      <form method="post" enctype="multipart/form-data">
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="title">
                                      <strong>Update your database</strong>
                                      <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm float-sm-right" id="info_upload"><i class="fa fa-info-circle"></i></button>
                                  </div>
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
                                      <?php foreach ($allTables as $table) { ?>
                                          <option value="<?php echo $table; ?>"><?php echo strtoupper($table); ?></option>
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
          <!-- Modal-->
          <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left show" style="display: none;">
              <div role="document" class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3">Please make attention!</strong>
                          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                      </div>
                      <div class="modal-body">
                          <p> Your file must have .csv extension (e.g. absentees.csv). </p>
                          <p> Your file doesn't need contain headers. </p>
                          <p> Download your file from <a href="download.php">here</a>!</p>
                          <p> For other information please contact administrator. </p>
                      </div>
                      <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Modal End -->
        <?php
        include '../common/includes/footer.php';
        ?>
      </div>
    </div>
    <!-- JavaScript files-->
  <?php
  include "./../common/includes/scripts.php";
  ?>
  <script src="./../common/vendor/pulsate/jquery.pulsate.js"></script>
  <script>
      $("#info_upload").pulsate({color:"#633b70;"});
  </script>
  </body>
</html>


