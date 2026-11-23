<?php
/**
 * HSB checkout rebuild — visual structure only.
 * Keep WooCommerce field names, payment/nonce flow and validation hooks intact.
 */
defined('ABSPATH') || exit;
if (!WC()->cart || WC()->cart->is_empty()) {
    wc_get_template('cart/cart-empty.php');
    return;
}
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
$base_country = WC()->countries ? WC()->countries->get_base_country() : 'IR';
$cart_count = WC()->cart->get_cart_contents_count();
$hsb_is_quote = false;
foreach (WC()->cart->get_cart() as $hsb_item) {
    $p = isset($hsb_item['data']) ? $hsb_item['data'] : null;
    if ($p && $p->exists() && '' === $p->get_price()) { $hsb_is_quote = true; break; }
}
$privacy_url = function_exists('get_privacy_policy_url') ? get_privacy_policy_url() : '';
$terms_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('terms') : '';
?>
<div class="hsb-co" dir="rtl">
    <header class="hsb-co__mast">
        <nav class="hsb-co__crumb" aria-label="موقعیت صفحه"><a href="<?php echo esc_url(wc_get_cart_url()); ?>">سبد خرید</a><span aria-hidden="true">/</span><span>تسویه‌حساب</span></nav>
        <h1>تکمیل خرید</h1>
        <p>برای تحویل درست سفارش، اطلاعات گیرنده و نشانی را وارد کنید. این اطلاعات فقط برای ثبت و پیگیری خرید استفاده می‌شود.</p>
    </header>
    <?php if (wc_coupons_enabled()) : ?>
    <details class="hsb-co__coupon">
        <summary>کد تخفیف دارید؟ <span>در صورت داشتن کد، اینجا وارد کنید</span></summary>
        <form class="checkout_coupon woocommerce-form-coupon hsb-co__coupon-form" method="post">
            <label for="coupon_code">کد تخفیف</label>
            <div class="hsb-co__coupon-row"><input id="coupon_code" name="coupon_code" type="text" placeholder="کد تخفیف" autocomplete="off" />
            <button type="submit" name="apply_coupon" value="<?php esc_attr_e('Apply coupon','woocommerce'); ?>">اعمال کد</button></div>
        </form>
    </details>
    <?php endif; ?>
    <form name="checkout" method="post" class="checkout woocommerce-checkout hsb-co__form" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
        <?php do_action('woocommerce_checkout_before_customer_details'); ?>
        <input type="hidden" name="billing_country" value="<?php echo esc_attr($checkout->get_value('billing_country') ?: $base_country); ?>" />
        <div class="hsb-co__grid">
            <main class="hsb-co__main">
                <section class="hsb-co__panel" aria-labelledby="hsb-co-personal-heading">
                    <div class="hsb-co__panel-head"><span class="hsb-co__num">۱</span><div><h2 id="hsb-co-personal-heading">اطلاعات گیرنده</h2><p>برای هماهنگی تحویل و پیگیری سفارش</p></div></div>
                    <div class="hsb-co__fields">
                        <div class="hsb-co__field hsb-co__field--full hsb-co__gender"><span class="hsb-co__label">عنوان</span>
                            <?php $gender = $checkout->get_value('billing_gender') ?: 'male'; ?>
                            <div class="hsb-co__radio-line"><label><input type="radio" id="gender-male" name="billing_gender" value="male" <?php checked($gender,'male'); ?> /> آقا</label><label><input type="radio" id="gender-female" name="billing_gender" value="female" <?php checked($gender,'female'); ?> /> خانم</label></div>
                        </div>
                        <?php
                        $personal = array(
                            array('billing_first_name','نام',true,'text','given-name'),
                            array('billing_last_name','نام خانوادگی',true,'text','family-name'),
                            array('billing_phone','شماره موبایل',true,'tel','tel'),
                            array('billing_national_code','کد ملی',true,'text','off'),
                            array('billing_company','نام فروشگاه یا شرکت',false,'text','organization'),
                            array('billing_email','ایمیل',false,'email','email'),
                            array('billing_birth_date','تاریخ تولد',false,'text','bday'),
                        );
                        foreach ($personal as $f): ?>
                            <div class="hsb-co__field"><label for="<?php echo esc_attr($f[0]); ?>"><?php echo esc_html($f[1]); ?><?php if ($f[2]): ?><span class="hsb-co__required" aria-hidden="true"> *</span><?php endif; ?></label>
                                <input id="<?php echo esc_attr($f[0]); ?>" name="<?php echo esc_attr($f[0]); ?>" type="<?php echo esc_attr($f[3]); ?>" autocomplete="<?php echo esc_attr($f[4]); ?>" value="<?php echo esc_attr($checkout->get_value($f[0])); ?>" <?php echo $f[2] ? 'required' : ''; ?> />
                            </div>
                        <?php endforeach; ?>
                        <?php if (!is_user_logged_in() && $checkout->is_registration_enabled()): ?>
                            <input type="hidden" name="createaccount" value="1" />
                            <div class="hsb-co__field"><label for="password">رمز عبور حساب</label><input id="password" name="account_password" type="password" autocomplete="new-password" /></div>
                            <div class="hsb-co__field"><label for="confirm-password">تکرار رمز عبور</label><input id="confirm-password" name="confirm_password" type="password" autocomplete="new-password" /><p id="confirmPasswordError" class="hsb-co__error" role="status"></p></div>
                        <?php endif; ?>
                    </div>
                </section>
                <section class="hsb-co__panel" aria-labelledby="hsb-co-address-heading">
                    <div class="hsb-co__panel-head"><span class="hsb-co__num">۲</span><div><h2 id="hsb-co-address-heading">نشانی تحویل</h2><p>نشانی کامل و کد پستی را با دقت وارد کنید.</p></div></div>
                    <div class="hsb-co__fields">
                        <?php
                        $address = array(
                            array('billing_state','استان',true,false,'address-level1'),
                            array('billing_city','شهر',true,false,'address-level2'),
                            array('billing_address_1','خیابان، کوچه و نشانی',true,true,'address-line1'),
                            array('billing_plaque','پلاک',true,false,'off'),
                            array('billing_address_2','واحد',false,false,'address-line2'),
                            array('billing_postcode','کد پستی',true,true,'postal-code'),
                        );
                        foreach ($address as $f): ?>
                            <div class="hsb-co__field<?php echo $f[3] ? ' hsb-co__field--full' : ''; ?>"><label for="<?php echo esc_attr($f[0]); ?>"><?php echo esc_html($f[1]); ?><?php if ($f[2]): ?><span class="hsb-co__required" aria-hidden="true"> *</span><?php endif; ?></label>
                                <input id="<?php echo esc_attr($f[0]); ?>" name="<?php echo esc_attr($f[0]); ?>" type="text" autocomplete="<?php echo esc_attr($f[4]); ?>" value="<?php echo esc_attr($checkout->get_value($f[0])); ?>" <?php echo $f[2] ? 'required' : ''; ?> />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                <section class="hsb-co__panel hsb-co__payment" aria-labelledby="hsb-co-payment-heading">
                    <div class="hsb-co__panel-head"><span class="hsb-co__num">۳</span><div><h2 id="hsb-co-payment-heading">روش پرداخت</h2><p><?php echo $hsb_is_quote ? 'درخواست استعلام پس از بررسی فروش نهایی می‌شود.' : 'روش موردنظر خود را برای پرداخت انتخاب کنید.'; ?></p></div></div>
                    <?php if ($hsb_is_quote): ?><p class="hsb-co__quote-note">در این سبد کالای فاقد قیمت وجود دارد. امکان پرداخت آن منوط به اعلام قیمت و تأیید نهایی است.</p><?php endif; ?>
                    <div id="order_review" class="woocommerce-checkout-review-order hsb-co__payment-review">
                        <?php
                        // Preserve native payment gateways, secure WooCommerce checkout and terms checkbox.
                        // Render WooCommerce review and payment in their native order: required by checkout AJAX.
                        do_action('woocommerce_checkout_order_review');
                        ?>
                    </div>
                </section>
            </main>
            <aside class="hsb-co__aside" aria-label="خلاصه سفارش">
                <div class="hsb-co__summary">
                    <div class="hsb-co__summary-head"><h2>خلاصه سفارش</h2><span><?php echo esc_html(number_format_i18n($cart_count)); ?> کالا</span></div>
                    <div class="hsb-co__items">
                        <?php foreach (WC()->cart->get_cart() as $cart_item):
                            $product = isset($cart_item['data']) ? $cart_item['data'] : null;
                            if (!$product || !$product->exists() || (int) $cart_item['quantity'] <= 0) continue;
                            $qty = (int) $cart_item['quantity']; ?>
                            <div class="hsb-co__item"><div class="hsb-co__thumb"><?php echo wp_kses_post($product->get_image('woocommerce_thumbnail')); ?></div><div class="hsb-co__item-content"><p><?php echo esc_html($product->get_name()); ?></p><small>تعداد: <?php echo esc_html(number_format_i18n($qty)); ?></small></div></div>
                        <?php endforeach; ?>
                    </div>
                    <p class="hsb-co__summary-note">مبلغ نهایی، تخفیف و هزینه‌های قابل پرداخت در بخش «روش پرداخت» نمایش داده می‌شود.</p>
                    <div class="hsb-co__trust"><span aria-hidden="true">✓</span> اطلاعات سفارش شما فقط برای پردازش خرید و هماهنگی تحویل استفاده می‌شود.</div>
                    <div class="hsb-co__legal">
                        <?php if ($terms_url): ?><a href="<?php echo esc_url($terms_url); ?>" target="_blank" rel="noopener">شرایط و مقررات خرید</a><?php endif; ?>
                        <?php if ($privacy_url): ?><a href="<?php echo esc_url($privacy_url); ?>" target="_blank" rel="noopener">سیاست حفظ حریم خصوصی</a><?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </form>
</div>
