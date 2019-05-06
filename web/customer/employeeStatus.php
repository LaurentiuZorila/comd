<?php
require_once 'core/init.php';
// All employees ids
$employeesID = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $lead->officesId()]), ['id']);
// Statuses
$stats = $leadDb->get(Params::TBL_STATS,['id', 'status'])->results();
foreach ($stats as $stat) {
    $allStats[$stat->id] = $stat->status;
}
/** Foreach to get employees id */
foreach ($employeesID as $ids) {
    //  Employees ids
    $employeesId[] = $ids->id;
}

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
    <script src="./../common/vendor/chart.js/Chart.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#employeesTable').DataTable();
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</head>
<script>
    function displayMessage(type, message) {
        if(type === "success") {
            $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-success"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
            setInterval(function() { $(".eventMessage").fadeOut(); }, 4000);
        } else if (type === "danger") {
            $(".response").html('<section class="eventMessage"><div class="row"><div class="col-lg-12"><div class="alert alert-dismissible fade show badge-danger"><p class="text-white mb-0">'+message+'</p></div></div></div></section>');
            setInterval(function() { $(".eventMessage").fadeOut(); }, 4000);
        }
    }
</script>
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Table'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t('Employees', ['ucfirst']); ?></li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="response"></div>
            <div class="container-fluid">
                <div class="row">
                    <!--        *********    DELETE EVENT MODAL START ********* -->
                    <div id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade">
                        <div role="document" class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header"><h3 id="exampleModalLabel" class="modal-title dashtext-3"><?php echo Translate::t('delete_confirmation', ['ucfirst']); ?></h3>
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body text-white-50" id="modalBody">
                                    <h4 id="title"></h4>
                                    Employee name: <span id="userName"></span><br>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn-sm btn-outline-secondary" data-dismiss="modal" aria-hidden="true"><?php echo Translate::t('close', ['ucfirst']); ?></button>
                                    <button type="submit" class="btn-sm btn-primary deleteOk" id="deleteOk"><?php echo Translate::t('delete', ['ucfirst']); ?></button>
                                    <input type="hidden" id="employeeId" value="" />
                                    <input type="hidden" id="leadofficeid" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--        *********    DELETE EVENT MODAL END ********* -->
                    <div class="col-lg-12">
                        <div class="block margin-bottom-sm">
                            <div class="table-responsive">
                                <table class="table" id="employeesTable">
                                    <thead>
                                    <tr role="row">
                                        <th class="text-primary"><?php echo Translate::t('Name', ['ucfirst']); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Username', ['ucfirst']); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Depart', ['ucfirst']); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Offices', ['ucfirst']); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Status', ['ucfirst']); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Change_status', ['ucfirst']); ?></th>
                                        <th class="text-primary"><?php echo Translate::t('Action', ['ucfirst']); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count($employeesId) > 0) {
                                        foreach ($employeesId as $id) { ?>
                                            <tr role="row" class="odd">
                                                <td class="">
                                                    <?php echo $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['name'], false)->name; ?>
                                                </td>
                                                <td>
                                                    <?php echo $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['username'], false)->username; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $departId = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['departments_id'], false)->departments_id;
                                                    $departName = $leadData->records(Params::TBL_DEPARTMENT, AC::where(['id', $departId]),['name'], false)->name;
                                                    echo strtoupper($departName);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $officeId = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['offices_id'], false)->offices_id;
                                                    $officeName = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $officeId]),['name'], false)->name;
                                                    echo strtolower($officeName);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $statusId = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['status'], false)->status;
                                                    echo Translate::t($allStats[$statusId], ['ucfirst']);
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group-sm dropright" role="group" aria-label="">
                                                        <a id="btnGroupDrop1" type="button" class="btn-sm btn-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="<?php echo Translate::t('Change_status',['ucfirst']) ;?>"></i></a>
                                                        <div class="dropdown-menu employeeStats" aria-labelledby="btnGroupDrop1">
                                                            <?php
                                                            foreach ($allStats as $ids => $stats) { ?>
                                                                <a class="dropdown-item employeeStats" style="cursor: pointer;" data-employeeId="<?php echo $id; ?>" data-stats="<?php echo $ids;?>"><?php echo Translate::t($stats, ['ucfirst']); ?></a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <td>
                                                    <div class="btn-group-sm" role="group" aria-label="">
                                                        <a href="<?php echo Config::get('route/updateUProf');?>" class="btn-sm btn-primary"><i class="fa fa-user-plus" data-toggle="tooltip" data-placement="top" title="<?php echo Translate::t('Edit', ['ucfirst']);?>"></i></a>
                                                        <a type="button" data-employeename="<?php echo $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $id]), ['name'], false)->name;?>" data-employeeid="<?php echo $id;?>" data-leadofficeid="<?php echo $lead->officesId(); ?>" class="btn-sm btn-danger deleteEmployee" style="cursor: pointer;"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="<?php echo Translate::t('Delete', ['ucfirst']);?>"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }
                                    }?>
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
include "./includes/js/employeeEdit.php";
include "./includes/js/markAsRead.php";
?>
<script>
    $('.deleteOk').click(function(){
        $('#myModal').modal('show');
    });
    $('.employeeStats').click(function () {
       $('#myModal').modal('show');
    });
    $(".deleteEmployee").click(function(){
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
</script>
</body>
</html>