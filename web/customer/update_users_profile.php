<?php
require_once 'core/init.php';
$customerUser  = new CustomerUser();
$data          = new CustomerProfile();

$allEmployees   = $data->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $customerUser->officesId()], ['name', 'offices_id', 'id', 'departments_id']);
$departments    = $data->records(Params::TBL_DEPARTMENT, [], ['id', 'name']);


if (Input::exists()) {
    $employeesId    = Input::post('user');
    $departmentId   = Input::post('department');
    $officesId      = Input::post('office');

    /** Instantiate validate class */
    $validate = new Validate();
    /** Check if all fields are not empty */
    $validation = $validate->check(Input::exists(), [
        'user'          => ['required' => true],
        'department'    => ['required' => true],
        'office'        => ['required' => true]
    ]);

/** Check if validation passed */
    if ($validation->passed()) {
        $employeesDetails       = $data->records(Params::TBL_EMPLOYEES, ['id', '=', $employeesId], ['departments_id', 'offices_id'], false);
        $employeesRecentTables  = $data->records(Params::TBL_OFFICE, ['id', '=', $employeesDetails->offices_id], ['tables'], false);

        /** Employees tables */
        $empRecentTables  = explode(',', $employeesRecentTables->tables);

        foreach ($empRecentTables as $allTables) {
            $tables[] = $data::PREFIX . $allTables;
        }

        $customerUser->update($data::TBL_EMPLOYEES, [
            'departments_id' => $departmentId,
            'offices_id'     => $officesId
        ], [
            'id' => $employeesId
        ]);


        $customerUser->insert(Params::TBL_CHANGES, [
                'employees_id'              => $employeesId,
                'current_departments_id'    => $employeesDetails->departments_id,
                'current_offices_id'        => $employeesDetails->offices_id,
                'new_departments_id'        => $departmentId,
                'new_offices_id'            => $officesId
            ]);

        foreach ($tables as $table) {
            $customerUser->update($table, [
                'departments_id' => $departmentId,
                'offices_id'     => $officesId
            ], [
                'employees_id' => $employeesId
            ]);
        }

        if (!$customerUser->errors()) {
            Errors::setErrorType('success', 'Your database is successfully updated.');
        } elseif ($customerUser->errors()) {
            Errors::setErrorType('danger', 'You have some errors, please try again!');
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
                            <div class="title"><strong>Update user</strong></div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Select employees</label>
                                        <div class="col-sm-9">
                                            <select name="user" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('user'))) {echo 'is-invalid';} ?>">
                                                <option value="">Select user</option>
                                                <?php
                                                foreach ($allEmployees as $employees) { ?>
                                                    <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?><small>(<?php echo escape($data->records(Params::TBL_DEPARTMENT, ['id', '=', $employees->departments_id], ['name'], false)->name);?> - <?php echo escape($data->records(Params::TBL_OFFICE, ['id', '=', $employees->offices_id], ['name'], false)->name); ?>)</small></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('user'))) { ?>
                                                <div class="invalid-feedback">Please select year.</div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Department to move:</label>
                                        <div class="col-sm-9">
                                            <select name="department" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('department'))) {echo 'is-invalid';} ?>">
                                                <option value="">Select department</option>
                                                <?php
                                                foreach ($departments as $department) { ?>
                                                <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('department'))) { ?>
                                                <div class="invalid-feedback">Please select year.</div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Office to move:</label>
                                        <div class="col-sm-9">
                                            <select name="office" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('office'))) {echo 'is-invalid';} ?>">
                                                <option value="">Select office</option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('office'))) { ?>
                                                <div class="invalid-feedback">Please select year.</div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="col-sm-9 ml-auto">
                                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                        <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
    include '../common/includes/footer.php';
    ?>
</div>
</div>
<!-- JavaScript files-->
<?php
include "./../common/includes/scripts.php";
?>
<?php
include 'includes/js/offices.php';
?>

</body>
</html>