<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Auth_Meta {


    public static function mark_verified($user_id) {


        update_user_meta(
            $user_id,
            'hsb_mobile_verified',
            true
        );


        update_user_meta(
            $user_id,
            'hsb_last_login',
            current_time('mysql')
        );


    }



    public static function is_verified($user_id) {


        return (bool) get_user_meta(
            $user_id,
            'hsb_mobile_verified',
            true
        );

    }


}
