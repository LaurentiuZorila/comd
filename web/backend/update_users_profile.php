<?php
require_once 'core/init.php';

$departments    = $backendUserProfile->records(Params::TBL_DEPARTMENT, ['1 = 1'], ['id', 'name']);
$leads          = $backendUserProfile->records(Params::TBL_TEAM_LEAD, ['supervisors_id', '=', $backendUser->userId()], ['id', 'name', 'offices_id']);
$offices        = $backendUserProfile->records(Params::TBL_OFFICE, ['departments_id', '=', $backendUser->departmentId()]);


if (Input::exists()) {
    /** Instantiate validate class */
    $validate   = new Validate();
    /** Check finput fields */
    $validation = $validate->check($_POST, [
        'leads' => ['required' => true],
        'departments'   => ['required'  => true],
        'offices'       => ['required'  => true]
    ]);

    /** If validation is passed */
    if ($validate->passed()) {

    $leadId     = Input::post('leads');
    $department = Input::post('departments');
    $offices    = Input::post('offices');
    $errors     = [];
    $uploadOk   = [];

        $user->update(Params::TBL_TEAM_LEAD, [
            'departments_id'    => $department,
            'offices_id'        => $offices,
            'supervisors_id'    => $department
        ], [
            'id' => $leadId
        ]);
        if ($user->errors()) {
            Errors::setErrorType('success', 'Your data base are successfully updated.');
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
<?php
include 'includes/navbar.php';
?>
<div class="d-flex align-items-stretch">
    <!-- Sidebar Navigation-->
    <?php
    include 'includes/sidebar.php';
    ?>
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Update user profile </h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a>
                </li>
                <li class="breadcrumb-item active">Update user profile
                </li>
            </ul>
        </div>
        <?php
        if (Input::exists() && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <!-- Form Elements -->
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title">
                                <strong>Update user</strong>
                            </div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Select Team Leader</label>
                                        <div class="col-sm-9">
                                            <select name="leads" class="form-control <?php if (Input::exists() && empty(Input::post('leads'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                                <option value="">Select team-leader</option>
                                                <?php
                                                foreach ($leads as $lead) { ?>
                                                    <option value="<?php echo $lead->id; ?>"><?php echo $lead->name; ?><small> (<?php echo $backendUserProfile->records(Params::TBL_OFFICE, ['id', '=', $lead->offices_id], ['name'], false)->name;?>)</small></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">New department</label>
                                        <div class="col-sm-9">
                                            <select name="departments" class="form-control <?php if (Input::exists() && empty(Input::post('departments'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                                <option value="">Select department</option>
                                                <?php
                                                foreach ($departments as $department) { ?>
                                                <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">New office</label>
                                        <div class="col-sm-9">
                                            <select name="offices" class="form-control <?php if (Input::exists() && empty(Input::post('offices'))) {echo 'is-invalid';} else { echo 'mb-3';}?>">
                                                <option value="">Select offices</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="col-sm-9 ml-auto">
                                        <input type="submit" name="save" class="btn btn-primary" value="Save"/>
                                        <input type="hidden" name="token" value="<?php echo Tokens::getSubmitToken(); ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php
    include '../common/includes/footer.php';
    ?>
</div>
</div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";

include 'includes/js/ajax_update_lead.php';
?>

</body>
</html>