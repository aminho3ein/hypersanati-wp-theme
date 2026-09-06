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

        $code = wp_rand(100000, 999999);

        HSB_OTP_Storage::save(
            $mobile,
            $code
        );

        return $this->provider->send(
            $mobile,
            "Your verification code is: " . $code
        );

    }


    public function verify_code($mobile, $code) {

        $stored = HSB_OTP_Storage::get($mobile);

        if (!$stored) {
            return false;
        }


        if ((string) $stored !== (string) $code) {
            return false;
        }


        HSB_OTP_Storage::delete($mobile);

        return true;

    }

}
