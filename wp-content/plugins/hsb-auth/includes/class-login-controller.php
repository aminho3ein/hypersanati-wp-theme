<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Login_Controller {


    public static function request_otp($mobile) {


        if (
            !HSB_Auth_Config::get(
                'mobile_verification_enabled',
                false
            )
        ) {
            return [
                'status'  => 'disabled',
                'message' => 'Mobile verification is disabled',
            ];
        }


        $mobile = HSB_Validator::mobile($mobile);


        if (!$mobile) {
            return false;
        }


        if (!HSB_OTP_Rate_Limit::can_request($mobile)) {

            return [
                'status'  => 'error',
                'message' => 'OTP request too frequent',
            ];

        }


        $provider = HSB_SMS_Factory::create();


        $otp = new HSB_OTP(
            $provider
        );


        $service = new HSB_OTP_Service(
            $otp
        );


        return $service->request(
            $mobile
        );

    }



    public static function verify_otp($mobile, $code) {


        if (
            !HSB_Auth_Config::get(
                'mobile_verification_enabled',
                false
            )
        ) {
            return false;
        }


        $mobile = HSB_Validator::mobile($mobile);


        if (!$mobile) {
            return false;
        }


        if (!HSB_Verify_Rate_Limit::can_attempt($mobile)) {

            return false;

        }


        $provider = HSB_SMS_Factory::create();


        $otp = new HSB_OTP(
            $provider
        );


        $service = new HSB_OTP_Service(
            $otp
        );


        if (
            !$service->verify(
                $mobile,
                $code
            )
        ) {

            return false;

        }


        $auth = new HSB_Auth();


        return $auth->login_with_mobile(
            $mobile
        );

    }



    public static function verify_otp_code($mobile, $code) {

        $mobile = HSB_Validator::mobile($mobile);

        if (!$mobile) {
            return false;
        }

        if (!HSB_Verify_Rate_Limit::can_attempt($mobile)) {
            return false;
        }

        $provider = HSB_SMS_Factory::create();

        $otp = new HSB_OTP(
            $provider
        );

        $service = new HSB_OTP_Service(
            $otp
        );

        return $service->verify(
            $mobile,
            $code
        );
    }

}
