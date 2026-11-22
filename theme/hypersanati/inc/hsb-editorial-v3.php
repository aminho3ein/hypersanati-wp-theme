<?php
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {
    if (!is_singular('post')) {
        return;
    }

    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    wp_enqueue_style(
        'hsb-editorial-v3',
        $uri . '/assets/css/hsb-editorial-v3.css',
        array('single-article-css'),
        filemtime($dir . '/assets/css/hsb-editorial-v3.css')
    );

    wp_enqueue_script(
        'hsb-editorial-v3',
        $uri . '/assets/js/hsb-editorial-v3.js',
        array(),
        filemtime($dir . '/assets/js/hsb-editorial-v3.js'),
        true
    );
}, 100);
