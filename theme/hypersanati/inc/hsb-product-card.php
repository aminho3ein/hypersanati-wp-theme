<?php
/** Shared WooCommerce card component: used by Shop, Home, AJAX sections. */
function hypersanati_render_product_card(
    $product_id = 0,
    $family = array()
) {
    $product_id = absint(
        $product_id ?: get_the_ID()
    );

    $card_product = wc_get_product(
        $product_id
    );

    if (
        !$card_product ||
        'publish' !== $card_product->get_status()
    ) {
        return;
    }

    $part_number = trim(
        (string) get_post_meta(
            $product_id,
            '_mpn_part_number',
            true
        )
    );

    $inner_diameter = trim(
        (string) get_post_meta(
            $product_id,
            '_inner_diameter',
            true
        )
    );

    $outer_diameter = trim(
        (string) get_post_meta(
            $product_id,
            '_outer_diameter',
            true
        )
    );

    $bearing_width = trim(
        (string) get_post_meta(
            $product_id,
            '_bearing_width',
            true
        )
    );

    $country = trim(
        (string) get_post_meta(
            $product_id,
            '_country_origin',
            true
        )
    );

    $brand_data = function_exists(
        'hsb_get_product_brand_data'
    )
        ? hsb_get_product_brand_data(
            $product_id
        )
        : array();

    $brand_name = trim(
        (string) (
            $brand_data['name'] ?? ''
        )
    );

    $format_mm = static function ($value) {
        $value = trim((string) $value);

        if ('' === $value) {
            return '—';
        }

        if (
            preg_match(
                '/^-?[0-9]+(?:[.,][0-9]+)?$/',
                $value
            )
        ) {
            return $value . ' mm';
        }

        return $value;
    };

    /* hsb-card-auto-family */
    if (
        empty($family) &&
        function_exists(
            'hsb_get_product_family_ids'
        ) &&
        function_exists(
            'hsb_group_product_ids_by_part_number'
        )
    ) {
        $family_ids =
            hsb_get_product_family_ids(
                $product_id
            );

        $family_groups =
            hsb_group_product_ids_by_part_number(
                $family_ids
            );

        if (!empty($family_groups[0])) {
            $family = $family_groups[0];
        }
    }

    $family_key = !empty(
        $family['family_key']
    )
        ? (string) $family['family_key']
        : (
            '' !== $part_number
                ? 'part:' . mb_strtoupper(
                    preg_replace(
                        '/\s+/u',
                        '',
                        $part_number
                    ),
                    'UTF-8'
                )
                : 'product:' . $product_id
        );

    $version_count = max(
        1,
        absint(
            $family['version_count'] ?? 1
        )
    );

    $brand_count = absint(
        $family['brand_count'] ?? 0
    );

    $country_count = absint(
        $family['country_count'] ?? 0
    );

    $is_family = $version_count > 1;

    $product_url = get_permalink(
        $product_id
    );
    ?>

    <a
        href="<?php echo esc_url($product_url); ?>"
        class="product-section hsb-family-product-card"
        data-family-key="<?php echo esc_attr(
            $family_key
        ); ?>"
        data-part-number="<?php echo esc_attr(
            $part_number
        ); ?>">

                <?php
        $display_image_id = absint(
            $card_product->get_image_id()
        );
        ?>

        <div class="hsb-shop-card-media product-cat-frame"><?php if ($display_image_id) : ?>

                <?php
                echo wp_get_attachment_image(
                    $display_image_id,
                    'woocommerce_thumbnail',
                    false,
                    array(
                        'loading' => 'lazy',
                        'alt' => $card_product->get_name(),
                    )
                );
                ?>

            <?php else : ?>

                <img
                    loading="lazy"
                    src="<?php echo esc_url(
                        wc_placeholder_img_src()
                    ); ?>"
                    alt="<?php echo esc_attr(
                        $card_product->get_name()
                    ); ?>">

            <?php endif; ?>


            <?php if ($is_family) : ?>

                <span class="hsb-family-card-badge">
                    <i class="fa-solid fa-layer-group"></i>

                    <?php
                    echo esc_html(
                        number_format_i18n(
                            $version_count
                        )
                    );
                    ?>
                    گزینه
                </span>

            <?php endif; ?>

        </div>


        <div class="product-name hsb-family-card-body">

            <div class="hsb-family-card-topline">

                <?php if ($part_number) : ?>

                    <div class="hsb-family-card-part">
                        <span>شماره محصول</span>

                        <strong dir="ltr">
                            <?php
                            echo esc_html(
                                $part_number
                            );
                            ?>
                        </strong>
                    </div>

                <?php endif; ?>


                <?php
                if (
                    !$is_family &&
                    ($brand_name || $country)
                ) :
                ?>

                    <div class="hsb-family-card-origin">

                        <?php if ($brand_name) : ?>
                            <span>
                                <?php
                                echo esc_html(
                                    $brand_name
                                );
                                ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($country) : ?>
                            <small>
                                <?php
                                echo esc_html(
                                    $country
                                );
                                ?>
                            </small>
                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>


            <h3 class="hsb-family-card-title">
                <?php
                echo esc_html(
                    $card_product->get_name()
                );
                ?>
            </h3>


            <?php
            hsb_render_product_card_rating(
                $card_product->get_average_rating(),
                $card_product->get_rating_count()
            );
            ?>


            <div class="hsb-family-card-dimensions">

                <div class="hsb-family-card-dimension">
                    <span>قطر داخلی</span>

                    <strong dir="ltr">
                        <?php
                        echo esc_html(
                            $format_mm(
                                $inner_diameter
                            )
                        );
                        ?>
                    </strong>

                    <small>d</small>
                </div>


                <div class="hsb-family-card-dimension">
                    <span>قطر خارجی</span>

                    <strong dir="ltr">
                        <?php
                        echo esc_html(
                            $format_mm(
                                $outer_diameter
                            )
                        );
                        ?>
                    </strong>

                    <small>D</small>
                </div>


                <div class="hsb-family-card-dimension">
                    <span>عرض</span>

                    <strong dir="ltr">
                        <?php
                        echo esc_html(
                            $format_mm(
                                $bearing_width
                            )
                        );
                        ?>
                    </strong>

                    <small>B</small>
                </div>

            </div>


            <?php if ($is_family) : ?>

                <div class="hsb-family-card-options">

                    <span>
                        <i class="fa-solid fa-layer-group"></i>

                        <?php
                        echo esc_html(
                            number_format_i18n(
                                $version_count
                            )
                        );
                        ?>
                        گزینه
                    </span>

                    <?php if ($brand_count > 0) : ?>

                        <span>
                            <i class="fa-solid fa-tag"></i>

                            <?php
                            echo esc_html(
                                number_format_i18n(
                                    $brand_count
                                )
                            );
                            ?>
                            برند
                        </span>

                    <?php endif; ?>

                    <?php if ($country_count > 1) : ?>

                        <span>
                            <i class="fa-solid fa-earth-asia"></i>

                            <?php
                            echo esc_html(
                                number_format_i18n(
                                    $country_count
                                )
                            );
                            ?>
                            کشور
                        </span>

                    <?php endif; ?>

                </div>

            <?php endif; ?>


            <div class="hsb-family-card-price-note">
    <?php if (
        !$is_family &&
        function_exists('hsb_dual_is_direct') &&
        hsb_dual_is_direct($card_product)
    ) : ?>
        <strong class="hsb-card-price-actual">
            <?php echo wp_kses_post($card_product->get_price_html()); ?>
        </strong>
    <?php elseif ($is_family) : ?>
        <span><span class="hsb-price-inquiry">قیمت نیازمند استعلام</span></span>
    <?php else : ?>
        <span><span class="hsb-price-inquiry">قیمت نیازمند استعلام</span></span>
    <?php endif; ?>
</div>


            <div class="hsb-family-card-action">

                <span>
                    <?php
                    echo $is_family
                        ? 'مشاهده و انتخاب برند'
                        : 'مشاهده محصول';
                    ?>
                </span>

                <?php
$hsb_stock = $card_product->get_stock_quantity();

if (
    !$is_family &&
    function_exists('hsb_dual_is_direct') &&
    hsb_dual_is_direct($card_product) &&
    $card_product->managing_stock() &&
    $hsb_stock !== null
) :
?>
    <span class="hsb-card-stock-count">
        موجودی:
        <?php echo esc_html(number_format_i18n($hsb_stock)); ?>
        عدد
    </span>
<?php endif; ?>

<i class="fa-solid fa-arrow-left"></i>

            </div>

        </div>

    <div class="hsb-quick-card-controls">
        <?php if ($is_family) : ?>
            <span class="hsb-quick-card-btn hsb-quick-card-btn--select">بررسی گزینه‌ها</span>
        <?php elseif ($card_product instanceof WC_Product
            && '' !== $card_product->get_price()
            && !$card_product->is_in_stock()) : ?>
            <span class="hsb-quick-card-btn hsb-stock-unavailable"
                  role="status" aria-disabled="true">
                اتمام موجودی
            </span>

        <?php elseif (hsb_dual_is_direct($card_product)) : ?>
            <?php
            $hsb_cart_qty = 0;

            if (WC()->cart) {
                foreach (WC()->cart->get_cart() as $hsb_cart_item) {
                    if (absint($hsb_cart_item['product_id'] ?? 0) === absint($product_id)) {
                        $hsb_cart_qty += absint($hsb_cart_item['quantity'] ?? 0);
                    }
                }
            }

            $hsb_limited = $card_product->managing_stock()
                && !$card_product->backorders_allowed();

            $hsb_available = $hsb_limited
                ? max(0, (int) $card_product->get_stock_quantity() - $hsb_cart_qty)
                : null;

            $hsb_exhausted = $hsb_limited && $hsb_available < 1;
            ?>

            <span class="hsb-quick-card-btn<?php echo $hsb_exhausted ? ' hsb-stock-unavailable' : ''; ?>"
                  <?php if (!$hsb_exhausted) : ?>
                      role="button"
                      tabindex="0"
                      data-hsb-direct-id="<?php echo esc_attr($product_id); ?>"
                  <?php else : ?>
                      role="status"
                      aria-disabled="true"
                  <?php endif; ?>>
                <?php echo $hsb_exhausted
                    ? 'سقف موجودی در سبد'
                    : 'افزودن به سبد خرید'; ?>
            </span>
        <?php else : ?>
            <span class="hsb-quick-card-btn hsb-quick-card-btn--quote"
      role="button" tabindex="0"
      data-hsb-quote-id="<?php echo esc_attr($product_id); ?>">
    افزودن به پیش‌فاکتور
</span>
        <?php endif; ?>
    </div>
    </a>

    <?php
}
