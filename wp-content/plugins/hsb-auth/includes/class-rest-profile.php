<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_REST_Profile {


    public function __construct() {

        add_action(
            'rest_api_init',
            [$this, 'register_routes']
        );

    }



    public function register_routes() {


        register_rest_route(
            'hsb-auth/v1',
            '/profile',
            [
                'methods'  => 'GET',
                'callback' => [$this, 'get_profile'],
                'permission_callback' => function () {
                    return is_user_logged_in();
                },
            ]
        );


        register_rest_route(
            'hsb-auth/v1',
            '/profile',
            [
                'methods'  => 'POST',
                'callback' => [$this, 'update_profile'],
                'permission_callback' => function () {
                    return is_user_logged_in();
                },
            ]
        );


    }



    public function get_profile() {


        return HSB_Auth_API::get_profile();

    }



    public function update_profile($request) {


        $data = $request->get_json_params();


        if (empty($data)) {

            $data = $request->get_params();

        }


        return [
            'success' => HSB_Auth_API::update_profile(
                $data
            )
        ];

    }


}
