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
        'offices'       => [
                'required'  => true
        ]
    ]);

    /** If validation is passed */
    if ($validate->passed()) {

        $fname     = Common::dbValues([Input::post('first_name') => ['trim', 'ucfirst']]);
        $lname     = Common::dbValues([Input::post('last_name') => ['trim', 'ucfirst']]);
        $username  = Common::makeUsername($fname);
        $name      = $fname . ' ' . $lname;
        $officeId  = Input::post('offices');

        $create = $backendUser->create(Params::TBL_TEAM_LEAD, [
                'departments_id'   => $backendUser->departmentId(),
                'offices_id'       => $officeId,
                'city_id'          => $backendUser->cityId(),
                'supervisors_id'   => $backendUser->userId(),
                'lang'             => Params::DEFAULTLANG,
                'fname'            => $fname,
                'lname'            => $lname,
                'name'             => $name,
                'username'         => $username,
                'password'         => password_hash('staffsuperpassword', PASSWORD_DEFAULT)
        ]);

        if ($create) {
            Errors::setErrorType('info', Translate::t('Db_success'));
            Errors::setErrorType('info', Translate::t('default_pass') . ': staffsuperpassword');
            Errors::setErrorType('info', sprintf("%s: %s - %s: staffsuperpassword", Translate::t('Username'), $username, Translate::t('Pass')));
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
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a>
                </li>
                <li class="breadcrumb-item active"><?php echo Translate::t('add_staff'); ?>
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
                                <strong><?php echo Translate::t('add_staff', ['ucfirst']); ?></strong>
                            </div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">
                                            <?php if (Input::exists() && empty(Input::post('first_name'))) { ?>
                                            <h6 class="text-danger"><?php echo Translate::t('FN', ['ucfirst']); ?><i class="fa fa-asterisk text-very-small align-text-top text-danger"></i></h6>
                                            <?php } else { ?>
                                            <h6><?php echo Translate::t('FN'); ?></h6>
                                            <?php } ?>
                                        </label>
                                        <div class="form-group col-sm-9">
                                            <input type="text" name="first_name" placeholder="<?php echo Translate::t('FN', ['ucfirst']); ?>" class="form-control <?php if (Input::exists() && empty(Input::post('first_name'))) {echo 'is-invalid';} ?>" value="<?php if (Input::exists() && Errors::countAllErrors('danger')) { echo Input::post('first_name'); }?>">
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
                                        <?php if (Input::exists() && empty(Input::post('offices'))) { ?>
                                            <h6 class="text-danger"><?php echo Translate::t('Select_office'); ?><i class="fa fa-asterisk text-very-small align-text-top text-danger"></i></h6>
                                        <?php } else { ?>
                                            <h6><?php echo Translate::t('Select_office'); ?></h6>
                                        <?php } ?>
                                        </label>
                                        <div class="form-group col-sm-9">
                                            <select class="selectpicker show-tick form-control <?php if (Input::exists() && empty(Input::post('offices'))) {echo 'is-invalid';} else { echo 'mb-3';}?>" data-live-search="true" name="offices" data-size="10">
                                                <option value="" class="text-white-50"><?php echo Translate::t('Select_office', ['ucfirst']); ?></option>
                                                <?php foreach ($backendUserProfile->getOffices(['id', 'name']) as $office) { ?>
                                                <option value="<?php echo $office->id; ?>"><?php echo $office->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"></label>
                                        <div class="col-sm-9">
                                            <button id="Submit" name="add" class="btn-sm btn-primary" type="submit"><?php echo Translate::t('create'); ?></button>
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