<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Auth_Hooks {


    public function __construct() {

        add_action(
            'hsb_user_logged_in',
            [$this, 'user_logged_in'],
            10,
            1
        );


    }


    public function user_logged_in($user_id) {

        do_action(
            'hsb_auth_login_success',
            $user_id
        );

    }


}
