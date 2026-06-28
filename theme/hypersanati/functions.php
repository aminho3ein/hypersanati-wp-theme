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

    // Scan directory for css files (ignore folders like responsive/)
    $css_files = glob( $base_dir . '/*.css' );

    if ( empty( $css_files ) ) return;

    foreach ( $css_files as $file_path ) {
        $filename = basename( $file_path );              // e.g. main.css
        $handle   = 'css-' . sanitize_title( $filename ); // unique-ish handle
        $src       = $base_url . '/' . $filename;

        // Add English comment (for code readability)
        // Enqueue: {filename}
        wp_enqueue_style( $handle, $src );
    }
}
add_action( 'wp_enqueue_scripts', 'hypersanati_enqueue_all_css', 20 );

/**
 * Enqueue remaining assets (JS + specific responsive folder if needed)
 */
function hypersanati_enqueue_all_js() {

    // Base URL for JS files
    $base_url = get_template_directory_uri() . '/assets/js';

    // Server path for JS files
    $base_dir = get_template_directory() . '/assets/js';

    // Scan directory for js files (ignore subfolders if any)
    $js_files = glob( $base_dir . '/*.js' );

    if ( empty( $js_files ) ) return;

    foreach ( $js_files as $file_path ) {
        $filename = basename( $file_path );                    // e.g. functions.js
        $handle   = 'js-' . sanitize_title( $filename );       // unique handle
        $src      = $base_url . '/' . $filename;

        // Enqueue: {filename}
        wp_enqueue_script( $handle, $src, array(), null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'hypersanati_enqueue_all_js', 30 );