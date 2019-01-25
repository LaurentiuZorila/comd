<?php
require_once 'core/init.php';

//require_once '../vendor/league/csv/autoload.php';
//use League\Csv\Reader;

// All tables
$allTables = $leadData->records(Params::TBL_OFFICE, ['id', '=', $lead->officesId()], ['tables'], false);
$allTables = explode(',', $allTables->tables);
array_map('trim', $allTables);


if (Input::existsName('post', Tokens::getInputName())) {
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
        $months = $leadData->records($table, AC::where([['offices_id', $lead->officesId()], ['year', $year]]), ['month']);
        $title  = ucfirst(Input::post('tables'));

        if (count($months) > 0) {
            foreach ($months as $dbMonth) {
                $allMonths[] = $dbMonth->month;
                // Months from database
                $allMonths = array_unique($allMonths);
            }
        } else {
            $allMonths = [];
        }

        // First delete present records if update button si checked and then Update form file
        if (in_array($month, $allMonths)) {
            // Delete conditions
            $whereCommon = AC::where([['offices_id', $lead->officesId()], ['month',$month], ['year', $year]]);
            $whereEvent  = AC::where([['lead_id', $lead->officesId()], ['month',$month], ['year', $year]]);

            $deleteCommonData       = CustomerDB::getInstance()->delete($table, $whereCommon);
            $deleteEventData        = CustomerDB::getInstance()->delete(Params::TBL_EVENTS, $whereEvent);

            if (!$deleteCommonData && !$deleteEventData) {
                Errors::setErrorType('danger', Translate::t('Db_error'));
            }
        }

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
                $data = fgetcsv($h, 1000, ",");
                if ($data[0] === 'Id' || $data[1] === 'Name' || $data[2] === 'Quantity'|| $data[3] === 'Days') {
                    while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                        if (is_numeric($data[2]) && Dates::checkDays($data[3])) {
                            $quantity = !empty($data[2]) ? $data[2] : 0;
                            $startDate = Dates::startDate(Dates::makeDateForDb($data[3], $month), $year);
                            $endDate   = Dates::endDate(Dates::makeDateForDb($data[3], $month), $year);

                            // Start inserting into DB
                            try {
                                $leadDb->getPdo()->beginTransaction();
                                // Insert data in common table
                                $lead->insert($table, [
                                    'offices_id'            => $lead->officesId(),
                                    'departments_id'        => $lead->departmentId(),
                                    'year'                  => $year,
                                    'month'                 => $month,
                                    'employees_id'          => $data[0],
                                    'employees_average_id'  => $data[0] . '_' . $year,
                                    'insert_type'           => Params::INSERT_TYPE['file'],
                                    'quantity'              => $quantity,
                                    'days'                  => Dates::makeDateForDb($data[3], $month)
                                ]);
                                // Insert data in events table
                                $lead->insert(Params::TBL_EVENTS, [
                                        'user_id'   => $data[0],
                                        'lead_id'   => $lead->customerId(),
                                        'title'     => $title,
                                        'Event_status'  => 'Accepted',
                                        'start'     => $startDate,
                                        'end'       => $endDate,
                                        'days_number'   => $quantity,
                                        'days'      => Dates::makeDateForDb($data[3], $month),
                                        'month'     => $month,
                                        'year'      => $year,
                                        'status'    => 1,
                                        'added'     => date('Y-m-d H:m:s'),
                                        'updated'   => date('Y-m-d H:m:s')
                                    ]);
                                $lead->insert(Params::TBL_NOTIFICATION, [
                                        'user_id'   => $data[0],
                                        'lead_id'   => $lead->customerId(),
                                        'status'    => 1,
                                        'view'      => 1,
                                        'employee_view'     => 0,
                                        'response'          => 'navNotification',
                                        'response_status'   => 1,
                                        'date'              => date('Y-m-d H:m:s')
                                ]);
                                $leadDb->getPdo()->commit();
                            } catch (PDOException $e) {
                                $leadDb->getPdo()->rollBack();
                                echo $e->getMessage();
                                Errors::setErrorType('danger', $e->getMessage());
                            }
                        } else {
                            Errors::setErrorType('danger', Translate::t('type_int', ['ucfirtst'=>true]));
                        }
                    }
                } else {
                    Errors::setErrorType('danger', Translate::t('not_correct_file', ['ucfirst'=>true]));
                }

                if ($lead->success() && !Errors::countAllErrors()) {
                    Errors::setErrorType('success', Translate::t('Db_success'));
                } else {
                    Errors::setErrorType('danger', Translate::t('Db_error'));
                }
                // Close the file
                fclose($h);
            }
        } else {
            Errors::setErrorType('warning', Translate::t('Csv_extension'));
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
    <link rel="stylesheet" href="./../common/css/spiner/style.css">
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
        // LOADING PRELOADER MODAL
        include './../common/includes/preloaders.php';
        ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Update_db'); ?></h2>
          </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo Translate::t('Update_db'); ?>
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
                                      <strong><?php echo Translate::t('Update_db'); ?></strong>
                                      <button type="button" data-toggle="modal" data-target="#info_modal" class="btn btn-primary btn-sm float-sm-right" id="info_upload"><i class="fa fa-info-circle"></i></button>
                                  </div>
                              </div>

                              <div class="col-sm-4 year">
                                  <select id="year" name="year" class="form-control mb-1 <?php if (Input::exists() && empty(Input::post('year'))) {echo 'is-invalid';} ?>">
                                      <option value=""><?php echo Translate::t('Select_year'); ?></option>
                                      <?php
                                      foreach (Common::getYearsList() as $year) { ?>
                                          <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                    if (Input::exists() && empty(Input::post('year'))) { ?>
                                        <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                  <?php }?>
                              </div>
                              <div class="col-sm-4 month">
                                  <select id="month" name="month" class="form-control mb-1 <?php if (Input::exists() && empty(Input::post('month'))) {echo 'is-invalid';} ?>">
                                      <option value=""><?php echo Translate::t('Select_month'); ?></option>
                                      <?php foreach (Common::getMonths($lang) as $key => $value) { ?>
                                          <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                  if (Input::exists() && empty(Input::post('month'))) { ?>
                                      <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                  <?php }?>
                              </div>
                              <div class="col-sm-4 tables" style="display: none;">
                                  <select id="tables" name="tables" class="form-control mb-1 <?php if (Input::exists() && empty(Input::post('tables'))) {echo 'is-invalid';} ?>">
                                      <option value=""><?php echo Translate::t('Select_table'); ?></option>
                                      <?php foreach ($allTables as $table) { ?>
                                          <option value="<?php echo $table; ?>"><?php echo strtoupper($table); ?></option>
                                      <?php } ?>
                                  </select>
                                  <?php
                                  if (Input::exists() && empty(Input::post('tables'))) { ?>
                                      <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
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
                                  <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
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
                      <div class="modal-header"><strong id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t('Make_attention'); ?></strong>
                          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                      </div>
                      <div class="modal-body">
                          <p> <?php echo Translate::t('Csv_extension', ['ucfirst']); ?> </p>
                          <p> <?php echo Translate::t('Download_file_from', ['ucfirst']); ?>: <a href="download.php"><?php echo Translate::t('File'); ?></a></p>
                          <hr />
                          <p> <?php echo Translate::t('completed_csv_file', ['ucfirst']); ?></p>
                          <p class="text-danger"><?php echo Translate::t('not_remove_id', ['ucfirst']); ?></p>
                          <div class="table-responsive" style="border-color: white;">
                              <table class="table" id="">
                                  <thead>
                                  <tr role="row">
                                      <th class="text-white-50"><?php echo Translate::t('ID', ['ucfirst']);?></th>
                                      <th class="text-white-50"><?php echo Translate::t('Name', ['ucfirst']);?></th>
                                      <th class="text-white-50"><?php echo Translate::t('quantity', ['ucfirst']);?></th>
                                      <th class="text-white-50"><?php echo Translate::t('days', ['ucfirst']);?></th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td class="">
                                              <?php echo 1;?>
                                          </td>
                                          <td class="text-white-50">
                                              <?php echo 'Stan Papusa';?>
                                          </td>
                                          <td class="text-white-50">
                                              <?php echo '3';?>
                                          </td>
                                          <td class="text-white-50">
                                              <?php echo '22,23,24';?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td class="">
                                              <?php echo 2;?>
                                          </td>
                                          <td class="text-white-50">
                                              <?php echo 'Meleaca Costel';?>
                                          </td>
                                          <td class="text-white-50">
                                              <?php echo '5';?>
                                          </td>
                                          <td class="text-white-50">
                                              <?php echo '1,2,3,4,5';?>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" data-dismiss="modal" class="btn btn-secondary"><?php echo Translate::t('Close'); ?></button>
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
      $('#Submit').click(function(){
          $('#myModal').modal('show');
      });
      $("#info_upload").pulsate({color:"#633b70;"});
  </script>
  </body>
</html>


