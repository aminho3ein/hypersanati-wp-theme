<?php
defined('ABSPATH') || exit;

/* Related products for the quote popup, using the existing
   recommendation renderer shared with direct purchases. */
add_action('wp_ajax_hsb_popup_related', 'hsb_popup_related');
add_action('wp_ajax_nopriv_hsb_popup_related', 'hsb_popup_related');

function hsb_popup_related() {
    $id = isset($_POST['product_id'])
        ? absint($_POST['product_id']) : 0;

    $product = $id ? wc_get_product($id) : false;

    if (
        !$product ||
        get_post_status($id) !== 'publish' ||
        !function_exists('hsb_cart_ajax_popup_suggestions_html')
    ) {
        wp_send_json_success(array('html' => ''));
    }

    wp_send_json_success(array(
        'html' => hsb_cart_ajax_popup_suggestions_html($id)
    ));
}

add_action('wp_enqueue_scripts', function () {
    if (!(
        is_product() ||
        is_shop() ||
        is_product_category() ||
        is_product_tag() ||
        is_front_page()
    )) {
        return;
    }

    $root = get_template_directory();
    $url  = get_template_directory_uri();

    wp_enqueue_style(
        'hsb-unified-popup-final',
        $url . '/assets/css/hsb-unified-popup-ui.css',
        array(),
        filemtime($root . '/assets/css/hsb-unified-popup-ui.css')
    );

    wp_enqueue_script(
        'hsb-unified-popup-final',
        $url . '/assets/js/hsb-unified-popup-ui.js',
        array(),
        filemtime($root . '/assets/js/hsb-unified-popup-ui.js'),
        true
    );

    wp_localize_script(
        'hsb-unified-popup-final',
        'hsbUnifiedPopup',
        array('ajaxUrl' => admin_url('admin-ajax.php'))
    );
}, 99);
