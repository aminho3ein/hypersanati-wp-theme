<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_OTP_Storage {


    public static function save($mobile, $code, $expire = 120) {

        $key = self::key($mobile);

        return set_transient(
            $key,
            wp_hash_password((string) $code),
            $expire
        );

    }


    public static function get($mobile) {

        return get_transient(
            self::key($mobile)
        );

    }


    public static function delete($mobile) {

        delete_transient(
            self::key($mobile)
        );

    }


    private static function key($mobile) {

        return 'hsb_otp_' . substr(
            hash_hmac(
                'sha256',
                $mobile,
                wp_salt('auth')
            ),
            0,
            40
        );

    }

}
