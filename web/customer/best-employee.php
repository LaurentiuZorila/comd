<?php
require_once 'core/init.php';
if (Input::existsName('get', 'show')) {
    $best = new BestEmployee($lead->officesId());
} else {
    $tablesData = $leadData->records(Params::TBL_OFFICE, AC::where(['id', $lead->officesId()]), ['tables_conditions', 'tables_priorities', 'data_visualisation'], false);
    $tables_conditions    = Common::toArray($tablesData->tables_conditions);
    $tables_priorities    = Common::toArray($tablesData->tables_priorities);
    ksort($tables_priorities);
    $tables_priorities    = array_flip($tables_priorities);
    $tables_visualisation = Common::toArray($tablesData->data_visualisation);

/** Remove common tables */
    foreach (Params::TBL_COMMON as $commonTables) {
        unset($tables_visualisation[$commonTables]);
    }

    $tablesPriorityMinValue = min($tables_priorities);
// merge all arrays
    $dataTables = array_merge_recursive($tables_priorities, $tables_visualisation, $tables_conditions);
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    include '../common/includes/head.php';
    ?>
    <link rel="stylesheet" href="./../common/css/spiner/style.css">
    <script src="./../common/vendor/chart.js/Chart.min.js"></script>
    <script src="./../common/vendor/chart.js/Chart.bundle.min.js"></script>
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
    <section class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"><?php echo Translate::t('Dashboard'); ?></h2>
            </div>
        </div>
        <?php
        if (Input::exists() && Errors::countAllErrors()) {
            include './../common/errors/errors.php';
        }
        ?>
        <?php if (!Input::existsName('get', 'show')) { ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-2 pb-2">
                                <ul class="nav nav-pills card-header-pills float-right">
                                    <li class="nav-item"><a href="?show=<?php echo 'yes'; ?>" class="nav-link active showGrpah"><?php echo Translate::t('show_graph', ['ucfirst']); ?> <i class="fa fa-line-chart"></i></a></li>
                                </ul>
                            </div>
                            <blockquote class="blockquote mb-0 card-body">
                                <p><?php echo Translate::t('best_details', ['ucfirst']); ?></p>
                                <footer class="blockquote-footer">
                                    <small class="text-muted"><?php echo Translate::t('best_details_second', ['ucfirst']); ?></small>
                                </footer>
                                <footer class="blockquote-footer">
                                    <small class="text-muted"><?php echo Translate::t('best_details_tree', ['ucfirst']); ?></small>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="block margin-bottom-sm">
                            <div class="title">
                                <strong><?php echo Translate::t('your_configuration'); ?></strong>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><?php echo Translate::t('Tables', ['ucfirst']); ?></th>
                                        <th><?php echo Translate::t('tables_priority', ['ucfirst']); ?></th>
                                        <th><?php echo Translate::t('tables_data_visualisation', ['ucfirst']); ?></th>
                                        <th><?php echo Translate::t('tables_conditions', ['ucfirst']); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dataTables as $key => $values) { ?>
                                            <tr>
                                                <th class="text-small"><?php echo $key; ?></th>
                                                <th class="text-small"><?php echo $values['0']; ?> <?php echo $values['0'] == $tablesPriorityMinValue ? ' (' . Translate::t('priority_table_details',['strtolower']) . ')' : '' ;?></th>
                                                <th class="text-small"><?php echo $values['1']; ?></th>
                                                <th class="text-small"><?php echo $values['2']; ?> <?php echo $values['2'] == '<' ? ' (' . Translate::t('condition_lower',['strtolower']) . ')' : ' (' . Translate::t('condition_highest',['strtolower']) . ')' ;?></th>
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
        <?php }
        if (Input::existsName('get', 'show') && Input::get('show') == 'yes') {
        ?>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="public-user-block block m-0 p-0">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-lg-6 d-flex align-items-center text-center">
                                            <div class="order">1th</div>
                                            <div class="avatar"> <img src="<?php echo Config::get('files/avatar_dir'). 'user.png';?>" alt="..." class="img-fluid"></div>
                                            <a href="<?php echo Config::get('route/uData') .'?id=' . $best->bestEmployeeData(['id']); ?>" class="name"><strong class="d-block"><?php echo $best->bestEmployeeData(['name']); ?></strong></a>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-center text-center">
                                            <div class="contributions"><?php echo Translate::t('Best_operator', ['strtoupper']); ?></div>
                                        </div>
                                        <div class="col-lg-3 d-flex align-items-center text-center">
                                            <div class="contributions"><?php echo Translate::t('year', ['strtoupper']) . ' - ' . date('Y'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="">
            <div class="container-fluid">
                <div class="row d-flex align-items-stretch">
                    <div class="col-lg-12">
                        <div class="stats-3-block block d-flex">
                            <div class="stats-3" style="width: 40%;"><strong class="d-block"><?php echo $best->bestEmployeeData(['displayData']); ?></strong><span class="d-block"><?php echo Translate::t($best->getFirstTable(), ['strtoupper']); ?></span>
                                <div class="progress progress-template progress-small mw-100">
                                    <div role="progressbar" style="width: <?php echo $best->bestEmployeeData(['average']); ?>%;" aria-valuenow="<?php echo $best->bestEmployeeData(['average']); ?>" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template progress-bar-small dashbg-1"></div>
                                </div>
                            </div>
                            <div class="stats-3 d-flex justify-content-between text-center" style="width: 60%;">
                                <?php foreach ($best->bestEmployeeCommonData() as $employeeData) { ?>
                                 <div class="item"><strong class="d-block strong-sm <?php echo Params::COMMONTBLSDASHTEXT[$employeeData['table']]; ?>"><?php echo $employeeData['sum']; ?></strong><span class="d-block span-sm"><?php echo Translate::t($employeeData['table'], ['ucfirst']);?></span>
                                    <div class="line"></div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<!--        BEST CHART-->
        <section class="margin-bottom-sm">
            <div class="container-fluid">
                <div class="row d-flex align-items-stretch">
                    <div class="col-lg-12">
                        <div class="stats-with-chart-1 block">
                            <div class="title"> <strong class="d-block"><?php echo Translate::t('year', ['strtoupper']) . ' - ' . date('Y'); ?></strong></div>
                            <div class="row d-flex align-items-end justify-content-between">
                                <div class="col-12">
                                    <div class="bar-chart chart">
                                        <canvas id="bestFirst" style="display: block; width: 194px; height: 97px;" width="194" height="97" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php } ?>
<!--        BEST CHART END-->
        <?php
        include '../common/includes/footer.php';
        ?>
    </div>
</div>
<?php
include "./includes/js/markAsRead.php";
?>
<script>
    $('.showGrpah').click(function(){
        $('#myModal').modal('show');
    });
</script>
<?php
/** BEST CHART and Form Chart */
include 'charts/bestChart.php';
?>
</body>
</html>
