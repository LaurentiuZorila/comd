<?php
require_once 'core/init.php';
$allEmployees   = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $lead->officesId()]), ['name', 'offices_id', 'id', 'departments_id'], true, ['ORDER BY' => 'name']);
$departments    = $leadData->records(Params::TBL_DEPARTMENT, [], ['id', 'name'], true, ['ORDER BY' => 'name']);
$citys          = $leadData->records(Params::TBL_CITY, [], ['id', 'city'], true, ['ORDER BY' => 'city']);

if (Input::exists() && Tokens::tokenVerify()) {
    /** Instantiate validate class */
    $validate = new Validate();
    /** Check if all fields are not empty */
    $validation = $validate->check($_POST, [
        'user'          => ['required' => true],
        'city'          => ['required' => true],
        'department'    => ['required' => true],
        'office'        => ['required' => true]
    ]);

/** Check if validation passed */
    if ($validation->passed()) {
        $employeesId    = Input::post('user');
        $departmentId   = Input::post('department');
        $officesId      = Input::post('office');
        $cityId         = Input::post('city');

        $employeesDetails       = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $employeesId]), ['departments_id', 'offices_id', 'city_id'], false);
        $employeesRecentTables  = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $employeesDetails->offices_id]), ['tables'], false);

        /** Employees tables */
        $empRecentTables  = explode(',', $employeesRecentTables->tables);

        foreach ($empRecentTables as $allTables) {
            $tables[] = Params::PREFIX . $allTables;
        }

        try {
            $leadDb->getPdo()->beginTransaction();

            $lead->update(Params::TBL_EMPLOYEES, [
                'departments_id' => $departmentId,
                'offices_id'     => $officesId,
                'city_id'        => $cityId
            ], [
                'id' => $employeesId
            ]);

            $lead->insert(Params::TBL_CHANGES, [
                'employees_id'              => $employeesId,
                'current_departments_id'    => $employeesDetails->departments_id,
                'current_offices_id'        => $employeesDetails->offices_id,
                'current_city_id'           => $employeesDetails->city_id,
                'new_departments_id'        => $departmentId,
                'new_offices_id'            => $officesId,
                'new_city_id'               => $cityId
            ]);

            foreach ($tables as $table) {
                $lead->update($table, [
                    'departments_id' => $departmentId,
                    'offices_id'     => $officesId,
                ], [
                    'employees_id' => $employeesId
                ]);
            }
            $leadDb->getPdo()->commit();
            Errors::setErrorType('success', Translate::t('Db_success', ['ucfirst'=>true]));
        } catch (PDOException $e) {
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
                                                    <option value="<?php echo $employees->id; ?>"><?php echo $employees->name; ?><small>(<?php echo escape($leadData->records(Params::TBL_DEPARTMENT, ['id', '=', $employees->departments_id], ['name'], false)->name);?> - <?php echo escape($leadData->records(Params::TBL_OFFICE, ['id', '=', $employees->offices_id], ['name'], false)->name); ?>)</small></option>
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
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t(['select', 'city'], ['ucfirst']); ?></label>
                                        <div class="col-sm-9">
                                            <select name="city" class="form-control <?php if (Input::exists() && empty(Input::post('department'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t(['select', 'city'], ['ucfirst']); ?></option>
                                                <?php
                                                foreach ($citys as $city) { ?>
                                                    <option value="<?php echo $city->id; ?>"><?php echo strtoupper($city->city); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('city'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t('New_depart'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="department" class="form-control <?php if (Input::exists() && empty(Input::post('department'))) {echo 'is-invalid';} ?>">
                                                <option value=""><?php echo Translate::t('Select_depart', ['ucfirst']); ?></option>
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
                                                <option value=""><?php echo Translate::t('Select_office', ['strttoupper']); ?></option>
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
                                                <button id="Submit" name="<?php echo Tokens::inputName(); ?>" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-secondary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                                <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                            </div>
                                        </div>
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