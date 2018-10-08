<?php
require_once 'core/init.php';

if (Input::exists() && Tokens::checkInput(Input::post('token'))) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'username' => array('required'  => true),
        'password' => array('required'  => true)
    ));

    if ($validation->passed()) {
        $user = new FrontendUser();
        $login = $user->login(Input::post('username'), Input::post('password'));
        if ($login) {
            Redirect::to('index.php');
        } else {
            Session::put('loginFailed', 'Username or password not valid! Please try again!');
        }
    } else {
        foreach ($validation->errors() as $error) {
            Session::put('validationError', $error);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<?php
include '../common/includes/head.php';
?>
  <body>
     <div class="login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
            <?php
            if (Session::exists('loginFailed')) { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Atention!</strong> <?php echo Session::flash('loginFailed'); ?>
                </div>
            <? }
            if (Session::exists('loginOk')) { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Atention!</strong> <?php echo Session::flash('loginOk'); ?>
                </div>
           <?php } ?>
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1>Login page</h1>
                  </div>
                  <p>Please insert your credentials!</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form method="post" class="form-validate">
                    <div class="form-group">
                      <input id="login-username" type="text" name="username" required data-msg="Please enter your username" class="input-material">
                      <label for="login-username" class="label-material">User Name</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="password" required data-msg="Please enter your password" class="input-material">
                        <input type="hidden" name="token" value="<?php echo Tokens::getInputToken(); ?>">
                      <label for="login-password" class="label-material">Password</label>
                    </div><button type="submit" id="login" class="btn btn-primary" name="login">Login</button>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                  </form><a href="#" class="forgot-pass">Forgot Password?</a><br>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- JavaScript files-->
     <?php
     include "./../common/includes/scripts.php";
     ?>
  </body>
</html>