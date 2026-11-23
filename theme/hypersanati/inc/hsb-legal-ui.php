<?php
/**
 * Legal pages UI — presentation only. Does not publish pages or alter their contents.
 */
if (!defined('ABSPATH')) { exit; }

function hsb_legal_ui_is_target() {
    if (!is_page()) { return false; }
    $id = get_queried_object_id();
    $slug = get_post_field('post_name', $id);
    return in_array((int) $id, array(11648, 11649), true)
        || in_array($slug, array('privacy-policy', 'terms-and-conditions'), true);
}

function hsb_legal_ui_kind() {
    $id = get_queried_object_id();
    $slug = get_post_field('post_name', $id);
    return ((int) $id === 11648 || $slug === 'privacy-policy') ? 'privacy' : 'terms';
}

add_action('wp_enqueue_scripts', function () {
    if (!hsb_legal_ui_is_target()) { return; }
    $base = get_template_directory();
    $uri = get_template_directory_uri();
    $css = '/assets/css/hsb-legal-ui.css';
    $js = '/assets/js/hsb-legal-ui.js';
    wp_enqueue_style('hsb-legal-ui', $uri . $css, array(), (string) filemtime($base . $css));
    wp_enqueue_script('hsb-legal-ui', $uri . $js, array(), (string) filemtime($base . $js), true);
});

add_filter('body_class', function ($classes) {
    if (hsb_legal_ui_is_target()) { $classes[] = 'hsb-legal-page'; }
    return $classes;
});

add_filter('the_content', function ($content) {
    if (is_admin() || !is_main_query() || !in_the_loop() || !hsb_legal_ui_is_target()) { return $content; }
    $kind = hsb_legal_ui_kind();
    $title = $kind === 'privacy' ? 'سیاست حفظ حریم خصوصی' : 'شرایط و مقررات خرید';
    $eyebrow = $kind === 'privacy' ? 'شفافیت در نگهداری اطلاعات' : 'راهنمای حقوق و تعهدات خریدار و فروشگاه';
    $intro = $kind === 'privacy'
      ? 'در این صفحه می‌توانید درباره اطلاعات دریافتی، اهداف پردازش و شیوه پیگیری درخواست‌های مرتبط با حریم خصوصی بخوانید.'
      : 'پیش از ثبت سفارش یا درخواست پیش‌فاکتور، روش خرید، پرداخت، ارسال و شرایط بررسی بازگشت کالا را مطالعه کنید.';
    $icon = $kind === 'privacy' ? '◈' : '§';
    $html = '<div class="hsb-legal" dir="rtl" data-kind="' . esc_attr($kind) . '">';
    $html .= '<div class="hsb-legal__breadcrumbs"><a href="' . esc_url(home_url('/')) . '">صفحه اصلی</a><span aria-hidden="true">‹</span><span>' . esc_html($title) . '</span></div>';
    $html .= '<header class="hsb-legal__hero"><span class="hsb-legal__hero-mark" aria-hidden="true">' . esc_html($icon) . '</span><div><p class="hsb-legal__eyebrow">' . esc_html($eyebrow) . '</p><h1>' . esc_html($title) . '</h1><p class="hsb-legal__intro">' . esc_html($intro) . '</p><div class="hsb-legal__review-slot" id="hsb-legal-review-slot"></div></div></header>';
    $html .= '<section class="hsb-legal__overview" aria-label="راهنمای مطالعه"><div class="hsb-legal__overview-head"><span class="hsb-legal__pill">راهنمای کاربردی فروشگاه</span><h2>پیش از ثبت سفارش این صفحه را مطالعه کنید</h2><p>اطلاعات این صفحه را برای آگاهی از روند خرید، مسئولیت‌ها و شیوه پیگیری در اختیار دارید.</p></div><div class="hsb-legal__highlights">';
    $cards = $kind === 'privacy'
      ? array(array('01','اطلاعات حساب کاربری','داده‌های مورد نیاز برای ثبت و پیگیری سفارش'),array('02','پرداخت و امنیت','نحوه پردازش اطلاعات و تراکنش‌ها'),array('03','پیگیری درخواست‌ها','مسیرهای ارتباط و درخواست بررسی اطلاعات'),array('04','بازنگری سیاست','پیگیری آخرین تغییرات این صفحه'))
      : array(array('01','خرید مستقیم','ثبت و پرداخت سفارش محصولات قیمت‌دار'),array('02','درخواست پیش‌فاکتور','استعلام دقیق قیمت و موجودی محصولات'),array('03','ارسال و تحویل','بررسی شرایط ارسال و دریافت محصول'),array('04','مغایرت و مرجوعی','آگاهی از مقررات کالا و بسته‌بندی محافظ'));
    foreach ($cards as $card) {
        $html .= '<div class="hsb-legal__highlight"><span>' . esc_html($card[0]) . '</span><strong>' . esc_html($card[1]) . '</strong><small>' . esc_html($card[2]) . '</small></div>';
    }
    $html .= '</div></section>';
    $html .= '<div class="hsb-legal__layout"><aside class="hsb-legal__sidebar"><nav aria-label="فهرست مطالب" class="hsb-legal__toc"><div class="hsb-legal__toc-head"><strong>فهرست مطالب</strong><span aria-hidden="true">☷</span></div><ol id="hsb-legal-toc"><li><span>در حال آماده‌سازی فهرست...</span></li></ol></nav><div class="hsb-legal__help"><strong>پرسشی دارید؟</strong><p>برای بررسی مورد خاص، از صفحه تماس با ما با پشتیبانی فروش در ارتباط باشید.</p><a href="' . esc_url(home_url('/contact-us/')) . '">ارتباط با پشتیبانی <span aria-hidden="true">←</span></a></div></aside>';
    $html .= '<main class="hsb-legal__article"><div class="hsb-legal__reading"><span class="hsb-legal__reading-dot"></span> راهنمای رسمی فروشگاه</div><div class="hsb-legal__body" id="hsb-legal-body">' . $content . '</div><footer class="hsb-legal__footer">در صورت ابهام درباره شرایط یک کالا یا سفارش، پیش از ثبت نهایی با واحد فروش هماهنگ کنید.</footer></main></div>';
    $html .= '<section class="hsb-legal__bottom-help" aria-label="نیاز به راهنمایی"><div><span class="hsb-legal__pill">پشتیبانی فروشگاه</span><h2>درباره شرایط خرید یا اطلاعات حساب سؤالی دارید؟</h2><p>برای بررسی شرایط مربوط به یک محصول یا سفارش، با واحد فروش در تماس باشید.</p></div><a href="' . esc_url(home_url('/contact-us/')) . '">تماس با ما <span aria-hidden="true">←</span></a></section></div>';
    return $html;
}, 30);
