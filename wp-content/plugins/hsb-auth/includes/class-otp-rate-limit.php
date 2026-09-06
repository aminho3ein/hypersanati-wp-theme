<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_OTP_Rate_Limit {


    public static function can_request($mobile) {


        $key = 'hsb_otp_limit_' . md5($mobile);


        if (get_transient($key)) {

            return false;

        }


        set_transient(
            $key,
            1,
            60
        );


        return true;

    }

}
