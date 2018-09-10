<?php
require_once 'core/init.php';
$user = new User();
// All users
$users = DB::getInstance()->get('users', array('user_id', '=', $user->userId()))->results();
// All tables
$allTables = DB::getInstance()->get('department', array('user_id', '=', $user->userId()))->results();
// Conditions for action
$year = date('Y');
$month = 1;

$where = [
    ['year', '=', $year],
    'AND',
    ['user_id', '=', $user->userId()],
    'AND',
    ['month', '=', $month]
];

foreach (Values::tables($allTables) as $value) {
    $tables[] = trim($value);
}


foreach ($tables as $table) {
    $allRecords[$table] = DB::getInstance()->get($table, $where, ['name', 'customer_id', 'quantity'])->results();
    $quantity[$table] = DB::getInstance()->get($table, $where, ['quantity'])->results();
}

?>

<!DOCTYPE html>
<html>
<?php
include 'includes/head.php';
?>
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
                <h2 class="h5 no-margin-bottom">Tables</h2>
            </div>
        </div>
        <!-- Breadcrumb-->
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">All users</li>
                <li class="breadcrumb-item active"><?php echo 'Data for month: '.Profile::getMonthsList()[$month]; ?></li>
            </ul>
        </div>
        <section class="no-padding-top">
            <div class="container-fluid">
                <div class="row">
                    <?php
                    foreach ($allRecords as $key => $records) { ?>
                    <div class="col-sm-3">
                        <div class="block margin-bottom-sm">
                            <div class="title"><strong><?php echo ucfirst($key); ?></strong></div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable1" rowspan="1" colspan="1"
                                                    aria-sort="ascending"
                                                    aria-label="">Name
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable1" rowspan="1" colspan="1"
                                                    style="width: 50px;" aria-label="">Data
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($records as $record) { ?>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1"><a
                                                            href="user_data.php?customer_id=<?php echo $record->customer_id; ?>"
                                                            class="text-muted"><?php echo $record->name; ?></a></td>
                                                <td><?php echo $record->quantity; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                             </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include 'includes/footer.php';
        ?>
    </div>
</div>
<!-- JavaScript files-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper.js/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="js/front.js"></script>

</body>
</html>