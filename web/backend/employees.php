<?php
require_once 'core/init.php';

$allEmployees = $backendUserProfile->records(Params::TBL_EMPLOYEES, ['departments_id', '=', $backendUser->userId()], ['offices_id', 'departments_id', 'name', 'id']);

?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Tables'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'All_users'); ?></li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block">
                            <div class="title"><strong><?php echo Translate::t($lang, 'All_employees'); ?></strong></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo Translate::t($lang, 'Name'); ?></th>
                                        <th><?php echo Translate::t($lang, 'Team'); ?></th>
                                        <th><?php echo Translate::t($lang, 'Depart'); ?></th>
                                        <th><?php echo Translate::t($lang, 'Action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $x = 1;
                                    foreach ($allEmployees as $employees) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $x; ?></th>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employees->id; ?>"><?php echo $employees->name; ?></a></td>
                                            <td><?php echo $backendUserProfile->records(Params::TBL_OFFICE, ['id', '=', $employees->offices_id], ['name'], false)->name; ?></td>
                                            <td><?php echo $backendUserProfile->records(Params::TBL_DEPARTMENT, ['id', '=', $employees->departments_id], ['name'], false)->name ;?></td>
                                            <td><a href="employees_data.php?employees_id=<?php echo $employees->id; ?>"><i class="fa fa-user"></i></a></td>
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
?>

</body>
</html>