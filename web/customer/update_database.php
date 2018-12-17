<?php
require_once 'core/init.php';
//require_once '../vendor/league/csv/autoload.php';
//use League\Csv\Reader;

// All tables
$allTables = $leadData->records(Params::TBL_OFFICE, ['id', '=', $lead->officesId()], ['tables'], false);
$allTables = explode(',', $allTables->tables);
array_map('trim', $allTables);


if (Input::exists() && Tokens::tokenVerify()) {
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
        $table  = Common::dbValues([Input::post('tables') => ['trim']]);
        $table  = Params::PREFIX . $table;
        $months = $leadData->records($table, ActionCond::where([['offices_id', $lead->officesId()], ['year', $year]]), ['month']);

        foreach ($months as $dbMonth) {
            $allMonths[] = $dbMonth->month;
            // Months from database
            $allMonths = array_unique($allMonths);
        }

        // First delete present records if update button si checked and then Update form file
        if (Input::existsName('post', 'confirmUpdate') && in_array($month, $allMonths)) {
            $db = CustomerDB::getInstance();
            $db->delete($table, ActionCond::where([['offices_id', $lead->officesId()], ['year', $year]]));
        }

        /** Check if for selected month exists records */
        if (!in_array($month, $allMonths)) {
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
                    // Read file
                    $data[] = fgetcsv($h, 1000, ",");

                    if ($data[0] === 'Id' || $data[1] === 'Name' || $data[2] === 'Quantity') {
                        // Escape first line of file
                        fgetcsv($h);

                        while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                            if (is_numeric($data[2])) {
                                $quantity = !empty($data[2]) ? $data[2] : 0;
                                $lead->insert($table, [
                                    'offices_id'            => $lead->officesId(),
                                    'departments_id'        => $lead->departmentId(),
                                    'year'                  => $year,
                                    'month'                 => $month,
                                    'employees_id'          => $data[0],
                                    'employees_average_id'  => $data[0] . '_' . $year,
                                    'insert_type'           => Params::INSERT_TYPE['file'],
                                    'quantity'              => $quantity,
                                    'days'                  => $data[3]
                                ]);
                            } else {
                                Errors::setErrorType('danger', Translate::t($lang, 'type_int', ['ucfirtst'=>true]));
                            }
                        }
                    } else {
                        Errors::setErrorType('danger', Translate::t($lang, 'correct_file', ['ucfirst'=>true]));
                    }

                    if ($lead->success() && !Errors::countAllErrors()) {
                        Errors::setErrorType('success', Translate::t($lang, 'Db_success'));
                    } else {
                        Errors::setErrorType('danger', Translate::t($lang, 'Db_error'));
                    }
                    // Close the file
                    fclose($h);
                }
            } else {
                Errors::setErrorType('warning', Translate::t($lang, 'Csv_extension'));
            }
        } else {
            Errors::setErrorType('info', Translate::t($lang, 'data_month_exists'));
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
<script>
    function displayMessage(type, message, time) {
        $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-'+type+'"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
        setInterval(function() { $(".eventMessage").fadeOut(); }, time);
    }
</script>
</head>
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
            <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Update_db'); ?></h2>
          </div>
        </div>
          <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade show">
              <div class="loader loader-3">
                  <div class="dot dot1"></div>
                  <div class="dot dot2"></div>
                  <div class="dot dot3"></div>
              </div>
          </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'Update_db'); ?>
            </li>
          </ul>
        </div>
          <?php if (Input::exists() && Errors::countAllErrors()) {
              include './../common/errors/errors.php';
          }
          if (Input::existsName('get', 'config') && Input::noPost() &&  Errors::countAllErrors()) {
              include './../common/errors/errors.php';
          }
          if (Session::exists('success')) {
              include './../common/errors/profileConfigOk.php';
          }
          ?>
          <section class="no-padding-top no-padding-bottom">
              <div class="response"></div>
              <div class="col-lg-12">
                  <div class="block">
                      <form method="post" enctype="multipart/form-data" class="" id="my-awesome-dropzone">
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="title">
                                      <strong><?php echo Translate::t($lang, 'Update_db'); ?></strong>
                                      <button type="button" data-toggle="modal" data-target="#info_modal" class="btn btn-primary btn-sm float-sm-right" id="info_upload"><i class="fa fa-info-circle"></i></button>
                                  </div>
                              </div>

                              <div class="col-sm-4 year">
                                  <select id="year" name="year" class="form-control mb-1 <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                      <option value=""><?php echo Translate::t($lang, 'Select_year'); ?></option>
                                      <?php
                                      foreach (Common::getYearsList() as $year) { ?>
                                          <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                    if (Input::exists() && empty(Input::post('year'))) { ?>
                                        <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                  <?php }?>
                              </div>
                              <div class="col-sm-4 month">
                                  <select id="month" name="month" class="form-control mb-1 <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} ?>">
                                      <option value=""><?php echo Translate::t($lang, 'Select_month'); ?></option>
                                      <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                          <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                  if (Input::exists() && empty(Input::post('month'))) { ?>
                                      <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                  <?php }?>
                              </div>
                              <div class="col-sm-4 tables" style="display: none;">
                                  <select id="tables" name="tables" class="form-control mb-1 <?php if (Input::exists() && empty(Input::post('tables'))) {echo 'is-invalid';} ?>">
                                      <option value=""><?php echo Translate::t($lang, 'Select_table'); ?></option>
                                      <?php foreach ($allTables as $table) { ?>
                                          <option value="<?php echo $table; ?>"><?php echo strtoupper($table); ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                  if (Input::exists() && empty(Input::post('tables'))) { ?>
                                      <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                  <?php }?>
                              </div>
                              <div class="col-sm-12 mt-2 mb-3">
                              <input type="file" name="fileToUpload" id="fileToUpload">
                              </div>
                              <div class="col-sm-12 pb-3 confirmUpdate" style="display: none;">
                                  <label>Update</label>
                                  <input type="checkbox" name="confirmUpdate" value="confirmUpdate" />
                              </div>
                              <div class="col-sm-2">
                                  <button id="Submit" value="<?php echo Translate::t($lang, 'Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t($lang, 'Submit'); ?></button>
                                  <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </section>
          <!-- Modal-->
          <div id="info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left show" style="display: none;">
              <div role="document" class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t($lang, 'Make_attention'); ?></strong>
                          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                      </div>
                      <div class="modal-body">
                          <p> <?php echo Translate::t($lang, 'Csv_extension'); ?> </p>
                          <p> <?php echo Translate::t($lang, 'Download_file_from'); ?>: <a href="download.php"><?php echo Translate::t($lang, 'File'); ?></a></p>
                      </div>
                      <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-secondary"><?php echo Translate::t($lang, 'Close'); ?></button>
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
  include "./includes/js/monthExists.php";
  ?>
  <script src="./../common/vendor/pulsate/jquery.pulsate.js"></script>
  <script>
      $('#myModal').click(function(){
          $('#myModal').modal('show');
      });
      $("#info_upload").pulsate({color:"#633b70;"});
  </script>
  </body>
</html>


