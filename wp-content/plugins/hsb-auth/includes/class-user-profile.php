<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_User_Profile {


    public static function find_by_mobile($mobile) {

        $mobile = HSB_Validator::mobile($mobile);

        if (!$mobile) {
            return false;
        }

        $users = get_users([
            'meta_key'   => 'hsb_mobile',
            'meta_value' => $mobile,
            'number'     => 1,
            'fields'     => 'ID',
        ]);


        return !empty($users)
            ? $users[0]
            : false;

    }


    public static function create_customer($mobile) {

        $mobile = HSB_Validator::mobile($mobile);

        if (!$mobile) {
            return false;
        }

        $existing = self::find_by_mobile($mobile);


        if ($existing) {
            return new WP_Error(
                'hsb_mobile_exists',
                'این شماره موبایل قبلاً در سایت ثبت شده است.'
            );
        }


        $user_id = wp_insert_user([

            'user_login' => 'hsb_' . $mobile,
            'user_pass'  => wp_generate_password(),
            'role'       => 'customer',

        ]);


        if (is_wp_error($user_id)) {
            return false;
        }


        update_user_meta(
            $user_id,
            'hsb_mobile',
            $mobile
        );


        update_user_meta(
            $user_id,
            'hsb_mobile_verified',
            1
        );


        return $user_id;

    }


    public static function create_customer_account($data) {

        $mobile = HSB_Validator::mobile($data['mobile']);

        if (!$mobile) {
            return false;
        }

        $existing = self::find_by_mobile($mobile);

        if ($existing) {
            return new WP_Error(
                'hsb_mobile_exists',
                'این شماره موبایل قبلاً در سایت ثبت شده است.'
            );
        }

        $user_id = wp_insert_user([

            'user_login' => sanitize_user($data['username']),
            'user_pass'  => $data['password'],
            'user_email' => sanitize_email($data['email']),
            'role'       => 'customer',

        ]);


        if (is_wp_error($user_id)) {
            return false;
        }


        update_user_meta(
            $user_id,
            'hsb_mobile',
            $mobile
        );


        update_user_meta(
            $user_id,
            'hsb_mobile_verified',
            0
        );


        return $user_id;

    }

}
