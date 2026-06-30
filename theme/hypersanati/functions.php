<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue all CSS files in assets/css directory
 * (adds English comments for each file)
 */
function hypersanati_enqueue_all_css() {

    $base_url = get_template_directory_uri() . '/assets/css';
    $base_dir = get_template_directory() . '/assets/css';

    // Main CSS
    wp_enqueue_style(
        'main-css',
        $base_url . '/main.css',
        array(),
        filemtime($base_dir . '/main.css')
    );

    // Tablet
    wp_enqueue_style(
        'tablet-responsive',
        $base_url . '/responsive/tablet-responsive.css',
        array('main-css'),
        filemtime($base_dir . '/responsive/tablet-responsive.css'),
        '(min-width:768px)'
    );

    // Laptop
    wp_enqueue_style(
        'laptop-responsive',
        $base_url . '/responsive/laptop-responsive.css',
        array('main-css'),
        filemtime($base_dir . '/responsive/laptop-responsive.css'),
        '(min-width:992px)'
    );

    // Desktop
    wp_enqueue_style(
        'desktop-responsive',
        $base_url . '/responsive/desktop-responsive.css',
        array('main-css'),
        filemtime($base_dir . '/responsive/desktop-responsive.css'),
        '(min-width:1200px)'
    );
}

add_action('wp_enqueue_scripts', 'hypersanati_enqueue_all_css');

/**
 * Enqueue remaining assets (JS + specific responsive folder if needed)
 */
function hypersanati_enqueue_scripts() {

    // JS
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/functions.js',
        array(),
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hypersanati_enqueue_scripts' );