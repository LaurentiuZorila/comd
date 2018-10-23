<?php
require_once '../core/setup-init.php';
$customer = new CustomerUser();
$records  = new CustomerProfile();

/** Check get token */
if (empty(Input::get('setup')) && !$customer->isLoggedIn()) {
    Redirect::to('../login.php');
}

$customerId         = Input::get('id');
$customerDetails    = $records->records(Params::TBL_TEAM_LEAD, ['id', '=', $customerId], ['username', 'name', 'id', 'offices_id', 'password'], false);

if (Input::exists()) {
    $newTables          = Input::post('tables');
    $defaultPassword    = Input::post('Password');
    $new_password       = Input::post('new_password');
    $confirm_pass       = Input::post('confirm_password');
    $passwordHash       = password_hash(Input::post('new_password'), PASSWORD_DEFAULT);
    $bestConditions     = Input::post('tables_conditions');
    $tablePriority      = Input::post('tables_priorities');

    /** User details */
    $customerId         = $customerDetails->id;
    $officesId          = $customerDetails->offices_id;

    /** Input errors */
    $inputErrors  = [];

    /** Check if all fields are filed */
    if (empty($newTables) || empty($defaultPassword) || empty($confirm_pass) || empty($bestConditions) || empty($tablePriority)) {
        $requiredErrors[] = 'All fields are required!';
    }

    /** Check if default password is correct */
    if (!password_verify($defaultPassword, $customerDetails->password)) {
        $inputErrors[] = 'Password inserted are wrong!';
        Session::put('wrongPassword', 'has-error');
    }

    /** Check if passwords match */
    if ($new_password !== $confirm_pass) {
        Session::put('matchPasswords', 'has-error');
    }

    /** Remove comma if exist at the end of strings */
    $bestConditions = Common::checkLastCharacter($bestConditions);
    $tablePriority  = Common::checkLastCharacter($tablePriority);
    $newTables      = Common::checkLastCharacter($newTables);
    $columnTables   = $newTables . ',' . implode(',', Params::TBL_COMMON);

    /** Array with conditions */
    $conditions = explode(',', trim($bestConditions));
    /** Array with priorities */
    $priorities = explode(',', trim($tablePriority));
    /** Array with tables to create */
    $newTables = explode(',', trim($newTables));

    /** Check if conditions are same with tables to create */
    if (count($conditions) != count($newTables)) {
        $inputErrors[] = 'You must have same number of conditions as tables!';
        Session::put('conditions', 'has-error');
    } else {
        /** Json with tables conditions table : conditions */
        $tablesConditions = Common::toJson(Common::assocArray($newTables, $conditions));
        /** Json with tables conditions table : priorities */
        $tablesPriorities = Common::toJson(Common::assocArray($priorities, $newTables));
    }


    foreach ($newTables as $newTable) {
        /** Tables to create with prefix */
        $tables[] = trim($newTable);
    }

        /** Instantiate Validate class */
        $validate   = new Validate();
        $validation = $validate->check($_POST, [
            'Password' => [
                'required'  => true,
                'min'       => Params::MIN_INPUT,
                'max'       => Params::MAX_INPUT
            ],
            'new_password' => [
                'required'  => true,
                'min'       => Params::MIN_INPUT,
                'max'       => Params::MAX_INPUT
            ],
            'confirm_password' => [
                'required'  => true,
                'matches'   => 'new_password'
            ],
            'tables' => [
                'required' => true
            ],
            'tables_conditions' => [
                'required' => true
            ]
        ]);


        /** Check if validation is passed and not found errors */
        if (count($requiredErrors) === 0 && count($inputErrors) === 0 && $validation->passed()) {
            /** Update users table with new password */
            $customer->update(Params::TBL_TEAM_LEAD, [
                'password' => $passwordHash
            ], [
                'id' => $customerId
            ]);

            /** Update offices table with new tables and configured */
            $customer->update(Params::TBL_OFFICE, [
                'tables'            => $columnTables,
                'configured'        => CustomerProfile::CONFIGURED,
                'tables_conditions' => $tablesConditions,
                'tables_priorities' => $tablesPriorities
            ], [
                'id' => $customerDetails->offices_id
            ]);

            /** Instantiate Create Class */
            $create = new Create();

            /** Create tables */
            foreach ($tables as $table) {
                $create->createTable($table);
            }

        } else {
            /** All errors (Validate Class errors & Create Class input errors) */
            $allErrors  = array_unique(array_merge($inputErrors, $validation->errors()));
        }

        if (count($allErrors) === 0)
        {
            Session::put('configOk', 'Now you need to update your database.');
            Redirect::to('../update_database.php');
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- CSS Files -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/material-bootstrap-wizard.css" rel="stylesheet" />
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="./../../common/css/fonts.css">

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
                        <?php
                        if (Input::exists() && count($allErrors) > 0) { ?>
                            <div class="alert alert-danger">
                                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="tim-icons icon-simple-remove">x</i>
                                </button>
                                <span><b>You have some errors!</b></span>
                                <?php
                                foreach ($allErrors as $allError) {
                                    echo '<p>' . $allError . '</p>';
                                    }
                                    foreach ($validation->errors() as $errors) {
                                        echo '<p>' . $errors . '</p>';
                                    }
                                ?>
                            </div>
                        <?php } ?>
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
													<div class="form-group label-floating passDefault <?php if (Session::exists('wrongPassword')) { echo Session::flash('wrongPassword'); } ?>" data-toggle="wizard-radio" rel="tooltip" title="Please insert password." id="passDefault" style="display: block;">
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
                                                    <div class="form-group label-floating newPass <?php if (Session::exists('matchPasswords')) { echo Session::get('matchPasswords'); } ?>" data-toggle="wizard-radio">
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
                                                    <div class="form-group label-floating againPass <?php if (Session::exists('matchPasswords')) { echo Session::flash('matchPasswords'); } ?>" data-toggle="wizard-radio">
                                                        <label class="control-label">Password again <small>(required)</small></label>
                                                        <input name="confirm_password" type="password" class="form-control" id="againPass" required>
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
		                                    <div class="col-sm-5">
	                                        	<div class="form-group label-floating <?php if (Session::exists('conditions')) { echo Session::get('conditions'); } ?> " data-toggle="wizard-radio" rel="tooltip" title="By default common tables are created (e.g. furlough, absentees, unpaid leaves). Insert tables names what you want to create followed by comma (e.g target,quality etc..)">
	                                        		<label class="control-label">Insert your tables to create</label>
	                                    			<input type="text" class="form-control" name="tables" id="tablesToCreate">
	                                        	</div>
		                                    </div>
                                            <div class="col-sm-3">
                                                <div class="form-group label-floating <?php if (Session::exists('conditions')) { echo Session::flash('conditions'); } ?>" data-toggle="wizard-radio" rel="tooltip" title="For each table inserted you need assign one symbol(>, <). If for first table highest data are best data, you need yo insert symbol '>', if lowest data are best data you need to insert symbol '<'. Please make attention!">
                                                    <label class="control-label">Best</label>
                                                    <input type="text" class="form-control" name="tables_conditions" id="tables_conditions">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group label-floating <?php if (Session::exists('conditions')) { echo Session::flash('conditions'); } ?>" data-toggle="wizard-radio" rel="tooltip" title="For each table inserted you need assign PRIORITIES (most important table must have assigned 1 value.). For each table assign values for your own priorities. MAKE ATTENTION THIS SETTING IS IMPORTANT TO CALCULATE BEST EMPLOYEES!">
                                                    <label class="control-label">Priorities tables</label>
                                                    <input type="text" class="form-control" name="tables_priorities" id="tables_priorities">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-sm-offset-0">
                                                <input type="button" class="btn btn-primary addTable" id="addTable" value="Add">
                                            </div>
                                            <div class="col-sm-2 col-sm-offset-0">
                                                <input type="button" class="btn btn-primary removeTable" id="removeTable" style="display: none;" value="Remove">
                                            </div>
		                                </div>
                                        <div class="row">
                                            <div class="col-sm-12 mt-2">
                                                <table class="table" id="tables" style="display: none;">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Table</th>
                                                        <th>Condition</th>
                                                        <th>Priorites</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tbodyTables">

                                                    </tbody>
                                                </table>
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
<script>
    $("#addTable").click(function () {
        var conditions = $( "#tables_conditions" ).val();
        var tables     = $("#tablesToCreate").val();
        var priorities = $( "#tables_priorities" ).val();

        if (conditions && tables) {
            $.ajax({
                url: "ajax/tables.php",
                dataType: 'Json',
                data: {'tables': tables, 'conditions': conditions, 'priority': priorities},
                success: function (data) {
                    $("#addTable").css("display","none");
                    $("#removeTable").css("display","block");
                    $("#tables").show();
                    $.each(data, function (key, value) {
                        $("#tbodyTables").append('<tr><td class="text-center">#</td><td class="text-primary">' + key + '</td> <td class="text-primary">' + value[0] + '</td><td class="text-primary">' + value[1] + '</td></tr>');
                    });
                }
            });
        }
    });


    $("#removeTable").click(function () {
        $("#addTable").css("display","block");
        $("#removeTable").css("display","none");
        $( "#tables_conditions" ).val("");
        $("#tablesToCreate").val("");
        $("#tables_priorities").val("");
        $("#tbodyTables tr").remove();
        $("#removeTable").hide();
        $("#tables").hide();
    });

</script>

</html>
