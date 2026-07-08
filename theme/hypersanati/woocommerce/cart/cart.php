<?php
/**
 * Custom Cart Page
 *
 * This template overrides WooCommerce cart/cart.php.
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');

if (WC()->cart->is_empty()) {
    do_action('woocommerce_cart_is_empty');

    if (wc_get_page_id('shop') > 0) {
        ?>
        <section class="cart-page-items">
            <div class="container">
                <div class="cart-page-items__header">
                    <h1 class="cart-page-items__title">سبد خرید</h1>
                </div>

                <div class="cart-page-items__empty">
                    <p>سبد خرید شما در حال حاضر خالی است.</p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="cart-page-items__continue-link">
                        بازگشت به فروشگاه
                    </a>
                </div>
            </div>
        </section>
        <?php
    }

    return;
}
?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <section class="cart-page-items">
        <div class="container">
            <div class="cart-page-items__header">
                <h1 class="cart-page-items__title">سبد خرید</h1>
            </div>

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="cart-page-items__layout">
                <aside class="cart-page-items__sidebar">
                    <div class="cart-sidebar-card">
                        <?php if (wc_coupons_enabled()) : ?>
                            <button class="cart-sidebar-card__toggle" type="button" aria-label="باز و بسته کردن کد تخفیف">
                                <i class="fa-solid fa-angle-up"></i>
                            </button>

                            <div class="cart-sidebar-card__coupon">
                                <p class="cart-sidebar-card__label">ثبت کد تخفیف</p>

                                <input type="text"
                                       name="coupon_code"
                                       class="input-text"
                                       id="coupon_code"
                                       value=""
                                       placeholder="کد تخفیف را وارد کنید" />

                                <button type="submit"
                                        class="cart-sidebar-card__coupon-btn"
                                        name="apply_coupon"
                                        value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                                    اعمال کد تخفیف
                                </button>

                                <?php do_action('woocommerce_cart_coupon'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="cart-sidebar-card__meta">
                            <p>هزینه مالیات:</p>
                            <strong><?php echo wp_kses_post(wc_price(WC()->cart->get_taxes_total())); ?></strong>
                        </div>

                        <div class="cart-sidebar-card__meta">
                            <p>هزینه باربری و پست:</p>
                            <strong>
                                <?php
                                $shipping_total = WC()->cart->get_shipping_total();

                                if ($shipping_total > 0) {
                                    echo wp_kses_post(wc_price($shipping_total));
                                } else {
                                    echo 'در تسویه حساب محاسبه می‌شود';
                                }
                                ?>
                            </strong>
                        </div>

                        <div class="cart-sidebar-card__summary">
                            <p>
                                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                آیتم در سبد خرید شماست.
                            </p>

                            <div class="cart-sidebar-card__divider"></div>

                            <p>
                                جمع جزء:
                                <strong><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></strong>
                            </p>

                            <p>
                                جمع کل:
                                <strong><?php echo wp_kses_post(WC()->cart->get_total()); ?></strong>
                            </p>
                        </div>

                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="cart-sidebar-card__checkout">
                            ادامه و تسویه حساب
                        </a>

                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-sidebar-card__edit">
                            مشاهده و ویرایش سبد خرید
                        </a>
                    </div>
                </aside>

                <div class="cart-page-items__content">
                    <div class="cart-page-items__table">
                        <div class="cart-page-items__table-head">
                            <div class="cart-page-items__head-item cart-page-items__head-item--product">آیتم</div>
                            <div class="cart-page-items__head-item">قیمت</div>
                            <div class="cart-page-items__head-item">تعداد</div>
                            <div class="cart-page-items__head-item">جمع جزء</div>
                            <div class="cart-page-items__head-item cart-page-items__head-item--actions"></div>
                        </div>

                        <?php
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                            if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) {
                                continue;
                            }

                            if (!apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                continue;
                            }

                            $product_permalink = apply_filters(
                                'woocommerce_cart_item_permalink',
                                $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                                $cart_item,
                                $cart_item_key
                            );

                            $product_name = apply_filters(
                                'woocommerce_cart_item_name',
                                $_product->get_name(),
                                $cart_item,
                                $cart_item_key
                            );
                            ?>

                            <div class="cart-page-items__row <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                <div class="cart-page-items__product">
                                    <div class="cart-page-items__product-image">
                                        <?php
                                        $thumbnail = apply_filters(
                                            'woocommerce_cart_item_thumbnail',
                                            $_product->get_image('woocommerce_thumbnail'),
                                            $cart_item,
                                            $cart_item_key
                                        );

                                        if (!$product_permalink) {
                                            echo wp_kses_post($thumbnail);
                                        } else {
                                            printf(
                                                '<a href="%s">%s</a>',
                                                esc_url($product_permalink),
                                                wp_kses_post($thumbnail)
                                            );
                                        }
                                        ?>
                                    </div>

                                    <div class="cart-page-items__product-info">
                                        <h2>
                                            <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post($product_name);
                                            } else {
                                                printf(
                                                    '<a href="%s">%s</a>',
                                                    esc_url($product_permalink),
                                                    wp_kses_post($product_name)
                                                );
                                            }
                                            ?>
                                        </h2>

                                        <?php
                                        echo wc_get_formatted_cart_item_data($cart_item);

                                        if ($_product->get_short_description()) {
                                            echo '<p>' . wp_kses_post(wp_trim_words($_product->get_short_description(), 18, '...')) . '</p>';
                                        }

                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                                        ?>
                                    </div>
                                </div>

                                <div class="cart-page-items__price">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_price',
                                        WC()->cart->get_product_price($_product),
                                        $cart_item,
                                        $cart_item_key
                                    );
                                    ?>
                                </div>

                                <div class="cart-page-items__quantity">
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }

                                    woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $product_name,
                                            'classes'      => array('cart-page-items__qty-input', 'qty'),
                                        ),
                                        $_product,
                                        true
                                    );
                                    ?>
                                </div>

                                <div class="cart-page-items__subtotal">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_subtotal',
                                        WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
                                        $cart_item,
                                        $cart_item_key
                                    );
                                    ?>
                                </div>

                                <div class="cart-page-items__actions">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="cart-page-items__action-btn cart-page-items__remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa-solid fa-xmark"></i></a>',
                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                            esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            </div>

                            <?php
                        }
                        ?>
                    </div>

                    <div class="cart-page-items__footer-actions">
                        <button type="submit"
                                class="cart-page-items__update-btn"
                                name="update_cart"
                                value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                            به‌روزرسانی سبد خرید
                        </button>

                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="cart-page-items__continue-link">
                            ادامه خرید
                        </a>

                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    </div>
                </div>
            </div>

            <?php do_action('woocommerce_after_cart_table'); ?>
        </div>
    </section>
</form>

<?php do_action('woocommerce_after_cart'); ?>