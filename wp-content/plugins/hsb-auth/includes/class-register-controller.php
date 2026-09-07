<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Register_Controller {


    public static function register($data) {

        $required = [
            'username',
            'password',
            'email',
            'mobile',
            'company_name',
        ];


        foreach ($required as $field) {

            if (empty($data[$field])) {
                return false;
            }

        }


        $user_id = HSB_User_Profile::create_customer_account($data);


        if (is_wp_error($user_id)) {
            return $user_id;
        }


        if (!$user_id) {
            return false;
        }


        HSB_Customer_Meta::save(
            $user_id,
            $data
        );


        wp_set_current_user($user_id);

        wp_set_auth_cookie(
            $user_id,
            true
        );


        do_action(
            'hsb_user_registered',
            $user_id
        );


        return $user_id;

    }

}
