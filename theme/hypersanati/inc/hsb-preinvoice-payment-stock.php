<?php
/**
 * Plugin-independent theme module: approved pre-invoice payment stock exception.
 * Do not mark products in-stock and do not alter stock quantities.
 * Only the existing payment route for a priced, approved pre-invoice qualifies.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check that the product being checked is actually an item on this order.
 */
function hsb_preinvoice_payment_contains_product( $order, $product ) {
    if ( ! $order instanceof WC_Order || ! $product instanceof WC_Product ) {
        return false;
    }

    $product_id = (int) $product->get_id();
    if ( $product_id <= 0 ) {
        return false;
    }

    foreach ( $order->get_items( 'line_item' ) as $item ) {
        if ( ! $item instanceof WC_Order_Item_Product || (float) $item->get_quantity() <= 0 ) {
            continue;
        }

        $item_product = $item->get_product();
        if ( $item_product instanceof WC_Product && (int) $item_product->get_id() === $product_id ) {
            return true;
        }
    }

    return false;
}

/**
 * Safeguard: no exception for drafts, unpaid/unapproved price quotes,
 * cancelled orders, completed payments or unrelated products/orders.
 */
function hsb_preinvoice_payment_stock_override_allowed( $order, $product ) {
    if ( ! class_exists( 'WC_Order' ) || ! class_exists( 'WC_Product' ) ) {
        return false;
    }

    if ( ! $order instanceof WC_Order || ! $product instanceof WC_Product ) {
        return false;
    }

    if ( 'preinv-approved' !== $order->get_status() ) {
        return false;
    }

    if ( 'yes' !== $order->get_meta( '_hsb_is_preinvoice', true ) ) {
        return false;
    }

    if ( ! $order->get_meta( '_hsb_preinvoice_approved_at', true ) ) {
        return false;
    }

    if ( (float) $order->get_total() <= 0 || ! $order->needs_payment() || $order->is_paid() ) {
        return false;
    }

    return hsb_preinvoice_payment_contains_product( $order, $product );
}

/**
 * WooCommerce classic order-pay and Store API order-pay stock guards.
 * Return stock pass only for a sale-team-approved, priced pre-invoice.
 */
function hsb_preinvoice_payment_allow_approved_stock( $available, $product, $order ) {
    if ( $available ) {
        return $available;
    }

    return hsb_preinvoice_payment_stock_override_allowed( $order, $product ) ? true : $available;
}

add_filter( 'woocommerce_pay_order_product_in_stock', 'hsb_preinvoice_payment_allow_approved_stock', 20, 3 );
add_filter( 'woocommerce_pay_order_product_has_enough_stock', 'hsb_preinvoice_payment_allow_approved_stock', 20, 3 );
