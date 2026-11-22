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
        )),
        'card' => array('title'=>'کارت محصول', 'desc'=>'پیش‌نمایش کارت نمونه شامل عنوان، شماره محصول و خرید', 'fields'=>array(
            'hsb-shop-card-bg'=>array('پس‌زمینه کارت','#ffffff'),
            'hsb-shop-card-media-bg'=>array('زمینه تصویر','#ffffff'),
            'hsb-shop-card-display-title'=>array('متن عنوان','#252c31'),
            'hsb-shop-card-part-emphasis'=>array('شماره محصول','#5e4420'),
            'hsb-shop-card-family-badge-base'=>array('نشان گزینه‌ها','#5e4420'),
            'hsb-shop-card-family-badge-text'=>array('متن نشان','#ffffff'),
        )),
        'sales' => array('title'=>'خرید و پیش‌فاکتور', 'desc'=>'دو مسیر فروش کاملاً مستقل باقی می‌مانند.', 'fields'=>array(
            'hsb-sale-direct-button-bg'=>array('دکمه خرید مستقیم','#167a50'),
            'hsb-sale-quote-button-bg'=>array('دکمه پیش‌فاکتور','#735936'),
            'hsb-sale-button-text'=>array('متن دکمه‌ها','#ffffff'),
            'hsb-sale-inquiry-status'=>array('وضعیت استعلام','#b42318'),
            'hsb-quote-popup-surface'=>array('زمینه پنجره پیش‌فاکتور','#f5f2ec'),
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
              <div class="hsb-ds-preview-caption">پیش‌نمایش زنده (نمونه سبک، نه تصویر کامل سایت)</div>
              <?php hsb_ds_render_preview($slug); ?>
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
    <?php } else { ?>
      <div class="hsb-p-content"><h3>نظرات کاربران ★★★★☆</h3><p>نمونه متن و جزئیات نظر درباره محصول</p><input type="text" placeholder="نظر خود را بنویسید" aria-label="نمونه فیلد نظر"><div class="hsb-p-table">مشخصات فنی محصول</div></div>
    <?php }
}
