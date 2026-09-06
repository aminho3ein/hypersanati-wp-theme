<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Auth_Config {


    public static function get($key, $default = null) {

        $configs = [

            'sms_provider' => defined('HSB_SMS_PROVIDER')
                ? HSB_SMS_PROVIDER
                : $default,

            'kavenegar_api_key' => defined('KAVENEGAR_API_KEY')
                ? KAVENEGAR_API_KEY
                : (
                    defined('HSB_KAVENEGAR_API_KEY')
                        ? HSB_KAVENEGAR_API_KEY
                        : $default
                ),

            'kavenegar_sender' => defined('HSB_KAVENEGAR_SENDER')
                ? HSB_KAVENEGAR_SENDER
                : $default,

            /*
             * Mobile OTP verification is intentionally OFF
             * until a real SMS provider is configured.
             *
             * Enable later in wp-config.php:
             *
             * define(
             *     'HSB_MOBILE_VERIFICATION_ENABLED',
             *     true
             * );
             */
            'mobile_verification_enabled' =>
                defined('HSB_MOBILE_VERIFICATION_ENABLED')
                    ? (bool) HSB_MOBILE_VERIFICATION_ENABLED
                    : false,

            /*
             * Test SMS requires an explicit opt-in and is
             * never allowed in the production environment.
             */
            'allow_test_sms' =>
                defined('HSB_ALLOW_TEST_SMS')
                    ? (bool) HSB_ALLOW_TEST_SMS
                    : false,

        ];


        return isset($configs[$key])
            ? $configs[$key]
            : $default;

    }

}
