<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Loader {


    public static function load($class) {


        if (strpos($class, 'HSB_') !== 0) {
            return;
        }


        $file = strtolower(
            str_replace(
                '_',
                '-',
                $class
            )
        );


        $path = HSB_AUTH_PATH .
            'includes/class-' .
            $file .
            '.php';


        if (file_exists($path)) {

            require_once $path;

        }

    }


}
