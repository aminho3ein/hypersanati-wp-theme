<?php

if (!defined('ABSPATH')) {
    exit;
}

final class HSB_SMS_Disabled_Provider implements HSB_SMS_Provider {

    private $reason;


    public function __construct(
        $reason = 'SMS provider is unavailable'
    ) {

        $this->reason =
            (string) $reason;
    }


    public function send(
        $mobile,
        $message
    ) {

        unset(
            $mobile,
            $message
        );

        return [
            'status'  => 'error',
            'message' => $this->reason,
        ];
    }
}
