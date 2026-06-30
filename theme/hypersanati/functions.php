<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue all CSS files in assets/css directory
 * (adds English comments for each file)
 */
function hypersanati_enqueue_all_css() {

    // Base URL for CSS files
    $base_url = get_template_directory_uri() . '/assets/css';

    // Server path for CSS files
    $base_dir = get_template_directory() . '/assets/css';

    // Get main css files
    $css_files = glob( $base_dir . '/*.css' );

    // Get responsive css files
    $responsive_files = glob( $base_dir . '/responsive/*.css' );

    // Merge all css files
    $all_css_files = array_merge( $css_files, $responsive_files );

    if ( empty( $all_css_files ) ) return;

    foreach ( $all_css_files as $file_path ) {

        $relative_path = str_replace( $base_dir . '/', '', $file_path );
        $handle        = 'css-' . sanitize_title( str_replace( '/', '-', $relative_path ) );
        $src           = $base_url . '/' . $relative_path;

        wp_enqueue_style( $handle, $src );
    }
}
add_action( 'wp_enqueue_scripts', 'hypersanati_enqueue_all_css', 20 );

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