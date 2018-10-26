<?php
switch ($backendUser->language()) {
    case 'ro':
        include './../common/lang/rom.php';
        break;
    case 'en':
        include './../common/lang/en.php';
        break;
    case 'it':
        include './../common/lang/it.php';
}
?>