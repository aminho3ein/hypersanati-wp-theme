<?php

if (!defined('ABSPATH')) {
    exit;
}

final class HSB_SMS_Kavenegar implements HSB_SMS_Provider {


    public function send(
        $mobile,
        $code
    ) {


        $api_key =
            HSB_Auth_Config::get(
                'kavenegar_api_key'
            );


        $template =
            defined('HSB_KAVENEGAR_VERIFY_TEMPLATE')
                ? HSB_KAVENEGAR_VERIFY_TEMPLATE
                : '';


        if (
            !$api_key ||
            !$template
        ) {

            return [
                'status'  => 'error',
                'message' => 'Kavenegar configuration missing',
            ];

        }


        $url =
            'https://api.kavenegar.com/v1/'
            . rawurlencode($api_key)
            . '/verify/lookup.json';


        $response =
            wp_remote_post(
                $url,
                [
                    'timeout' => 20,

                    'body' => [
                        'receptor' => $mobile,
                        'token'    => $code,
                        'template' => $template,
                    ],
                ]
            );


        if (
            is_wp_error($response)
        ) {

            return [
                'status'  => 'error',
                'message' => $response->get_error_message(),
            ];

        }


        $body =
            json_decode(
                wp_remote_retrieve_body($response),
                true
            );


        $status =
            isset($body['return']['status'])
                ? (int) $body['return']['status']
                : 0;


        if (
            200 === $status
        ) {

            return [
                'status' => 'success',
            ];

        }


        return [
            'status'  => 'error',
            'message' => $body,
        ];

    }

}
