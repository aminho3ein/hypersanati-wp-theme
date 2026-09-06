<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Verify_Rate_Limit {


    public static function can_attempt($mobile) {


        $key = 'hsb_verify_' . md5($mobile);


        $attempts = get_transient($key);


        if ($attempts === false) {

            set_transient(
                $key,
                1,
                300
            );

            return true;

        }


        if ($attempts >= 5) {

            return false;

        }


        set_transient(
            $key,
            $attempts + 1,
            300
        );


        return true;

    }

}
