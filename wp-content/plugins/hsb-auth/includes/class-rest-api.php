<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_REST_API {


    public function __construct() {

        add_action(
            'rest_api_init',
            [$this, 'register_routes']
        );

    }



    public function register_routes() {


        register_rest_route(
            'hsb-auth/v1',
            '/me',
            [
                'methods'  => 'GET',
                'callback' => [$this, 'me'],
                'permission_callback' => function () {
                    return is_user_logged_in();
                },
            ]
        );


        register_rest_route(
            'hsb-auth/v1',
            '/check-mobile',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'check_mobile'],
                'permission_callback' => '__return_true',
            ]
        );


        register_rest_route(
            'hsb-auth/v1',
            '/request-otp',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'request_otp'],
                'permission_callback' => '__return_true',
            ]
        );






        register_rest_route(
            'hsb-auth/v1',
            '/login',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'login'],
                'permission_callback' => '__return_true',
            ]
        );


        register_rest_route(
            'hsb-auth/v1',
            '/register',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'register'],
                'permission_callback' => '__return_true',
            ]
        );


        register_rest_route(
            'hsb-auth/v1',
            '/verify-otp',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'verify_otp'],
                'permission_callback' => '__return_true',
            ]
        );


    }





    public function check_mobile($request) {

        $mobile = $request->get_param('mobile');

        $exists = HSB_User_Profile::find_by_mobile($mobile);


        return [
            'exists' => (bool) $exists,
        ];

    }

    public function request_otp($request) {


        $mobile = $request->get_param('mobile');


        return HSB_Login_Controller::request_otp(
            $mobile
        );

    }



    public function verify_otp($request) {


        $mobile = $request->get_param('mobile');

        $code = $request->get_param('code');


        $user_id = HSB_Login_Controller::verify_otp(
            $mobile,
            $code
        );


        if (!$user_id) {

            return [
                'status' => 'error',
                'message' => 'Invalid OTP',
            ];

        }


        return [
            'status'  => 'success',
            'user_id' => $user_id,
        ];

    }





    public function login($request) {

        $username = $request->get_param('username');
        $password = $request->get_param('password');


        $user_id = HSB_Password_Login_Controller::login(
            $username,
            $password
        );


        if (is_wp_error($user_id)) {

            return [
                'status'  => 'error',
                'message' => $user_id->get_error_message(),
            ];

        }


        if (!$user_id) {
            return [
                'status' => 'error',
                'message' => 'Login failed',
            ];
        }


        return [
            'status'  => 'success',
            'user_id' => $user_id,
        ];

    }


    public function register($request) {

        $data = $request->get_params();

        $user_id = HSB_Register_Controller::register(
            $data
        );

        if (is_wp_error($user_id)) {

            return [
                'status'  => 'error',
                'message' => $user_id->get_error_message(),
            ];

        }


        if (!$user_id) {
            return [
                'status' => 'error',
                'message' => 'Registration failed',
            ];
        }

        return [
            'status'  => 'success',
            'user_id' => $user_id,
        ];

    }


    public function me() {


        return [
            'user_id' => get_current_user_id(),
            'mobile'  => HSB_Auth_API::get_mobile(),
            'profile' => HSB_Auth_API::get_profile(),
        ];

    }


}
