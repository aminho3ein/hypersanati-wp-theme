<?php
/**
 * Dashboard Page - Hypersanati Theme
 * صفحه داشبورد کاربری با سیستم تب‌های دینامیک و تیکتینگ
 */

if (!defined('ABSPATH')) {
    exit;
}

// بررسی login بودن کاربر
if (!is_user_logged_in()) {
    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;
}

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();
$customer = new WC_Customer($customer_id);


/* =========================================================
   SUPPORT TICKET FORM HANDLERS
========================================================= */

$dashboard_redirect_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('myaccount')
    : home_url('/my-account/');

if (empty($dashboard_redirect_url)) {
    $dashboard_redirect_url = home_url('/my-account/');
}

$redirect_to_support = function ($notice) use ($dashboard_redirect_url) {
    $url = add_query_arg(
        array(
            'dashboard_tab'  => 'support',
            'ticket_notice' => sanitize_key($notice),
        ),
        $dashboard_redirect_url
    );

    wp_safe_redirect($url . '#tab-support');
    exit;
};

/**
 * Create new support ticket
 */
if (isset($_POST['create_ticket'])) {

    if (
        ! isset($_POST['ticket_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['ticket_nonce'])),
            'create_support_ticket'
        )
    ) {
        $redirect_to_support('security_error');
    }

    $ticket_category = isset($_POST['ticket_category'])
        ? sanitize_key(wp_unslash($_POST['ticket_category']))
        : '';

    $ticket_order_id = isset($_POST['ticket_order_id'])
        ? absint($_POST['ticket_order_id'])
        : 0;

    $ticket_subject = isset($_POST['ticket_subject'])
        ? sanitize_text_field(wp_unslash($_POST['ticket_subject']))
        : '';

    $ticket_message = isset($_POST['ticket_message'])
        ? sanitize_textarea_field(wp_unslash($_POST['ticket_message']))
        : '';

    if (empty($ticket_category) || empty($ticket_subject) || empty($ticket_message)) {
        $redirect_to_support('required_error');
    }

    $ticket_id = wp_insert_comment(array(
        'comment_post_ID'      => 0,
        'comment_author'       => $current_user->display_name ? $current_user->display_name : $current_user->user_login,
        'comment_author_email' => $current_user->user_email,
        'comment_author_url'   => '',
        'comment_content'      => $ticket_message,
        'comment_type'         => 'support_ticket',
        'comment_parent'       => 0,
        'user_id'              => $customer_id,
        'comment_approved'     => 1,
        'comment_date'         => current_time('mysql'),
    ));

    if (!$ticket_id || is_wp_error($ticket_id)) {
        $redirect_to_support('save_error');
    }

    add_comment_meta($ticket_id, 'ticket_status', 'open');
    add_comment_meta($ticket_id, 'ticket_category', $ticket_category);
    add_comment_meta($ticket_id, 'ticket_subject', $ticket_subject);
    add_comment_meta($ticket_id, 'ticket_order_id', $ticket_order_id);
    add_comment_meta($ticket_id, 'ticket_created_at', current_time('mysql'));

    $redirect_to_support('created');
}

/**
 * Reply to support ticket
 */
if (isset($_POST['reply_ticket'])) {

    $ticket_id = isset($_POST['ticket_id']) ? absint($_POST['ticket_id']) : 0;

    if (
        ! $ticket_id ||
        ! isset($_POST['reply_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['reply_nonce'])),
            'reply_ticket_' . $ticket_id
        )
    ) {
        $redirect_to_support('security_error');
    }

    $ticket = get_comment($ticket_id);

    if (
        ! $ticket ||
        'support_ticket' !== $ticket->comment_type ||
        (int) $ticket->user_id !== (int) $customer_id
    ) {
        $redirect_to_support('not_allowed');
    }

    $ticket_status = get_comment_meta($ticket_id, 'ticket_status', true);

    if ('closed' === $ticket_status) {
        $redirect_to_support('ticket_closed');
    }

    $reply_message = isset($_POST['reply_message'])
        ? sanitize_textarea_field(wp_unslash($_POST['reply_message']))
        : '';

    if (empty($reply_message)) {
        $redirect_to_support('required_error');
    }

    $reply_id = wp_insert_comment(array(
        'comment_post_ID'      => 0,
        'comment_author'       => $current_user->display_name ? $current_user->display_name : $current_user->user_login,
        'comment_author_email' => $current_user->user_email,
        'comment_author_url'   => '',
        'comment_content'      => $reply_message,
        'comment_type'         => 'ticket_reply',
        'comment_parent'       => $ticket_id,
        'user_id'              => $customer_id,
        'comment_approved'     => 1,
        'comment_date'         => current_time('mysql'),
    ));

    if (!$reply_id || is_wp_error($reply_id)) {
        $redirect_to_support('save_error');
    }

    add_comment_meta($reply_id, 'is_support_reply', '0');
    update_comment_meta($ticket_id, 'ticket_status', 'open');

    $redirect_to_support('reply_created');
}

/**
 * Close support ticket
 */
if (isset($_POST['close_ticket'])) {

    $ticket_id = isset($_POST['ticket_id']) ? absint($_POST['ticket_id']) : 0;

    if (
        ! $ticket_id ||
        ! isset($_POST['close_nonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['close_nonce'])),
            'close_ticket_' . $ticket_id
        )
    ) {
        $redirect_to_support('security_error');
    }

    $ticket = get_comment($ticket_id);

    if (
        ! $ticket ||
        'support_ticket' !== $ticket->comment_type ||
        (int) $ticket->user_id !== (int) $customer_id
    ) {
        $redirect_to_support('not_allowed');
    }

    update_comment_meta($ticket_id, 'ticket_status', 'closed');

    $redirect_to_support('closed');
}

// دریافت سفارشات کاربر
$customer_orders = wc_get_orders(array(
    'customer_id' => $customer_id,
    'limit' => 10,
    'orderby' => 'date',
    'order' => 'DESC',
));

get_header();
?>

<div class="dashboard-container">
    
    <!-- Sidebar Navigation -->
    <aside class="dashboard-sidebar">
        <div class="dashboard-user-welcome">
            <i class="fa-solid fa-user-circle"></i>
            <h3><?php echo esc_html($current_user->display_name ?: 'کاربر گرامی'); ?></h3>
            <p class="user-phone"><?php echo esc_html($customer->get_billing_phone()); ?></p>
        </div>

        <nav class="dashboard-nav">
            <button class="dashboard-nav-btn active" data-tab="account">
                <i class="fa-solid fa-user"></i>
                <span>حساب کاربری</span>
            </button>
            
            <button class="dashboard-nav-btn" data-tab="orders">
                <i class="fa-solid fa-shopping-bag"></i>
                <span>سفارش‌های من</span>
                <?php if (count($customer_orders) > 0) : ?>
                    <span class="badge"><?php echo count($customer_orders); ?></span>
                <?php endif; ?>
            </button>

            <button class="dashboard-nav-btn" data-tab="addresses">
                <i class="fa-solid fa-location-dot"></i>
                <span>آدرس‌های من</span>
            </button>

            <button class="dashboard-nav-btn" data-tab="support">
                <i class="fa-solid fa-headset"></i>
                <span>تیکت‌های پشتیبانی</span>
                <?php 
                $open_tickets_count = get_comments(array(
                    'user_id' => $customer_id,
                    'type'    => 'support_ticket',
                    'meta_query' => array(
                        array(
                            'key'     => 'ticket_status',
                            'value'   => 'closed',
                            'compare' => '!=',
                        ),
                    ),
                    'count' => true,
                ));

                if ($open_tickets_count > 0) : ?>
                    <span class="badge"><?php echo esc_html($open_tickets_count); ?></span>
                <?php endif; ?>
            </button>

            <a href="<?php echo esc_url(wc_logout_url()); ?>" class="dashboard-nav-btn logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>خروج از حساب</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="dashboard-content">

        <!-- Tab: حساب کاربری -->
        <section class="dashboard-tab active" id="tab-account">
            <div class="dashboard-header">
                <h2>حساب کاربری من</h2>
                <p class="dashboard-subtitle">مدیریت اطلاعات شخصی</p>
            </div>

            <div class="dashboard-card">
                <h3 class="card-title">اطلاعات شخصی</h3>
                
                <form class="profile-form" method="post" action="">
                    <?php wp_nonce_field('update_user_profile', 'profile_nonce'); ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>نام</label>
                            <input type="text" name="first_name" value="<?php echo esc_attr($customer->get_first_name()); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>نام خانوادگی</label>
                            <input type="text" name="last_name" value="<?php echo esc_attr($customer->get_last_name()); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>شماره موبایل</label>
                            <input type="tel" name="billing_phone" value="<?php echo esc_attr($customer->get_billing_phone()); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>ایمیل</label>
                            <input type="email" name="billing_email" value="<?php echo esc_attr($customer->get_billing_email()); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>کد ملی</label>
                            <input type="text" name="billing_national_code" value="<?php echo esc_attr(get_user_meta($customer_id, '_billing_national_code', true)); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>نام فروشگاه/کارگاه (اختیاری)</label>
                            <input type="text" name="billing_company" value="<?php echo esc_attr($customer->get_billing_company()); ?>">
                        </div>
                    </div>

                    <button type="submit" name="update_profile" class="btn-primary">
                        <i class="fa-solid fa-check"></i>
                        ذخیره تغییرات
                    </button>
                </form>
            </div>

            <div class="dashboard-card">
                <h3 class="card-title">تغییر رمز عبور</h3>
                
                <form class="password-form" method="post" action="">
                    <?php wp_nonce_field('change_user_password', 'password_nonce'); ?>
                    
                    <div class="form-group">
                        <label>رمز عبور فعلی</label>
                        <input type="password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label>رمز عبور جدید</label>
                        <input type="password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label>تکرار رمز عبور جدید</label>
                        <input type="password" name="confirm_password" required>
                    </div>

                    <button type="submit" name="change_password" class="btn-primary">
                        <i class="fa-solid fa-key"></i>
                        تغییر رمز عبور
                    </button>
                </form>
            </div>
        </section>

        <!-- Tab: سفارش‌های من -->
        <section class="dashboard-tab" id="tab-orders">
            <div class="dashboard-header">
                <h2>سفارش‌های من</h2>
                <p class="dashboard-subtitle">مشاهده و پیگیری سفارشات</p>
            </div>

            <?php if (count($customer_orders) > 0) : ?>
                <div class="orders-list">
                    <?php foreach ($customer_orders as $order) : ?>
                        <?php
                        $order_id = $order->get_id();
                        $order_date = $order->get_date_created();
                        $order_status = $order->get_status();
                        $order_total = $order->get_total();
                        $order_items = $order->get_items();
                        
                        $status_labels = array(
                            'pending' => 'در انتظار پرداخت',
                            'processing' => 'در حال پردازش',
                            'on-hold' => 'معلق',
                            'completed' => 'تکمیل شده',
                            'cancelled' => 'لغو شده',
                            'refunded' => 'بازپرداخت شده',
                            'failed' => 'ناموفق',
                        );
                        
                        $status_label = isset($status_labels[$order_status]) ? $status_labels[$order_status] : $order_status;
                        ?>
                        
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-number">
                                    <span class="label">سفارش #</span>
                                    <span class="value"><?php echo esc_html($order_id); ?></span>
                                </div>
                                
                                <span class="order-status status-<?php echo esc_attr($order_status); ?>">
                                    <?php echo esc_html($status_label); ?>
                                </span>
                            </div>

                            <div class="order-meta">
                                <div class="order-date">
                                    <i class="fa-solid fa-calendar"></i>
                                    <?php echo esc_html($order_date->date_i18n('j F Y')); ?>
                                </div>
                                
                                <div class="order-total">
                                    <i class="fa-solid fa-money-bill"></i>
                                    <?php echo wc_price($order_total); ?>
                                </div>

                                <div class="order-items-count">
                                    <i class="fa-solid fa-box"></i>
                                    <?php echo count($order_items); ?> محصول
                                </div>
                            </div>

                            <div class="order-items">
                                <?php foreach ($order_items as $item) : ?>
                                    <?php
                                    $product = $item->get_product();
                                    if (!$product) continue;
                                    ?>
                                    <div class="order-item">
                                        <div class="item-image">
                                            <?php echo $product->get_image('thumbnail'); ?>
                                        </div>
                                        <div class="item-details">
                                            <h4><?php echo esc_html($item->get_name()); ?></h4>
                                            <span class="item-qty">تعداد: <?php echo esc_html($item->get_quantity()); ?></span>
                                        </div>
                                        <div class="item-price">
                                            <?php echo wc_price($item->get_total()); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="order-actions">
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn-secondary">
                                    <i class="fa-solid fa-eye"></i>
                                    مشاهده جزئیات
                                </a>
                                
                                <a href="<?php echo esc_url($order->get_checkout_order_received_url()); ?>#salesInvoiceSection" class="btn-secondary btn-invoice" target="_blank">
                                    <i class="fa-solid fa-file-invoice"></i>
                                    مشاهده فاکتور
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <i class="fa-solid fa-shopping-bag"></i>
                    <h3>هنوز سفارشی ثبت نکرده‌اید</h3>
                    <p>برای مشاهده سفارشات خود، ابتدا یک خرید انجام دهید.</p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-primary">
                        مشاهده محصولات
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <!-- Tab: آدرس‌ها -->
        <section class="dashboard-tab" id="tab-addresses">
            <div class="dashboard-header">
                <h2>آدرس‌های من</h2>
                <p class="dashboard-subtitle">مدیریت آدرس‌های ارسال</p>
            </div>

            <div class="addresses-grid">
                <div class="address-card">
                    <div class="address-header">
                        <h3>آدرس صورتحساب</h3>
                    </div>
                    
                    <?php if ($customer->get_billing_address_1()) : ?>
                        <div class="address-content">
                            <p><strong><?php echo esc_html($customer->get_billing_first_name() . ' ' . $customer->get_billing_last_name()); ?></strong></p>
                            <p><?php echo esc_html($customer->get_billing_address_1()); ?></p>
                            <?php if ($customer->get_billing_address_2()) : ?>
                                <p><?php echo esc_html($customer->get_billing_address_2()); ?></p>
                            <?php endif; ?>
                            <p><?php echo esc_html($customer->get_billing_city()); ?>, <?php echo esc_html($customer->get_billing_state()); ?></p>
                            <p>کدپستی: <?php echo esc_html($customer->get_billing_postcode()); ?></p>
                            <p>تلفن: <?php echo esc_html($customer->get_billing_phone()); ?></p>
                        </div>
                    <?php else : ?>
                        <p class="no-address">آدرسی ثبت نشده است</p>
                    <?php endif; ?>

                    <button class="btn-secondary edit-address-btn">
                        <i class="fa-solid fa-edit"></i>
                        ویرایش آدرس
                    </button>
                </div>

                <div class="address-card">
                    <div class="address-header">
                        <h3>آدرس ارسال</h3>
                    </div>
                    
                    <?php if ($customer->get_shipping_address_1()) : ?>
                        <div class="address-content">
                            <p><strong><?php echo esc_html($customer->get_shipping_first_name() . ' ' . $customer->get_shipping_last_name()); ?></strong></p>
                            <p><?php echo esc_html($customer->get_shipping_address_1()); ?></p>
                            <?php if ($customer->get_shipping_address_2()) : ?>
                                <p><?php echo esc_html($customer->get_shipping_address_2()); ?></p>
                            <?php endif; ?>
                            <p><?php echo esc_html($customer->get_shipping_city()); ?>, <?php echo esc_html($customer->get_shipping_state()); ?></p>
                            <p>کدپستی: <?php echo esc_html($customer->get_shipping_postcode()); ?></p>
                        </div>
                    <?php else : ?>
                        <p class="no-address">آدرسی ثبت نشده است</p>
                    <?php endif; ?>

                    <button class="btn-secondary edit-address-btn">
                        <i class="fa-solid fa-edit"></i>
                        ویرایش آدرس
                    </button>
                </div>
            </div>
        </section>

        <!-- Tab: تیکت‌های پشتیبانی -->
        <?php include(get_template_directory() . '/woocommerce/myaccount/dashboard-tickets.php'); ?>

    </main>

</div>

<?php get_footer(); ?>
