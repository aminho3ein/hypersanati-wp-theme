<?php
/**
 * HSB Commerce Clarity: purchase guide, footer legal links, restrained design tokens.
 * This module does not handle payments or automatically publish legal claims.
 */
defined('ABSPATH') || exit;

if (!function_exists('hsb_render_purchase_guide')) {
    function hsb_render_purchase_guide($context = 'home') {
        ?>
        <section class="hsb-commerce-guide container" dir="rtl" aria-labelledby="hsb-guide-title-<?php echo esc_attr($context); ?>">
            <div class="hsb-commerce-guide__head">
                <p class="hsb-commerce-guide__eyebrow">راهنمای خرید</p>
                <h2 id="hsb-guide-title-<?php echo esc_attr($context); ?>">چطور از صنعت گستران الفت خرید کنیم؟</h2>
                <p>برای سرعت و شفافیت بیشتر، فرایند سفارش بر اساس مشخص‌بودن قیمت کالا انجام می‌شود.</p>
            </div>
            <div class="hsb-commerce-guide__grid">
                <article class="hsb-commerce-guide__card">
                    <span class="hsb-commerce-guide__step">۰۱</span>
                    <h3>کالاهای دارای قیمت؛ خرید مستقیم</h3>
                    <p>محصول دارای قیمت را به سبد خرید اضافه کنید، اطلاعات سفارش را تکمیل کنید و با درگاه پرداخت فعال، خرید را انجام دهید. وضعیت سفارش از حساب کاربری قابل پیگیری است.</p>
                </article>
                <article class="hsb-commerce-guide__card">
                    <span class="hsb-commerce-guide__step">۰۲</span>
                    <h3>کالاهای بدون قیمت؛ درخواست پیش‌فاکتور</h3>
                    <p>محصولات را برای استعلام ارسال کنید. واحد فروش قیمت و موجودی را بررسی می‌کند و پیش‌فاکتور را جهت تأیید در اختیار شما می‌گذارد. پرداخت فقط پس از نهایی‌شدن مبلغ و تأیید شما انجام می‌شود.</p>
                </article>
            </div>
            <p class="hsb-commerce-guide__note">ارسال کالا پس از تأیید سفارش و پرداخت، مطابق شرایط اعلام‌شده هنگام ثبت سفارش انجام می‌شود.</p>
        </section>
        <?php
    }
}

add_action('wp_footer', function () {
    // Intentionally empty: retain existing frontend scripts and hooks.
}, 1);

add_action('wp_enqueue_scripts', function () {
    $file = get_template_directory() . '/assets/css/hsb-commerce-clarity.css';
    if (!is_readable($file)) return;
    wp_enqueue_style('hsb-commerce-clarity', get_template_directory_uri() . '/assets/css/hsb-commerce-clarity.css', array(), filemtime($file));
}, 110);

// Front page: show guide once before the main footer, without editing unknown front-page templates.
add_action('get_footer', function () {
    if (is_front_page() && !is_admin() && !is_shop()) {
        hsb_render_purchase_guide('home');
    }
}, 5);

// HSB checkout visual refinement: only on the checkout page, without changing order processing.
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_checkout') || !is_checkout() || (function_exists('is_order_received_page') && is_order_received_page())) return;
    $file = get_template_directory() . '/assets/css/hsb-checkout-refine.css';
    if (!is_readable($file)) return;
    wp_enqueue_style('hsb-checkout-refine', get_template_directory_uri() . '/assets/css/hsb-checkout-refine.css', array('hsb-commerce-clarity'), filemtime($file));
}, 120);

// HSB checkout rebuild — enqueue isolated stylesheet
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_checkout') || !is_checkout() || (function_exists('is_order_received_page') && is_order_received_page())) return;
    $path = get_template_directory() . '/assets/css/hsb-checkout-rebuild.css';
    if (!is_readable($path)) return;
    wp_enqueue_style('hsb-checkout-rebuild', get_template_directory_uri() . '/assets/css/hsb-checkout-rebuild.css', array(), filemtime($path));
}, 999);

// HSB order-pay unified UI enqueue
add_action('wp_enqueue_scripts',function(){
    if (!function_exists('is_wc_endpoint_url') || !is_wc_endpoint_url('order-pay')) return;
    $path=get_template_directory().'/assets/css/hsb-order-pay.css';
    if (is_readable($path)) wp_enqueue_style('hsb-order-pay',get_template_directory_uri().'/assets/css/hsb-order-pay.css',array('hsb-checkout-rebuild'),filemtime($path));
},1001);
