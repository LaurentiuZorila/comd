<?php
require_once 'core/init.php';
require_once '../vendor/league/csv/autoload.php';
use League\Csv\Reader;

if (Input::existsName('post', Tokens::getInputName())) {
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'fileToUpload'  => ['extension' => Params::EXTENSIONS[0],
            'fileError' => 0,
            'fileRequired' => true],
    ]);

    /** Check if validation is passed */
    if ($validation->passed() && !Errors::countAllErrors('danger')) {
        $update = new UpdateDb();
        // Put filename in session
        $fileName   = Session::put(Config::get('files/file_name'), time() .'_' . $_FILES["fileToUpload"]["name"]);

        // Move file
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],
            $update->fileDir() . $fileName);

        // Put complete dir in session
        Session::put(Config::get('files/complete_dirFile'), $update->fileDir() . $fileName);

        if (!Errors::countAllErrors()) {
            //load the CSV document from a file path
            $csv = Reader::createFromPath(Session::get(Config::get('files/complete_dirFile')), 'r');
            $csv->setHeaderOffset(0);


            foreach ($csv as $records) {
                $firstName  = Common::dbValues([$records['First Name'] => ['trim', 'ucfirst']]);
                $lastName   = Common::dbValues([$records['Last Name'] => ['trim', 'ucfirst']]);
                $fullName   = $firstName . ' ' . $lastName;
                $username   = Common::makeUsername($firstName);
                $password   = password_hash('parola', PASSWORD_DEFAULT);

                $insert = $leadDb->insert(Params::TBL_EMPLOYEES, [
                    'departments_id'    => $lead->departmentId(),
                    'offices_id'        => $lead->officesId(),
                    'supervisors_id'    => $lead->supervisorId(),
                    'lang'              => 0,
                    'status'            => '1',
                    'fname'             => $firstName,
                    'lname'             => $lastName,
                    'name'              => $fullName,
                    'username'          => $username,
                    'password'          => $password
                ]);

                if (!$insert) {
                    Errors::setErrorType('danger', 'Db_error');
                }
            }

        }
        // Delete file
        unlink(Session::get(Config::get('files/complete_dirFile')));
        Session::delete(Config::get('files/complete_dirFile'));
    }

    if (!Errors::countAllErrors()) {
        Errors::setErrorType('success', Translate::t('Db_success'));
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
    <script src="./../common/vendor/pulsate/jquery.pulsate.js"></script>
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('add_employees', ['ucfirst']); ?></h2>
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
                                    <strong><?php echo Translate::t('add_employees'); ?></strong>
                                    <button type="button" data-toggle="modal" data-target="#info_modal" class="btn btn-primary btn-sm float-sm-right" id="info_upload"><i class="fa fa-info-circle"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2 mb-3">
                                <input type="file" name="fileToUpload" id="fileToUpload">
                            </div>
                            <div class="col-sm-2">
                                <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-primary" type="submit"><?php echo Translate::t('Submit'); ?></button>
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
                        <p> <?php echo Translate::t('insert_employee_template', ['ucfirst']); ?>: <a href="downloadEmployeesTemplate.php"><?php echo Translate::t('File'); ?></a></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn-sm btn-danger"><?php echo Translate::t('Close'); ?></button>
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
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
    $("#info_upload").pulsate({color:"#633b70;"});
</script>
<?php
include "./includes/js/markAsRead.php";
?>
</body>
</html>


