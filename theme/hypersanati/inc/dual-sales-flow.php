<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/online-sale-category.php';

/**
 * Direct sale is allowed only when a simple product
 * has a positive price and is available for purchase.
 */
function hsb_dual_is_direct($product) {
    return $product instanceof WC_Product
        && $product->is_type('simple')
        && 'publish' === $product->get_status()
        && '' !== $product->get_price()
        && (float) $product->get_price() > 0
        && $product->is_in_stock()
        && $product->is_purchasable()
        && (
            !function_exists('hsb_get_required_product_ids')
            || !hsb_get_required_product_ids($product->get_id())
        );
}

add_filter('woocommerce_add_to_cart_validation',
    function($valid, $product_id, $quantity) {
        $product = wc_get_product($product_id);

        if ($product && !hsb_dual_is_direct($product)) {
            wc_add_notice(
                'این کالا نیازمند استعلام قیمت یا موجودی است.',
                'error'
            );
            return false;
        }

        return $valid;
    }, 20, 3
);

/* Shop-card quick actions */
add_action('wp_enqueue_scripts', function () {
    if (!(
        is_shop() ||
        is_product_category() ||
        is_product_tag() ||
        is_product() ||
        is_front_page()
    )) {
        return;
    }

    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    wp_enqueue_style(
        'hsb-quick-cards',
        $uri . '/assets/css/hsb-quick-cards.css',
        array(),
        filemtime($dir . '/assets/css/hsb-quick-cards.css')
    );

    wp_enqueue_script(
        'hsb-quick-quote',
        $uri . '/assets/js/hsb-quick-quote.js',
        array('hsb-cart-ajax-popup'),
        filemtime($dir . '/assets/js/hsb-quick-quote.js'),
        true
    );

    wp_localize_script('hsb-quick-quote', 'hsbQuickQuote', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('hsb_preinvoice_nonce'),
    ));
}, 20);

/* Capture quote-card click before parent product link navigates. */
add_action('wp_enqueue_scripts', function () {
    if (!(
        is_shop() ||
        is_product_category() ||
        is_product_tag() ||
        is_front_page()
    )) {
        return;
    }

    $relative = '/assets/js/hsb-quote-click-fix.js';
    $path = get_template_directory() . $relative;

    if (is_readable($path)) {
        wp_enqueue_script(
            'hsb-quote-click-fix',
            get_template_directory_uri() . $relative,
            array('hsb-quick-quote'),
            filemtime($path),
            true
        );
    }
}, 30);

/* Direct-cart quantity updates and removal; quote cart stays separate. */
add_action('template_redirect', function () {
    if (!is_cart() || !WC()->cart) return;

    $direct = add_query_arg('hsb_view', 'direct', wc_get_cart_url());

    if (isset($_POST['hsb_direct_update'])) {
        check_admin_referer('hsb_direct_cart', 'hsb_direct_nonce');

        $rows = isset($_POST['cart']) && is_array($_POST['cart'])
            ? wp_unslash($_POST['cart']) : array();

        foreach ($rows as $key => $row) {
            $key = wc_clean($key);
            if (!isset(WC()->cart->cart_contents[$key])) continue;

            $qty = isset($row['qty']) ? wc_stock_amount($row['qty']) : 0;
            WC()->cart->set_quantity($key, max(0, $qty), false);
        }

        WC()->cart->calculate_totals();
        wp_safe_redirect($direct);
        exit;
    }

    if (isset($_GET['hsb_direct_remove'], $_GET['_wpnonce'])) {
        $key = wc_clean(wp_unslash($_GET['hsb_direct_remove']));
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));

        if (wp_verify_nonce($nonce, 'hsb_direct_remove_' . $key)) {
            WC()->cart->remove_cart_item($key);
            wp_safe_redirect($direct);
            exit;
        }
    }
}, 5);

/* Cart-only styles: loaded on both quote and direct cart pages. */
add_action('wp_enqueue_scripts', function () {
    if (!is_cart()) return;

    $file = '/assets/css/hsb-dual-cart.css';
    $path = get_template_directory() . $file;

    if (is_readable($path)) {
        wp_enqueue_style(
            'hsb-dual-cart',
            get_template_directory_uri() . $file,
            array(),
            filemtime($path)
        );
    }
}, 100);

/* Unified cart tabs: same position on both cart views. */
add_action('wp_enqueue_scripts', function () {
    if (!is_cart()) {
        return;
    }

    $relative = '/assets/js/hsb-cart-tabs-align.js';
    $path = get_template_directory() . $relative;

    wp_enqueue_script(
        'hsb-cart-tabs-align',
        get_template_directory_uri() . $relative,
        array(),
        filemtime($path),
        true
    );
}, 110);

/* HSB EMPTY CART STATE V1 */
add_action('wp_enqueue_scripts', function () {
    if (!is_cart() || !function_exists('WC')) {
        return;
    }

    if (!wp_script_is('hsb-cart-tabs-align', 'enqueued')) {
        return;
    }

    $quote_items = function_exists('hsb_get_preinvoice_items')
        ? hsb_get_preinvoice_items()
        : array();

    $direct_empty = !WC()->cart || WC()->cart->is_empty();
    $quote_empty  = empty($quote_items);

    wp_add_inline_script(
        'hsb-cart-tabs-align',
        'window.hsbDualCartState = ' . wp_json_encode(array(
            'directEmpty' => $direct_empty,
            'quoteEmpty'  => $quote_empty,
        )) . ';',
        'before'
    );
}, 120);

/* HSB_SERVER_CART_NAVIGATION */
add_action('wp_enqueue_scripts', function () {
    if (is_cart()) {
        wp_dequeue_script('hsb-cart-tabs-align');
    }
}, 999);

/* HSB DUAL CART COUNTERS */
function hsb_dual_cart_counts_data() {
    if (function_exists('wc_load_cart') && (!WC()->session || !WC()->cart)) {
        wc_load_cart();
    }

    $direct = WC()->cart
        ? (int) WC()->cart->get_cart_contents_count()
        : 0;

    $quote = 0;
    $items = function_exists('hsb_get_preinvoice_items')
        ? hsb_get_preinvoice_items()
        : array();

    foreach ($items as $item) {
        $quote += absint($item['quantity'] ?? 0);
    }

    return array('direct' => $direct, 'quote' => $quote);
}

add_action('wp_ajax_hsb_dual_cart_counts', function () {
    wp_send_json_success(hsb_dual_cart_counts_data());
});

add_action('wp_ajax_nopriv_hsb_dual_cart_counts', function () {
    wp_send_json_success(hsb_dual_cart_counts_data());
});

add_action('wp_enqueue_scripts', function () {
    $relative = '/assets/js/hsb-cart-counts.js';
    $path = get_template_directory() . $relative;

    wp_enqueue_script(
        'hsb-cart-counts',
        get_template_directory_uri() . $relative,
        array(),
        filemtime($path),
        true
    );

    wp_localize_script('hsb-cart-counts', 'hsbCountConfig', array(
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));
}, 120);

add_action('wp_enqueue_scripts', function () {
    $relative = '/assets/css/hsb-cart-counts.css';
    $path = get_template_directory() . $relative;

    wp_enqueue_style(
        'hsb-cart-counts',
        get_template_directory_uri() . $relative,
        array(),
        filemtime($path)
    );
}, 121);

/* HSB QUOTE POPUP QUANTITY V1 */
add_action('wp_ajax_hsb_quote_popup_set_quantity', 'hsb_quote_popup_set_quantity');
add_action('wp_ajax_nopriv_hsb_quote_popup_set_quantity', 'hsb_quote_popup_set_quantity');

function hsb_quote_popup_set_quantity() {
    check_ajax_referer('hsb_preinvoice_nonce', 'nonce');

    if (!function_exists('WC') || !WC()->session) {
        wp_send_json_error(array('message' => 'سشن پیش‌فاکتور در دسترس نیست.'), 400);
    }

    $id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $qty = isset($_POST['quantity']) ? absint($_POST['quantity']) : 0;

    if (!$id || $qty < 1 || $qty > 9999) {
        wp_send_json_error(array('message' => 'تعداد انتخابی معتبر نیست.'), 400);
    }

    $items = hsb_get_preinvoice_items();
    $key = (string) $id;

    if (!isset($items[$key])) {
        wp_send_json_error(array('message' => 'محصول در پیش‌فاکتور یافت نشد.'), 404);
    }

    $old_qty = absint($items[$key]['quantity']);
    $items[$key]['quantity'] = $qty;

    /*
     * Keep the quantities of required companion products synchronized
     * with the parent product when they belong to this request.
     */
    $delta = $qty - $old_qty;

    foreach ($items as $item_key => &$item) {
        if (
            ($item['relation']['type'] ?? '') === 'required' &&
            absint($item['relation']['parent_product_id'] ?? 0) === $id
        ) {
            $item['quantity'] = max(
                1,
                absint($item['quantity']) + $delta
            );
        }
    }
    unset($item);

    WC()->session->set('hsb_preinvoice_items', $items);
    WC()->session->save_data();

    $total = 0;
    foreach ($items as $item) {
        $total += absint($item['quantity'] ?? 0);
    }

    wp_send_json_success(array(
        'product_id'     => $id,
        'quantity'       => $qty,
        'total_quantity' => $total,
        'items_count'    => count($items),
    ));
}

add_action('wp_enqueue_scripts', function () {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    $js = '/assets/js/hsb-quote-popup-controls.js';
    $css = '/assets/css/hsb-quote-popup-controls.css';

    wp_enqueue_script(
        'hsb-quote-popup-controls',
        $uri . $js,
        array(),
        filemtime($dir . $js),
        true
    );

    wp_localize_script(
        'hsb-quote-popup-controls',
        'hsbQuotePopupControls',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hsb_preinvoice_nonce'),
            'quoteUrl' => wc_get_cart_url(),
        )
    );

    wp_enqueue_style(
        'hsb-quote-popup-controls',
        $uri . $css,
        array(),
        filemtime($dir . $css)
    );
}, 130);

/* HSB STOCK AWARE SHOP BUTTON V1 */
add_action('wp_enqueue_scripts', function () {
    if (!(is_shop() || is_product_category() || is_product_tag())) {
        return;
    }

    $rel = '/assets/css/hsb-stock-button.css';
    $path = get_template_directory() . $rel;

    if (file_exists($path)) {
        wp_enqueue_style(
            'hsb-stock-button',
            get_template_directory_uri() . $rel,
            array(),
            filemtime($path)
        );
    }
}, 150);
