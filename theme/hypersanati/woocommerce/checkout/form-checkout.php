<?php
/**
 * Custom WooCommerce Checkout
 */

defined('ABSPATH') || exit;

if (!WC()->cart || WC()->cart->is_empty()) {
    wc_get_template('cart/cart-empty.php');
    return;
}

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters(
        'woocommerce_checkout_must_be_logged_in_message',
        __('You must be logged in to checkout.', 'woocommerce')
    ));
    return;
}

$cart_count = WC()->cart->get_cart_contents_count();
$cart_hash  = WC()->cart->get_cart_hash();

$order_reference = $cart_hash ? strtoupper(substr($cart_hash, 0, 12)) : date_i18n('Ymd-His');

$total_weight = 0;

foreach (WC()->cart->get_cart() as $cart_item) {
    if (!empty($cart_item['data']) && $cart_item['data']->has_weight()) {
        $total_weight += (float) $cart_item['data']->get_weight() * (int) $cart_item['quantity'];
    }
}

$weight_unit = get_option('woocommerce_weight_unit');
$chosen_shipping_methods = WC()->session ? WC()->session->get('chosen_shipping_methods') : array();
$shipping_method_text = 'ارسال عادی';

if (!empty($chosen_shipping_methods[0])) {
    $shipping_method_text = wc_clean($chosen_shipping_methods[0]);
}

$base_country = WC()->countries ? WC()->countries->get_base_country() : 'IR';
?>

<div class="container">

    <section class="checkout-order-reference">
        <div class="checkout-order-reference__inner">
            <p class="checkout-order-reference__text">
                شماره سفارش :
                <span class="checkout-order-reference__code"><?php echo esc_html($order_reference); ?></span>
            </p>
        </div>
    </section>

    <?php if (wc_coupons_enabled()) : ?>
        <section class="checkout-discount-code">
            <div class="checkout-discount-code__header">
                <h2 class="checkout-discount-code__title">کد تخفیف</h2>
            </div>

            <form class="checkout-discount-code__form checkout_coupon woocommerce-form-coupon" method="post">
                <div class="checkout-discount-code__field">
                    <input
                        type="text"
                        class="checkout-discount-code__input"
                        id="coupon_code"
                        name="coupon_code"
                        placeholder="کد تخفیف را وارد کنید"
                    />

                    <button type="submit"
                            class="checkout-discount-code__button"
                            name="apply_coupon"
                            value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                        ثبت
                    </button>
                </div>
            </form>
        </section>
    <?php endif; ?>

    <form name="checkout"
          method="post"
          class="checkout woocommerce-checkout"
          action="<?php echo esc_url(wc_get_checkout_url()); ?>"
          enctype="multipart/form-data">

        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

        <input type="hidden" name="billing_country" value="<?php echo esc_attr($checkout->get_value('billing_country') ?: $base_country); ?>" />

        <section class="checkout-address">
            <div class="checkout-address__header">
                <h2 class="checkout-address__title">آدرس من</h2>
            </div>

            <div class="checkout-address__form">
                <div class="checkout-address__grid checkout-address__grid--two-col">
                    <div class="checkout-address__field">
                        <label class="checkout-address__label" for="billing_state">استان <span>*</span></label>
                        <input
                            class="checkout-address__input"
                            type="text"
                            id="billing_state"
                            name="billing_state"
                            placeholder="استان"
                            value="<?php echo esc_attr($checkout->get_value('billing_state')); ?>"
                            autocomplete="address-level1"
                        />
                    </div>

                    <div class="checkout-address__field">
                        <label class="checkout-address__label" for="billing_city">شهر <span>*</span></label>
                        <input
                            class="checkout-address__input"
                            type="text"
                            id="billing_city"
                            name="billing_city"
                            placeholder="شهر"
                            value="<?php echo esc_attr($checkout->get_value('billing_city')); ?>"
                            autocomplete="address-level2"
                        />
                    </div>
                </div>

                <div class="checkout-address__field checkout-address__field--full">
                    <label class="checkout-address__label" for="billing_address_1">آدرس <span>*</span></label>
                    <input
                        class="checkout-address__input"
                        type="text"
                        id="billing_address_1"
                        name="billing_address_1"
                        placeholder="مثال کوچه و خیابان و جزئیات آدرس"
                        value="<?php echo esc_attr($checkout->get_value('billing_address_1')); ?>"
                        autocomplete="address-line1"
                    />

                    <p class="checkout-address__hint">
                        در صورت تغییر این بخش و ناهماهنگی آن با موقعیت مکانی، ممکن است ارسال سفارش با مشکل مواجه شود.
                    </p>
                </div>

                <div class="checkout-address__grid checkout-address__grid--two-col">
                    <div class="checkout-address__field">
                        <label class="checkout-address__label" for="billing_plaque">پلاک <span>*</span></label>
                        <input
                            class="checkout-address__input"
                            type="text"
                            id="billing_plaque"
                            name="billing_plaque"
                            value="<?php echo esc_attr($checkout->get_value('billing_plaque')); ?>"
                        />
                    </div>

                    <div class="checkout-address__field">
                        <label class="checkout-address__label" for="billing_address_2">واحد</label>
                        <input
                            class="checkout-address__input"
                            type="text"
                            id="billing_address_2"
                            name="billing_address_2"
                            value="<?php echo esc_attr($checkout->get_value('billing_address_2')); ?>"
                            autocomplete="address-line2"
                        />
                    </div>
                </div>

                <div class="checkout-address__field checkout-address__field--full">
                    <label class="checkout-address__label" for="billing_postcode">کدپستی <span>*</span></label>
                    <input
                        class="checkout-address__input"
                        type="text"
                        id="billing_postcode"
                        name="billing_postcode"
                        placeholder="باید ده رقمی باشد"
                        value="<?php echo esc_attr($checkout->get_value('billing_postcode')); ?>"
                        autocomplete="postal-code"
                    />
                </div>
            </div>
        </section>

        <section class="checkout-personal-info">
            <div class="checkout-personal-info__header">
                <h2 class="checkout-personal-info__title">اطلاعات شخصی من</h2>
            </div>

            <div class="checkout-personal-info__form" id="personalInfoForm">
                <div class="checkout-personal-info__gender">
                    <div class="checkout-personal-info__gender-options">
                        <?php
                        $gender_value = $checkout->get_value('billing_gender') ?: 'male';
                        ?>

                        <label class="checkout-personal-info__radio">
                            <input type="radio" name="billing_gender" value="male" <?php checked($gender_value, 'male'); ?> />
                            <span class="checkout-personal-info__radio-box"></span>
                            <span class="checkout-personal-info__radio-text">آقا</span>
                        </label>

                        <label class="checkout-personal-info__radio">
                            <input type="radio" name="billing_gender" value="female" <?php checked($gender_value, 'female'); ?> />
                            <span class="checkout-personal-info__radio-box"></span>
                            <span class="checkout-personal-info__radio-text">خانم</span>
                        </label>
                    </div>
                </div>

                <div class="checkout-personal-info__grid">
                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_first_name">نام <span>*</span></label>
                        <input
                            class="checkout-personal-info__input"
                            type="text"
                            id="billing_first_name"
                            name="billing_first_name"
                            placeholder="نام خود را وارد کنید *"
                            value="<?php echo esc_attr($checkout->get_value('billing_first_name')); ?>"
                            autocomplete="given-name"
                        />
                    </div>

                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_last_name">نام خانوادگی <span>*</span></label>
                        <input
                            class="checkout-personal-info__input"
                            type="text"
                            id="billing_last_name"
                            name="billing_last_name"
                            placeholder="نام خانوادگی خود را وارد کنید *"
                            value="<?php echo esc_attr($checkout->get_value('billing_last_name')); ?>"
                            autocomplete="family-name"
                        />
                    </div>

                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_company">نام فروشگاه</label>
                        <input
                            class="checkout-personal-info__input"
                            type="text"
                            id="billing_company"
                            name="billing_company"
                            placeholder="نام فروشگاه یا کارگاه خود را وارد کنید"
                            value="<?php echo esc_attr($checkout->get_value('billing_company')); ?>"
                        />
                    </div>

                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_national_code">شماره کد ملی <span>*</span></label>
                        <input
                            class="checkout-personal-info__input"
                            type="text"
                            id="billing_national_code"
                            name="billing_national_code"
                            placeholder="شماره ملی خود را وارد کنید *"
                            value="<?php echo esc_attr($checkout->get_value('billing_national_code')); ?>"
                        />
                    </div>

                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_phone">شماره موبایل <span>*</span></label>
                        <input
                            class="checkout-personal-info__input"
                            type="tel"
                            id="billing_phone"
                            name="billing_phone"
                            placeholder="شماره موبایل خود را وارد کنید *"
                            value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>"
                            autocomplete="tel"
                        />
                    </div>

                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_birth_date">تاریخ تولد شما</label>
                        <input
                            class="checkout-personal-info__input"
                            type="text"
                            id="billing_birth_date"
                            name="billing_birth_date"
                            placeholder="تاریخ تولد خود را وارد کنید"
                            value="<?php echo esc_attr($checkout->get_value('billing_birth_date')); ?>"
                        />
                    </div>

                    <div class="checkout-personal-info__field">
                        <label class="checkout-address__label" for="billing_email">ایمیل</label>
                        <input
                            class="checkout-personal-info__input"
                            type="email"
                            id="billing_email"
                            name="billing_email"
                            placeholder="ایمیل خود را وارد کنید"
                            value="<?php echo esc_attr($checkout->get_value('billing_email')); ?>"
                            autocomplete="email"
                        />
                    </div>

                    <?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
                        <input type="hidden" name="createaccount" value="1" />

                        <div class="checkout-personal-info__field checkout-personal-info__field--password-group">
                            <label class="checkout-address__label" for="password">رمز عبور خود را انتخاب کنید</label>

                            <div class="checkout-personal-info__password-error" id="confirmPasswordError"></div>

                            <div class="checkout-personal-info__password-row">
                                <div class="checkout-personal-info__password-col">
                                    <div class="checkout-personal-info__password-wrap">
                                        <input
                                            class="checkout-personal-info__input checkout-personal-info__input--password"
                                            type="password"
                                            id="password"
                                            name="account_password"
                                            placeholder="رمز عبور"
                                        />

                                        <button
                                            class="checkout-personal-info__password-toggle"
                                            type="button"
                                            aria-label="نمایش موقت رمز عبور"
                                            data-toggle-password="password"
                                        >
                                            👁
                                        </button>
                                    </div>
                                </div>

                                <div class="checkout-personal-info__password-col">
                                    <div class="checkout-personal-info__password-wrap">
                                        <input
                                            class="checkout-personal-info__input checkout-personal-info__input--password"
                                            type="password"
                                            id="confirm-password"
                                            name="confirm_password"
                                            placeholder="تکرار رمز عبور"
                                        />

                                        <button
                                            class="checkout-personal-info__password-toggle"
                                            type="button"
                                            aria-label="نمایش موقت تکرار رمز عبور"
                                            data-toggle-password="confirm-password"
                                        >
                                            👁
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

        <section class="checkout-payment-gateway">
            <div class="checkout-payment-gateway__header">
                <h2 class="checkout-payment-gateway__title">انتخاب درگاه پرداخت</h2>
            </div>

            <div id="order_review" class="woocommerce-checkout-review-order checkout-payment-gateway__woocommerce">
                <?php do_action('woocommerce_checkout_order_review'); ?>
            </div>
        </section>

        <section class="checkout-order-summary">
            <div class="checkout-order-summary__header">
                <h2 class="checkout-order-summary__title">خلاصه سفارش</h2>
            </div>

            <div class="checkout-order-summary__top">
                <div class="checkout-order-summary__shipping-meta">
                    <p class="checkout-order-summary__shipping-date">
                        زمان ارسال در مرحله پردازش سفارش مشخص می‌شود
                    </p>

                    <p class="checkout-order-summary__shipping-count">
                        <?php echo esc_html(number_format_i18n($cart_count)); ?> کالا
                    </p>

                    <p class="checkout-order-summary__shipping-method">
                        <?php echo esc_html($shipping_method_text); ?>
                    </p>
                </div>

                <button
                    id="orderSummaryToggle"
                    class="checkout-order-summary__accordion-btn"
                    type="button"
                    aria-expanded="true"
                    aria-controls="orderSummaryContent"
                >
                    <span class="checkout-order-summary__accordion-text">جزئیات مرسوله</span>
                    <span class="checkout-order-summary__accordion-chevron" aria-hidden="true"></span>
                </button>
            </div>

            <div class="checkout-order-summary__accordion-content is-open" id="orderSummaryContent">
                <div class="checkout-order-summary__details">
                    <div class="checkout-order-summary__detail-item">
                        <span class="checkout-order-summary__detail-label">نام گیرنده:</span>
                        <span class="checkout-order-summary__detail-value" data-summary-recipient>
                            نام و نام خانوادگی مخاطب
                        </span>
                    </div>

                    <div class="checkout-order-summary__detail-item">
                        <span class="checkout-order-summary__detail-label">شماره تماس:</span>
                        <span class="checkout-order-summary__detail-value" data-summary-mobile>
                            ۰۹۱۲۰۰۰۰۰۰۰
                        </span>
                    </div>

                    <div class="checkout-order-summary__detail-item">
                        <span class="checkout-order-summary__detail-label">آدرس ارسال:</span>
                        <span class="checkout-order-summary__detail-value" data-summary-address>
                            استان، شهر، خیابان، کوچه، پلاک، واحد، کدپستی
                        </span>
                    </div>

                    <div class="checkout-order-summary__detail-item">
                        <span class="checkout-order-summary__detail-label">وزن مرسوله:</span>
                        <span class="checkout-order-summary__detail-value" data-summary-weight>
                            <?php
                            if ($total_weight > 0) {
                                echo esc_html(number_format_i18n($total_weight, 2) . ' ' . $weight_unit);
                            } else {
                                echo 'ثبت نشده';
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <div class="checkout-order-summary__products">
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                        <?php
                        $_product = $cart_item['data'];

                        if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) {
                            continue;
                        }

                        $product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : wc_get_cart_url();
                        ?>

                        <a href="<?php echo esc_url($product_permalink); ?>"
                           class="checkout-order-summary__product"
                           aria-label="<?php echo esc_attr($_product->get_name()); ?>">
                            <?php echo wp_kses_post($_product->get_image('woocommerce_thumbnail')); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="checkout-order-summary__actions">
                <button type="button" class="checkout-order-summary__submit-btn" id="hsbCustomPlaceOrder">
                    تأیید و ادامه
                </button>
            </div>
        </section>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>

    </form>
</div>