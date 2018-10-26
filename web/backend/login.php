<?php
require_once 'core/login-init.php';


if (Input::exists() && Tokens::tokenVerify(Input::post('token'))) {
    /** Instantiate validate class */
    $validate = new Validate();

    /** Validate fields */
    $validation = $validate->check($_POST, [
        'username' => [
                'required'  => true
        ],
        'password' => [
                'required'  => true
            ]
    ]);

    /** Check if validation is passed */
    if ($validation->passed()) {
        $user = new BackendUser();
        $login = $user->login(Input::post('username'), Input::post('password'));
        if ($login) {
            Redirect::to('index.php');
        } else {
            Errors::setErrorType('danger', 'Username or password not valid! Please try again!');
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
            if (Errors::countAllErrors()) {
                include './../common/errors/errors.php';
            }
            ?>
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
                      <input id="login-username" type="text" name="username" data-msg="Please enter your username" class="input-material">
                      <label for="login-username" class="label-material">User Name</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="password" data-msg="Please enter your password" class="input-material">
                      <input type="hidden" name="token" value="<?php echo Tokens::getSubmitToken(); ?>">
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