<?php

/**
 * Class CustomerRedirect
 */

class CustomerRedirect {

    /**
     * @param null $location
     * @param array $setup
     */
    public static function to($location = null, array $setup = []) {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                        include './../../error/404.php';
                        exit();
                        break;
                }
            }
            // check if header have get option
            if (count($setup) > 0) {
                if (count($setup) < 2) {
                    foreach ($setup as $k => $v) {
                        $header = sprintf('%s=%s', $k, $v);
                    }
                } else {
                    foreach ($setup as $k => $v) {
                        $header[] = sprintf('%s=%s', $k, $v);
                    }
                    $header = implode('&', $header);
                }
                header('Location: '.$location . '?' . $header);
                exit();
            }
            header('Location: '.$location);
            exit();
        }
    }
}