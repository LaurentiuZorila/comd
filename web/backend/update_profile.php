<?php
require_once 'core/init.php';

$name           = $backendUser->name();

if (Input::exists()) {
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
                        'id'    => $backendUser->userId(),
                        'table' => Params::TBL_SUPERVISORS
                    ]
            ],
            'new_password' =>  [
                'required'  => true,
                'min'       => 6,
                'max'       => 30
            ]
        ]);

        /** If validation is passed */
        if ($validation->passed()) {
            $first_name = Common::dbValues([Input::post('first_name') => ['trim', 'ucfirst']]);
            $last_name  = Common::dbValues([Input::post('last_name') => ['trim', 'ucfirst']]);
            $name       = $first_name . ' ' . $last_name;
            $username   = Common::dbValues([Input::post('username') => ['trim']]);
            $password   = Input::post('new_password');
            $password   = password_hash($password, PASSWORD_DEFAULT);

            /** Update supervisor table */
            $update = $backendUser->update(Params::TBL_SUPERVISORS, [
                'fname'     => $first_name,
                'lname'     => $last_name,
                'name'      => $name,
                'password'  => $password
            ], [
                'id' => $backendUser->userId()
            ]);

            if ($update) {
                Errors::setErrorType('success', Translate::t($lang, 'Profile_success_updated'));
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
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Profile'); ?></h2>
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
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'Edit_profile'); ?> </li>
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
                            <div style="" class="card-header">
                                <h4 class="mb-2 mt-1 text-gray-light text-center"><?php echo $name; ?></h4>
                            </div>
                            <div class="card-body text-center"><img src="./../common/img/user.png" class="card-profile-img">
                                <p class="mb-1">Depart</p>
                                <p class="mb-1">Offices</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <form class="card" method="post">
                            <div class="card-header">
                                <h5 class="card-title"><?php echo Translate::t($lang, 'Edit_profile'); ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t($lang, 'FN'); ?></label>
                                            <input type="text" name="first_name" placeholder="<?php echo $backendUser->fName(); ?>" class="form-control" value="<?php if (Input::exists()) { echo $first_name; }?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-md-8">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t($lang, 'LN'); ?></label>
                                            <input type="text" name="last_name" placeholder="<?php echo $backendUser->lName(); ?>" class="form-control" value="<?php if (Input::exists()) { echo $last_name; }?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-3">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t($lang, 'Username'); ?></label>
                                            <input type="text" name="username" placeholder="<?php echo $backendUser->uName(); ?>" class="form-control" value="<?php if (Input::exists()) { echo $last_name; }?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t($lang, 'current_pass'); ?></label>
                                            <input type="text" name="password" placeholder="<?php echo Translate::t($lang, 'current_pass'); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php echo Translate::t($lang, 'new_pass'); ?></label>
                                            <input type="text" name="new_password" placeholder="<?php echo Translate::t($lang, 'new_pass'); ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button id="Submit" value="<?php echo Translate::t($lang, 'Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t($lang, 'Submit'); ?></button>
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
<?php
include "./../common/includes/scripts.php";
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>
