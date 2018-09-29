<?php

/**
 * Class Redirect
 */
class Redirect {

    /**
     * @param null $location
     */
    public static function to($location = null) {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                        include 'html/404.php';
                        exit();
                        break;
                }
            }
            header('Location: '.$location);
            exit();
        }
    }
}
