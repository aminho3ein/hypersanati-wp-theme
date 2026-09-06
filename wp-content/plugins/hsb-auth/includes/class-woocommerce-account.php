<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_WooCommerce_Account {


    public function __construct() {

        add_action(
            'woocommerce_before_customer_login_form',
            [$this, 'render_login'],
            5
        );

    }


    public function render_login() {

        echo do_shortcode('[hsb_auth_login]');

    }

}
