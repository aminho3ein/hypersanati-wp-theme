<?php

if (!defined('ABSPATH')) {
    exit;
}

final class HSB_SMS_Factory {


    public static function create() {

        $provider =
            strtolower(
                trim(
                    (string)
                    HSB_Auth_Config::get(
                        'sms_provider',
                        'disabled'
                    )
                )
            );


        if ('kavenegar' === $provider) {

            if (
                HSB_Auth_Config::get(
                    'kavenegar_api_key'
                ) &&
                HSB_Auth_Config::get(
                    'kavenegar_sender'
                )
            ) {

                return new HSB_SMS_Kavenegar();
            }


            return new HSB_SMS_Disabled_Provider(
                'Kavenegar configuration is incomplete'
            );
        }


        if ('test' === $provider) {

            if (
                self::test_provider_allowed()
            ) {

                return new HSB_SMS_Test_Provider();
            }


            return new HSB_SMS_Disabled_Provider(
                'Test SMS provider is not allowed in this environment'
            );
        }


        return new HSB_SMS_Disabled_Provider(
            'SMS provider is disabled'
        );
    }



    public static function test_provider_allowed() {

        if (
            !HSB_Auth_Config::get(
                'allow_test_sms',
                false
            )
        ) {
            return false;
        }


        $environment =
            function_exists(
                'wp_get_environment_type'
            )
                ? wp_get_environment_type()
                : 'production';


        return
            'production' !==
            strtolower(
                (string) $environment
            );
    }
}
