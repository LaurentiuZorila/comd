<?php
require_once 'core/init.php';

// All tables, employees id
$allTables   = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['tables'], false);
$employeesID = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['offices_id', $lead->officesId()]), ['id']);

$allTables   = explode(',', trim($allTables->tables));
// Trim values
$allTables   = array_map('trim', $allTables);

/** Foreach to get employees id */
foreach ($employeesID as $ids) {
    //  Employees ids
    $employeesId[] = $ids->id;
}

/** Data display */
$dataDisplay = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['data_visualisation'], false)->data_visualisation;
$dataDisplay = (array)json_decode($dataDisplay);
foreach ($dataDisplay as $tableData => $v){
    $tblDataDisplay[] = $tableData;
}

// Conditions for action
$year   = date('Y');
$month  = date('n');
$prefix = Params::PREFIX;

    $where = AC::where([
        ['year', $year],
        ['offices_id', $lead->officesId()],
        ['month', $month]
    ]);

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
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Table'); ?></h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><?php echo Translate::t('Home'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo empty(Input::existsName('get', 'month')) ? Translate::t('Data') . ' ' . Common::getMonths($lang)[$month] : Translate::t('Data') . ' ' . Common::getMonths($lang)[Input::get('month')]; ?></li>
            </ul>
        </div>

        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block margin-bottom-sm">
                            <div class="title text-center">
                                <div class="col-sm-3">
                                    <div class="btn-group btn-block" id="changeMonth">
                                        <button type="button" class="btn btn-primary btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php
                                            if (Input::existsName('get', 'month')) {
                                            echo Common::numberToMonth(Input::get('month'), $lang);
                                            } else {
                                            echo Translate::t('Select_month');
                                            }
                                            ?>
                                        </button>
                                        <div class="dropdown-menu btn-block">
                                            <?php foreach (Common::getMonths($lang) as $key => $value) {
                                                ?>
                                                <a class="dropdown-item changeMonth" href="?month=<?php echo $key; ?>"><?php echo $value; ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <strong class="text-primary">
                                    <?php
                                    if (Input::existsName('get', 'month')) {
                                    echo Translate::t('Data') . ' ' . Translate::t('All_employees', ['strtolower' => true]) . ' - ' . Common::numberToMonth(Input::get('month'), $lang) . ' - ' . date('Y');
                                    } else {
                                    echo Translate::t('Data') . ' ' . Translate::t('All_employees', ['strtolower' => true]) . ' - ' . Common::numberToMonth($month, $lang) . ' - ' . date('Y');
                                    }
                                    ?>
                                </strong>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="employeesTable">
                                    <thead>
                                        <tr role="row">
                                            <th class="text-primary"><?php echo Translate::t('Name', ['strtoupper' => true]); ?></th>
                                            <?php foreach ($tables as $table) { ?>
                                            <th class="text-primary"> <?php echo Translate::t($table, ['strtoupper' => true]); ?> </th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach ($employeesId as $id) { ?>
                                            <tr role="row" class="odd">
                                                <td class="">
                                                    <a href="user_data.php?id=<?php echo $id;?>" class="text-white"><?php echo $leadData->records(Params::TBL_EMPLOYEES, ['id', '=', $id], ['name'], false)->name; ?></a>
                                                </td>
                                                <?php foreach ($tables as $k => $v) { ?>
                                                <td class="text-white-50">
                                                    <?php
                                                    if (Input::exists('get')) {
                                                        if (in_array(strtolower($v), $tblDataDisplay) && $dataDisplay[strtolower($v)] === 'percentage') {
                                                            echo $leadData->records($k, AC::where([['employees_id', $id], ['month',  Input::get('month')], ['year', date('Y')]]), ['quantity'], false)->quantity ?: 0;
                                                            echo '%';
                                                        } else {
                                                            if (in_array(strtolower($v), Params::TBL_COMMON)) {
                                                                echo $leadData->records($k, AC::where([['employees_id', $id], ['month',  Input::get('month')], ['year', date('Y')]]), ['quantity'], false)->quantity ?: 0;
                                                                echo ' <small>' . Translate::t('Days', ['strtolower'=>true]) . '</small>';
                                                            } else {
                                                                echo $leadData->records($k, AC::where([['employees_id', $id], ['month',  Input::get('month')], ['year', date('Y')]]), ['quantity'], false)->quantity ?: 0;
                                                            }
                                                        }
                                                    } else {
                                                        if (in_array(strtolower($v), $tblDataDisplay) && $dataDisplay[strtolower($v)] === 'percentage' ) {
                                                            echo $leadData->records($k, AC::where([['employees_id', $id], ['month', date('n')], ['year', date('Y')]]), ['quantity'], false)->quantity ?: 0;
                                                            echo '%';
                                                        } else {
                                                            if (in_array(strtolower($v), Params::TBL_COMMON)) {
                                                                echo $leadData->records($k, AC::where([['employees_id', $id], ['month', date('n')], ['year', date('Y')]]), ['quantity'], false)->quantity ?: 0;
                                                                echo ' <small>' . Translate::t('Days', ['strtolower'=>true]) . '</small>';
                                                            } else {
                                                                echo $leadData->records($k, AC::where([['employees_id', $id], ['month', date('n')], ['year', date('Y')]]), ['quantity'], false)->quantity ?: 0;
                                                            }
                                                        }
                                                    }
                                                    ?>
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
<script>
    $('.changeMonth').click(function(){
        $('#myModal').modal('show');
    });
</script>

</body>
</html>