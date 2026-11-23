<?php
/** HSB shop purchase guide, independent of WooCommerce cart and payment flow. */
defined('ABSPATH') || exit;

function hsb_shop_purchase_popup_render() {
    ?>
    <div class="hsb-shop-assist" dir="rtl">
      <div class="hsb-shop-assist__intro">
        <div class="hsb-shop-assist__message"><span class="hsb-shop-assist__symbol" aria-hidden="true">؟</span><span>برای خرید قطعات صنعتی دو روش ساده دارید.</span></div>
        <button type="button" class="hsb-shop-assist__trigger" data-hsb-guide-open aria-haspopup="dialog" aria-controls="hsb-shop-guide-dialog">راهنمای خرید <span aria-hidden="true">←</span></button>
      </div>
    </div>
    <div id="hsb-shop-guide" class="hsb-shop-guide" hidden>
      <div class="hsb-shop-guide__backdrop" data-hsb-guide-close></div>
      <section class="hsb-shop-guide__dialog" id="hsb-shop-guide-dialog" role="dialog" aria-modal="true" aria-labelledby="hsb-shop-guide-title" aria-describedby="hsb-shop-guide-desc" dir="rtl" tabindex="-1">
        <div class="hsb-shop-guide__top">
          <span class="hsb-shop-guide__eyebrow">راهنمای خرید صنعت گستران الفت</span>
          <button type="button" class="hsb-shop-guide__close" data-hsb-guide-close aria-label="بستن راهنمای خرید">×</button>
        </div>
        <h2 id="hsb-shop-guide-title">چطور خرید کنم؟</h2>
        <p id="hsb-shop-guide-desc">با توجه به اینکه قیمت کالا مشخص شده یا نیاز به استعلام دارد، یکی از مسیرهای زیر را دنبال کنید.</p>
        <div class="hsb-shop-guide__options">
          <article class="hsb-shop-guide__option">
            <div class="hsb-shop-guide__option-top"><span class="hsb-shop-guide__number">۰۱</span><span class="hsb-shop-guide__tag">قیمت مشخص</span></div>
            <h3>خرید مستقیم</h3>
            <p>کالای قیمت‌دار را به سبد خرید اضافه کنید؛ پس از ثبت اطلاعات و پرداخت، سفارش را در حساب کاربری پیگیری کنید.</p>
          </article>
          <article class="hsb-shop-guide__option">
            <div class="hsb-shop-guide__option-top"><span class="hsb-shop-guide__number">۰۲</span><span class="hsb-shop-guide__tag">نیازمند استعلام</span></div>
            <h3>درخواست پیش‌فاکتور</h3>
            <p>کالای بدون قیمت را به فهرست پیش‌فاکتور اضافه کنید. واحد فروش قیمت و تعداد را بررسی و تأیید می‌کند؛ سپس شما مبلغ تأییدشده را پرداخت می‌کنید.</p>
          </article>
        </div>
        <div class="hsb-shop-guide__bottom">
          <p>درخواست پیش‌فاکتور به‌معنی خرید یا پرداخت قطعی نیست. ارسال کالا پس از نهایی‌شدن سفارش و پرداخت انجام می‌شود.</p>
          <button type="button" class="hsb-shop-guide__done" data-hsb-guide-close>متوجه شدم، مشاهده محصولات</button>
        </div>
      </section>
    </div>
    <?php
}

add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_shop') || !is_shop()) return;
    $dir = get_template_directory();
    $uri = get_template_directory_uri();
    $css = '/assets/css/hsb-shop-popup.css';
    $js = '/assets/js/hsb-shop-popup.js';
    if (is_readable($dir . $css)) wp_enqueue_style('hsb-shop-popup', $uri . $css, array(), filemtime($dir . $css));
    if (is_readable($dir . $js)) wp_enqueue_script('hsb-shop-popup', $uri . $js, array(), filemtime($dir . $js), true);
}, 120);
