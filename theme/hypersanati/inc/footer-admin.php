<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Footer settings admin page.
 */
add_action('admin_menu', 'hypersanati_register_footer_settings_page');

function hypersanati_register_footer_settings_page() {
    add_theme_page(
        'مدیریت فوتر',
        'مدیریت فوتر',
        'manage_options',
        'hypersanati-footer-settings',
        'hypersanati_render_footer_settings_page'
    );
}

add_action('admin_enqueue_scripts', 'hypersanati_footer_admin_assets');

function hypersanati_footer_admin_assets($hook) {
    if ('appearance_page_hypersanati-footer-settings' !== $hook) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('jquery');

    $script = <<<'JS'
jQuery(function ($) {
    let footerLogoFrame = null;

    $(document).on('click', '.hs-footer-logo-select', function (event) {
        event.preventDefault();

        if (footerLogoFrame) {
            footerLogoFrame.open();
            return;
        }

        footerLogoFrame = wp.media({
            title: 'انتخاب لوگوی فوتر',
            button: {
                text: 'استفاده از این تصویر'
            },
            library: {
                type: 'image'
            },
            multiple: false
        });

        footerLogoFrame.on('select', function () {
            const attachment =
                footerLogoFrame
                    .state()
                    .get('selection')
                    .first()
                    .toJSON();

            const previewUrl =
                attachment.sizes &&
                attachment.sizes.medium
                    ? attachment.sizes.medium.url
                    : attachment.url;

            $('#hs-footer-logo-id')
                .val(attachment.id);

            $('#hs-footer-logo-preview')
                .attr('src', previewUrl)
                .show();

            $('#hs-footer-logo-empty')
                .hide();
        });

        footerLogoFrame.open();
    });

    $(document).on('click', '.hs-footer-logo-remove', function (event) {
        event.preventDefault();

        $('#hs-footer-logo-id')
            .val('');

        $('#hs-footer-logo-preview')
            .attr('src', '')
            .hide();

        $('#hs-footer-logo-empty')
            .show();
    });
});
JS;

    wp_add_inline_script(
        'jquery',
        $script
    );
}


add_action('admin_init', 'hypersanati_register_footer_settings');

function hypersanati_register_footer_settings() {
    register_setting(
        'hypersanati_footer_settings_group',
        'hypersanati_footer_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'hypersanati_sanitize_footer_settings',
            'default'           => array(),
        )
    );
}

function hypersanati_get_footer_settings_defaults() {
    return array(
        'footer_logo_id'   => 0,
        'intro_title'      => 'هایپر صنعتی الفت',
        'intro_text'       => 'تأمین تخصصی بلبرینگ، رولبرینگ و قطعات صنعتی با امکان استعلام قیمت، موجودی و دریافت پیش‌فاکتور.',

        'service_1_title'   => 'تأمین تخصصی قطعات صنعتی',
        'service_1_text'    => 'انتخاب تخصصی متناسب با نیاز صنعتی شما',
        'service_2_title'   => 'استعلام قیمت و موجودی',
        'service_2_text'    => 'بررسی قیمت و موجودی توسط واحد فروش',
        'service_3_title'   => 'پیش‌فاکتور رسمی',
        'service_3_text'    => 'ثبت و پیگیری پیش‌فاکتور از حساب کاربری',
        'service_4_title'   => 'پشتیبانی فروش',
        'service_4_text'    => 'همراهی در انتخاب و تأمین محصول',

        'products_title'    => 'دسته‌بندی‌های مهم',
        'quick_links_title' => 'دسترسی سریع',
        'contact_title'     => 'تماس با ما',

        'product_1_title'   => 'بلبرینگ',
        'product_1_url'     => '',
        'product_2_title'   => 'رولبرینگ',
        'product_2_url'     => '',
        'product_3_title'   => 'یاتاقان',
        'product_3_url'     => '',
        'product_4_title'   => 'کاسه نمد',
        'product_4_url'     => '',
        'product_5_title'   => 'قطعات صنعتی',
        'product_5_url'     => '',

        'quick_1_title'     => 'فروشگاه',
        'quick_1_url'       => '/shop/',
        'quick_2_title'     => 'درباره ما',
        'quick_2_url'       => '/about-us/',
        'quick_3_title'     => 'تماس با ما',
        'quick_3_url'       => '/contact-us/',
        'quick_4_title'     => 'حساب کاربری',
        'quick_4_url'       => '/my-account/',
        'quick_5_title'     => 'پیش‌فاکتور',
        'quick_5_url'       => '/cart/',

        'contact_address'   => 'تهران، خیابان سعدی جنوبی، خیابان اکباتان، کوچه ناظم الاطبا شمالی، پاساژ امام حسین، زیر همکف، پلاک 32 بلبرینگ همگام صنعت برتر',
        'contact_phone_1'   => '02133989930',
        'contact_phone_2'   => '02133989940',
        'contact_email'     => 'info@hamgamsanatbartar.com',

        'terms_title'       => 'قوانین و مقررات',
        'terms_url'         => '',
        'privacy_title'     => 'حریم خصوصی',
        'privacy_url'       => '',

        'copyright_text'    => 'کلیه حقوق برای هایپر صنعتی الفت محفوظ است.',
        'developer_text'    => 'طراحی و توسعه توسط هوش افزار ایرانیان',
        'developer_url'     => '',
    );
}

function hypersanati_get_footer_settings() {
    $saved = get_option('hypersanati_footer_settings', array());

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args(
        $saved,
        hypersanati_get_footer_settings_defaults()
    );
}

function hypersanati_sanitize_footer_settings($input) {
    $defaults = hypersanati_get_footer_settings_defaults();
    $output   = array();

    if (!is_array($input)) {
        return $defaults;
    }

    $textarea_fields = array(
        'intro_text',
        'contact_address',
    );

    $email_fields = array(
        'contact_email',
    );

    foreach ($defaults as $key => $default_value) {
        $value = isset($input[$key]) ? $input[$key] : $default_value;

        if ('footer_logo_id' === $key) {
            $output[$key] = absint($value);
            continue;
        }


        if (in_array($key, $textarea_fields, true)) {
            $output[$key] = sanitize_textarea_field($value);
            continue;
        }

        if (in_array($key, $email_fields, true)) {
            $output[$key] = sanitize_email($value);
            continue;
        }

        if (substr($key, -4) === '_url') {
            $output[$key] = esc_url_raw($value);
            continue;
        }

        $output[$key] = sanitize_text_field($value);
    }

    return $output;
}

function hypersanati_footer_admin_link_row($settings, $prefix, $index) {
    $title_key = "{$prefix}_{$index}_title";
    $url_key   = "{$prefix}_{$index}_url";
    ?>
    <div style="display:grid;grid-template-columns:minmax(180px,1fr) minmax(280px,2fr);gap:12px;margin-bottom:12px;max-width:850px;">
        <input
            type="text"
            name="hypersanati_footer_settings[<?php echo esc_attr($title_key); ?>]"
            value="<?php echo esc_attr($settings[$title_key]); ?>"
            placeholder="عنوان لینک"
        >

        <input
            type="text"
            dir="ltr"
            name="hypersanati_footer_settings[<?php echo esc_attr($url_key); ?>]"
            value="<?php echo esc_attr($settings[$url_key]); ?>"
            placeholder="/shop/ یا https://..."
        >
    </div>
    <?php
}

function hypersanati_render_footer_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = hypersanati_get_footer_settings();

    $footer_logo_preview = !empty($settings['footer_logo_id'])
        ? wp_get_attachment_image_url(
            absint($settings['footer_logo_id']),
            'medium'
        )
        : '';

    ?>
    <div class="wrap">
        <h1>مدیریت فوتر سایت</h1>

        <p>
            تمام محتوای اصلی فوتر سایت را از این بخش مدیریت کنید.
        </p>

        <form method="post" action="options.php">
            <?php settings_fields('hypersanati_footer_settings_group'); ?>

            <h2>معرفی مجموعه</h2>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        لوگوی فوتر
                    </th>

                    <td>
                        <input
                            id="hs-footer-logo-id"
                            type="hidden"
                            name="hypersanati_footer_settings[footer_logo_id]"
                            value="<?php echo esc_attr(
                                absint($settings['footer_logo_id'])
                            ); ?>"
                        >

                        <div
                            style="display:flex;align-items:center;gap:14px;margin-bottom:12px;"
                        >
                            <img
                                id="hs-footer-logo-preview"
                                src="<?php echo esc_url(
                                    $footer_logo_preview
                                ); ?>"
                                alt=""
                                style="width:110px;height:70px;object-fit:contain;border:1px solid #dcdcde;border-radius:8px;background:#fff;<?php echo $footer_logo_preview ? '' : 'display:none;'; ?>"
                            >

                            <span
                                id="hs-footer-logo-empty"
                                style="<?php echo $footer_logo_preview ? 'display:none;' : ''; ?>"
                            >
                                لوگوی اختصاصی انتخاب نشده؛ لوگوی پیش‌فرض قالب نمایش داده می‌شود.
                            </span>
                        </div>

                        <button
                            type="button"
                            class="button hs-footer-logo-select"
                        >
                            انتخاب / تغییر لوگو
                        </button>

                        <button
                            type="button"
                            class="button hs-footer-logo-remove"
                        >
                            حذف لوگوی انتخابی
                        </button>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="hs-footer-intro-title">عنوان معرفی</label>
                    </th>
                    <td>
                        <input
                            id="hs-footer-intro-title"
                            class="regular-text"
                            type="text"
                            name="hypersanati_footer_settings[intro_title]"
                            value="<?php echo esc_attr($settings['intro_title']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="hs-footer-intro-text">متن معرفی</label>
                    </th>
                    <td>
                        <textarea
                            id="hs-footer-intro-text"
                            class="large-text"
                            rows="4"
                            name="hypersanati_footer_settings[intro_text]"
                        ><?php echo esc_textarea($settings['intro_text']); ?></textarea>
                    </td>
                </tr>
            </table>

            <hr>

            <h2>نوار اعتماد و خدمات</h2>

            <table class="form-table" role="presentation">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <tr>
                        <th scope="row">
                            خدمت <?php echo esc_html($i); ?>
                        </th>
                        <td>
                            <input
                                class="regular-text"
                                type="text"
                                name="hypersanati_footer_settings[service_<?php echo esc_attr($i); ?>_title]"
                                value="<?php echo esc_attr($settings["service_{$i}_title"]); ?>"
                                placeholder="عنوان"
                            >

                            <br><br>

                            <input
                                class="large-text"
                                type="text"
                                name="hypersanati_footer_settings[service_<?php echo esc_attr($i); ?>_text]"
                                value="<?php echo esc_attr($settings["service_{$i}_text"]); ?>"
                                placeholder="توضیح کوتاه"
                            >
                        </td>
                    </tr>
                <?php endfor; ?>
            </table>

            <hr>

            <h2>دسته‌بندی‌های مهم</h2>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">عنوان ستون</th>
                    <td>
                        <input
                            class="regular-text"
                            type="text"
                            name="hypersanati_footer_settings[products_title]"
                            value="<?php echo esc_attr($settings['products_title']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">لینک‌ها</th>
                    <td>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <?php hypersanati_footer_admin_link_row($settings, 'product', $i); ?>
                        <?php endfor; ?>
                    </td>
                </tr>
            </table>

            <hr>

            <h2>دسترسی سریع</h2>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">عنوان ستون</th>
                    <td>
                        <input
                            class="regular-text"
                            type="text"
                            name="hypersanati_footer_settings[quick_links_title]"
                            value="<?php echo esc_attr($settings['quick_links_title']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">لینک‌ها</th>
                    <td>
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <?php hypersanati_footer_admin_link_row($settings, 'quick', $i); ?>
                        <?php endfor; ?>
                    </td>
                </tr>
            </table>

            <hr>

            <h2>اطلاعات تماس</h2>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">عنوان ستون</th>
                    <td>
                        <input
                            class="regular-text"
                            type="text"
                            name="hypersanati_footer_settings[contact_title]"
                            value="<?php echo esc_attr($settings['contact_title']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">آدرس</th>
                    <td>
                        <textarea
                            class="large-text"
                            rows="3"
                            name="hypersanati_footer_settings[contact_address]"
                        ><?php echo esc_textarea($settings['contact_address']); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th scope="row">شماره تماس اول</th>
                    <td>
                        <input
                            class="regular-text"
                            dir="ltr"
                            type="text"
                            name="hypersanati_footer_settings[contact_phone_1]"
                            value="<?php echo esc_attr($settings['contact_phone_1']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">شماره تماس دوم</th>
                    <td>
                        <input
                            class="regular-text"
                            dir="ltr"
                            type="text"
                            name="hypersanati_footer_settings[contact_phone_2]"
                            value="<?php echo esc_attr($settings['contact_phone_2']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">ایمیل</th>
                    <td>
                        <input
                            class="regular-text"
                            dir="ltr"
                            type="email"
                            name="hypersanati_footer_settings[contact_email]"
                            value="<?php echo esc_attr($settings['contact_email']); ?>"
                        >
                    </td>
                </tr>
            </table>

            <hr>

            <h2>قوانین و حریم خصوصی</h2>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">قوانین و مقررات</th>
                    <td>
                        <input
                            class="regular-text"
                            type="text"
                            name="hypersanati_footer_settings[terms_title]"
                            value="<?php echo esc_attr($settings['terms_title']); ?>"
                            placeholder="عنوان"
                        >

                        <br><br>

                        <input
                            class="large-text"
                            dir="ltr"
                            type="text"
                            name="hypersanati_footer_settings[terms_url]"
                            value="<?php echo esc_attr($settings['terms_url']); ?>"
                            placeholder="/terms/"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">حریم خصوصی</th>
                    <td>
                        <input
                            class="regular-text"
                            type="text"
                            name="hypersanati_footer_settings[privacy_title]"
                            value="<?php echo esc_attr($settings['privacy_title']); ?>"
                            placeholder="عنوان"
                        >

                        <br><br>

                        <input
                            class="large-text"
                            dir="ltr"
                            type="text"
                            name="hypersanati_footer_settings[privacy_url]"
                            value="<?php echo esc_attr($settings['privacy_url']); ?>"
                            placeholder="/privacy-policy/"
                        >
                    </td>
                </tr>
            </table>

            <hr>

            <h2>نوار پایینی</h2>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">متن کپی‌رایت</th>
                    <td>
                        <input
                            class="large-text"
                            type="text"
                            name="hypersanati_footer_settings[copyright_text]"
                            value="<?php echo esc_attr($settings['copyright_text']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">متن طراح / توسعه‌دهنده</th>
                    <td>
                        <input
                            class="large-text"
                            type="text"
                            name="hypersanati_footer_settings[developer_text]"
                            value="<?php echo esc_attr($settings['developer_text']); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">لینک طراح / توسعه‌دهنده</th>
                    <td>
                        <input
                            class="large-text"
                            dir="ltr"
                            type="text"
                            name="hypersanati_footer_settings[developer_url]"
                            value="<?php echo esc_attr($settings['developer_url']); ?>"
                        >
                    </td>
                </tr>
            </table>

            <?php submit_button('ذخیره تنظیمات فوتر'); ?>
        </form>
    </div>
    <?php
}
