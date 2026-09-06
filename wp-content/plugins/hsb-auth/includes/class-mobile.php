<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Mobile {


    public static function normalize($mobile) {


        $mobile = trim($mobile);


        $mobile = str_replace(
            [' ', '-', '(', ')'],
            '',
            $mobile
        );


        if (strpos($mobile, '+98') === 0) {

            $mobile = '0' . substr($mobile, 3);

        }


        if (strpos($mobile, '0098') === 0) {

            $mobile = '0' . substr($mobile, 4);

        }


        if (strpos($mobile, '98') === 0) {

            $mobile = '0' . substr($mobile, 2);

        }


        return $mobile;

    }


}
