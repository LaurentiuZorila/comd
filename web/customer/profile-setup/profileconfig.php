<?php
require_once '../core/setup-init.php';
$customer = new CustomerUser();
$records  = new CustomerProfile();

if (Input::exists('get')) {
    $token          = Input::get('setup');
    $setupTokenHash = Session::get('setupToken');
    if (!CustomerUser::passCheck($token, $setupTokenHash)) {
        CustomerRedirect::to('../login.php');
    }
}
$customerId         = Input::get('id');
$customerDetails    = $records->records(Params::TBL_TEAM_LEAD, ['id', '=', $customerId], ['username', 'name', 'id', 'offices_id'], false);

if (Input::exists()) {
    $newTables  = Input::post('tables');
    // Array with tables to create
    $newTables  = explode(',', trim($newTables));
    foreach ($newTables as $newTable) {
        $tables[] = Params::PREFIX . trim($newTable);
    }

    $password   = password_hash(Input::post('new_password'), PASSWORD_DEFAULT);
    $customerId = $customerDetails->id;
    $officesId  = $customerDetails->offices_id;

    // Instantiate Validate class
    $validate   = new Validate();
    $validation = $validate->check($_POST, [
            'Password'  => [
                    'required'  => true,
                    'min'       => 6
            ]
    ]);
    if ($validation->passed()) {
        // Update users table with new password
        $customer->update(Params::TBL_TEAM_LEAD,[
                'password'  => $password
        ], [
                'id' => $customerDetails->id
        ]);

        $customer->update(Params::TBL_OFFICE, [
                'tables'        => $tables,
                'configured'    => CustomerProfile::CONFIGURED
        ], [
                'offices_id' => $customerDetails->offices_id
        ]);

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon.ico">

    <title>COMD</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
	<link rel="icon" type="image/png" href="assets/img/favicon.png" />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

	<!-- CSS Files -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/material-bootstrap-wizard.css" rel="stylesheet" />

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="assets/css/demo.css" rel="stylesheet" />
</head>

<body>
	<div class="image-container set-full-height" style="background-image: url('assets/img/wizard-profile.jpg')">
	    <!--   Creative Tim Branding   -->
	    <a href="http://creative-tim.com">
	         <div class="logo-container">
	            <div class="logo">
	                <img src="assets/img/default-avatar.png">
	            </div>
	            <div class="brand">
	                Profile Configuration
	            </div>
	        </div>
	    </a>

		<!--  Made With Material Kit  -->
		<a href="http://demos.creative-tim.com/material-kit/index.html?ref=material-bootstrap-wizard" class="made-with-mk">
			<div class="brand">MK</div>
			<div class="made-with">Made with <strong>Material Kit</strong></div>
		</a>

	    <!--   Big container   -->
	    <div class="container">
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">
		            <!--      Wizard container        -->
		            <div class="wizard-container">
		                <div class="card wizard-card" data-color="purple" id="wizardProfile">
		                    <form action="" method="post">
		                <!--        You can switch " data-color="purple" "  with one of the next bright colors: "green", "orange", "red", "blue"       -->

		                    	<div class="wizard-header">
		                        	<h3 class="wizard-title">
		                        	   Hello, <?php echo $customerDetails->name; ?>
		                        	</h3>
									<h5>This information will let us know more about you.</h5>
		                    	</div>
								<div class="wizard-navigation">
									<ul>
			                            <li><a href="#about" data-toggle="tab">About</a></li>
			                            <li><a href="#account" data-toggle="tab">Account</a></li>
			                            <li><a href="#address" data-toggle="tab">Database</a></li>
			                        </ul>
								</div>

		                        <div class="tab-content">
		                            <div class="tab-pane" id="about">
		                              <div class="row">
		                                	<h4 class="info-text"> Let's start with the basic information (with validation)</h4>
		                                	<div class="col-sm-4 col-sm-offset-1">
		                                    	<div class="picture-container">
		                                        	<div class="picture">
                                        				<img src="assets/img/default-avatar.png" class="picture-src" id="wizardPicturePreview" title=""/>
		                                        	</div>
		                                        	<h6></h6>
		                                    	</div>
		                                	</div>
		                                	<div class="col-sm-5">
												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">account_box</i>
													</span>
													<div class="form-group label-floating" data-toggle="wizard-radio" rel="tooltip" title="We have selected user for you.">
			                                          <label class="control-label"><?php echo $customerDetails->username; ?> <small>(default user)</small></label>
			                                          <input name="username" type="text" disabled class="form-control" value="<?php echo $customerDetails->username; ?>">
			                                        </div>
												</div>

												<div class="input-group">
													<span class="input-group-addon">
														<i class="material-icons">lock_open</i>
													</span>
													<div class="form-group label-floating passDefault" data-toggle="wizard-radio" rel="tooltip" title="Please insert password." id="passDefault" style="display: block;">
													  <label class="control-label">Insert default password <small>(required)</small></label>
													  <input name="Password" type="password" class="form-control default_password" id="pass" required/>
													</div>
												</div>
		                                	</div>
		                            	</div>
		                            </div>
		                            <div class="tab-pane" id="account">
		                                <h4 class="info-text"> Insert you new password! </h4>
		                                <div class="row">
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock</i>
                                                </span>
                                                    <div class="form-group label-floating newPass" data-toggle="wizard-radio">
                                                        <label class="control-label">New password <small>(required)</small></label>
                                                        <input name="new_password" type="password" class="form-control" id="newPass" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock</i>
                                                </span>
                                                    <div class="form-group label-floating againPass" data-toggle="wizard-radio">
                                                        <label class="control-label">Password again <small>(required)</small></label>
                                                        <input name="password_again" type="password" class="form-control" id="againPass" required>
                                                    </div>
                                                </div>
                                            </div>
		                                </div>
		                            </div>
		                            <div class="tab-pane" id="address">
		                                <div class="row">
		                                    <div class="col-sm-12">
		                                        <h4 class="info-text"> Config your data base! </h4>
		                                    </div>
		                                    <div class="col-sm-7">
	                                        	<div class="form-group label-floating" data-toggle="wizard-radio" rel="tooltip" title="For default we have created common tables (e.g. furlough, absentees, unpaid leaves). Insert tables name what you need created followed by comma (e.g target,quality etc..)">
	                                        		<label class="control-label">Insert your tables to create</label>
	                                    			<input type="text" class="form-control" name="tables">
	                                        	</div>
		                                    </div>
                                            <div class="col-sm-3 col-sm-offset-1">
                                                <div class="form-group label-floating" data-toggle="wizard-radio" rel="tooltip" title="For each table inserted you need assign one symbol. If for first table highest data are best data, you need yo insert symbol '>', if lowest data are best data you need to insert symbol '<'. Please make attention!">
                                                    <label class="control-label">Best</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group label-floating">
                                                    <label class="control-label">Select your department</label>
                                                    <select name="country" class="form-control">
                                                        <option disabled="" selected=""></option>
                                                        <option value="Afghanistan"> Afghanistan </option>
                                                        <option value="Albania"> Albania </option>
                                                    </select>
                                                </div>
                                            </div>
		                                    <div class="col-sm-6">
		                                        <div class="form-group label-floating">
		                                            <label class="control-label">Select your office</label>
	                                            	<select name="country" class="form-control">
														<option disabled="" selected=""></option>
	                                                	<option value="Afghanistan"> Afghanistan </option>
	                                                	<option value="Albania"> Albania </option>
	                                            	</select>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="wizard-footer">
		                            <div class="pull-right">
		                                <input type='button' class='btn btn-next btn-fill btn-primary btn-wd' name='next' value='Next' />
		                                <input type="submit" class='btn btn-finish btn-fill btn-primary btn-wd' name="finish" value='Finish' />
		                            </div>

		                            <div class="pull-left">
		                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Previous' />
		                            </div>
		                            <div class="clearfix"></div>
		                        </div>
		                    </form>
		                </div>
		            </div> <!-- wizard container -->
		        </div>
	        </div><!-- end row -->
	    </div> <!--  big container -->

	    <div class="footer">
	        <div class="container text-center">
	             Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>. Free download <a href="http://www.creative-tim.com/product/bootstrap-wizard">here.</a>
	        </div>
	    </div>
	</div>

</body>
	<!--   Core JS Files   -->
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.bootstrap.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="assets/js/material-bootstrap-wizard.js"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="assets/js/jquery.validate.min.js"></script>
<?php
include 'checkPassword.php';
include 'newPasswords.php';
?>


</html>
