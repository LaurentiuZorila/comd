<?php
require_once 'core/init.php';
$user    = new FrontendUser();
$profile = new FrontendProfile();

$name           = $user->name();
$departmentName = $profile->departmentDetails($user->departmentId(), 'name');
$officeName     = $profile->officeDetails($user->officeId(), 'name');

if (Input::exists() && Token::check(Input::post('token'))) {
    $fname      = trim(Input::post('fname'));
    $lname      = trim(Input::post('lname'));
    $name       = $fname . ' ' . $lname;
    $username   = trim(Input::post('username'));
    $password   = trim(Input::post('password'));
    $password   = password_hash($password, PASSWORD_DEFAULT);

    if (!empty($fname) && !empty($lname) && !empty($username) && !empty($password)) {
        $users->update(Params::TBL_EMPLOYEES, [
            'name'      => $name,
            'username'  => $username,
            'password'  => $password
        ], [
            'id' => $user->userId()
        ]);
    }

}
?>

<!DOCTYPE html>
<html>
<?php
include 'includes/head.php';
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
                <li class="breadcrumb-item active">Profile            </li>
            </ul>
        </div>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-profile">
                            <div style="" class="card-header">
                                <h4 class="mb-2 mt-1 text-gray-light text-center"><?php echo escape($name); ?></h4>
                            </div>
                            <div class="card-body text-center"><img src="img/user.png" class="card-profile-img">
                                <p class="mb-1"><?php echo escape($departmentName); ?></p>
                                <p class="mb-1"><?php echo escape($officeName); ?></p>
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
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="fname" placeholder="First name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="lname" placeholder="Last Name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" placeholder="Username" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="text" name="password" placeholder="Password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" name="Update" class="btn btn-primary" value="Update Profile">
                                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer">
            <div class="footer__block block no-margin-bottom">
                <div class="container-fluid text-center">
                    <p class="no-margin-bottom">2018 © Comdata. </p>
                </div>
            </div>
        </footer>
    </div>
</div>