<?php
require_once 'core/init.php';

// All tables, employees id
$allTables   = $leadData->records(Params::TBL_OFFICE, ['id', '=', $lead->officesId()], ['tables'], false);
$employeesID = $leadData->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $lead->officesId()], ['id']);
$allTables   = explode(',', trim($allTables->tables));
// Trim values
$allTables   = array_map('trim', $allTables);

/** Foreach to get employees id */
foreach ($employeesID as $ids) {
    //  Employees ids
    $employeesId[] = $ids->id;
}

// Conditions for action
$year   = date('Y');
$month  = 1;
$prefix = Params::PREFIX;

    $where = [
        ['year', '=', $year],
        'AND',
        ['offices_id', '=', $lead->officesId()],
        'AND',
        ['month', '=', $month]
    ];

    /** Tables with prefix and without prefixTbl => tbl */
    foreach ($allTables as $value) {
        $tables[$prefix . trim($value)] = trim($value);
    }
    // Transform to upper case values of array
    $tables = array_map('strtoupper', $tables);
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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t($lang, 'Table'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t($lang, 'Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo Translate::t($lang, 'Data_for') . Common::getMonths($lang)[$month]; ?></li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block margin-bottom-sm">
                            <div class="title text-center"><strong class="text-primary"><?php echo Translate::t($lang, 'Data_for') . ' ' . Translate::t($lang, 'All_employees', ['strtolower' => true]); ?></strong></div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr role="row">
                                            <th class="text-primary"><?php echo Translate::t($lang, 'Name'); ?></th>
                                            <?php foreach ($tables as $upperTable) { ?>
                                            <th class="text-primary"> <?php echo $upperTable; ?> </th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach ($employeesId as $id) { ?>
                                            <tr role="row" class="odd">
                                                <td class="">
                                                    <a href="user_data.php?id=<?php echo $id;?>" class="text-white-50"><?php echo $leadData->records(Params::TBL_EMPLOYEES, ['id', '=', $id], ['name'], false)->name; ?></a>
                                                </td>
                                                <?php foreach ($tables as $k => $v) { ?>
                                                <td class="text-white-50">
                                                    <?php echo $leadData->records($k, ['employees_id', '=', $id,], ['quantity'], false)->quantity; ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                     <?php } ?>
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