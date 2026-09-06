<?php

if (!defined('ABSPATH')) {
    exit;
}

final class HSB_SMS_Kavenegar implements HSB_SMS_Provider {


    public function send(
        $mobile,
        $message
    ) {

        unset(
            $mobile,
            $message
        );


        $api_key =
            HSB_Auth_Config::get(
                'kavenegar_api_key'
            );


        $sender =
            HSB_Auth_Config::get(
                'kavenegar_sender'
            );


        if (
            !$api_key ||
            !$sender
        ) {

            return [
                'status'  => 'error',
                'message' =>
                    'Kavenegar configuration missing',
            ];
        }


        /*
         * Real Kavenegar delivery has not been implemented yet.
         *
         * Never report a successful or ready SMS delivery until
         * the provider has actually accepted the message.
         */
        return [
            'status'  => 'error',
            'message' =>
                'Kavenegar delivery is not implemented yet',
        ];
    }
}
