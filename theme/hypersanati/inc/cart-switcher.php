<?php
defined('ABSPATH') || exit;

$hsb_direct = isset($_GET['hsb_view'])
    && sanitize_key(wp_unslash($_GET['hsb_view'])) === 'direct';

$hsb_direct_url = add_query_arg(
    'hsb_view',
    'direct',
    wc_get_cart_url()
);

$hsb_quote_url = remove_query_arg(
    'hsb_view',
    wc_get_cart_url()
);
?>

<nav class="hsb-dual-cart-tabs hsb-dual-tabs-unified"
     aria-label="انتخاب نوع سبد">

    <a href="<?php echo esc_url($hsb_direct_url); ?>"
       class="<?php echo $hsb_direct ? 'is-active' : ''; ?>"
       <?php if ($hsb_direct) : ?>aria-current="page"<?php endif; ?>>
        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
        <span>سبد خرید</span>
<span class="hsb-tab-count" data-hsb-count="direct"><?php
    echo esc_html(number_format_i18n(
        WC()->cart ? WC()->cart->get_cart_contents_count() : 0
    ));
?></span>
    </a>

    <a href="<?php echo esc_url($hsb_quote_url); ?>"
       class="<?php echo !$hsb_direct ? 'is-active' : ''; ?>"
       <?php if (!$hsb_direct) : ?>aria-current="page"<?php endif; ?>>
        <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
        <span>سبد پیش‌فاکتور</span>
<span class="hsb-tab-count" data-hsb-count="quote"><?php
    $hsb_total = 0;
    $hsb_items = function_exists('hsb_get_preinvoice_items')
        ? hsb_get_preinvoice_items() : array();
    foreach ($hsb_items as $hsb_item) {
        $hsb_total += absint($hsb_item['quantity'] ?? 0);
    }
    echo esc_html(number_format_i18n($hsb_total));
?></span>
    </a>

</nav>
