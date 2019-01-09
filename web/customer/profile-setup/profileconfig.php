<?php
require_once '../core/setup-init.php';
$customer = new CustomerUser();
$records  = new CustomerProfile();

/** Check get token */
if (empty(Input::get('setup')) && !$customer->isLoggedIn()) {
    Redirect::to('../login.php');
}

$tablesText     = 'By default common tables are created (e.g. furlough, absentees, unpaid leaves, medical leaves). Insert tables names what you want to create followed by comma (e.g target,quality etc..)';
$condText       = 'For each table inserted you need assign one symbol(>, <). If for first table highest data are best data, you need yo insert symbol \'>\', if lowest data are best data you need to insert symbol \'<\'. Please make attention!';
$prioritiesText = 'For each table inserted you need assign PRIORITIES (most important table must have assigned 1 value.). For each table assign values for your own priorities. MAKE ATTENTION THIS SETTING IS IMPORTANT TO CALCULATE BEST EMPLOYEES!';
$dataDisplayText = 'For each table you need to insert how data are displayed followed by comma e.g (if your table display numbers you need to insert: NUMBER, if your table need display percentage you need to insert: PERCENTAGE.';

$customerId         = Input::get('id');
$customerDetails    = $records->records(Params::TBL_TEAM_LEAD, ['id', '=', $customerId], ['username', 'name', 'id', 'offices_id', 'password'], false);

// If form is submitted
if (Input::exists()) {
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
            'required'  => true,
            'blanks'    => true
        ],
        'tables_conditions' => [
            'required'  => true,
            'equals'    => 'tables',
            'blanks'    => true
        ],
        'tables_priorities' => [
            'required'  => true,
            'equals'    => 'tables',
            'numbers'   => true,
            'blanks'    => true
        ],
        'data_display' => [
            'required'  => true,
            'equals'    => 'tables',
            'dataType'  => Params::DATADISPLAY,
            'blanks'    => true
        ]
    ], true);

        /** Check if validation is passed and not found errors */
        if ($validation->passed()) {
            $newTables          = Common::dbValues([Input::post('tables') => ['trim', 'strtolower']]);
            $defaultPassword    = Input::post('Password');
            $new_password       = Input::post('new_password');
            $confirm_pass       = Input::post('confirm_password');
            $passwordHash       = password_hash(Input::post('new_password'), PASSWORD_DEFAULT);
            $bestConditions     = Input::post('tables_conditions');
            $tablePriority      = Input::post('tables_priorities');
            $tableDataDisplay   = Common::dbValues([Input::post('data_display') => ['trim', 'strtolower']]);

            /** Remove comma if exist at the end of strings */
            $bestConditions     = Common::checkLastCharacter($bestConditions);
            $tablePriority      = Common::checkLastCharacter($tablePriority);
            $newTables          = Common::checkLastCharacter($newTables);
            $tableDataDisplay   = Common::checkLastCharacter($tableDataDisplay);
            $commonTables       = $newTables . ',' . implode(',', Params::TBL_COMMON);

            /** Array with conditions */
            $conditions = explode(',', $bestConditions);
            /** Array with priorities */
            $priorities = explode(',', $tablePriority);
            /** Array with displayData */
            $displayData = explode(',', $tableDataDisplay);
            /** Array with tables to create */
            $newTables = explode(',', $newTables);

            /** Json with tables conditions table : conditions */
            $tablesConditions = Common::toJson(Common::assocArray($newTables, $conditions));
            /** Json with tables conditions table : priorities */
            $tablesPriorities = Common::toJson(Common::assocArray($priorities, $newTables));
            /** Json with tables : data display */
            $tablesDisplay = Common::toJson(Common::assocArray($newTables, $displayData));
            /** Tables */
            $tablesToCreate = Common::assocArray($newTables, $displayData);

            /** User details */
            $customerId         = $customerDetails->id;
            $officesId          = $customerDetails->offices_id;


            /** Update users table with new password */
            $customer->update(Params::TBL_TEAM_LEAD, [
                'password' => $passwordHash
            ], [
                'id' => $customerId
            ]);

            /** Update offices table with new tables and configured */
            $customer->update(Params::TBL_OFFICE, [
                'tables'                => $commonTables,
                'configured'            => CustomerProfile::CONFIGURED,
                'tables_conditions'     => $tablesConditions,
                'tables_priorities'     => $tablesPriorities,
                'data_visualisation'    => $tablesDisplay
            ], [
                'id' => $customerDetails->offices_id
            ]);

            /** Instantiate Create Class */
            $create = new Create();
            /** Create tables */
            foreach ($tablesToCreate as $table => $type) {
                if ($type === 'number') {
                    $create->createTable($table, 'int', true);
                } elseif ($type === 'percentage') {
                    $create->createTable($table, 'float', true);
                }
            }

            foreach (Params::PREFIX_TBL_COMMON as $commonTables) {
                $create->createTable($commonTables, 'int');
            }
        }

        if (!Errors::countAllErrors())
        {
            Session::put('success', Translate::t('next_update_db'));
            Redirect::to('../update_database.php?config='. Tokens::getRoute());
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
	    <a href="#">
	         <div class="logo-container">
	            <div class="logo">
	                <img src="assets/img/default-avatar.png">
	            </div>
	            <div class="brand">
	                Profile Configuration
	            </div>
	        </div>
	    </a>

	    <!--   Big container   -->
	    <div class="container">
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">
		            <!--      Wizard container        -->
		            <div class="wizard-container">
                        <?php
                        if (Input::exists() && Errors::countAllErrors()) { ?>
                            <div class="alert alert-danger">
                                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="tim-icons icon-simple-remove">x</i>
                                </button>
                                <span><b>You have some errors!</b></span>
                                <?php
                                foreach (Errors::getErrors() as $allError) {
                                    echo '<p>' . $allError . '</p>';
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
                                                    <div class="form-group label-floating newPass <?php if (Session::exists('new_password')) { echo Session::get('new_password'); } ?>" data-toggle="wizard-radio">
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
                                                    <div class="form-group label-floating againPass <?php if (Session::exists('confirm_password')) { echo Session::flash('confirm_password'); } ?>" data-toggle="wizard-radio">
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
		                                    <div class="col-sm-3">
	                                        	<div class="form-group label-floating <?php if (Session::exists('tables')) { echo Session::get('tables'); } ?> " data-toggle="wizard-radio" rel="tooltip" title="<?php echo $tablesText; ?>">
	                                        		<label class="control-label">Tables to create</label>
	                                    			<input type="text" class="form-control" name="tables" id="tablesToCreate" value="<?php if (Input::exists() && Input::existsName('post', 'tables')) { echo Input::post('tables'); }; ?>" />
	                                        	</div>
		                                    </div>
                                            <div class="col-sm-3">
                                                <div class="form-group label-floating <?php if (Session::exists('tables_conditions')) { echo Session::flash('tables_conditions'); } ?>" data-toggle="wizard-radio" rel="tooltip" title="<?php echo $condText; ?>">
                                                    <label class="control-label">Tables conditions</label>
                                                    <input type="text" class="form-control" name="tables_conditions" id="tables_conditions" value="<?php if (Input::exists() && Input::existsName('post', 'tables_conditions')) { echo Input::post('tables_conditions'); }; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group label-floating <?php if (Session::exists('tables_priorities')) { echo Session::flash('tables_priorities'); } ?>" data-toggle="wizard-radio" rel="tooltip" title="<?php echo $prioritiesText; ?>">
                                                    <label class="control-label">Priorities tables</label>
                                                    <input type="text" class="form-control" name="tables_priorities" id="tables_priorities" value="<?php if (Input::exists() && Input::existsName('post', 'tables_priorities')) { echo Input::post('tables_priorities'); }; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group label-floating <?php if (Session::exists('data_display')) { echo Session::flash('data_display'); } ?>" data-toggle="wizard-radio" rel="tooltip" title="<?php echo $dataDisplayText; ?>">
                                                    <label class="control-label">Data display</label>
                                                    <input type="text" class="form-control" name="data_display" id="data_display" value="<?php if (Input::exists() && Input::existsName('post', 'data_display')) { echo Input::post('data_display'); }; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-sm-offset-0">
                                                <input type="button" class="btn btn-primary btn-sm addTable" id="addTable" value="Add">
                                            </div>
                                            <div class="col-sm-2 col-sm-offset-0">
                                                <input type="button" class="btn btn-primary btn-sm removeTable" id="removeTable" style="display: none;" value="Remove">
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
                                                        <th>Data display</th>
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
		                            <div class="pull-right hiddenAppear">
		                                <input type='button' class='btn btn-next btn-fill btn-primary btn-wd' name='next' value='Next' />
		                                <input type="submit" class='btn btn-fill btn-primary btn-wd finishBtn' name="finish" value='Finish' style="display: none;"/>
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
	             Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim
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
        var conditions  = $( "#tables_conditions" ).val();
        var tables      = $("#tablesToCreate").val();
        var priorities  = $( "#tables_priorities" ).val();
        var displayData = $("#data_display").val();

        $(".hiddenAppear").append('<input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">');

        if (conditions && tables && priorities) {
            $.ajax({
                url: "ajax/tables.php",
                dataType: 'Json',
                data: {'tables': tables, 'conditions': conditions, 'priority': priorities, 'dataDisplay': displayData},
                success: function (data) {
                    $("#addTable").css("display","none");
                    $("#removeTable").css("display","block");
                    $(".finishBtn").show();
                    $("#tables").show();
                    $.each(data, function (key, value) {
                        $("#tbodyTables").append(
                            '<tr>' +
                            '<td class="text-center">#</td>' +
                            '<td class="text-primary">' + key + '</td>' +
                            '<td class="text-primary">' + value[0] + '</td>' +
                            '<td class="text-primary">' + value[1] + '</td>' +
                            '<td class="text-primary">' + value[2] + '</td>' +
                            '</tr>'
                        );
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
        $("#data_display").val("");
        $("#tbodyTables tr").remove();
        $("#removeTable").hide();
        $("#tables").hide();
        $(".finishBtn").hide();
    });

</script>

</html>
