<?php
spl_autoload_register(function($class) {
    require_once '../classes/'. $class . '.php';
});

$user_id = $_GET['id'];
$prefix = 'cmd_';


$allTables = DB::getInstance()->get('cmd_offices', ['id', '=', $user_id])->results();

foreach (Values::tables($allTables) as $table) {
    $tables[] = $prefix . trim(strtolower($table));
}

foreach ($tables as $table) {
    $data[] = DB::getInstance()->get($table, $where = ['1 = 1'], ['year'])->results();
}

foreach ($data as $values) {
    foreach ($values as $value) {
        $year[] = $value->year;
    }
}

echo json_encode(array_unique($year));