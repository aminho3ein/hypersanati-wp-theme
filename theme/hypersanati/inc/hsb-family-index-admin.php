<?php

defined('ABSPATH') || exit;

add_action('admin_menu', function () {

    add_options_page(
        'مدیریت خانواده محصولات',
        'مدیریت خانواده محصولات',
        'manage_options',
        'hsb-family-index',
        'hsb_family_index_admin_page'
    );

});

function hsb_family_index_admin_page() {

    if (!current_user_can('manage_options')) {
        return;
    }

    $index = hsb_family_index_get_active();

    ?>

    <div class="wrap" dir="rtl">

        <h1>مدیریت خانواده محصولات</h1>

        <p>
            بازسازی دستی و ذخیره دائمی صف
            نمایش خانواده محصولات فروشگاه
        </p>

        <table class="widefat striped"
               style="max-width:850px;margin-top:20px">

            <tbody>

                <tr>
                    <td>تعداد کل محصولات</td>
                    <td id="hsb-family-total-products">
                        <?php echo esc_html(
                            $index['total_products'] ?? '—'
                        ); ?>
                    </td>
                </tr>

                <tr>
                    <td>تعداد خانواده‌های محصولات</td>
                    <td id="hsb-family-total-families">
                        <?php echo esc_html(
                            $index['total_families'] ?? '—'
                        ); ?>
                    </td>
                </tr>

                <tr>
                    <td>کارت‌های مستقل فروش آنلاین</td>
                    <td id="hsb-family-online-cards"><?php echo esc_html($index['stats']['online_cards'] ?? '—'); ?></td>
                </tr>

                <tr>
                    <td>تعداد کارت‌های عادی فروشگاه</td>
                    <td id="hsb-family-normal-cards"><?php echo esc_html($index['stats']['normal_cards'] ?? '—'); ?></td>
                </tr>

                <tr>
                    <td><strong>تعداد نهایی کارت‌های شاپ</strong></td>
                    <td id="hsb-family-total-cards"><?php echo esc_html($index['stats']['total_cards'] ?? '—'); ?></td>
                </tr>

                <tr>
                    <td>درصد کاهش تعداد کارت‌ها</td>
                    <td id="hsb-family-reduction">
    <?php echo esc_html(
        isset($index['stats']['reduction'])
            ? $index['stats']['reduction'] . '%'
            : '—'
    ); ?>
</td>
                </tr>

                <tr>
                    <td>آخرین بازسازی موفق</td>
                    <td id="hsb-family-built-at">
                        <?php echo esc_html(
                            $index['built_at'] ?? 'هنوز انجام نشده'
                        ); ?>
                    </td>
                </tr>

                <tr>
                    <td>وضعیت ایندکس</td>
                    <td id="hsb-family-status">
                        <?php echo empty($index)
                            ? 'در انتظار اولین بازسازی'
                            : 'آماده استفاده'; ?>
                    </td>
                </tr>

            </tbody>

        </table>

        <div style="max-width:850px;margin-top:25px">

            <p>
                <strong>پیشرفت بازسازی:</strong>
                <span id="hsb-family-progress-text">۰٪</span>
            </p>

            <div role="progressbar"
                 id="hsb-family-progress-container"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 aria-valuenow="0"
                 style="background:#ddd;height:20px;
                        border-radius:5px;overflow:hidden">

                <div id="hsb-family-progress-bar"
                     style="background:#2271b1;width:0%;
                            height:100%;transition:width .3s">
                </div>

            </div>

            <p id="hsb-family-message"
               role="status"
               aria-live="polite"></p>

            <button type="button"
                    id="hsb-family-rebuild"
                    class="button button-primary button-large"
                    >
                بازسازی خانواده محصولات
            </button>

            <p class="description">
                هنگام بازسازی، آخرین صف سالم فروشگاه
                همچنان قابل استفاده خواهد بود.
            </p>

        </div>

    </div>

    <?php
}


add_action('wp_ajax_hsb_family_index_rebuild', function () {

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Access denied.', 403);
    }

    check_ajax_referer('hsb_family_index_admin', 'nonce');

    /* HSB Family Rebuild Resources */
    @ini_set('memory_limit', '512M');
    @set_time_limit(0);

    $result = hsb_family_index_rebuild();

    if (is_wp_error($result)) {
        wp_send_json_error(
            $result->get_error_message()
        );
    }

    wp_send_json_success(array(
        'total_products' => $result['total_products'],
        'total_families' => $result['total_families'],
        'built_at'       => $result['built_at'],
        'stats'          => $result['stats'],
    ));
});


add_action('wp_ajax_hsb_family_index_progress', function () {

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Access denied.', 403);
    }

    check_ajax_referer('hsb_family_index_admin', 'nonce');

    wp_send_json_success(
        hsb_family_index_get_progress()
    );
});


add_action('admin_footer-settings_page_hsb-family-index', function () {

    $nonce = wp_create_nonce('hsb_family_index_admin');

    ?>

    <script>
    (function () {

        const button = document.getElementById(
            'hsb-family-rebuild'
        );

        if (!button) return;

        const nonce = <?php echo wp_json_encode($nonce); ?>;

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        };

        function request(action) {

            return fetch(ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                body: new URLSearchParams({
                    action: action,
                    nonce: nonce
                })
            }).then(r => r.json());
        }

        function progress(percent, message) {

            percent = Math.max(0, Math.min(100, Number(percent)));

            document.getElementById(
                'hsb-family-progress-bar'
            ).style.width = percent + '%';

            document.getElementById(
                'hsb-family-progress-container'
            ).setAttribute('aria-valuenow', percent);

            setText(
                'hsb-family-progress-text',
                percent + '%'
            );

            setText('hsb-family-message', message);
        }

        let running = false;
        let polling = false;

        async function poll() {

            if (!running || polling) return;

            polling = true;

            try {

                const response = await request(
                    'hsb_family_index_progress'
                );

                if (running && response.success) {
                    progress(
                        response.data.percent,
                        response.data.message
                    );
                }

            } catch (error) {
                console.warn(error);
            } finally {
                polling = false;
            }
        }

        button.addEventListener('click', async function () {

            if (running) return;

            running = true;
            button.disabled = true;

            progress(0, 'در حال شروع بازسازی...');

            setText('hsb-family-status', 'در حال بازسازی');

            const timer = setInterval(poll, 1000);

            try {

                const response = await request(
                    'hsb_family_index_rebuild'
                );

                if (!response.success) {
                    throw new Error(
                        typeof response.data === 'string'
                            ? response.data
                            : 'خطا در بازسازی'
                    );
                }

                const data = response.data;
                const stats = data.stats;

                setText('hsb-family-total-products', data.total_products);
                setText('hsb-family-total-families', data.total_families);
                setText('hsb-family-online-cards', stats.online_cards);
                setText('hsb-family-normal-cards', stats.normal_cards);
                setText('hsb-family-total-cards', stats.total_cards);
                setText('hsb-family-reduction', stats.reduction + '%');
                setText('hsb-family-built-at', data.built_at);
                setText('hsb-family-status', 'آماده استفاده');

                progress(100, 'بازسازی با موفقیت انجام شد.');

            } catch (error) {

                setText(
                    'hsb-family-status',
                    'بازسازی تأیید نشد'
                );

                setText(
                    'hsb-family-message',
                    error.message
                );

            } finally {

                running = false;
                clearInterval(timer);
                button.disabled = false;
            }
        });

    })();
    </script>

    <?php
});
