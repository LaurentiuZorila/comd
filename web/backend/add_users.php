<?php
require_once 'core/init.php';

if (Input::exists() && Tokens::tokenVerify()) {
    /** Instantiate validate class */
    $validate   = new Validate();
    /** Check finput fields */
    $validation = $validate->check($_POST, [
        'first_name'         => [
            'required' => true,
            'min'      => 2,
            'max'      => 20
        ],
        'last_name'   => [
            'required'  => true,
            'min'       => 2,
            'max'       => 20
        ],
        'username'       => [
            'required'  => true,
            'min'       => 2,
            'max'       => 20,
            'unique'    => Params::TBL_TEAM_LEAD
        ]
    ]);

    /** If validation is passed */
    if ($validate->passed()) {

        $fname     = Common::dbValues([Input::post('first_name') => ['trim', 'ucfirst']]);
        $lname     = Common::dbValues([Input::post('last_name') => ['trim', 'ucfirst']]);
        $username  = Common::dbValues([Input::post('username') => ['trim']]);
        $name      = $fname . ' ' . $lname;

        $create = $backendUser->create(Params::TBL_TEAM_LEAD, [
                'departments_id'   => $backendUser->departmentId(),
                'offices_id'       => $backendUser->officesId(),
                'supervisors_id'   => $backendUser->userId(),
                'lang'             => Params::DEFAULTLANG,
                'fname'            => $fname,
                'lname'            => $lname,
                'name'             => $name,
                'username'         => $username,
                'password'         => password_hash('parola', PASSWORD_DEFAULT)
        ]);

        if ($create) {
            Errors::setErrorType('info', Translate::t($lang, 'Db_success'));
            Errors::setErrorType('info', Translate::t($lang, 'default_pass') . ': parola');
            Errors::setErrorType('info', sprintf("%s: %s - %s: parola", Translate::t($lang, 'Username'), $username, Translate::t($lang, 'Pass')));
        } else {
            Errors::setErrorType('danger', Translate::t($lang, 'Db_error'));
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'add_user'); ?></h2>
            </div>
        </div>
        <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade hide">
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
                <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'add_user'); ?>
                </li>
            </ul>
        </div>
        <?php
        if (Input::exists() && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <!-- Form Elements -->
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title">
                                <strong><?php echo Translate::t($lang, 'add_user'); ?></strong>
                            </div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t($lang, 'FN'); ?></label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" name="first_name" placeholder="<?php echo Translate::t($lang, 'FN'); ?>" class="form-control" value="<?php if (Input::exists() && Errors::countAllErrors('danger')) { echo Input::post('first_name');; }?>">
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t($lang, 'LN'); ?></label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" name="last_name" placeholder="<?php echo Translate::t($lang, 'LN'); ?>" class="form-control" value="<?php if (Input::exists() && Errors::countAllErrors('danger')) { echo Input::post('last_name'); }?>">
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t($lang, 'Username'); ?></label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" name="username" placeholder="<?php echo Translate::t($lang, 'Username'); ?>" class="form-control" value="<?php if (Input::exists() && Errors::countAllErrors('danger')) { echo Input::post('username'); }?>">
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="col-sm-9 ml-auto">
                                        <div class="form-group row">
                                            <button id="Submit" name="add" value="<?php echo Translate::t($lang, 'Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t($lang, 'create'); ?></button>
                                            <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php
    include '../common/includes/footer.php';
    ?>
</div>
</div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";

include 'includes/js/ajax_update_lead.php';
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>