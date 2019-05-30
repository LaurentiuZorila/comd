<?php
require_once 'core/init.php';
?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
    <link rel="stylesheet" href="./../common/css/spiner/style.css">
    <link rel="stylesheet" href="../common/vendor/dataTables/dataTables.bootstrap4.min.css">
    <script src="../common/vendor/dataTables/datatables.min.js"></script>
    <script src="../common/vendor/dataTables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#employeesTable').DataTable();
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
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
        <!--        *********    DELETE EVENT MODAL START ********* -->
        <div id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><h3 id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t('delete_confirmation', ['ucfirst']); ?></h3>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body text-white-50" id="modalBody">
                        <span class="text-white-50"><?php echo Translate::t('Name', ['ucfirst']); ?>: </span> <span class="text-white" id="userName"></span>
                        <br />
                        <span class="text-white-50"><?php echo Translate::t('Depart',['ucfirst']); ?>:</span> <span class="text-white" id="department"></span>
                        <br />
                        <span class="text-white-50"><?php echo Translate::t('Offices',['ucfirst']); ?>:</span> <span class="text-white" id="offices"></span>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-sm btn-outline-secondary" data-dismiss="modal" aria-hidden="true"><?php echo Translate::t('close', ['ucfirst']); ?></button>
                        <button type="submit" class="btn-sm btn-primary deleteOk" id="deleteOk"><?php echo Translate::t('delete', ['ucfirst']); ?></button>
                        <input type="hidden" id="employeeId" value="" />
                        <input type="hidden" id="employeeName" value="" />
                        <input type="hidden" id="leadofficeid" value="" />
                    </div>
                </div>
            </div>
        </div>
        <!--        *********    DELETE EVENT MODAL END ********* -->
        <!-- Page Header-->
        <div class="page-header no-margin-bottom">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Tables'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('All_employees'); ?></li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t('All_employees'); ?></strong></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="employeesTable">
                                    <thead>
                                    <tr>
                                        <th class="text-primary">#</th>
                                        <th class="text-primary"><?php echo Translate::t('Name'); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Team'); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Depart'); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Status', ['ucfirst']); ?></th>
                                        <th class="text-primary text-center"><?php echo Translate::t('Action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($backendUserProfile->getEmployeesData(['order','name'],[],['status'], true) as $employees) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><?php echo $employees->name; ?></td>
                                            <td><?php echo $backendUserProfile->getEmployeeOfficeData($employees->offices_id, ['name'])->name; ?></td>
                                            <td><?php echo $backendUserProfile->getEmployeeDepartmentData($employees->departments_id, ['name'])->name;?></td>
                                            <td>
                                                <?php
                                                $statusId = $backendUserProfile->getEmployeesData([], AC::where(['id', $employees->id]), ['status', true])->status;
                                                echo Translate::t($backendUserProfile->getStatus()[$statusId], ['ucfirst']);
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-inline no-margin-bottom">
                                                    <a href="<?php echo Config::get('route/emplData'); ?>?employees_id=<?php echo $employees->id; ?>" class="btn-sm btn-bd-download"><i class="icon-chart" data-toggle="tooltip" data-placement="top" title="<?php echo Translate::t('view_data',['ucfirst']) ;?>"></i></a>
                                                    <a href="<?php echo Config::get('route/updateUserProfile'); ?>?employees_id=<?php echo $employees->id; ?>" class="btn-sm  btn-bd-download"><i class="icon-user-outline text-info mr-1" data-toggle="tooltip" data-placement="top" title="<?php echo Translate::t('edit',['ucfirst']) ;?>"></i></a>
                                                    <a href="#" class="btn-sm btn-bd-download deleteEmployee" data-offices="<?php echo strtoupper($backendUserProfile->records(Params::TBL_OFFICE, ['id', '=', $employees->offices_id], ['name'], false)->name); ?>" data-department="<?php echo strtoupper($backendUserProfile->records(Params::TBL_DEPARTMENT, ['id', '=', $employees->departments_id], ['name'], false)->name );?>" data-employeename="<?php echo $employees->name;?>" data-employeeid="<?php echo $employees->id;?>" data-leadofficeid="<?php echo $employees->offices_id; ?>"><i class="fa fa-trash-o text-danger" data-toggle="tooltip" data-placement="top" title="<?php echo Translate::t('delete',['ucfirst']) ;?>"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
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
include "./includes/js/deleteEmployee.php";
?>
</body>
</html>