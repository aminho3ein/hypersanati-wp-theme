<?php
/**
 * HSB Visual Design System V1.
 * Admin-only controls. No changes to theme markup or sales logic.
 */
if (!defined('ABSPATH')) { exit; }

function hsb_ds_catalog() {
    return array(
        'brand' => array('title'=>'رنگ‌های اصلی', 'desc'=>'رنگ پایه و حالت‌های تعاملی؛ تغییرات پس از ذخیره روی سایت اعمال می‌شوند.', 'fields'=>array(
            'color-primary'=>array('رنگ اصلی','#9f8561'),
            'color-secondary'=>array('رنگ ثانویه','#5e4420'),
            'color-primary-hover'=>array('هاور اصلی','#8a7353'),
            'color-primary-active'=>array('حالت فعال اصلی','#bd9f78'),
        )),
        'header' => array('title'=>'ویرایش هدر', 'desc'=>'نوار بالا، پس‌زمینه، متن منو و آیکن‌ها', 'fields'=>array(
            'hsb-header-top-bg'=>array('پس‌زمینه نوار بالا','#8a7353'),
            'hsb-header-top-text'=>array('متن نوار بالا','#ffffff'),
            'hsb-header-main-bg'=>array('پس‌زمینه منوی اصلی','#f5f5f5'),
            'hsb-header-nav-text'=>array('متن منو','#171717'),
            'hsb-header-nav-active'=>array('لینک فعال','#5e4420'),
            'hsb-header-icon-bg'=>array('زمینه آیکن','#5e4420'),
            'hsb-header-icon-text'=>array('رنگ آیکن','#ffffff'),
            'hsb-header-icon-hover-bg'=>array('هاور زمینه آیکن','#735936'),
            'hsb-header-badge-direct-bg'=>array('نشان خرید آنلاین','#168052'),
            'hsb-header-badge-quote-bg'=>array('نشان پیش‌فاکتور','#e53935'),
        )),
        'footer' => array('title'=>'ویرایش فوتر', 'desc'=>'پیش‌نمایش فوتر و بخش خدمات', 'fields'=>array(
            'hsb-footer-bg'=>array('پس‌زمینه فوتر','#1f2937'),
            'hsb-footer-text'=>array('متن فوتر','#e5e7eb'),
            'hsb-footer-heading'=>array('عنوان‌های فوتر','#ffffff'),
            'hsb-footer-services-bg'=>array('زمینه خدمات','#f4f1eb'),
            'hsb-footer-service-title'=>array('عنوان خدمات','#25211c'),
            'hsb-footer-contact-heading-bg'=>array('سربرگ تماس','#5e4420'),
            'hsb-footer-bottom-bg'=>array('نوار پایانی','#171717'),
            'hsb-footer-description-text'=>array('متن توضیحات برند','#94A3B8'),
            'hsb-footer-brand-label'=>array('برچسب بالای برند','#93C5FD'),
            'hsb-footer-identity-bg'=>array('زمینه کارت هویت برند','#334155'),
            'hsb-footer-identity-border'=>array('حاشیه کارت هویت برند','#475569'),
            'hsb-footer-identity-description'=>array('توضیحات کارت برند','#CBD5E1'),
            'hsb-footer-logo-box-bg'=>array('زمینه لوگوی برند','#FFFFFF'),
            'hsb-footer-enamad-bg'=>array('زمینه نماد اعتماد','#FFFFFF'),
            'hsb-footer-enamad-title'=>array('عنوان نماد اعتماد','#CBD5E1'),
            'hsb-footer-service-text'=>array('متن عمومی نوار خدمات','#1E293B'),
            'hsb-footer-service-description'=>array('توضیحات هر خدمت','#64748B'),
            'hsb-footer-service-icon-color'=>array('رنگ آیکون خدمات','#2563EB'),
            'hsb-footer-service-icon-bg'=>array('زمینه آیکون خدمات','#E2E8F0'),
            'hsb-footer-service-icon-border'=>array('حاشیه آیکون خدمات','#CBD5E1'),
            'hsb-footer-service-border-top'=>array('حاشیه بالای نوار خدمات','#CBD5E1'),
            'hsb-footer-service-border-bottom'=>array('حاشیه پایین نوار خدمات','#CBD5E1'),
            'hsb-footer-service-divider'=>array('جداکننده خدمات','#E2E8F0'),
            'hsb-footer-cta-bg'=>array('زمینه کارت استعلام قیمت','#334155'),
            'hsb-footer-cta-border'=>array('حاشیه کارت استعلام','#475569'),
            'hsb-footer-cta-heading'=>array('عنوان کارت استعلام','#FFFFFF'),
            'hsb-footer-cta-description'=>array('توضیحات کارت استعلام','#CBD5E1'),
            'hsb-footer-contact-bg'=>array('زمینه کارت تماس','#334155'),
            'hsb-footer-contact-border'=>array('حاشیه کارت تماس','#475569'),
            'hsb-footer-contact-heading-text'=>array('متن سربرگ تماس','#FFFFFF'),
            'hsb-footer-contact-subtitle'=>array('زیرعنوان تماس','#DBEAFE'),
            'hsb-footer-contact-divider'=>array('خط جداکننده تماس','#475569'),
            'hsb-footer-contact-label'=>array('برچسب‌های تلفن و ایمیل','#93C5FD'),
            'hsb-footer-contact-value-text'=>array('متن اطلاعات تماس','#E2E8F0'),
            'hsb-footer-contact-link-text'=>array('رنگ لینک تماس','#E2E8F0'),
            'hsb-footer-contact-link-hover-text'=>array('هاور لینک تماس','#FFFFFF'),
            'hsb-footer-phone-link'=>array('رنگ شماره تلفن','#E2E8F0'),
            'hsb-footer-phone-link-hover'=>array('هاور شماره تلفن','#93C5FD'),
            'hsb-footer-phone-icon-color'=>array('آیکون تلفن','#60A5FA'),
            'hsb-footer-phone-icon-bg'=>array('زمینه آیکون تلفن','#1E293B'),
            'hsb-footer-phone-separator'=>array('جداکننده شماره‌ها','#64748B'),
            'hsb-footer-mobile-name'=>array('نام مخاطب موبایل','#CBD5E1'),
            'hsb-footer-mobile-number'=>array('شماره موبایل','#E2E8F0'),
            'hsb-footer-mobile-number-hover'=>array('هاور شماره موبایل','#93C5FD'),
            'hsb-footer-email-hover'=>array('هاور ایمیل','#93C5FD'),
            'hsb-footer-navigation-bg'=>array('زمینه بخش دسترسی','#334155'),
            'hsb-footer-navigation-border'=>array('حاشیه بخش دسترسی','#475569'),
            'hsb-footer-nav-description'=>array('توضیح بخش دسترسی','#94A3B8'),
            'hsb-footer-nav-link-text'=>array('متن لینک‌های دسترسی','#E2E8F0'),
            'hsb-footer-nav-item-bg'=>array('زمینه دکمه دسترسی','#1E293B'),
            'hsb-footer-nav-item-border'=>array('حاشیه دکمه دسترسی','#475569'),
            'hsb-footer-nav-link-hover-text'=>array('متن هاور دسترسی','#FFFFFF'),
            'hsb-footer-nav-item-hover-bg'=>array('زمینه هاور دسترسی','#1E40AF'),
            'hsb-footer-nav-item-hover-border'=>array('حاشیه هاور دسترسی','#2563EB'),
            'hsb-footer-link-icon-color'=>array('رنگ فلش دسترسی','#60A5FA'),
            'hsb-footer-link-icon-bg'=>array('زمینه فلش دسترسی','#334155'),
            'hsb-footer-link-icon-hover-text'=>array('رنگ فلش در هاور','#FFFFFF'),
            'hsb-footer-link-icon-hover-bg'=>array('زمینه فلش در هاور','#2563EB'),
            'hsb-footer-bottom-border'=>array('خط بالای کپی‌رایت','#334155'),
            'hsb-footer-bottom-text'=>array('متن کپی‌رایت','#CBD5E1'),
            'hsb-footer-bottom-link-hover'=>array('هاور لینک کپی‌رایت','#93C5FD'),
        )),
        'card' => array('title'=>'کارت محصول', 'desc'=>'رنگ‌های کارت واقعی در هر دو وضعیت خرید مستقیم و استعلام؛ تغییرات در نمونه زنده نمایش داده می‌شوند.', 'fields'=>array(
            // Base surfaces and borders
            'hsb-shop-card-bg'=>array('زمینه اصلی کارت','#FFFFFF'),
            'hsb-shop-card-border'=>array('رنگ حاشیه کارت','#475569'),
            'hsb-shop-card-hover-border'=>array('حاشیه کارت در هاور','#2563EB'),
            'hsb-card-shadow-color'=>array('رنگ سایه کارت','#181E23'),
            'hsb-shop-card-media-bg'=>array('زمینه تصویر محصول','#FFFFFF'),
            'hsb-card-media-divider'=>array('خط جداکننده تصویر','#E2E8F0'),
            // Identity and family badge
            'hsb-shop-card-display-title'=>array('عنوان محصول','#0F172A'),
            'hsb-card-origin-brand'=>array('نام برند','#1E293B'),
            'hsb-card-origin-country'=>array('کشور سازنده','#64748B'),
            'hsb-shop-card-part-emphasis'=>array('متن شماره محصول','#1D4ED8'),
            'hsb-card-part-bg'=>array('زمینه شماره محصول','#EFF6FF'),
            'hsb-shop-card-family-badge-base'=>array('رنگ پایه نشان گزینه‌ها','#2563EB'),
            'hsb-shop-card-family-badge-bg'=>array('زمینه نهایی نشان گزینه‌ها','#2563EB'),
            'hsb-shop-card-family-badge-text'=>array('متن نشان گزینه‌ها','#FFFFFF'),
            'hsb-card-badge-icon-color'=>array('آیکون نشان گزینه‌ها','#DBEAFE'),
            // Rating and specification grids
            'hsb-card-rating-text'=>array('متن امتیاز','#64748B'),
            'hsb-card-rating-empty'=>array('ستاره‌های خالی','#CBD5E1'),
            'hsb-card-rating-active'=>array('ستاره‌های فعال','#F59E0B'),
            'hsb-card-dimension-bg'=>array('زمینه مشخصات فنی','#F8FAFC'),
            'hsb-card-dimension-border'=>array('حاشیه مشخصات فنی','#E2E8F0'),
            'hsb-card-dimension-label'=>array('عنوان مشخصات فنی','#64748B'),
            'hsb-card-dimension-value'=>array('مقدار مشخصات فنی','#0F172A'),
            'hsb-card-options-bg'=>array('زمینه نشان‌های برند و کشور','#EFF6FF'),
            'hsb-card-options-text'=>array('متن نشان‌های برند و کشور','#1D4ED8'),
            'hsb-card-options-icon'=>array('آیکون نشان‌های برند و کشور','#2563EB'),
            // Two distinct price states
            'hsb-card-price-note-bg'=>array('زمینه عمومی بخش قیمت','#F1F5F9'),
            'hsb-card-price-note-text'=>array('متن عمومی بخش قیمت','#475569'),
            'hsb-card-price-inquiry-bg'=>array('زمینه قیمت استعلامی','#EDF3EF'),
            'hsb-card-price-inquiry-text'=>array('متن قیمت استعلامی','#B42318'),
            'hsb-card-price-actual-text'=>array('متن قیمت واقعی','#168052'),
            'hsb-card-price-old-text'=>array('قیمت خط‌خورده','#888888'),
            // Action/link and inventory states
            'hsb-card-action-text'=>array('لینک مشاهده محصول','#1D4ED8'),
            'hsb-card-stock-bg'=>array('زمینه تعداد موجودی','#EDF5EF'),
            'hsb-card-action-direct-bg'=>array('دکمه خرید آنلاین روی کارت','#168052'),
            'hsb-card-action-direct-hover-bg'=>array('هاور خرید آنلاین روی کارت','#168052'),
            'hsb-card-action-direct-active-bg'=>array('حالت فعال خرید آنلاین','#168052'),
            'hsb-card-action-quote-bg'=>array('دکمه پیش‌فاکتور روی کارت','#735936'),
            'hsb-card-action-quote-hover-bg'=>array('هاور پیش‌فاکتور روی کارت','#735936'),
            'hsb-card-action-quote-active-bg'=>array('حالت فعال پیش‌فاکتور','#735936'),
            'hsb-card-action-button-text'=>array('متن دکمه‌های کارت','#FFFFFF'),
            'hsb-card-action-disabled-bg'=>array('زمینه دکمه غیرفعال','#D7DCDA'),
            'hsb-card-action-disabled-text'=>array('متن دکمه غیرفعال','#49534E'),
        )),
        'sales' => array('title'=>'خرید و پیش‌فاکتور', 'desc'=>'دو مسیر فروش کاملاً مستقل باقی می‌مانند.', 'fields'=>array(
            'hsb-sale-direct-button-bg'=>array('دکمه خرید مستقیم','#167a50'),
            'hsb-sale-quote-button-bg'=>array('دکمه پیش‌فاکتور','#735936'),
            'hsb-sale-button-text'=>array('متن دکمه‌ها','#ffffff'),
            'hsb-sale-inquiry-status'=>array('وضعیت استعلام','#b42318'),
            'hsb-quote-popup-surface'=>array('زمینه پنجره پیش‌فاکتور','#f5f2ec'),
        )),

        'cart_page' => array('title'=>'سبدهای خرید', 'desc'=>'رنگ‌های صفحه سبد خرید و تب‌های خرید مستقیم و پیش‌فاکتور، با پیش‌نمایش مستقل.', 'fields'=>array(
            'hsb-cart-neutral-white'=>array('توکن موجود · cart neutral white','#ffffff'),
            'hsb-cart-quote-border'=>array('توکن موجود · cart quote border','#d9ccbb'),
            'hsb-cart-quote-surface'=>array('توکن موجود · cart quote surface','#f5f0e9'),
            'hsb-cart-direct-surface'=>array('توکن موجود · cart direct surface','#f3f6f3'),
            'hsb-cart-muted-text'=>array('توکن موجود · cart muted text','#66736b'),
            'hsb-cart-page-001'=>array('سبد خرید · زمینه · cart-page-items__header  after (1)','#d89a00'),
            'hsb-cart-page-002'=>array('سبد خرید · زمینه · cart-sidebar-card (2)','#cfcfcf'),
            'hsb-cart-page-003'=>array('سبد خرید · حاشیه · cart-sidebar-card__coupon input (3)','#b08d57'),
            'hsb-cart-page-004'=>array('سبد خرید · زمینه · cart-sidebar-card__summary (4)','#ececec'),
            'hsb-cart-page-005'=>array('سبد خرید · زمینه · cart-sidebar-card__divider (5)','#bdbdbd'),
            'hsb-cart-page-006'=>array('سبد خرید · رنگ · cart-page-items__table-head (6)','#d89a00'),
            'hsb-cart-page-007'=>array('سبد خرید · رنگ · cart-page-items__row (7)','#d89a00'),
            'hsb-cart-page-008'=>array('سبد خرید · حاشیه · cart-page-items__product-image (8)','#cfcfcf'),
            'hsb-cart-page-009'=>array('سبد خرید · زمینه · cart-page-items__product-image (9)','#f7f7f7'),
            'hsb-cart-page-010'=>array('سبد خرید · حاشیه · cart-page-items__qty-btn (10)','#8a8a8a'),
            'hsb-cart-page-011'=>array('سبد خرید · حاشیه · cart-page-items__row (11)','#d8d8d8'),
            'hsb-cart-page-012'=>array('سبد خرید · حاشیه · cart-page-items__quantity qty (12)','#d0d0d0'),
            'hsb-cart-page-013'=>array('سبد خرید · زمینه · cart-page-items__empty (13)','#ffffff'),
            'hsb-cart-page-014'=>array('سبد خرید · حاشیه · cart-page-items__empty (14)','#e5e5e5'),
            'hsb-cart-page-015'=>array('سبد خرید · زمینه · hsb-preinvoice-empty__icon-wrap (15)','#2f353b'),
            'hsb-cart-page-016'=>array('سبد خرید · زمینه · hsb-preinvoice-empty__icon-wrap (16)','#171b1f'),
            'hsb-cart-page-017'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__icon-wrap (17)','#ffffff'),
            'hsb-cart-page-018'=>array('سبد خرید · حاشیه · hsb-preinvoice-empty__icon-dot (18)','#ffffff'),
            'hsb-cart-page-019'=>array('سبد خرید · زمینه · hsb-preinvoice-empty__icon-dot (19)','#9d7e4e'),
            'hsb-cart-page-020'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__icon-dot (20)','#ffffff'),
            'hsb-cart-page-021'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__label (21)','#866a3f'),
            'hsb-cart-page-022'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__content h2 (22)','#20262c'),
            'hsb-cart-page-023'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__content p (23)','#687078'),
            'hsb-cart-page-024'=>array('سبد خرید · زمینه · hsb-preinvoice-empty__step   span (24)','#ffffff'),
            'hsb-cart-page-025'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__step   span (25)','#8b6e43'),
            'hsb-cart-page-026'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__step strong (26)','#2a3035'),
            'hsb-cart-page-027'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__step small (27)','#80868c'),
            'hsb-cart-page-028'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__step-arrow (28)','#b9bdc1'),
            'hsb-cart-page-029'=>array('سبد خرید · زمینه · hsb-preinvoice-empty__primary (29)','#8f7042'),
            'hsb-cart-page-030'=>array('سبد خرید · زمینه · hsb-preinvoice-empty__primary (30)','#71552f'),
            'hsb-cart-page-031'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__primary (31)','#ffffff'),
            'hsb-cart-page-032'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__hint (32)','#858b90'),
            'hsb-cart-page-033'=>array('سبد خرید · متن/آیکون · hsb-preinvoice-empty__hint i (33)','#9d7e4e'),
            'hsb-cart-page-034'=>array('سبد خرید · زمینه · preinvoice-page cart-page-items__quantity quantity (34)','#f7f8f8'),
            'hsb-cart-page-035'=>array('سبد خرید · متن/آیکون · preinvoice-page cart-page-items__qty-btn (35)','#31363b'),
            'hsb-cart-page-036'=>array('سبد خرید · متن/آیکون · preinvoice-page cart-page-items__qty-btn hover (36)','#795a2d'),
            'hsb-cart-page-037'=>array('سبد خرید · زمینه · preinvoice-page cart-page-items__quantity qty (37)','#ffffff'),
            'hsb-cart-page-038'=>array('سبد خرید · متن/آیکون · preinvoice-page cart-page-items__quantity qty (38)','#20252a'),
            'hsb-cart-page-039'=>array('سبد خرید · متن/آیکون · preinvoice-products-head__eyebrow (39)','#9d7e4e'),
            'hsb-cart-page-040'=>array('سبد خرید · متن/آیکون · preinvoice-products-head h2 (40)','#22282d'),
            'hsb-cart-page-041'=>array('سبد خرید · متن/آیکون · preinvoice-products-head__count (41)','#7c6037'),
            'hsb-cart-page-042'=>array('سبد خرید · زمینه · preinvoice-product-card (42)','#ffffff'),
            'hsb-cart-page-043'=>array('سبد خرید · متن/آیکون · preinvoice-product-card__remove (43)','#a0a5a9'),
            'hsb-cart-page-044'=>array('سبد خرید · متن/آیکون · preinvoice-product-card__remove hover (44)','#c93737'),
            'hsb-cart-page-045'=>array('سبد خرید · زمینه · preinvoice-product-card__image (45)','#f8f9f9'),
            'hsb-cart-page-046'=>array('سبد خرید · متن/آیکون · preinvoice-primary-badge (46)','#555d64'),
            'hsb-cart-page-047'=>array('سبد خرید · متن/آیکون · preinvoice-required-badge (47)','#806138'),
            'hsb-cart-page-048'=>array('سبد خرید · متن/آیکون · preinvoice-product-card__title (48)','#22282d'),
            'hsb-cart-page-049'=>array('سبد خرید · متن/آیکون · preinvoice-product-card__title a hover (49)','#7f6239'),
            'hsb-cart-page-050'=>array('سبد خرید · زمینه · preinvoice-product-code (50)','#f5f6f7'),
            'hsb-cart-page-051'=>array('سبد خرید · متن/آیکون · preinvoice-product-code span (51)','#8a9095'),
            'hsb-cart-page-052'=>array('سبد خرید · متن/آیکون · preinvoice-product-code strong (52)','#343a40'),
            'hsb-cart-page-053'=>array('سبد خرید · زمینه · preinvoice-product-spec (53)','#f8f9f9'),
            'hsb-cart-page-054'=>array('سبد خرید · زمینه · preinvoice-product-spec__icon (54)','#ffffff'),
            'hsb-cart-page-055'=>array('سبد خرید · متن/آیکون · preinvoice-product-spec__icon (55)','#967747'),
            'hsb-cart-page-056'=>array('سبد خرید · متن/آیکون · preinvoice-product-spec small (56)','#92979c'),
            'hsb-cart-page-057'=>array('سبد خرید · متن/آیکون · preinvoice-product-spec strong (57)','#30363b'),
            'hsb-cart-page-058'=>array('سبد خرید · متن/آیکون · preinvoice-product-card__qty-label (58)','#6d747a'),
            'hsb-cart-page-059'=>array('سبد خرید · متن/آیکون · preinvoice-product-card__details-link (59)','#87683b'),
        )),
        'cart_popup' => array('title'=>'پنجره‌ها و شمارنده‌ها', 'desc'=>'رنگ‌های پنجره افزودن به سبد، پیام موفقیت، شمارنده و کنترل‌های مربوط.', 'fields'=>array(
            'hsb-sale-neutral-border'=>array('توکن موجود · sale neutral border','#dddddd'),
            'hsb-quote-popup-border'=>array('توکن موجود · quote popup border','#e5d8c7'),
            'hsb-quote-popup-text'=>array('توکن موجود · quote popup text','#32291e'),
            'hsb-cart-popup-muted-border'=>array('توکن موجود · cart popup muted border','#b8b8b8'),
            'hsb-cart-popup-secondary-border'=>array('توکن موجود · cart popup secondary border','#bdbdbd'),
            'hsb-cart-popup-light-border'=>array('توکن موجود · cart popup light border','#dcdcdc'),
            'hsb-cart-popup-success-border'=>array('توکن موجود · cart popup success border','#b8d8c2'),
            'hsb-cart-popup-success-accent'=>array('توکن موجود · cart popup success accent','#18a957'),
            'hsb-cart-popup-light-surface'=>array('توکن موجود · cart popup light surface','#f3f3f3'),
            'hsb-cart-popup-divider'=>array('توکن موجود · cart popup divider','#d0d0d0'),
            'hsb-cart-popup-001'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__product (1)','#f7f8f7'),
            'hsb-cart-popup-002'=>array('پنجره افزودن به سبد · رنگ · cartAjaxPopup cart-ajax-popup__product (2)','#e5e9e7'),
            'hsb-cart-popup-003'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__product-image (3)','#ffffff'),
            'hsb-cart-popup-004'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__summary (4)','#ffffff'),
            'hsb-cart-popup-005'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__notice (5)','#e8f4ed'),
            'hsb-cart-popup-006'=>array('پنجره افزودن به سبد · حاشیه · cartAjaxPopup cart-ajax-popup__notice (6)','#cbe7d5'),
            'hsb-cart-popup-007'=>array('پنجره افزودن به سبد · متن/آیکون · cartAjaxPopup cart-ajax-popup__summary-title  before (7)','#138353'),
            'hsb-cart-popup-008'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__summary-title  before (8)','#e4f5eb'),
            'hsb-cart-popup-009'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__product-text (9)','#f6f7f7'),
            'hsb-cart-popup-010'=>array('پنجره افزودن به سبد · حاشیه · cartAjaxPopup cart-ajax-popup__product-text (10)','#eceeed'),
            'hsb-cart-popup-011'=>array('پنجره افزودن به سبد · زمینه · cartAjaxPopup cart-ajax-popup__prices (11)','#f7f8f7'),
            'hsb-cart-popup-012'=>array('پنجره افزودن به سبد · رنگ · cartAjaxPopup cart-ajax-popup__product (12)','#e5e9e7'),
            'hsb-cart-count-001'=>array('شمارنده سبد · متن/آیکون · hsb-direct-header-badge (1)','#ffffff'),
            'hsb-cart-count-002'=>array('شمارنده سبد · حاشیه · hsb-dual-cart-tabs hsb-tab-count (2)','#ffffff'),
            'hsb-cart-count-003'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs hsb-tab-count (3)','#168052'),
            'hsb-cart-count-004'=>array('شمارنده سبد · متن/آیکون · hsb-dual-cart-tabs hsb-tab-count (4)','#ffffff'),
            'hsb-cart-count-005'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs a not(is-active) hsb-tab-count (5)','#735936'),
            'hsb-cart-count-006'=>array('شمارنده سبد · حاشیه · hsb-direct-header-link hsb-direct-header-badge (6)','#ffffff'),
            'hsb-cart-count-007'=>array('شمارنده سبد · متن/آیکون · hsb-direct-header-link hsb-direct-header-badge (7)','#ffffff'),
            'hsb-cart-count-008'=>array('شمارنده سبد · حاشیه · hsb-dual-cart-tabs hsb-tab-count (8)','#ffffff'),
            'hsb-cart-count-009'=>array('شمارنده سبد · متن/آیکون · hsb-dual-cart-tabs hsb-tab-count (9)','#ffffff'),
            'hsb-cart-count-010'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs [data-hsb-count="direct"] (10)','#168052'),
            'hsb-cart-count-011'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs [data-hsb-count="quote"] (11)','#e53935'),
            'hsb-cart-count-012'=>array('شمارنده سبد · حاشیه · hsb-direct-header-link hsb-direct-header-badge (12)','#ffffff'),
            'hsb-cart-count-013'=>array('شمارنده سبد · متن/آیکون · hsb-direct-header-link hsb-direct-header-badge (13)','#ffffff'),
            'hsb-cart-count-014'=>array('شمارنده سبد · حاشیه · hsb-dual-cart-tabs hsb-tab-count (14)','#ffffff'),
            'hsb-cart-count-015'=>array('شمارنده سبد · متن/آیکون · hsb-dual-cart-tabs hsb-tab-count (15)','#ffffff'),
            'hsb-cart-count-016'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs [data-hsb-count="direct"] (16)','#168052'),
            'hsb-cart-count-017'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs [data-hsb-count="quote"] (17)','#e53935'),
            'hsb-cart-count-018'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs a[aria-current="page"] [data-hsb-count="direc (18)','#168052'),
            'hsb-cart-count-019'=>array('شمارنده سبد · متن/آیکون · hsb-dual-cart-tabs a[aria-current="page"] [data-hsb-count="direc (19)','#ffffff'),
            'hsb-cart-count-020'=>array('شمارنده سبد · زمینه · hsb-dual-cart-tabs a[aria-current="page"] [data-hsb-count="quote (20)','#e53935'),
            'hsb-cart-count-021'=>array('شمارنده سبد · متن/آیکون · hsb-dual-cart-tabs a[aria-current="page"] [data-hsb-count="quote (21)','#ffffff'),
        )),
        'content' => array('title'=>'محتوا و جزئیات', 'desc'=>'فیلدها، نظرات و حاشیه جدول‌ها', 'fields'=>array(
            'hsb-review-surface'=>array('زمینه بخش نظرات','#ffffff'),
            'hsb-review-text-strong'=>array('عنوان نظرات','#111827'),
            'hsb-review-star'=>array('رنگ ستاره‌ها','#f59e0b'),
            'hsb-review-focus'=>array('فوکوس فرم','#2563eb'),
            'hsb-sp-description-table-border'=>array('حاشیه جدول توضیحات','#e5d9cb'),
        )),
    );
}

function hsb_ds_allowed_tokens() {
    $result = array();
    foreach (hsb_ds_catalog() as $group) {
        foreach ($group['fields'] as $token => $meta) { $result[$token] = $meta; }
    }
    return $result;
}

function hsb_ds_clean_settings($candidate) {
    $clean = array();
    if (!is_array($candidate)) { return $clean; }
    $allowed = hsb_ds_allowed_tokens();
    foreach ($allowed as $token => $meta) {
        if (empty($candidate[$token]) || !is_array($candidate[$token])) { continue; }
        $entry = $candidate[$token];
        $mode = isset($entry['mode']) && is_string($entry['mode']) ? $entry['mode'] : 'default';
        if (!in_array($mode, array('default','custom','primary','secondary'), true)) { continue; }
        if (in_array($token, array('color-primary','color-secondary'), true) && in_array($mode, array('primary','secondary'), true)) { continue; }
        if ($mode === 'default') { continue; }
        if ($mode === 'custom') {
            $color = isset($entry['color']) && is_string($entry['color']) ? sanitize_hex_color($entry['color']) : null;
            if (!$color) { continue; }
            $clean[$token] = array('mode'=>'custom','color'=>$color);
        } else {
            $clean[$token] = array('mode'=>$mode);
        }
    }
    return $clean;
}

function hsb_ds_output_css() {
    $settings = hsb_ds_clean_settings(get_option('hsb_ds_color_overrides', array()));
    if (!$settings) { return; }
    $declarations = array();
    foreach ($settings as $token => $entry) {
        if ($entry['mode'] === 'custom') { $value = $entry['color']; }
        elseif ($entry['mode'] === 'primary') { $value = 'var(--color-primary)'; }
        elseif ($entry['mode'] === 'secondary') { $value = 'var(--color-secondary)'; }
        else { continue; }
        $declarations[] = '--' . $token . ':' . $value . ';';
    }
    if ($declarations) {
        echo "\n<style id=\"hsb-ds-color-overrides\">:root{" . implode('', $declarations) . "}</style>\n";
    }
}
add_action('wp_head', 'hsb_ds_output_css', 999);

function hsb_ds_menu() {
    add_theme_page('HSB Design System', 'ویرایش بصری رنگ‌ها', 'manage_options', 'hsb-design-system', 'hsb_ds_admin_page');
}
add_action('admin_menu', 'hsb_ds_menu');

function hsb_ds_admin_enqueue($hook) {
    if ($hook !== 'appearance_page_hsb-design-system') { return; }
    $uri = get_template_directory_uri();
    $dir = get_template_directory();
    wp_enqueue_style('hsb-ds-admin', $uri . '/assets/css/hsb-design-system-admin.css', array(), filemtime($dir . '/assets/css/hsb-design-system-admin.css'));
    wp_enqueue_script('hsb-ds-admin', $uri . '/assets/js/hsb-design-system-admin.js', array(), filemtime($dir . '/assets/js/hsb-design-system-admin.js'), true);
}
add_action('admin_enqueue_scripts', 'hsb_ds_admin_enqueue');

function hsb_ds_admin_page() {
    if (!current_user_can('manage_options')) { wp_die(esc_html__('Access denied.', 'hypersanati')); }
    $notice = '';
    if (isset($_POST['hsb_ds_action'])) {
        check_admin_referer('hsb_ds_save_colors');
        $action = sanitize_key(wp_unslash($_POST['hsb_ds_action']));
        if ($action === 'reset') {
            delete_option('hsb_ds_color_overrides');
            $notice = 'تمام تغییرات پنل حذف شد؛ رنگ‌های اصلی CSS دوباره اعمال می‌شوند.';
        } elseif ($action === 'save') {
            $raw = isset($_POST['hsb_ds']) ? wp_unslash($_POST['hsb_ds']) : array();
            $clean = hsb_ds_clean_settings($raw);
            update_option('hsb_ds_color_overrides', $clean, false);
            $notice = 'تغییرات رنگ ذخیره شد.';
        }
    }
    $saved = hsb_ds_clean_settings(get_option('hsb_ds_color_overrides', array()));
    $catalog = hsb_ds_catalog();
    ?>
    <div class="wrap hsb-ds-app" dir="rtl">
      <h1>HSB Design System <small>ویرایش بصری رنگ‌های سایت</small></h1>
      <p class="description">در این صفحه ابتدا تغییرات را در نمونه‌ها می‌بینید و سپس با «ذخیره رنگ‌ها» در سایت اعمال می‌کنید. گزینه «پیش‌فرض» یعنی همان رنگی که اکنون در فایل CSS تعریف شده است.</p>
      <?php if ($notice) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html($notice); ?></p></div><?php endif; ?>
      <div class="hsb-ds-shell">
        <nav class="hsb-ds-nav" aria-label="بخش‌های طراحی">
          <?php $first = true; foreach ($catalog as $slug => $group) : ?>
            <button type="button" class="hsb-ds-tab <?php echo $first ? 'is-active' : ''; ?>" data-hsb-tab="<?php echo esc_attr($slug); ?>" aria-selected="<?php echo $first ? 'true':'false'; ?>"><?php echo esc_html($group['title']); ?></button>
          <?php $first = false; endforeach; ?>
        </nav>
        <form method="post" class="hsb-ds-form" id="hsb-ds-form">
          <?php wp_nonce_field('hsb_ds_save_colors'); ?>
          <input type="hidden" name="hsb_ds_action" value="save" id="hsb-ds-action">
          <?php $first = true; foreach ($catalog as $slug => $group) : ?>
          <section class="hsb-ds-section <?php echo $first ? 'is-active':''; ?>" data-hsb-panel="<?php echo esc_attr($slug); ?>">
            <div class="hsb-ds-editor">
              <h2><?php echo esc_html($group['title']); ?></h2><p><?php echo esc_html($group['desc']); ?></p>
              <button type="button" class="button button-secondary hsb-ds-reset-group" data-reset-group="<?php echo esc_attr($slug); ?>">بازنشانی این بخش به UI Kit اصلی</button>
              <p class="hsb-ds-reset-note">فقط رنگ‌های همین بخش در پیش‌نمایش بازنشانی می‌شوند؛ برای اعمال در سایت، «ذخیره رنگ‌ها» را بزنید.</p>
              <?php foreach ($group['fields'] as $token => $meta) :
                 $entry = isset($saved[$token]) ? $saved[$token] : array('mode'=>'default');
                 $mode = $entry['mode'];
                 $value = isset($entry['color']) ? $entry['color'] : $meta[1];
                 $is_core = in_array($token, array('color-primary','color-secondary'), true);
              ?>
              <div class="hsb-ds-field" data-token="<?php echo esc_attr($token); ?>" data-default="<?php echo esc_attr($meta[1]); ?>">
                <label class="hsb-ds-name" for="hsb-ds-mode-<?php echo esc_attr($token); ?>"><?php echo esc_html($meta[0]); ?></label>
                <div class="hsb-ds-controls">
                  <select id="hsb-ds-mode-<?php echo esc_attr($token); ?>" name="hsb_ds[<?php echo esc_attr($token); ?>][mode]" class="hsb-ds-mode">
                    <?php foreach (array('default'=>'پیش‌فرض', 'custom'=>'رنگ مستقل', 'primary'=>'ارث‌بری از اصلی', 'secondary'=>'ارث‌بری از ثانویه') as $key => $label) : if ($is_core && in_array($key, array('primary','secondary'), true)) { continue; } ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($mode, $key); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                  </select>
                  <input type="color" name="hsb_ds[<?php echo esc_attr($token); ?>][color]" class="hsb-ds-color" value="<?php echo esc_attr($value); ?>" aria-label="انتخاب بصری رنگ <?php echo esc_attr($meta[0]); ?>">
                  <input type="text" class="hsb-ds-hex" value="<?php echo esc_attr($value); ?>" maxlength="7" inputmode="text" spellcheck="false" autocomplete="off" dir="ltr" aria-label="کد HEX رنگ <?php echo esc_attr($meta[0]); ?>" title="کد رنگ مانند #168052" placeholder="#168052">
                </div>
                <code>--<?php echo esc_html($token); ?></code>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="hsb-ds-preview" data-preview="<?php echo esc_attr($slug); ?>">
              <div class="hsb-ds-preview-caption">پیش‌نمایش زنده — قالب واقعی برای هدر، فوتر و کارت‌ها</div>
              <?php if (in_array($slug, array('header','footer','card'), true)) : ?>
                <div class="hsb-ds-real-preview-toolbar">
                  <strong><?php echo esc_html($slug === 'card' ? 'دو وضعیت واقعی محصول' : 'قالب واقعی سایت'); ?></strong>
                  <button type="button" class="button hsb-ds-reload-preview">بارگذاری مجدد پیش‌نمایش</button>
                </div>
                <iframe class="hsb-ds-real-frame" title="<?php echo esc_attr('پیش‌نمایش واقعی ' . $group['title']); ?>" data-hsb-frame="<?php echo esc_attr($slug); ?>" data-src="<?php echo esc_url(add_query_arg(array('hsb_ds_real_preview'=>$slug, '_wpnonce'=>wp_create_nonce('hsb_ds_real_preview')), home_url('/'))); ?>" loading="lazy" referrerpolicy="same-origin"></iframe>
                <p class="hsb-ds-preview-note">این بخش از قالب واقعی و CSS سایت استفاده می‌کند. ویرایش رنگ فقط در پیش‌نمایش اعمال می‌شود و پس از ذخیره در سایت منتشر خواهد شد.</p>
                <?php else : ?><?php hsb_ds_render_preview($slug); ?><?php endif; ?>
              <p class="hsb-ds-preview-note">تغییرات این پیش‌نمایش تا زمان ذخیره، روی سایت منتشر نمی‌شوند.</p>
            </div>
          </section>
          <?php $first = false; endforeach; ?>
          <div class="hsb-ds-actions">
            <button type="submit" class="button button-primary button-large">ذخیره رنگ‌ها</button>
            <button type="button" class="button button-secondary" id="hsb-ds-reset">بازگشت تمام رنگ‌ها به پیش‌فرض</button>
            <span class="hsb-ds-unsaved" aria-live="polite"></span>
          </div>
        </form>
      </div>
    </div>
    <?php
}

function hsb_ds_render_preview($type) {
    if ($type === 'brand') { ?>
      <div class="hsb-p-brand"><div class="hsb-p-brand-primary">رنگ اصلی</div><div class="hsb-p-brand-secondary">رنگ ثانویه</div><button class="hsb-p-brand-hover" type="button">نمونه هاور</button></div>
    <?php } elseif ($type === 'header') { ?>
      <div class="hsb-p-header"><div class="hsb-p-header-top">صنعت گستران الفت | تماس با ما</div><div class="hsb-p-header-main"><b>الفت</b><span>خانه</span><span>فروشگاه</span><span class="hsb-p-active">درباره ما</span><span class="hsb-p-header-icon">♧<i></i></span></div></div>
    <?php } elseif ($type === 'footer') { ?>
      <div class="hsb-p-footer"><div class="hsb-p-services"><strong>خدمات صنعت گستران الفت</strong><p>مشاوره تخصصی · تأمین قطعات · خدمات صنعتی</p></div><div class="hsb-p-footer-content"><strong>صنعت گستران الفت</strong><p>تأمین محصولات و قطعات صنعتی</p><div class="hsb-p-footer-contact">راه‌های ارتباط با ما</div><p>فروشگاه &nbsp; | &nbsp; درباره ما &nbsp; | &nbsp; تماس</p></div><div class="hsb-p-footer-bottom">تمام حقوق محفوظ است.</div></div>
    <?php } elseif ($type === 'card') { ?>
      <div class="hsb-p-card"><div class="hsb-p-card-img"><span class="hsb-p-badge">۳ گزینه</span><span class="hsb-p-stars">★★★★★</span><span class="hsb-p-bearing">◎</span></div><div class="hsb-p-card-content"><div class="hsb-p-part">شماره محصول <b>QJ309M</b></div><h3>بلبرینگ تماس چهار نقطه‌ای KG</h3><div class="hsb-p-specs"><span>قطر داخلی<br><b>45 mm</b></span><span>قطر خارجی<br><b>100 mm</b></span><span>عرض<br><b>25 mm</b></span></div><div class="hsb-p-meta">۳ گزینه &nbsp; | &nbsp; ۲ برند &nbsp; | &nbsp; ۲ کشور</div><div class="hsb-p-inquiry">قیمت نیازمند استعلام</div><button type="button" class="hsb-p-quote">بررسی گزینه‌ها</button></div></div>
    <?php } elseif ($type === 'sales') { ?>
      <div class="hsb-p-sales"><button class="hsb-p-direct" type="button">افزودن به سبد خرید مستقیم</button><button class="hsb-p-quote" type="button">افزودن به پیش‌فاکتور</button><div class="hsb-p-inquiry">قیمت نیازمند استعلام</div><div class="hsb-p-popup">نمونه زمینه پنجره پیش‌فاکتور</div></div>
    <?php } elseif ($type === 'cart_page') { ?>
      <div class="hsb-p-cart-demo" dir="rtl">
        <div class="hsb-p-cart-tabs"><span>سبد خرید آنلاین</span><span>سبد پیش‌فاکتور</span></div>
        <div class="hsb-p-cart-demo-grid"><div class="hsb-p-cart-demo-item"><strong>نمونه محصول صنعتی</strong><small>تعداد: ۲ · قابل خرید</small><span>قیمت: ۲٬۵۰۰٬۰۰۰ تومان</span></div><div class="hsb-p-cart-demo-item"><strong>محصول استعلامی</strong><small>در انتظار استعلام</small><span>ثبت درخواست پیش‌فاکتور</span></div></div>
        <div class="hsb-p-cart-summary"><span>جمع کل خرید</span><strong>۵٬۰۰۰٬۰۰۰ تومان</strong></div>
      </div>
    <?php } elseif ($type === 'cart_popup') { ?>
      <div class="hsb-p-popup-demo" dir="rtl"><div class="hsb-p-popup-demo-header">✓ محصول به سبد خرید اضافه شد</div><p>نمونه اعلان خرید مستقیم / پیش‌فاکتور</p><div class="hsb-p-popup-demo-btn">مشاهده سبد خرید</div></div>
    <?php } else { ?>
      <div class="hsb-p-content"><h3>نظرات کاربران ★★★★☆</h3><p>نمونه متن و جزئیات نظر درباره محصول</p><input type="text" placeholder="نظر خود را بنویسید" aria-label="نمونه فیلد نظر"><div class="hsb-p-table">مشخصات فنی محصول</div></div>
    <?php }
}


/* HSB REAL-THEME PREVIEW V4 — read-only, admin-only, nonce protected. */
add_action('template_redirect', 'hsb_ds_real_preview_route', 0);
function hsb_ds_real_preview_route() {
    if (!isset($_GET['hsb_ds_real_preview'])) { return; }
    $kind = sanitize_key(wp_unslash($_GET['hsb_ds_real_preview']));
    if (!in_array($kind, array('header','footer','card'), true)) {
        status_header(404); exit;
    }
    if (!is_user_logged_in() || !current_user_can('manage_options') ||
        !isset($_GET['_wpnonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'hsb_ds_real_preview')) {
        status_header(403); exit;
    }
    nocache_headers();
    header('X-Robots-Tag: noindex, nofollow', true);

    // Preview must reflect the live CSS defaults and *only* the editor's unsaved state.
    remove_action('wp_head', 'hsb_ds_output_css', 999);
    add_action('wp_head', static function () use ($kind) {
        $kind_class = 'hsb-ds-real-' . esc_attr($kind);
        echo '<style id="hsb-real-preview-scope">';
        echo 'html,body{margin:0!important;min-height:0!important;overflow-x:hidden!important}';
        echo 'body{background:#fff!important}';
        echo '.hsb-ds-real-stage{box-sizing:border-box}';
        if ($kind === 'header') {
            echo '.site-footer,.my-foot,.hsb-ds-real-stage{display:none!important}';
        } elseif ($kind === 'footer') {
            echo '.header,.hsb-ds-real-stage{display:none!important}';
        } else {
            echo '.header,.site-footer,.my-foot{display:none!important}';
            echo '.hsb-ds-real-stage{padding:18px;background:#f4f5f6;min-height:530px}';
            echo '.hsb-ds-card-grid{display:grid;grid-template-columns:repeat(2,minmax(230px,1fr));align-items:start;gap:18px;max-width:750px;margin:auto}';
            echo '.hsb-ds-card-slot{min-width:0}.hsb-ds-card-slot>h2{font-size:15px;margin:0 0 12px;color:#293036}';
            echo '.hsb-ds-card-slot .hsb-family-product-card{width:100%!important;max-width:360px!important;margin:auto!important}';
            echo '.hsb-ds-preview-empty{padding:18px;background:white;border:1px dashed #aaa;border-radius:12px;color:#555}';
            echo '@media(max-width:570px){.hsb-ds-card-grid{grid-template-columns:1fr}}';
        }
        echo '</style>';
    }, 9998);

    get_header();
    echo '<main class="hsb-ds-real-stage" aria-label="پیش‌نمایش رنگ‌ها">';
    if ($kind === 'card') { hsb_ds_real_product_cards(); }
    echo '</main>';
    // Only a CSS-variable preview. All links/buttons are inert inside the preview.
    ?>
    <script>
    (function () {
      'use strict';
      var allowed = <?php echo wp_json_encode(array_keys(hsb_ds_allowed_tokens())); ?>;
      var known = new Set(allowed);
      document.addEventListener('click', function(e) {
        if (e.target.closest('a,button,input[type="submit"]')) { e.preventDefault(); e.stopImmediatePropagation(); }
      }, true);
      document.addEventListener('submit', function(e) { e.preventDefault(); e.stopImmediatePropagation(); }, true);
      window.addEventListener('message', function(event) {
        if (event.origin !== window.location.origin || !event.data || event.data.type !== 'hsb-ds-preview-colors') { return; }
        var entries = event.data.values || {};
        known.forEach(function(token) {
          var value = entries[token];
          if (typeof value === 'string' && (/^#[0-9a-fA-F]{6}$/.test(value) || value === 'var(--color-primary)' || value === 'var(--color-secondary)')) {
            document.documentElement.style.setProperty('--' + token, value);
          } else { document.documentElement.style.removeProperty('--' + token); }
        });
      });
      window.parent.postMessage({type:'hsb-ds-preview-ready'},window.location.origin);
      function sendHeight() {
        var section = <?php echo wp_json_encode($kind); ?>;
        var node = section === 'footer' ? document.querySelector('footer') :
                   section === 'header' ? document.querySelector('.header') : document.querySelector('.hsb-ds-real-stage');
        if (!node) return;
        var height = Math.ceil(node.getBoundingClientRect().height + 24);
        window.parent.postMessage({type:'hsb-ds-preview-height', height:height}, window.location.origin);
      }
      window.addEventListener('load',sendHeight);
      if (window.ResizeObserver) {
        var target = <?php echo wp_json_encode($kind); ?> === 'footer' ? document.querySelector('footer') :
                     <?php echo wp_json_encode($kind); ?> === 'header' ? document.querySelector('.header') : document.querySelector('.hsb-ds-real-stage');
        if(target) new ResizeObserver(sendHeight).observe(target);
      }
      sendHeight();
    })();
    </script>
    <?php
    get_footer();
    exit;
}

function hsb_ds_real_card_is_single_variant($product_id) {
    // The shop card suppresses the actual price for multi-variant families.
    // Validate the card family, not just the WooCommerce price field.
    if (!function_exists('hsb_get_product_family_ids') ||
        !function_exists('hsb_group_product_ids_by_part_number')) {
        return true;
    }
    $family_ids = hsb_get_product_family_ids($product_id);
    $families = hsb_group_product_ids_by_part_number($family_ids);
    if (!empty($families[0])) {
        return max(1, absint($families[0]['version_count'] ?? 1)) === 1;
    }
    return true;
}

function hsb_ds_real_product_cards() {
    echo '<div class="hsb-ds-card-grid">';
    if (!function_exists('wc_get_products') ||
        !function_exists('hypersanati_render_product_card') ||
        !function_exists('hsb_dual_is_direct')) {
        echo '<p class="hsb-ds-preview-empty">کامپوننت کارت یا تابع تشخیص فروش مستقیم در دسترس نیست.</p></div>';
        return;
    }

    $direct = 0;
    $other = 0;
    $scanned = 0;
    // Search beyond the most recent 80. This runs only when the card tab's
    // preview iframe is opened, never on ordinary storefront requests.
    for ($page = 1; $page <= 30 && (!$direct || !$other); ++$page) {
        $ids = wc_get_products(array(
            'status'  => 'publish',
            'limit'   => 100,
            'page'    => $page,
            'orderby' => 'ID',
            'order'   => 'DESC',
            'return'  => 'ids',
        ));
        if (empty($ids)) { break; }
        foreach ($ids as $id) {
            ++$scanned;
            $product = wc_get_product($id);
            if (!$product) { continue; }
            if (!$direct && hsb_dual_is_direct($product) &&
                hsb_ds_real_card_is_single_variant($id)) {
                $direct = (int) $id;
            }
            if (!$other && !hsb_dual_is_direct($product)) {
                $other = (int) $id;
            }
            if ($direct && $other) { break; }
        }
        if (count($ids) < 100) { break; }
    }

    $slots = array(
        array('title' => 'محصول قیمت‌دار / خرید آنلاین', 'id' => $direct),
        array('title' => 'محصول استعلامی یا ناموجود', 'id' => $other),
    );
    foreach ($slots as $slot) {
        echo '<section class="hsb-ds-card-slot"><h2>' . esc_html($slot['title']) . '</h2>';
        if ($slot['id']) {
            hypersanati_render_product_card($slot['id']);
        } else {
            $hint = $slot['title'] === $slots[0]['title']
                ? 'محصول قیمت‌دار، قابل خرید و تک‌گزینه‌ای'
                : 'محصول استعلامی یا ناموجود';
            echo '<div class="hsb-ds-preview-empty">' . esc_html(
                'در ' . number_format_i18n($scanned) . ' محصول بررسی‌شده، ' .
                $hint . ' پیدا نشد. برای حفظ صحت پیش‌نمایش، قیمت یا محصول ساختگی نمایش داده نمی‌شود.'
            ) . '</div>';
        }
        echo '</section>';
    }
    echo '</div>';
}
