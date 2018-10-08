<?php
require_once 'core/init.php';
$profile = new FrontendProfile();
$user    = new FrontendUser();
if (!$user->isLoggedIn()) {
    Redirect::to('login.php');
}


$name           = $user->name();
$departmentName = $profile->records(Params::TBL_DEPARTMENT, ['id', '=', $user->departmentId()], ['name'], false);
$officeName     = $profile->records(Params::TBL_OFFICE, ['id', '=', $user->officeId()], ['name'], false);

if (Input::exists()) {
    $first_name = trim(Input::post('first_name'));
    $last_name  = trim(Input::post('last_name'));
    $name       = $first_name . ' ' . $last_name;
    $username   = trim(Input::post('username'));
    $password   = trim(Input::post('password'));
    $password   = password_hash($password, PASSWORD_DEFAULT);

        $validate = new Validate();
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
            'username'  =>  [
                'required'  => true,
                'min'       => 2,
                'max'       => 50,
                'unique'    => Params::TBL_EMPLOYEES
            ],
            'password' =>  [
                'required'  => true,
                'min'   => 6,
                'max'   => 30
            ],
            'repeat_password'   =>  [
                'matches'   => 'password'
            ]
        ]);

        if ($validation->passed()) {
            $users->update(Params::TBL_EMPLOYEES, [
                'name'      => $name,
                'username'  => $username,
                'password'  => $password
            ], [
                'id' => $user->userId()
            ]);
        } else {
            $validationErrors = $validation->errors();
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
    <div class="page-content" style="padding-bottom: 70px;">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Profile</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Profile </li>
            </ul>
        </div>
        <?php
            if (Input::exists() && count($validationErrors) > 0) { ?>
            <section>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-body">
                            <div class="alert alert-dismissible fade show badge-danger" role="alert">
                                <strong class="text-white-50"> You have some errors! </strong>
                                <?php
                                foreach ($validationErrors as $error) { ?>
                                    <p class="text-white-50 mb-0"><?php echo $error; ?></p>
                                <?php } ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-profile">
                            <div style="" class="card-header">
                                <h4 class="mb-2 mt-1 text-gray-light text-center"><?php echo $name; ?></h4>
                            </div>
                            <div class="card-body text-center"><img src="./../common/img/user.png" class="card-profile-img">
                                <p class="mb-1"><?php echo $departmentName->name; ?></p>
                                <p class="mb-1"><?php echo $officeName->name; ?></p>
                                <button class="btn btn-outline-secondary"><span class="fa fa-twitter"></span> Follow</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <form class="card" method="post">
                            <div class="card-header">
                                <h5 class="card-title">Edit Profile</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="first_name" placeholder="First name" class="form-control" value="<?php if (Input::exists()) { echo $first_name; }?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-md-8">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="last_name" placeholder="Last Name" class="form-control" value="<?php if (Input::exists()) { echo $last_name; }?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-3">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" placeholder="Username" class="form-control" value="<?php if (Input::exists()) { echo $last_name; }?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="text" name="password" placeholder="Password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Password again</label>
                                            <input type="text" name="repeat_password" placeholder="Password again" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" name="Update" class="btn btn-primary" value="Update Profile">
                                <input type="hidden" name="token" value="" />
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
</body>
</html>
