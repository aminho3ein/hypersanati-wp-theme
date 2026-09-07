<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_OTP {


    private $provider;


    public function __construct(HSB_SMS_Provider $provider) {

        $this->provider = $provider;

    }


    public function send_code($mobile) {

        $code = random_int(100000, 999999);

        HSB_OTP_Storage::save(
            $mobile,
            $code
        );

        return $this->provider->send(
            $mobile,
            $code
        );

    }


    public function verify_code($mobile, $code) {

        $stored = HSB_OTP_Storage::get($mobile);

        if (!$stored) {
            return false;
        }


        if (!wp_check_password((string) $code, $stored)) {
            return false;
        }


        HSB_OTP_Storage::delete($mobile);

        return true;

    }

}
