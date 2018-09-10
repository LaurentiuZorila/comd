<?php
spl_autoload_register(function($class) {
    require_once '../classes/'. $class . '.php';
});


$user_id = $_GET['id'];

$allTables = DB::getInstance()->get('cmd_offices', ['id', '=', $user_id])->results();

foreach (Values::tables($allTables) as $tables) {
    $table[] = trim(strtoupper($tables));
    $table = array_combine($table, $table);
}

echo json_encode($table);