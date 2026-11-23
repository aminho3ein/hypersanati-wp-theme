<?php
/** HSB payment for an existing order. This is NOT cart checkout. */
defined('ABSPATH') || exit;
if (!($order instanceof WC_Order)) return;
$totals = $order->get_order_item_totals();
$is_quote = 'yes' === $order->get_meta('_hsb_is_preinvoice',true);
?>
<div class="hsb-co hsb-order-pay" dir="rtl">
  <header class="hsb-co__mast">
    <nav class="hsb-co__crumb" aria-label="موقعیت صفحه"><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">حساب کاربری</a><span>/</span><span>پرداخت سفارش</span></nav>
    <h1>پرداخت <?php echo $is_quote ? 'پیش‌فاکتور تأییدشده' : 'سفارش'; ?></h1>
    <p>اقلام، تعداد و مبلغ تأییدشده را بررسی کنید؛ سپس پرداخت را از طریق درگاه فعال تکمیل کنید.</p>
  </header>
  <form id="order_review" method="post" class="hsb-order-pay__form">
    <div class="hsb-order-pay__grid">
      <main class="hsb-order-pay__main">
        <section class="hsb-co__panel" aria-labelledby="hsb-order-pay-review">
          <div class="hsb-co__panel-head"><span class="hsb-co__num">۱</span><div><h2 id="hsb-order-pay-review">بررسی سفارش</h2><p>شماره <?php echo $is_quote ? 'پیش‌فاکتور' : 'سفارش'; ?>: <?php echo esc_html($order->get_order_number()); ?></p></div></div>
          <div class="hsb-order-pay__products">
            <?php foreach ($order->get_items('line_item') as $item_id=>$item) :
              if (!apply_filters('woocommerce_order_item_visible',true,$item)) continue;
              $product=$item->get_product(); ?>
              <div class="hsb-order-pay__product">
                <span class="hsb-order-pay__photo"><?php echo $product ? $product->get_image('woocommerce_thumbnail') : ''; ?></span>
                <div class="hsb-order-pay__product-name">
                  <strong><?php echo esc_html($item->get_name()); ?></strong>
                  <?php do_action('woocommerce_order_item_meta_start',$item_id,$item,$order,false); ?>
                  <?php wc_display_item_meta($item); ?>
                  <?php do_action('woocommerce_order_item_meta_end',$item_id,$item,$order,false); ?>
                  <small>تعداد: <?php echo esc_html(wc_format_decimal($item->get_quantity(),0)); ?></small>
                </div>
                <span class="hsb-order-pay__amount"><?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="hsb-order-pay__totals">
            <?php if($totals) foreach($totals as $key=>$total): ?>
              <div class="hsb-order-pay__total <?php echo $key==='order_total'?'is-final':''; ?>">
                <span><?php echo wp_kses_post($total['label']); ?></span>
                <strong><?php echo wp_kses_post($total['value']); ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      </main>
      <aside class="hsb-order-pay__aside">
        <section class="hsb-co__panel" aria-labelledby="hsb-order-pay-method">
          <div class="hsb-co__panel-head"><span class="hsb-co__num">۲</span><div><h2 id="hsb-order-pay-method">روش پرداخت</h2><p>درگاه پرداخت آنلاین</p></div></div>
          <?php do_action('woocommerce_pay_order_before_payment'); ?>
          <div id="payment">
            <?php if($order->needs_payment()): ?>
              <?php if(!empty($available_gateways)): ?>
                <ul class="wc_payment_methods payment_methods methods" aria-label="روش‌های پرداخت">
                  <?php foreach($available_gateways as $gateway): wc_get_template('checkout/payment-method.php',array('gateway'=>$gateway)); endforeach; ?>
                </ul>
              <?php else: ?>
                <div class="hsb-order-pay__notice" role="status">درگاه پرداخت آنلاین برای این سفارش در دسترس نیست. لطفاً برای بررسی وضعیت پرداخت با پشتیبانی تماس بگیرید.</div>
              <?php endif; ?>
            <?php endif; ?>
            <div class="form-row place-order">
              <input type="hidden" name="woocommerce_pay" value="1" />
              <?php wc_get_template('checkout/terms.php'); ?>
              <?php do_action('woocommerce_pay_order_before_submit'); ?>
              <?php if(!empty($available_gateways) && $order->needs_payment()): ?>
                <?php echo apply_filters('woocommerce_pay_order_button_html','<button type="submit" class="button alt" id="place_order" value="'.esc_attr($order_button_text).'" data-value="'.esc_attr($order_button_text).'">'.esc_html($order_button_text).'</button>'); ?>
              <?php endif; ?>
              <?php do_action('woocommerce_pay_order_after_submit'); ?>
              <?php wp_nonce_field('woocommerce-pay','woocommerce-pay-nonce'); ?>
            </div>
          </div>
        </section>
      </aside>
    </div>
  </form>
</div>
