<?php
defined('ABSPATH') || exit;

$quote_items = function_exists('hsb_get_preinvoice_items')
    ? hsb_get_preinvoice_items()
    : array();

$is_direct = isset($_GET['hsb_view'])
    && sanitize_key(wp_unslash($_GET['hsb_view'])) === 'direct';

$direct_empty = !WC()->cart || WC()->cart->is_empty();
$quote_empty = empty($quote_items);
$both_empty = $direct_empty && $quote_empty;

if (!$is_direct && !$quote_empty) {
    wc_get_template('cart/cart.php');
    return;
}

$shop_url = wc_get_page_permalink('shop') ?: home_url('/');

$title = $both_empty
    ? 'سبد خرید و سبد پیش‌فاکتور شما خالی هستند'
    : ($is_direct
        ? 'سبد خرید شما خالی است'
        : 'سبد پیش‌فاکتور شما خالی است');

$description = $both_empty
    ? 'هنوز محصولی برای خرید مستقیم یا دریافت پیش‌فاکتور انتخاب نکرده‌اید.'
    : ($is_direct
        ? 'هنوز محصولی به سبد خرید اضافه نکرده‌اید.'
        : 'هنوز محصولی به سبد پیش‌فاکتور اضافه نکرده‌اید.');
?>

<section class="cart-page-items preinvoice-page hsb-preinvoice-empty">

    <div class="container">

        <?php get_template_part('inc/cart-switcher'); ?>

        <div class="cart-page-items__header">
            <div class="cart-page-items__header-inner">

                <span class="cart-page-items__eyebrow">
                    مدیریت خرید و استعلام قیمت
                </span>

                <h1 class="cart-page-items__title">
                    سبدهای من
                </h1>

            </div>
        </div>

        <div class="hsb-preinvoice-empty__body">

            <div class="hsb-preinvoice-empty__card">

                <div class="hsb-preinvoice-empty__icon-wrap">
                    <i class="fa-solid fa-basket-shopping"></i>

                    <span class="hsb-preinvoice-empty__icon-dot">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </div>

                <div class="hsb-preinvoice-empty__content">

                    <span class="hsb-preinvoice-empty__label">
                        آماده انتخاب محصولات
                    </span>

                    <h2><?php echo esc_html($title); ?></h2>

                    <p><?php echo esc_html($description); ?></p>

                </div>

                <!-- Original three-step visual design restored -->
                <div class="hsb-preinvoice-empty__steps">

                    <div class="hsb-preinvoice-empty__step">
                        <span>۳</span>
                        <div>
                            <strong>ثبت نهایی سفارش</strong>
                            <small>
                                پرداخت خرید مستقیم یا ارسال
                                درخواست استعلام به واحد فروش.
                            </small>
                        </div>
                    </div>

                    <div class="hsb-preinvoice-empty__step-arrow">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>

                    <div class="hsb-preinvoice-empty__step">
                        <span>۲</span>
                        <div>
                            <strong>تکمیل سبد موردنظر</strong>
                            <small>
                                تعداد و مشخصات محصولات را
                                در سبد خرید یا پیش‌فاکتور بررسی کنید.
                            </small>
                        </div>
                    </div>

                    <div class="hsb-preinvoice-empty__step-arrow">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>

                    <div class="hsb-preinvoice-empty__step">
                        <span>۱</span>
                        <div>
                            <strong>انتخاب محصولات</strong>
                            <small>
                                کالاهای موردنیاز خود را از فروشگاه
                                انتخاب و به سبد مناسب اضافه کنید.
                            </small>
                        </div>
                    </div>

                </div>

                <div class="hsb-preinvoice-empty__actions">

                    <a href="<?php echo esc_url($shop_url); ?>"
                       class="hsb-preinvoice-empty__primary">
                        <i class="fa-solid fa-box-open"></i>
                        مشاهده محصولات
                    </a>

                    <span class="hsb-preinvoice-empty__hint">
                        <i class="fa-solid fa-circle-info"></i>
                        کالاهای موجود را مستقیم خریداری کنید؛
                        برای سایر کالاها درخواست پیش‌فاکتور ثبت کنید.
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>
