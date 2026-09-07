<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_OTP_Rate_Limit {


    public static function can_request($mobile) {


        $key = 'hsb_otp_limit_' . substr(
            hash_hmac(
                'sha256',
                $mobile,
                wp_salt('auth')
            ),
            0,
            40
        );


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
