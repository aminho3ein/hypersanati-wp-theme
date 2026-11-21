<?php
defined('ABSPATH') || exit;

/**
 * Automatically maintain the additional "Online Sale"
 * WooCommerce product category.
 */

function hsb_online_sale_category_id($create = false) {
    $term = get_term_by(
        'slug',
        'hsb-online-sale',
        'product_cat'
    );

    if ($term && !is_wp_error($term)) {
        return (int) $term->term_id;
    }

    if (!$create) {
        return 0;
    }

    $created = wp_insert_term(
        'فروش آنلاین',
        'product_cat',
        array(
            'slug'        => 'hsb-online-sale',
            'description' => 'محصولات دارای قیمت و موجودی، آماده سفارش آنلاین',
        )
    );

    if (is_wp_error($created)) {
        if ($created->get_error_code() === 'term_exists') {
            return (int) $created->get_error_data();
        }
        return 0;
    }

    return (int) $created['term_id'];
}

function hsb_sync_online_sale_product($product_id) {
    $product_id = absint($product_id);
    if (!$product_id) {
        return false;
    }

    $term_id = hsb_online_sale_category_id();
    if (!$term_id) {
        return false;
    }

    $product = wc_get_product($product_id);

    $eligible = $product
        && $product->is_type('simple')
        && function_exists('hsb_dual_is_direct')
        && hsb_dual_is_direct($product);

    $assigned = has_term(
        $term_id,
        'product_cat',
        $product_id
    );

    if ($eligible && !$assigned) {
        $result = wp_set_object_terms(
            $product_id,
            array($term_id),
            'product_cat',
            true
        );

        return !is_wp_error($result);
    }

    if (!$eligible && $assigned) {
        return wp_remove_object_terms(
            $product_id,
            array($term_id),
            'product_cat'
        );
    }

    return true;
}

/* Changes made through WooCommerce product saves. */
add_action(
    'woocommerce_after_product_object_save',
    function ($product) {
        if ($product instanceof WC_Product) {
            hsb_sync_online_sale_product(
                $product->get_id()
            );
        }
    },
    30
);

/* Stock changes through WooCommerce APIs. */
add_action(
    'woocommerce_product_set_stock',
    function ($product) {
        if ($product instanceof WC_Product) {
            hsb_sync_online_sale_product(
                $product->get_id()
            );
        }
    },
    30
);

add_action(
    'woocommerce_product_set_stock_status',
    function ($product_id) {
        hsb_sync_online_sale_product($product_id);
    },
    30
);
