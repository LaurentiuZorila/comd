<?php
require_once 'core/init.php';
$allEmployees   = $leadData->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $lead->officesId()], ['name', 'offices_id', 'id', 'departments_id']);
$departments    = $leadData->records(Params::TBL_DEPARTMENT, [], ['id', 'name']);


if (Input::exists()) {
    $employeesId    = Common::valuesToInsert(Input::post('user'));
    $departmentId   = Common::valuesToInsert(Input::post('department'));
    $officesId      = Common::valuesToInsert(Input::post('office'));

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
            $tables[] = Params::PREFIX . $allTables;
        }

        $lead->update($data::TBL_EMPLOYEES, [
            'departments_id' => $departmentId,
            'offices_id'     => $officesId
        ], [
            'id' => $employeesId
        ]);


        $lead->insert(Params::TBL_CHANGES, [
                'employees_id'              => $employeesId,
                'current_departments_id'    => $employeesDetails->departments_id,
                'current_offices_id'        => $employeesDetails->offices_id,
                'new_departments_id'        => $departmentId,
                'new_offices_id'            => $officesId
            ]);

        foreach ($tables as $table) {
            $lead->update($table, [
                'departments_id' => $departmentId,
                'offices_id'     => $officesId
            ], [
                'employees_id' => $employeesId
            ]);
        }

        if (!$user->errors()) {
            Errors::setErrorType('success', Translate::t($lang, 'Db_success'));
        } elseif ($user->errors()) {
            Errors::setErrorType('danger', Translate::t($lang, 'Db_error'));
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
    <link rel="stylesheet" href="./../common/css/spiner/style.css">
</head>
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Update_employees_profile'); ?></h2>
            </div>
        </div>
        <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade hide">
            <div class="loader loader-3">
                <div class="dot dot1"></div>
                <div class="dot dot2"></div>
                <div class="dot dot3"></div>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a>
                </li>
                <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'Update_employees_profile'); ?>
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
                            <div class="title"><strong><?php echo Translate::t($lang, 'Update_employees_profile'); ?></strong></div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t($lang, 'Select_Employees'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="user" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('user'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t($lang, 'Select_Employees'); ?></option>
                                                <?php
                                                foreach ($allEmployees as $employees) { ?>
                                                    <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?><small>(<?php echo escape($leadData->records(Params::TBL_DEPARTMENT, ['id', '=', $employees->departments_id], ['name'], false)->name);?> - <?php echo escape($leadData->records(Params::TBL_OFFICE, ['id', '=', $employees->offices_id], ['name'], false)->name); ?>)</small></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('user'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t($lang, 'New_depart'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="department" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('department'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t($lang, 'Select_depart'); ?></option>
                                                <?php
                                                foreach ($departments as $department) { ?>
                                                <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('department'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t($lang, 'New_office'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="office" class="form-control mb-3 mb-3 <?php if (Input::exists() && empty(Input::post('office'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t($lang, 'Select_office'); ?></option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('office'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t($lang, 'This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="col-sm-9 ml-auto">
                                        <button id="Submit" value="<?php echo Translate::t($lang, 'Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t($lang, 'Submit'); ?></button>
                                        <button type="submit" name="save" class="btn btn-primary"><?php echo Translate::t($lang, 'Save'); ?></button>
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
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>