<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Auth_API {


    public static function get_current_user_id() {

        return get_current_user_id();

    }


    public static function is_logged_in() {

        return is_user_logged_in();

    }


    public static function get_mobile($user_id = null) {

        if (!$user_id) {
            $user_id = get_current_user_id();
        }


        if (!$user_id) {
            return false;
        }


        return get_user_meta(
            $user_id,
            'hsb_mobile',
            true
        );

    }


    public static function mobile_verification_enabled() {

        return (bool) HSB_Auth_Config::get(
            'mobile_verification_enabled',
            false
        );

    }



    public static function is_verified($user_id = null) {

        if (!$user_id) {
            $user_id = get_current_user_id();
        }


        if (!$user_id) {
            return false;
        }


        return HSB_Auth_Meta::is_verified($user_id);

    }


    public static function require_login() {

        if (!self::is_logged_in()) {

            return false;

        }


        return true;

    }


    public static function get_profile($user_id = null) {


        if (!$user_id) {
            $user_id = get_current_user_id();
        }


        if (!$user_id) {
            return false;
        }


        return HSB_Customer_Meta::get($user_id);

    }



    public static function update_profile($data, $user_id = null) {


        if (!$user_id) {
            $user_id = get_current_user_id();
        }


        if (!$user_id) {
            return false;
        }


        return HSB_Customer_Meta::save(
            $user_id,
            $data
        );

    }


}
