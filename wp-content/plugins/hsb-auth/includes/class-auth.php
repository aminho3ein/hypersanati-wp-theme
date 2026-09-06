<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Auth {


    public function __construct() {

        add_action(
            'init',
            [$this, 'register']
        );

    }


    public function register() {

        // Authentication hooks will be added here.

    }


    public function login_with_mobile($mobile) {

        $mobile = HSB_Validator::mobile($mobile);

        if (!$mobile) {
            return false;
        }

        $user_id = HSB_User_Profile::create_customer($mobile);


        if (!$user_id) {
            return false;
        }


        wp_set_current_user($user_id);

        wp_set_auth_cookie($user_id, true);


        HSB_Auth_Meta::mark_verified(
            $user_id
        );


        do_action('hsb_user_logged_in', $user_id);


        return $user_id;

    }

}
