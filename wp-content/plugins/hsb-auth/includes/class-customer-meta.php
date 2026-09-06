<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Customer_Meta {


    private static $fields = [

        'first_name',
        'last_name',
        'email',

        'company_name',
        'company_buyer_name',
        'company_position',
        'national_id',
        'economic_code',
        'registration_number',
        'industry',

        'province',
        'city',
        'address',
        'postal_code',
        'receiver_name',
        'receiver_phone',

    ];


    public static function save($user_id, $data) {


        foreach (self::$fields as $field) {


            if (isset($data[$field])) {

                update_user_meta(
                    $user_id,
                    'hsb_' . $field,
                    sanitize_text_field(
                        $data[$field]
                    )
                );

            }

        }


        return true;

    }


    public static function get($user_id) {


        $result = [];


        foreach (self::$fields as $field) {

            $result[$field] = get_user_meta(
                $user_id,
                'hsb_' . $field,
                true
            );

        }


        return $result;

    }


}
