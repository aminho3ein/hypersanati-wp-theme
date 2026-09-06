<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Password_Login_Controller {


    public static function login($username, $password) {


        $user = wp_signon([
            'user_login'    => sanitize_user($username),
            'user_password' => $password,
            'remember'      => true,
        ]);


        if (is_wp_error($user)) {
            return false;
        }


        return $user->ID;

    }

}
