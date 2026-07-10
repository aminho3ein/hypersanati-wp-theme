<?php
/**
 * Dashboard Tickets Section
 * بخش تیکت‌های پشتیبانی
 */

if (!defined('ABSPATH')) {
    exit;
}

// دریافت تیکت‌های کاربر
$user_tickets = get_comments(array(
    'user_id' => $customer_id,
    'type' => 'support_ticket',
    'status' => 'all',
    'orderby' => 'comment_date',
    'order' => 'DESC',
));

$open_tickets = array_filter($user_tickets, function($ticket) {
    return get_comment_meta($ticket->comment_ID, 'ticket_status', true) !== 'closed';
});

$closed_tickets = array_filter($user_tickets, function($ticket) {
    return get_comment_meta($ticket->comment_ID, 'ticket_status', true) === 'closed';
});
?>

<section class="dashboard-tab" id="tab-support">
    <div class="dashboard-header">
        <h2>پشتیبانی و تیکت‌ها</h2>
        <p class="dashboard-subtitle">ارسال و پیگیری تیکت‌های پشتیبانی</p>
    </div>


    <?php
    $ticket_notice = isset($_GET['ticket_notice'])
        ? sanitize_key(wp_unslash($_GET['ticket_notice']))
        : '';

    $ticket_notice_messages = array(
        'created'        => 'تیکت شما با موفقیت ثبت شد. تیم پشتیبانی در سریع‌ترین زمان ممکن پاسخ خواهد داد.',
        'reply_created'  => 'پاسخ شما با موفقیت ثبت شد.',
        'closed'         => 'تیکت با موفقیت بسته شد.',
        'required_error' => 'لطفاً فیلدهای الزامی را کامل کنید.',
        'security_error' => 'درخواست معتبر نیست. لطفاً صفحه را دوباره بارگذاری کنید.',
        'save_error'     => 'در ذخیره اطلاعات مشکلی پیش آمد. لطفاً دوباره تلاش کنید.',
        'not_allowed'    => 'شما اجازه انجام این عملیات را ندارید.',
        'ticket_closed'  => 'این تیکت بسته شده و امکان پاسخ دادن ندارد.',
    );

    if (!empty($ticket_notice) && isset($ticket_notice_messages[$ticket_notice])) :
        $is_error = in_array($ticket_notice, array('required_error', 'security_error', 'save_error', 'not_allowed', 'ticket_closed'), true);
    ?>
        <div class="dashboard-alert <?php echo $is_error ? 'dashboard-alert-error' : 'dashboard-alert-success'; ?>">
            <?php echo esc_html($ticket_notice_messages[$ticket_notice]); ?>
        </div>
    <?php endif; ?>


    
    <!-- Ticket Stats -->
    <div class="ticket-stats">
        <div class="ticket-stat-card">
            <i class="fa-solid fa-ticket"></i>
            <div class="ticket-stat-info">
                <h3><?php echo count($user_tickets); ?></h3>
                <p>کل تیکت‌ها</p>
            </div>
        </div>
        
        <div class="ticket-stat-card active">
            <i class="fa-solid fa-clock"></i>
            <div class="ticket-stat-info">
                <h3><?php echo count($open_tickets); ?></h3>
                <p>تیکت‌های باز</p>
            </div>
        </div>
        
        <div class="ticket-stat-card closed">
            <i class="fa-solid fa-check-circle"></i>
            <div class="ticket-stat-info">
                <h3><?php echo count($closed_tickets); ?></h3>
                <p>تیکت‌های بسته</p>
            </div>
        </div>
    </div>

    <!-- New Ticket Form -->
    <div class="dashboard-card">
        <h3 class="card-title">
            <i class="fa-solid fa-plus-circle"></i>
            ایجاد تیکت جدید
        </h3>
        
        <form class="ticket-form" id="newTicketForm" method="post">
            <?php wp_nonce_field('create_support_ticket', 'ticket_nonce'); ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label>دسته‌بندی تیکت <span class="required">*</span></label>
                    <select name="ticket_category" required>
                        <option value="">انتخاب کنید...</option>
                        <option value="order">مشکل در سفارش</option>
                        <option value="product">سوال درباره محصول</option>
                        <option value="payment">مشکل در پرداخت</option>
                        <option value="shipping">مشکل در ارسال</option>
                        <option value="return">بازگشت کالا</option>
                        <option value="technical">مشکل فنی</option>
                        <option value="other">سایر موارد</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>شماره سفارش (اختیاری)</label>
                    <input type="text" name="ticket_order_id" placeholder="مثلاً: 12345">
                    <small class="form-hint">اگر تیکت مربوط به سفارش خاصی است</small>
                </div>
            </div>

            <div class="form-group">
                <label>عنوان تیکت <span class="required">*</span></label>
                <input type="text" name="ticket_subject" required placeholder="عنوان کوتاه برای مشکل خود">
            </div>

            <div class="form-group">
                <label>شرح کامل مشکل <span class="required">*</span></label>
                <textarea name="ticket_message" rows="6" required placeholder="لطفاً مشکل خود را به صورت کامل توضیح دهید..."></textarea>
            </div>

            <button type="submit" name="create_ticket" class="btn-primary">
                <i class="fa-solid fa-paper-plane"></i>
                ارسال تیکت
            </button>
        </form>
    </div>

    <!-- Tickets List -->
    <?php if (count($user_tickets) > 0) : ?>
        <div class="tickets-list">
            <h3 class="section-title">
                <i class="fa-solid fa-list"></i>
                تیکت‌های من
            </h3>
            
            <?php foreach ($user_tickets as $ticket) : ?>
                <?php
                $ticket_id = $ticket->comment_ID;
                $ticket_status = get_comment_meta($ticket_id, 'ticket_status', true) ?: 'open';
                $ticket_category = get_comment_meta($ticket_id, 'ticket_category', true);
                $ticket_subject = get_comment_meta($ticket_id, 'ticket_subject', true);
                $ticket_order_id = get_comment_meta($ticket_id, 'ticket_order_id', true);
                
                // دریافت پاسخ‌های تیکت
                $ticket_replies = get_comments(array(
                    'parent' => $ticket_id,
                    'type' => 'ticket_reply',
                    'orderby' => 'comment_date',
                    'order' => 'ASC',
                ));
                
                $status_labels = array(
                    'open' => 'باز',
                    'answered' => 'پاسخ داده شده',
                    'closed' => 'بسته شده',
                );
                
                $category_labels = array(
                    'order' => 'سفارش',
                    'product' => 'محصول',
                    'payment' => 'پرداخت',
                    'shipping' => 'ارسال',
                    'return' => 'بازگشت',
                    'technical' => 'فنی',
                    'other' => 'سایر',
                );
                ?>
                
                <div class="ticket-card ticket-status-<?php echo esc_attr($ticket_status); ?>" data-ticket-id="<?php echo esc_attr($ticket_id); ?>">
                    <div class="ticket-card-header">
                        <div class="ticket-card-title">
                            <h4>
                                <span class="ticket-number">#<?php echo esc_html($ticket_id); ?></span>
                                <?php echo esc_html($ticket_subject); ?>
                            </h4>
                            <div class="ticket-card-meta">
                                <span class="ticket-category">
                                    <i class="fa-solid fa-tag"></i>
                                    <?php echo esc_html($category_labels[$ticket_category] ?? 'سایر'); ?>
                                </span>
                                
                                <?php if ($ticket_order_id) : ?>
                                    <span class="ticket-order">
                                        <i class="fa-solid fa-shopping-bag"></i>
                                        سفارش #<?php echo esc_html($ticket_order_id); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <span class="ticket-date">
                                    <i class="fa-solid fa-calendar"></i>
                                    <?php echo esc_html(wp_date('j F Y', strtotime($ticket->comment_date))); ?>
                                </span>
                            </div>
                        </div>
                        
                        <span class="ticket-status status-badge-<?php echo esc_attr($ticket_status); ?>">
                            <?php echo esc_html($status_labels[$ticket_status] ?? 'باز'); ?>
                        </span>
                    </div>

                    <div class="ticket-card-body">
                        <!-- پیام اصلی تیکت -->
                        <div class="ticket-message user-message">
                            <div class="ticket-message-author">
                                <i class="fa-solid fa-user-circle"></i>
                                <strong>شما</strong>
                                <span class="message-time"><?php echo esc_html(wp_date('H:i', strtotime($ticket->comment_date))); ?></span>
                            </div>
                            <div class="ticket-message-content">
                                <?php echo wp_kses_post(wpautop($ticket->comment_content)); ?>
                            </div>
                        </div>

                        <!-- پاسخ‌های تیکت -->
                        <?php if (count($ticket_replies) > 0) : ?>
                            <div class="ticket-replies">
                                <?php foreach ($ticket_replies as $reply) : ?>
                                    <?php $is_support = get_comment_meta($reply->comment_ID, 'is_support_reply', true); ?>
                                    <div class="ticket-message <?php echo $is_support ? 'support-message' : 'user-message'; ?>">
                                        <div class="ticket-message-author">
                                            <i class="fa-solid fa-<?php echo $is_support ? 'headset' : 'user-circle'; ?>"></i>
                                            <strong><?php echo $is_support ? 'پشتیبانی' : 'شما'; ?></strong>
                                            <span class="message-time"><?php echo esc_html(wp_date('j F Y - H:i', strtotime($reply->comment_date))); ?></span>
                                        </div>
                                        <div class="ticket-message-content">
                                            <?php echo wp_kses_post(wpautop($reply->comment_content)); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- فرم پاسخ به تیکت -->
                        <?php if ($ticket_status !== 'closed') : ?>
                            <div class="ticket-reply-form" style="display: none;">
                                <form method="post" class="reply-ticket-form" data-ticket-id="<?php echo esc_attr($ticket_id); ?>">
                                    <?php wp_nonce_field('reply_ticket_' . $ticket_id, 'reply_nonce'); ?>
                                    <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id); ?>">
                                    
                                    <div class="form-group">
                                        <label>پاسخ شما</label>
                                        <textarea name="reply_message" rows="4" required placeholder="پاسخ خود را بنویسید..."></textarea>
                                    </div>
                                    
                                    <div class="reply-actions">
                                        <button type="submit" name="reply_ticket" class="btn-secondary">
                                            <i class="fa-solid fa-reply"></i>
                                            ارسال پاسخ
                                        </button>
                                        <button type="button" class="btn-cancel-reply">
                                            <i class="fa-solid fa-times"></i>
                                            انصراف
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="ticket-card-footer">
                        <?php if ($ticket_status !== 'closed') : ?>
                            <button class="btn-reply" data-ticket-id="<?php echo esc_attr($ticket_id); ?>">
                                <i class="fa-solid fa-reply"></i>
                                پاسخ به تیکت
                            </button>
                            
                            <form method="post" style="display: inline;" class="close-ticket-form">
                                <?php wp_nonce_field('close_ticket_' . $ticket_id, 'close_nonce'); ?>
                                <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id); ?>">
                                <button type="submit" name="close_ticket" class="btn-close-ticket">
                                    <i class="fa-solid fa-times-circle"></i>
                                    بستن تیکت
                                </button>
                            </form>
                        <?php else : ?>
                            <span class="ticket-closed-note">
                                <i class="fa-solid fa-info-circle"></i>
                                این تیکت بسته شده است
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="empty-state">
            <i class="fa-solid fa-ticket"></i>
            <h3>هنوز تیکتی ثبت نکرده‌اید</h3>
            <p>برای ارتباط با پشتیبانی، تیکت جدید ایجاد کنید</p>
        </div>
    <?php endif; ?>

    <!-- Contact Info -->
    <div class="dashboard-card support-info-card">
        <h3 class="card-title">
            <i class="fa-solid fa-circle-info"></i>
            راه‌های دیگر ارتباطی
        </h3>
        
        <div class="contact-methods">
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <div>
                    <h4>تلفن پشتیبانی</h4>
                    <p><?php echo esc_html(get_theme_mod('hypersanati_contact_phone', '۰۲۱-۳۳۹۸۹۹۳۰')); ?></p>
                </div>
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <div>
                    <h4>ایمیل</h4>
                    <p><?php echo esc_html(get_theme_mod('hypersanati_contact_email', 'info@hypersanati.com')); ?></p>
                </div>
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <h4>ساعات کاری</h4>
                    <p>شنبه تا پنج‌شنبه: ۹ صبح تا ۶ عصر</p>
                </div>
            </div>
        </div>
    </div>
</section>
