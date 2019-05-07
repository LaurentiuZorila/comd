<?php
require_once 'core/init.php';

if (Input::exists()) {
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
        ]
    ]);

    /** If validation is passed */
    if ($validate->passed()) {
        $fname     = Common::dbValues([Input::post('first_name') => ['trim', 'ucfirst']]);
        $lname     = Common::dbValues([Input::post('last_name') => ['trim', 'ucfirst']]);
        $username  = Common::dbValues([Input::post('first_name') => ['trim', 'strtolower']]);
        $username  = Common::makeUsername($fname);
        $name      = $fname . ' ' . $lname;

        $lead->create(Params::TBL_EMPLOYEES, [
                'departments_id'   => $lead->departmentId(),
                'offices_id'       => $lead->officesId(),
                'supervisors_id'   => $lead->supervisorId(),
                'city_id'          => $lead->cityId(),
                'lang'             => Params::DEFAULTLANG,
                'status'           => 1,
                'fname'            => $fname,
                'lname'            => $lname,
                'name'             => $name,
                'username'         => $username,
                'password'         => password_hash('superpassword', PASSWORD_DEFAULT)
        ]);

        if ($lead->success()) {
            Errors::setErrorType('info', Translate::t('Db_success'));
            Errors::setErrorType('info', Translate::t('default_pass') . ': superpassword');
            Errors::setErrorType('info', sprintf("%s: %s - %s: superpassword", Translate::t('Username'), $username, Translate::t('Pass')));
        } else {
            Errors::setErrorType('danger', Translate::t('Db_error'));
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('add_user'); ?></h2>
            </div>
        </div>
        <?php
        include './../common/includes/preloaders.php';
        ?>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a>
                </li>
                <li class="breadcrumb-item active"><?php echo Translate::t('add_user'); ?>
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
                                <strong><?php echo Translate::t('add_user'); ?></strong>
                            </div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">
                                            <?php if (Input::exists() && empty(Input::post('first_name'))) { ?>
                                            <h6 class="text-danger"><?php echo Translate::t('FN'); ?><i class="fa fa-asterisk text-very-small align-text-top text-danger"></i></h6>
                                            <?php } else { ?>
                                            <h6><?php echo Translate::t('FN'); ?></h6>
                                            <?php } ?>
                                        </label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" name="first_name" placeholder="<?php echo Translate::t('FN'); ?>" class="form-control <?php if (Input::exists() && empty(Input::post('first_name'))) {echo 'is-invalid';} ?>" value="<?php if (Input::exists() && Errors::countAllErrors('danger')) { echo Input::post('first_name'); }?>">
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">
                                        <?php if (Input::exists() && empty(Input::post('last_name'))) { ?>
                                            <h6 class="text-danger"><?php echo Translate::t('LN'); ?><i class="fa fa-asterisk text-very-small align-text-top text-danger"></i></h6>
                                        <?php } else { ?>
                                            <h6><?php echo Translate::t('LN'); ?></h6>
                                        <?php } ?>
                                        </label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" name="last_name" placeholder="<?php echo Translate::t('LN'); ?>" class="form-control <?php if (Input::exists() && empty(Input::post('last_name'))) {echo 'is-invalid';} ?>" value="<?php if (Input::exists() && Errors::countAllErrors('danger')) { echo Input::post('last_name'); }?>">
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">
                                        </label>
                                        <div class="form-group col-sm-9">
                                            <button id="Submit" name="add" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t('confirm'); ?></button>
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
include "./includes/js/markAsRead.php";
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>