<?php
/**
 * HyperSanati Preinvoice Empty State
 */

defined('ABSPATH') || exit;

$preinvoice_items = function_exists('hsb_get_preinvoice_items')
    ? hsb_get_preinvoice_items()
    : array();

/*
 * WooCommerce cart can be empty while our custom
 * preinvoice session contains products.
 */
if (!empty($preinvoice_items)) {
    wc_get_template('cart/cart.php');
    return;
}

$shop_url = wc_get_page_permalink('shop');

if (!$shop_url) {
    $shop_url = home_url('/');
}
?>

<section class="cart-page-items hsb-preinvoice-empty">

    <div class="cart-page-items__header">
        <div class="cart-page-items__header-inner">

            <span class="cart-page-items__eyebrow">
                استعلام قیمت و موجودی
            </span>

            <h1 class="cart-page-items__title">
                پیش‌فاکتور من
            </h1>

        </div>
    </div>


    <div class="hsb-preinvoice-empty__body">

        <div class="hsb-preinvoice-empty__card">

            <div class="hsb-preinvoice-empty__icon-wrap">
                <i class="fa-solid fa-file-invoice"></i>

                <span class="hsb-preinvoice-empty__icon-dot">
                    <i class="fa-solid fa-plus"></i>
                </span>
            </div>


            <div class="hsb-preinvoice-empty__content">

                <span class="hsb-preinvoice-empty__label">
                    پیش‌فاکتور فعلی
                </span>

                <h2>
                    هنوز محصولی انتخاب نکرده‌اید
                </h2>

                <p>
                    محصولات موردنیاز خود را به پیش‌فاکتور اضافه کنید.
                    پس از تکمیل لیست، درخواست شما برای بررسی قیمت و
                    موجودی به واحد فروش ارسال می‌شود.
                </p>

            </div>


            <div class="hsb-preinvoice-empty__steps">

                <div class="hsb-preinvoice-empty__step">
                    <span>۳</span>
                    <div>
                        <strong>بررسی واحد فروش</strong>
                        <small>
                            قیمت و موجودی نهایی توسط فروش تأیید می‌شود.
                        </small>
                    </div>
                </div>

                <div class="hsb-preinvoice-empty__step-arrow">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>

                <div class="hsb-preinvoice-empty__step">
                    <span>۲</span>
                    <div>
                        <strong>تکمیل پیش‌فاکتور</strong>
                        <small>
                            تعداد و اقلام موردنظر را بررسی کنید.
                        </small>
                    </div>
                </div>

                <div class="hsb-preinvoice-empty__step-arrow">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>

                <div class="hsb-preinvoice-empty__step">
                    <span>۱</span>
                    <div>
                        <strong>انتخاب محصول</strong>
                        <small>
                            کالاهای موردنیاز را از فروشگاه انتخاب کنید.
                        </small>
                    </div>
                </div>

            </div>


            <div class="hsb-preinvoice-empty__actions">

                <a
                    href="<?php echo esc_url($shop_url); ?>"
                    class="hsb-preinvoice-empty__primary"
                >
                    <i class="fa-solid fa-box-open"></i>
                    مشاهده محصولات
                </a>

                <span class="hsb-preinvoice-empty__hint">
                    <i class="fa-solid fa-circle-info"></i>
                    برای ثبت پیش‌فاکتور نیازی به پرداخت در این مرحله نیست.
                </span>

            </div>

        </div>

    </div>

</section>
