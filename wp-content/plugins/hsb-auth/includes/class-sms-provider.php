<?php

if (!defined('ABSPATH')) {
    exit;
}

interface HSB_SMS_Provider {

    public function send($mobile, $message);

}
