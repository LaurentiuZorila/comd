<?php
require_once 'core/init.php';

if (Input::exists() && Tokens::tokenVerify()) {
    /** Instantiate validate class */
    $validate   = new Validate();
    /** Check finput fields */
    $validation = $validate->check($_POST, [
        'leads'         => ['required' => true],
        'departments'   => ['required'  => true],
        'offices'       => ['required'  => true]
    ]);

    /** If validation is passed */
    if ($validate->passed()) {

    $leadId     = Input::post('leads');
    $department = Input::post('departments');
    $offices    = Input::post('offices');
    $cityId     = Input::post('city');

        $backendUser->update(Params::TBL_TEAM_LEAD, [
            'departments_id'    => $department,
            'offices_id'        => $offices,
            'supervisors_id'    => $department,
            'city_id'           => $cityId
        ], [
            'id' => $leadId
        ]);

        if ($backendUser->errors()) {
            Errors::setErrorType('danger', Translate::t('Db_error', ['ucfirst']));
        } else {
            Errors::setErrorType('success', Translate::t('Db_success', ['ucfirst']));
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
    include './../common/includes/preloaders.php';
    ?>
    <!-- Sidebar Navigation end-->
    <div class="page-content">
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Update_user_profile', ['ucfirst']); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home', ['ucfirst']); ?></a>
                </li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Update_user_profile', ['ucfirst']); ?>
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
                                <strong><?php echo Translate::t('Update_user_profile', ['ucfirst']); ?></strong>
                            </div>
                            <div class="block-body">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t('Select_leader'); ?></label>
                                        <div class="col-sm-9">
                                            <select class="selectpicker show-tick form-control <?php if (Input::exists() && empty(Input::post('leads'))) {echo 'is-invalid';} else { echo 'mb-3';}?>" data-live-search="true" name="leads" data-size="10">
                                                <option value=""><?php echo Translate::t('Select_leader'); ?></option>
                                                <?php
                                                foreach ($backendUserProfile->leadData() as $lead) { ?>
                                                    <option value="<?php echo $lead->id; ?>"><?php echo $lead->name; ?><small> (<?php echo $backendUserProfile->leadDepartName($lead->id) . ' - ' . $backendUserProfile->leadOfficeName($lead->id) ;?>)</small></option>
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
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t(['select', 'city'], ['ucfirst']); ?></label>
                                        <div class="col-sm-9">
                                            <select class="selectpicker show-tick form-control <?php if (Input::exists() && empty(Input::post('leads'))) {echo 'is-invalid';} else { echo 'mb-3';}?>" data-live-search="true" name="city" data-size="10">
                                                <option value=""><?php echo Translate::t(['select', 'city'], ['ucfirst']); ?></option>
                                                <?php
                                                foreach ($backendUserProfile->getCity() as $city) { ?>
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
                                            <select name="departments" class="selectpicker show-tick form-control <?php if (Input::exists() && empty(Input::post('departments'))) {echo 'is-invalid';} else { echo 'mb-3';}?>" data-live-search="true" data-size="10">
                                                <option value=""><?php echo Translate::t('Select_depart'); ?></option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('departments'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"><?php echo Translate::t('New_office'); ?></label>
                                        <div class="col-sm-9">
                                            <select name="offices" class="selectpicker show-tick form-control <?php if (Input::exists() && empty(Input::post('offices'))) {echo 'is-invalid';} else { echo 'mb-3';}?>"  data-live-search="false" data-size="10">
                                                <option value=""><?php echo Translate::t('Select_office'); ?></option>
                                            </select>
                                            <?php
                                            if (Input::exists() && empty(Input::post('offices'))) { ?>
                                                <div class="invalid-feedback"><?php echo Translate::t('This_field_required'); ?></div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label"></label>
                                        <div class="col-sm-9">
                                            <button id="Submit" value="<?php echo Translate::t('Submit'); ?>" class="btn-sm btn-outline-primary" type="submit"><?php echo Translate::t('Submit'); ?></button>
                                            <input type="hidden" name="<?php echo Tokens::getInputName(); ?>" value="<?php echo Tokens::getSubmitToken(); ?>">
                                        </div>
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
include 'includes/js/ajax_update_lead.php';
?>
<script>
    $('#Submit').click(function(){
        $('#myModal').modal('show');
    });
</script>
</body>
</html>