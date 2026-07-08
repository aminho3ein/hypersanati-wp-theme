<?php
/**
 * Custom WooCommerce Thank You / Sales Invoice Page
 */

defined('ABSPATH') || exit;

if (!isset($order) || !($order instanceof WC_Order)) {
    $order_id = absint(get_query_var('order-received'));
    $order    = wc_get_order($order_id);
}

if (!$order) {
    ?>
    <section class="thank-you-page">
        <div class="container">
            <div class="thank-you-page__card">
                <h1>سفارش پیدا نشد</h1>
                <p>متأسفانه اطلاعات سفارش در دسترس نیست.</p>

                <a href="<?php echo esc_url(home_url('/')); ?>" class="thank-you-page__btn">
                    بازگشت به صفحه اصلی
                </a>
            </div>
        </div>
    </section>
    <?php
    return;
}

$order_id       = $order->get_id();
$order_number   = $order->get_order_number();
$order_date     = $order->get_date_created() ? wc_format_datetime($order->get_date_created(), 'Y/m/d') : '';
$payment_title  = $order->get_payment_method_title();
$transaction_id = $order->get_transaction_id();

$billing_first_name = $order->get_billing_first_name();
$billing_last_name  = $order->get_billing_last_name();
$customer_name      = trim($billing_first_name . ' ' . $billing_last_name);

if (!$customer_name) {
    $customer_name = 'مشتری گرامی';
}

$billing_country  = $order->get_billing_country();
$billing_state    = $order->get_billing_state();
$billing_city     = $order->get_billing_city();
$billing_address  = $order->get_billing_address_1();
$billing_unit     = $order->get_billing_address_2();
$billing_postcode = $order->get_billing_postcode();
$billing_phone    = $order->get_billing_phone();

$billing_plaque = $order->get_meta('_billing_plaque');
if (!$billing_plaque) {
    $billing_plaque = $order->get_meta('billing_plaque');
}

$billing_national_code = $order->get_meta('_billing_national_code');
if (!$billing_national_code) {
    $billing_national_code = $order->get_meta('billing_national_code');
}

$state_name = $billing_state;

if (function_exists('WC') && WC()->countries && $billing_country) {
    $states = WC()->countries->get_states($billing_country);

    if (is_array($states) && isset($states[$billing_state])) {
        $state_name = $states[$billing_state];
    }
}

$address_parts = array_filter(array(
    $state_name,
    $billing_city,
    $billing_address,
    $billing_plaque ? 'پلاک ' . $billing_plaque : '',
    $billing_unit ? 'واحد ' . $billing_unit : '',
));

$full_address = implode('، ', $address_parts);

$invoice_filename = 'invoice-' . $order_number . '.pdf';
?>

<section class="thank-you-page">
    <div class="container">
        <div class="thank-you-page__card">
            <div class="thank-you-page__icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <h1 class="thank-you-page__title">
                سفارش شما با موفقیت ثبت شد
            </h1>

            <p class="thank-you-page__text">
                <?php echo esc_html($customer_name); ?> عزیز، از خرید شما سپاسگزاریم. اطلاعات سفارش شما ثبت شد و فاکتور خرید در همین صفحه قابل مشاهده و دانلود است.
            </p>

            <div class="thank-you-page__meta">
                <div>
                    <span>شماره سفارش</span>
                    <strong><?php echo esc_html($order_number); ?></strong>
                </div>

                <div>
                    <span>تاریخ سفارش</span>
                    <strong><?php echo esc_html($order_date); ?></strong>
                </div>

                <div>
                    <span>مبلغ پرداختی</span>
                    <strong><?php echo wp_kses_post($order->get_formatted_order_total()); ?></strong>
                </div>

                <?php if ($payment_title) : ?>
                    <div>
                        <span>روش پرداخت</span>
                        <strong><?php echo esc_html($payment_title); ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <div class="thank-you-page__actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="thank-you-page__btn thank-you-page__btn--secondary">
                    بازگشت به صفحه اصلی
                </a>

                <button type="button" class="thank-you-page__btn" id="viewSalesInvoice">
                    دیدن فاکتور خرید
                </button>
            </div>
        </div>
    </div>
</section>

<section class="sales-invoice is-hidden" id="salesInvoiceSection" aria-hidden="true">
    <div class="sales-invoice__container">
        <div class="sales-invoice__actions">
            <button id="downloadInvoicePdf"
                    class="sales-invoice__download-btn"
                    type="button"
                    data-filename="<?php echo esc_attr($invoice_filename); ?>">
                دانلود PDF
            </button>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="sales-invoice__download-btn sales-invoice__download-btn--link">
                بازگشت به صفحه اصلی
            </a>
        </div>
    </div>

    <div class="sales-invoice__print-area" id="invoicePrintArea">
        <div class="sales-invoice__container">
            <div class="sales-invoice__topbar"></div>

            <div class="sales-invoice__header">
                <div class="sales-invoice__brand">
                    <div class="sales-invoice__brand-logo">H</div>

                    <div class="sales-invoice__brand-text">
                        <h1>همگام صنعت برتر</h1>
                        <p>تأمین و فروش تخصصی محصولات صنعتی</p>
                    </div>
                </div>

                <div class="sales-invoice__badge">فاکتور فروش</div>
            </div>

            <div class="sales-invoice__meta">
                <div class="sales-invoice__customer">
                    <h2>صورتحساب برای:</h2>

                    <h3><?php echo esc_html($customer_name); ?></h3>

                    <?php if ($full_address) : ?>
                        <p><?php echo esc_html($full_address); ?></p>
                    <?php endif; ?>

                    <?php if ($billing_postcode) : ?>
                        <p>کدپستی: <?php echo esc_html($billing_postcode); ?></p>
                    <?php endif; ?>

                    <?php if ($billing_phone) : ?>
                        <p>شماره تماس: <?php echo esc_html($billing_phone); ?></p>
                    <?php endif; ?>

                    <?php if ($billing_national_code) : ?>
                        <p>کد ملی: <?php echo esc_html($billing_national_code); ?></p>
                    <?php endif; ?>
                </div>

                <div class="sales-invoice__details">
                    <p><strong>شماره فاکتور:</strong> <?php echo esc_html($order_number); ?></p>
                    <p><strong>تاریخ:</strong> <?php echo esc_html($order_date); ?></p>
                    <p><strong>وضعیت سفارش:</strong> <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></p>

                    <?php if ($payment_title) : ?>
                        <p><strong>روش پرداخت:</strong> <?php echo esc_html($payment_title); ?></p>
                    <?php endif; ?>

                    <?php if ($transaction_id) : ?>
                        <p><strong>کد تراکنش:</strong> <?php echo esc_html($transaction_id); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sales-invoice__table-wrap">
                <table class="sales-invoice__table">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نام محصول</th>
                            <th>تعداد</th>
                            <th>قیمت</th>
                            <th>جمع</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $row_index = 1;

                        foreach ($order->get_items() as $item_id => $item) :
                            $qty = $item->get_quantity();

                            $unit_price = $qty > 0
                                ? $order->get_item_subtotal($item, false, true)
                                : 0;
                            ?>
                            <tr>
                                <td><?php echo esc_html(number_format_i18n($row_index)); ?></td>

                                <td>
                                    <?php echo esc_html($item->get_name()); ?>

                                    <?php
                                    $item_meta = wc_display_item_meta($item, array(
                                        'before'    => '<div class="sales-invoice__item-meta">',
                                        'after'     => '</div>',
                                        'separator' => '، ',
                                        'echo'      => false,
                                    ));

                                    if ($item_meta) {
                                        echo wp_kses_post($item_meta);
                                    }
                                    ?>
                                </td>

                                <td><?php echo esc_html(number_format_i18n($qty)); ?></td>

                                <td>
                                    <?php
                                    echo wp_kses_post(wc_price($unit_price, array(
                                        'currency' => $order->get_currency(),
                                    )));
                                    ?>
                                </td>

                                <td>
                                    <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
                                </td>
                            </tr>
                            <?php
                            $row_index++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="sales-invoice__bottom">
                <div class="sales-invoice__payment">
                    <h3>اطلاعات پرداخت:</h3>

                    <?php if ($payment_title) : ?>
                        <p>روش پرداخت: <?php echo esc_html($payment_title); ?></p>
                    <?php endif; ?>

                    <?php if ($transaction_id) : ?>
                        <p>کد تراکنش: <?php echo esc_html($transaction_id); ?></p>
                    <?php endif; ?>

                    <p>شماره سفارش: <?php echo esc_html($order_number); ?></p>
                </div>

                <div class="sales-invoice__summary">
                    <div class="sales-invoice__summary-row">
                        <span>جمع جزء</span>
                        <strong><?php echo wp_kses_post($order->get_subtotal_to_display()); ?></strong>
                    </div>

                    <?php if ((float) $order->get_discount_total() > 0) : ?>
                        <div class="sales-invoice__summary-row">
                            <span>تخفیف</span>
                            <strong><?php echo wp_kses_post($order->get_discount_to_display()); ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if ((float) $order->get_shipping_total() > 0) : ?>
                        <div class="sales-invoice__summary-row">
                            <span>ارسال</span>
                            <strong><?php echo wp_kses_post($order->get_shipping_to_display()); ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if ((float) $order->get_total_tax() > 0) : ?>
                        <div class="sales-invoice__summary-row">
                            <span>مالیات</span>
                            <strong>
                                <?php
                                echo wp_kses_post(wc_price($order->get_total_tax(), array(
                                    'currency' => $order->get_currency(),
                                )));
                                ?>
                            </strong>
                        </div>
                    <?php endif; ?>

                    <div class="sales-invoice__summary-row sales-invoice__summary-row--total">
                        <span>جمع کل</span>
                        <strong><?php echo wp_kses_post($order->get_formatted_order_total()); ?></strong>
                    </div>
                </div>
            </div>

            <div class="sales-invoice__footer">
                <p class="sales-invoice__thanks">از خرید شما سپاسگزاریم</p>

                <div class="sales-invoice__signature">
                    <span class="sales-invoice__signature-line"></span>
                    <p>تأیید و امضا</p>
                </div>
            </div>
        </div>
    </div>

    <div class="sales-invoice__container sales-invoice__woocommerce-hooks">
        <?php
        do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id());
        do_action('woocommerce_thankyou', $order->get_id());
        ?>
    </div>
</section>