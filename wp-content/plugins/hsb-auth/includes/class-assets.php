<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Auth_Assets {


    public function __construct() {

        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue']
        );

    }



    public function enqueue() {


        wp_enqueue_style(
            'hsb-auth',
            HSB_AUTH_URL . 'assets/css/auth.css',
            [],
            HSB_AUTH_VERSION
        );


        wp_enqueue_script(
            'hsb-auth',
            HSB_AUTH_URL . 'assets/js/auth.js',
            ['jquery'],
            HSB_AUTH_VERSION,
            true
        );


        wp_localize_script(
            'hsb-auth',
            'HSB_AUTH',
            [
                'rest_url' => rest_url(
                    'hsb-auth/v1/'
                ),

                'nonce' => wp_create_nonce(
                    'wp_rest'
                ),
            ]
        );

    }


}
