<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_SMS_Test_Provider implements HSB_SMS_Provider {


    public function send($mobile, $message) {

        return [
            'status'  => 'success',
            'mobile'  => $mobile,
            'message' => $message,
        ];

    }

}
