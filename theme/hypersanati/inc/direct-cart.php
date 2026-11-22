<?php
defined('ABSPATH') || exit;

$cart = WC()->cart;
$cart->calculate_totals();

$direct_url = add_query_arg('hsb_view', 'direct', wc_get_cart_url());
$quote_url = remove_query_arg('hsb_view', wc_get_cart_url());
$shop_url = wc_get_page_permalink('shop');

$items = $cart->get_cart();
?>

<section class="cart-page-items preinvoice-page hsb-direct-cart-page">
<div class="container">

    <?php get_template_part("inc/cart-switcher"); ?>

    <div class="cart-page-items__header">
        <h1 class="cart-page-items__title">سبد خرید من</h1>

        <?php if (!empty($items)) : ?>
            <div class="preinvoice-status-badge">در حال تکمیل</div>
        <?php endif; ?>
    </div>

    <?php wc_print_notices(); ?>

    <?php if (empty($items)) : ?>

        <div class="cart-page-items__empty">
            <p>هنوز محصولی به سبد خرید اضافه نکرده‌اید.</p>
            <a href="<?php echo esc_url($shop_url); ?>"
               class="cart-page-items__continue-link">
                مشاهده محصولات
            </a>
        </div>

    <?php else : ?>

    <form class="woocommerce-cart-form preinvoice-form hsb-direct-cart-form"
          action="<?php echo esc_url($direct_url); ?>"
          method="post">

        <div class="cart-page-items__layout">

            <aside class="cart-page-items__sidebar">
                <div class="cart-sidebar-card">
                    <div class="cart-sidebar-card__summary">

                        <p>
                            <?php echo esc_html(number_format_i18n(count($items))); ?>
                            قلم کالا
                        </p>

                        <p>
                            مجموع تعداد:
                            <strong>
                                <?php echo esc_html(number_format_i18n($cart->get_cart_contents_count())); ?>
                            </strong>
                        </p>

                        <div class="cart-sidebar-card__divider"></div>

                        <p>
                            جمع قیمت کالاها:
                            <strong>
                                <?php echo wp_kses_post($cart->get_cart_subtotal()); ?>
                            </strong>
                        </p>

                        <?php if ($cart->get_discount_total() > 0) : ?>
                            <p>
                                تخفیف:
                                <strong>
                                    <?php echo wc_price(
                                        $cart->get_discount_total() +
                                        $cart->get_discount_tax()
                                    ); ?>
                                </strong>
                            </p>
                        <?php endif; ?>

                        <div class="cart-sidebar-card__divider"></div>

                        <p class="hsb-direct-total">
                            مبلغ فعلی سبد:
                            <strong>
                                <?php echo wp_kses_post($cart->get_total()); ?>
                            </strong>
                        </p>

                        <small>
                            هزینه ارسال و مبالغ نهایی در تسویه‌حساب
                            محاسبه می‌شوند.
                        </small>
                    </div>

                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
                       class="cart-sidebar-card__checkout hsb-direct-checkout">
                        تأیید نهایی و ادامه به پرداخت
                    </a>

                    <small class="preinvoice-submit-note">
                        پیش از پرداخت، اطلاعات سفارش و نشانی تحویل
                        را بررسی کنید.
                    </small>
                </div>
            </aside>

            <div class="cart-page-items__content">
                <div class="cart-page-items__table">

                    <div class="preinvoice-products-head">
                        <div>
                            <span class="preinvoice-products-head__eyebrow">
                                اقلام انتخاب‌شده
                            </span>
                            <h2>بررسی مشخصات محصولات</h2>
                        </div>

                        <span class="preinvoice-products-head__count">
                            <?php echo esc_html(number_format_i18n(count($items))); ?>
                            قلم
                        </span>
                    </div>

                    <?php foreach ($items as $key => $item) :
                        $product = $item['data'] ?? null;
                        if (!$product || !$product->exists()) continue;

                        $id = (int) $item['product_id'];
                        $qty = (int) $item['quantity'];
                        $url = $product->get_permalink($item);

                        $sku = trim((string) $product->get_sku());
                        $part = get_post_meta($id, '_mpn_part_number', true);
                        $country = get_post_meta($id, '_country_origin', true);

                        $brand = '';
                        if (function_exists('hsb_get_product_brand_data')) {
                            $brand_data = hsb_get_product_brand_data($id);
                            if (is_array($brand_data)) {
                                $brand = $brand_data['name'] ?? '';
                            }
                        }

                        if (!$brand) {
                            $terms = get_the_terms($id, 'product_brand');
                            if ($terms && !is_wp_error($terms)) {
                                $brand = $terms[0]->name;
                            }
                        }

                        $meta_specs = array(
                            array('برند', $brand, 'fa-tag'),
                            array('کشور سازنده', $country, 'fa-earth-asia'),
                            array('قطر داخلی', get_post_meta($id, '_inner_diameter', true), 'fa-circle-dot'),
                            array('قطر خارجی', get_post_meta($id, '_outer_diameter', true), 'fa-circle'),
                            array('عرض', get_post_meta($id, '_bearing_width', true), 'fa-arrows-left-right'),
                            array('آب‌بندی', get_post_meta($id, '_bearing_seal', true), 'fa-shield-halved'),
                            array('لقی', get_post_meta($id, '_bearing_clearance', true), 'fa-up-right-and-down-left-from-center'),
                            array('کلاس دقت', get_post_meta($id, '_bearing_precision', true), 'fa-crosshairs'),
                            array('قفسه', get_post_meta($id, '_bearing_cage', true), 'fa-layer-group'),
                        );

                        $remove_url = wp_nonce_url(
                            add_query_arg(
                                array(
                                    'hsb_view' => 'direct',
                                    'hsb_direct_remove' => $key,
                                ),
                                wc_get_cart_url()
                            ),
                            'hsb_direct_remove_' . $key
                        );
                    ?>

                    <article class="preinvoice-product-card hsb-direct-product">

                        <a href="<?php echo esc_url($remove_url); ?>"
                           class="preinvoice-product-card__remove"
                           aria-label="حذف محصول"
                           title="حذف از سبد خرید">
                            <i class="fa-regular fa-trash-can"></i>
                        </a>

                        <div class="preinvoice-product-card__top">

                            <a href="<?php echo esc_url($url); ?>"
                               class="preinvoice-product-card__image">
                                <?php echo wp_kses_post(
                                    $product->get_image('woocommerce_thumbnail')
                                ); ?>
                            </a>

                            <div class="preinvoice-product-card__main">

                                <div class="preinvoice-product-card__labels">
                                    <span class="preinvoice-primary-badge">
                                        خرید مستقیم
                                    </span>
                                </div>

                                <h2 class="preinvoice-product-card__title">
                                    <a href="<?php echo esc_url($url); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h2>

                                <div class="preinvoice-product-card__codes">
                                    <?php if ($part) : ?>
                                        <div class="preinvoice-product-code">
                                            <span>Part Number</span>
                                            <strong dir="ltr"><?php echo esc_html($part); ?></strong>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($sku) : ?>
                                        <div class="preinvoice-product-code">
                                            <span>SKU</span>
                                            <strong dir="ltr"><?php echo esc_html($sku); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <div class="preinvoice-product-specs">
                            <?php foreach ($meta_specs as $spec) :
                                $value = trim((string) $spec[1]);
                                if ($value === '') continue;

                                if (
                                    in_array($spec[0], array('قطر داخلی', 'قطر خارجی', 'عرض'), true)
                                    && is_numeric(str_replace(',', '.', $value))
                                ) {
                                    $value .= ' mm';
                                }
                            ?>
                                <div class="preinvoice-product-spec">
                                    <span class="preinvoice-product-spec__icon">
                                        <i class="fa-solid <?php echo esc_attr($spec[2]); ?>"></i>
                                    </span>
                                    <div>
                                        <small><?php echo esc_html($spec[0]); ?></small>
                                        <strong><?php echo esc_html($value); ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="hsb-direct-prices">
                            <div>
                                <span>قیمت واحد</span>
                                <strong>
                                    <?php echo wp_kses_post($cart->get_product_price($product)); ?>
                                </strong>
                            </div>
                            <div>
                                <span>مجموع این محصول</span>
                                <strong>
                                    <?php echo wp_kses_post(
                                        $cart->get_product_subtotal($product, $qty)
                                    ); ?>
                                </strong>
                            </div>
                        </div>

                        <div class="preinvoice-product-card__footer">
                            <div class="preinvoice-product-card__qty-group">
                                <span class="preinvoice-product-card__qty-label">
                                    تعداد موردنیاز
                                </span>

                                <div class="cart-page-items__quantity">
                                    <div class="quantity">
                                        <button type="button"
                                                class="cart-page-items__qty-btn cart-page-items__qty-btn--minus"
                                                aria-label="کاهش تعداد">−</button>

                                        <input type="number"
                                               class="cart-page-items__qty-input qty"
                                               name="cart[<?php echo esc_attr($key); ?>][qty]"
                                               value="<?php echo esc_attr($qty); ?>"
                                               min="0"
                                               max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>"
                                               step="1">

                                        <button type="button"
                                                class="cart-page-items__qty-btn cart-page-items__qty-btn--plus"
                                                aria-label="افزایش تعداد">+</button>
                                    </div>
                                </div>
                            </div>

                            <a href="<?php echo esc_url($url); ?>"
                               class="preinvoice-product-card__details-link">
                                مشاهده مشخصات کامل محصول
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                        </div>

                    </article>

                    <?php endforeach; ?>

                </div>

                <div class="cart-page-items__footer-actions">
                    <button type="submit"
                            class="cart-page-items__update-btn"
                            name="hsb_direct_update"
                            value="1">
                        به‌روزرسانی سبد خرید
                    </button>

                    <a href="<?php echo esc_url($shop_url); ?>"
                       class="cart-page-items__continue-link">
                        ادامه انتخاب محصولات
                    </a>

                    <?php wp_nonce_field('hsb_direct_cart', 'hsb_direct_nonce'); ?>
                </div>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>
</section>
