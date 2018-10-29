<?php
require_once 'core/login-init.php';

if (Input::exists()) {
        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'Username' =>
                ['required' => true,
                 'min'      => 2,
                 'max'      => 40
                ],
            'Password' =>
                ['required' => true,
                 'min'      => 5,
                 'max'      => 40
                ]
        ]);

        if ($validation->passed()) {
            $user       = new CustomerUser();
            $config     = new CustomerProfile();
            $login      = $user->login(Input::post('Username'), Input::post('Password'));

            /**  Get configured status */
            $configured = $config->records(Params::TBL_OFFICE, ['id', '=', $user->officesId()], ['configured'], false)->configured;

            if ($login) {
                /** Check if user is configured */
                if ($configured) {
                    Redirect::to('index.php');
                } else {
                    Redirect::to('profile-setup/profileconfig.php', ['id' => $user->customerId(), 'setup' => 'sadadsada2323232']);
                }
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
            if (Input::exists() && Errors::countAllErrors()) {
                include './../common/errors/errors.php';
            } ?>
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
                      <input id="login-username" type="text" name="Username" required data-msg="Please enter your username" class="input-material">
                      <label for="login-username" class="label-material">User Name</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="Password" required data-msg="Please enter your password" class="input-material">
                      <label for="login-password" class="label-material">Password</label>
                    </div><button type="submit" id="login" class="btn btn-primary" name="login">Login</button>
                      <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
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