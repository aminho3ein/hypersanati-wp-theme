<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_OTP_Service {


    private $otp;


    public function __construct(HSB_OTP $otp) {

        $this->otp = $otp;

    }


    public function request($mobile) {

        $mobile = HSB_Validator::mobile($mobile);


        if (!$mobile) {
            return false;
        }


        return $this->otp->send_code($mobile);

    }


    public function verify($mobile, $code) {

        $mobile = HSB_Validator::mobile($mobile);


        if (!$mobile) {
            return false;
        }


        return $this->otp->verify_code(
            $mobile,
            $code
        );

    }

}
