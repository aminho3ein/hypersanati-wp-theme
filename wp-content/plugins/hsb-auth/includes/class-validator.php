<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Validator {


    public static function mobile($mobile) {

        $mobile = HSB_Mobile::normalize($mobile);


        if (!preg_match('/^09[0-9]{9}$/', $mobile)) {

            return false;

        }


        return $mobile;

    }

}
