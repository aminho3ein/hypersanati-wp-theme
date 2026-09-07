<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Password_Login_Controller {


    public static function login($username, $password) {


        $username = trim($username);


        if (is_email($username)) {

            $user = get_user_by(
                'email',
                $username
            );

            if ($user) {
                $username = $user->user_login;
            }

        }


        $user = wp_signon([
            'user_login'    => sanitize_user($username),
            'user_password' => $password,
            'remember'      => true,
        ]);


        if (is_wp_error($user)) {
            return false;
        }


        if (
            class_exists('HSB_Staff_Login') &&
            HSB_Staff_Login::is_staff_user($user)
        ) {

            wp_logout();

            return new WP_Error(
                'staff_gateway_only',
                'این حساب فقط از طریق پنل مدیریت قابل ورود است.'
            );

        }


        return $user->ID;

    }

}
