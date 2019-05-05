<?php
require_once 'core/init.php';

$name           = $frontUser->name();
$departmentName = $frontProfile->records(Params::TBL_DEPARTMENT, ['id', '=', $frontUser->departmentId()], ['name'], false);
$officeName     = $frontProfile->records(Params::TBL_OFFICE, ['id', '=', $frontUser->officeId()], ['name'], false);

if (Input::exists() && Tokens::tokenVerify(Tokens::getInputName())) {
    /** Instantiate validate class */
        $validate = new Validate();

        /** Validate fields */
        $validation = $validate->check($_POST, [
            'first_name'     =>  [
                'required'  => true,
                'min'       => 2,
                'max'       => 30
            ],
            'last_name'     =>  [
                'required'  => true,
                'min'       => 2,
                'max'       => 50
            ],
            'password' =>  [
                'matches_db'    => [
                    'id'    => $frontUser->userId(),
                    'table' => Params::TBL_EMPLOYEES
                ]
            ],
            'new_password'   =>  [
                'required'  => true,
                'min'       => 6,
                'max'       => 20
            ]
        ]);

        /** If validation is passed */
        if ($validation->passed()) {
            $first_name = Common::dbValues([Input::post('first_name') => ['ucfirst']]);
            $last_name  = Common::dbValues([Input::post('last_name') => ['ucfirst']]);
            $name       = $first_name . ' ' . $last_name;
            $frontUsername   = trim(Input::post('username'));
            $password   = trim(Input::post('new_password'));
            $password   = password_hash($password, PASSWORD_DEFAULT);

            /** Update employees table */
            $update = $frontUser->update(Params::TBL_EMPLOYEES, [
                'fname'     => $first_name,
                'lname'     => $last_name,
                'name'      => $name,
                'password'  => $password
            ], [
                'id' => $frontUser->userId()
            ]);

            if ($frontUser->dbSuccess()) {
                Redirect::timeTo(5, '../index.php');
                Errors::setErrorType('success', Translate::t('Profile_success_updated'));
                Errors::setErrorType('success', Translate::t('login_again'));
                $frontUser->logout(false);
            } else {
                Errors::setErrorType('success', Translate::t('Db_error'));
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
    // LOADING PRELOADER MODAL
    include './../common/includes/preloaders.php';
    ?>
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Profile'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Edit_profile'); ?> </li>
            </ul>
        </div>
        <?php
            if (Input::exists() && Errors::countAllErrors()) {
                include './../common/errors/errors.php';
            }
        ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-profile">
                            <div style="background-image: url(./../common/img/wallp.jpg);" class="card-header"></div>
                            <div class="card-body text-center"><img src="./../common/img/user.png" class="card-profile-img">
                                <h4 class="mb-3 text-gray-light"><?php echo $name; ?></h4>
                                <h5 class="mb-1"><?php echo strtoupper($departmentName->name); ?></h5>
                                <h5 class="mb-1"><?php echo strtoupper($officeName->name); ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <form class="card" method="post">
                            <div class="card-header">
                                <h5 class="card-title"><?php echo Translate::t('Edit_profile'); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4" >
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t('FN'); ?></label>
                                            <input type="text" name="first_name" placeholder="<?php echo $frontUser->fName(); ?>" class="form-control <?php echo Input::exists('post') && empty(Input::post('first_name')) ? 'is-invalid' : ''; ?>" value="<?php if (Input::exists()) { echo $first_name; }?>">
                                            <?php if (Input::exists() && empty(Input::post('first_name'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="col-sm-8 col-md-8">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t('LN'); ?></label>
                                            <input type="text" name="last_name" placeholder="<?php echo $frontUser->lName(); ?>" class="form-control <?php echo Input::exists('post') && empty(Input::post('last_name')) ? 'is-invalid' : ''; ?>" value="<?php if (Input::exists()) { echo $last_name; }?>">
                                            <?php if (Input::exists() && empty(Input::post('last_name'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-3">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t('Username'); ?></label>
                                            <input type="text" name="username" placeholder="<?php echo $frontUser->userName(); ?>" class="form-control" value="<?php if (Input::exists()) { echo $frontUser->userName(); }?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t('Pass'); ?></label>
                                            <input type="text" name="password" placeholder="<?php echo Translate::t('Pass'); ?>" class="form-control <?php echo Input::exists('post') && empty(Input::post('password')) ? 'is-invalid' : ''; ?>">
                                            <?php if (Input::exists() && empty(Input::post('password'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t('new_pass'); ?></label>
                                            <input type="text" name="new_password" placeholder="<?php echo Translate::t('new_pass'); ?>" class="form-control <?php echo Input::exists('post') && empty(Input::post('new_password')) ? 'is-invalid' : ''; ?>">
                                            <?php if (Input::exists() && empty(Input::post('new_pass'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include '../common/includes/footer.php';
        ?>
    </div>
</div>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>
