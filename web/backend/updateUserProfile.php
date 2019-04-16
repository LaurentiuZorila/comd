<?php
require_once 'core/init.php';
$allEmployees   = $backendUserProfile->records(Params::TBL_EMPLOYEES, AC::where(['departments_id', $backendUser->departmentId()]), ['name', 'offices_id', 'id', 'departments_id'], true, ['ORDER BY' => 'name']);
$departments    = $backendUserProfile->records(Params::TBL_DEPARTMENT, [], ['id', 'name'], true, ['ORDER BY' => 'name']);
$offices        = $backendUserProfile->records(Params::TBL_OFFICE, AC::where(['departments_id', $backendUser->departmentId()]), ['id', 'name'], true, ['ORDER BY' => 'name']);


if (Input::exists()) {
    /** Instantiate validate class */
    $validate = new Validate();
    /** Check if all fields are not empty */
    $validation = $validate->check($_POST, [
        'user'          => ['required' => true],
        'department'    => ['required' => true],
        'office'        => ['required' => true]
    ]);

    /** Check if validation passed */
    if ($validation->passed()) {
        $employeesId    = Input::post('user');
        $departmentId   = Input::post('department');
        $officesId      = Input::post('office');

        $employeesDetails       = $backendUserProfile->records(Params::TBL_EMPLOYEES, AC::where(['id', $employeesId]), ['departments_id', 'offices_id'], false);
        $employeesRecentTables  = $backendUserProfile->records(Params::TBL_OFFICE, AC::where(['id', $employeesDetails->offices_id]), ['tables'], false);
        $staffIds               = $backendUserProfile->records(Params::TBL_OFFICE, AC::where(['departments_id', $backendUser->departmentId()]), ['id'], true);

        // Array with staff ids
        foreach ($staffIds as $staffId) {
            $staffIDs[] = $staffId->id;
        }

        /** Employees tables */
        $empRecentTables  = explode(',', $employeesRecentTables->tables);

        foreach ($empRecentTables as $allTables) {
            $tables[] = Params::PREFIX . $allTables;
        }

        try {
            $backendDB->getPdo()->beginTransaction();
            $backendUser->update(Params::TBL_EMPLOYEES, [
                'departments_id' => $departmentId,
                'offices_id'     => $officesId
            ], [
                'id' => $employeesId
            ]);

            $backendUser->create(Params::TBL_CHANGES, [
                'employees_id'              => $employeesId,
                'current_departments_id'    => $employeesDetails->departments_id,
                'current_offices_id'        => $employeesDetails->offices_id,
                'new_departments_id'        => $departmentId,
                'new_offices_id'            => $officesId
            ]);

            $backendUser->create(Params::TBL_NOTIFICATION, [
               'lead_id'    => $employeesDetails->offices_id,
                'status'    => 1,
                'message'   => 'employee_moved',
            ]);

            foreach ($tables as $table) {
                $backendUser->update($table, [
                    'departments_id' => $departmentId,
                    'offices_id'     => $officesId
                ], [
                    'employees_id' => $employeesId
                ]);
            }

            $backendDB->getPdo()->commit();
            Errors::setErrorType('success', Translate::t('Db_success', ['ucfirst'=>true]));

        } catch (PDOException $e) {
            $backendDB->getPdo()->rollBack();
            Errors::setErrorType('danger', Translate::t('Db_error', ['ucfirst'=>true]));
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
    // LOADING PRELOADER MODAL
    include './../common/includes/preloaders.php';
    ?>
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Update_employees_profile'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a>
                </li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Update_employees_profile'); ?>
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
                            <div class="title"><strong><?php echo Translate::t('Update_employees_profile'); ?></strong></div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t('Select_Employees'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="user" class="form-control <?php if (Input::exists() && empty(Input::post('user'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t('Select_Employees'); ?></option>
                                                <?php
                                                foreach ($allEmployees as $employees) { ?>
                                                    <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?><small>(<?php echo escape($backendUserProfile->records(Params::TBL_OFFICE, AC::where(['id', $employees->offices_id]), ['name'], false)->name); ?>)</small></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('user'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t('New_depart'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="department" class="form-control <?php if (Input::exists() && empty(Input::post('department'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t('Select_depart', ['ucfirst'=>true]); ?></option>
                                                <?php
                                                foreach ($departments as $department) { ?>
                                                    <option value="<?php echo $department->id; ?>"><?php echo strtoupper($department->name); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('department'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t('New_office'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="office" class="form-control <?php if (Input::exists() && empty(Input::post('office'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t('Select_office', ['strttoupper'=>true]); ?></option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('office'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"></label>
                                        <div class="col-sm-9">
                                            <button id="Submit" name="<?php echo Tokens::inputName(); ?>" value="<?php echo Translate::t('Submit'); ?>" class="btn btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                            <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
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
include "./includes/js/office.php";
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>