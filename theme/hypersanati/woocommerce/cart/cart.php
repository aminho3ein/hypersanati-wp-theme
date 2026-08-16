<?php
/**
 * HyperSanati Preinvoice Draft Page
 */

defined('ABSPATH') || exit;

if (!function_exists('WC')) {
    return;
}

if (null === WC()->session) {
    WC()->initialize_session();
}

$items = function_exists('hsb_get_preinvoice_items')
    ? hsb_get_preinvoice_items()
    : array();

$shop_url = wc_get_page_permalink('shop');

if (!$shop_url) {
    $shop_url = home_url('/');
}


/* ---------------------------------------------------------
 * Submit preinvoice to sales team
 * --------------------------------------------------------- */
if (
    'POST' === $_SERVER['REQUEST_METHOD'] &&
    isset($_POST['hsb_preinvoice_submit'])
) {
    check_admin_referer(
        'hsb_update_preinvoice',
        'hsb_preinvoice_nonce'
    );

    if (
        class_exists('HSB_Auth_API') &&
        !HSB_Auth_API::is_logged_in()
    ) {

        if (WC()->session) {
            WC()->session->set(
                'hsb_preinvoice_login_required',
                'yes'
            );
        }

        wp_safe_redirect(
            wc_get_page_permalink('myaccount')
        );
        exit;
    }

    $result = hsb_create_preinvoice_order_from_session();

    if (is_wp_error($result)) {

        wp_safe_redirect(
            add_query_arg(
                'preinvoice_error',
                rawurlencode(
                    $result->get_error_message()
                ),
                wc_get_cart_url()
            )
        );

        exit;
    }

    wp_safe_redirect(
        add_query_arg(
            array(
                'dashboard_tab'        => 'preinvoices',
                'preinvoice_submitted' => 1,
                'preinvoice_id'        => $result->get_id(),
            ),
            wc_get_page_permalink('myaccount')
        ) . '#tab-preinvoices'
    );

    exit;
}


/* ---------------------------------------------------------
 * Update quantities
 * --------------------------------------------------------- */
if (
    'POST' === $_SERVER['REQUEST_METHOD'] &&
    isset($_POST['hsb_preinvoice_update'])
) {
    check_admin_referer(
        'hsb_update_preinvoice',
        'hsb_preinvoice_nonce'
    );

    $posted_quantities = isset($_POST['preinvoice_qty'])
        ? (array) wp_unslash($_POST['preinvoice_qty'])
        : array();

    foreach ($posted_quantities as $product_id => $quantity) {

        $product_id = absint($product_id);
        $quantity   = absint($quantity);
        $key        = (string) $product_id;

        if (!isset($items[$key])) {
            continue;
        }

        if ($quantity < 1) {
            unset($items[$key]);
            continue;
        }

        $items[$key]['quantity'] = $quantity;
    }

    WC()->session->set(
        'hsb_preinvoice_items',
        $items
    );

    wp_safe_redirect(wc_get_cart_url());
    exit;
}


/* ---------------------------------------------------------
 * Remove item
 * --------------------------------------------------------- */
if (
    isset($_GET['hsb_remove_preinvoice_item'],
          $_GET['_wpnonce'])
) {
    $remove_id = absint(
        $_GET['hsb_remove_preinvoice_item']
    );

    if (
        wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_GET['_wpnonce'])
            ),
            'hsb_remove_preinvoice_' . $remove_id
        )
    ) {
        unset($items[(string) $remove_id]);

        WC()->session->set(
            'hsb_preinvoice_items',
            $items
        );

        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
}


/* ---------------------------------------------------------
 * Re-read current state
 * --------------------------------------------------------- */
$items = function_exists('hsb_get_preinvoice_items')
    ? hsb_get_preinvoice_items()
    : array();

$total_quantity = 0;

foreach ($items as $item) {
    $total_quantity += isset($item['quantity'])
        ? absint($item['quantity'])
        : 0;
}
?>

<section class="cart-page-items preinvoice-page">
    <div class="container">

        <?php if (!empty($_GET['preinvoice_error'])) : ?>
            <div class="woocommerce-error preinvoice-error-message">
                <?php
                echo esc_html(
                    rawurldecode(
                        wp_unslash($_GET['preinvoice_error'])
                    )
                );
                ?>
            </div>
        <?php endif; ?>


        <div class="cart-page-items__header">
            <h1 class="cart-page-items__title">
                پیش‌فاکتور من
            </h1>

            <?php if (!empty($items)) : ?>
                <div class="preinvoice-status-badge">
                    در حال تکمیل
                </div>
            <?php endif; ?>
        </div>


        <?php if (empty($items)) : ?>

            <div class="cart-page-items__empty">

                <p>
                    هنوز محصولی به پیش‌فاکتور اضافه نکرده‌اید.
                </p>

                <a
                    href="<?php echo esc_url($shop_url); ?>"
                    class="cart-page-items__continue-link">
                    مشاهده محصولات
                </a>

            </div>

        <?php else : ?>

            <form
                method="post"
                action="<?php echo esc_url(wc_get_cart_url()); ?>"
                class="woocommerce-cart-form preinvoice-form">

                <div class="cart-page-items__layout">

                    <aside class="cart-page-items__sidebar">

                        <div class="cart-sidebar-card">

                            <div class="cart-sidebar-card__summary">

                                <p>
                                    <?php
                                    echo esc_html(
                                        number_format_i18n(
                                            count($items)
                                        )
                                    );
                                    ?>
                                    قلم کالا
                                </p>

                                <p>
                                    مجموع تعداد:
                                    <strong>
                                        <?php
                                        echo esc_html(
                                            number_format_i18n(
                                                $total_quantity
                                            )
                                        );
                                        ?>
                                    </strong>
                                </p>

                                <div
                                    class="cart-sidebar-card__divider">
                                </div>

                                <p>
                                    قیمت کالاها پس از بررسی
                                    واحد فروش تعیین می‌شود.
                                </p>

                            </div>

                            <?php if (is_user_logged_in()) : ?>

                                <button
                                    type="submit"
                                    name="hsb_preinvoice_submit"
                                    value="1"
                                    class="cart-sidebar-card__checkout">
                                    ارسال برای بررسی فروش
                                </button>

                            <?php else : ?>

                                <button
                                    type="submit"
                                    name="hsb_preinvoice_submit"
                                    value="1"
                                    class="cart-sidebar-card__checkout">
                                    ورود و ارسال برای بررسی
                                </button>

                            <?php endif; ?>

                            <small class="preinvoice-submit-note">
                                پس از ارسال، واحد فروش موجودی و
                                قیمت نهایی کالاها را بررسی می‌کند.
                            </small>

                        </div>

                    </aside>


                    <div class="cart-page-items__content">

                        <div class="cart-page-items__table">

                            <div class="preinvoice-products-head">

                                <div>
                                    <span class="preinvoice-products-head__eyebrow">
                                        اقلام انتخاب‌شده
                                    </span>

                                    <h2>
                                        بررسی مشخصات محصولات
                                    </h2>
                                </div>

                                <span class="preinvoice-products-head__count">
                                    <?php
                                    echo esc_html(
                                        number_format_i18n(count($items))
                                    );
                                    ?>
                                    قلم
                                </span>

                            </div>


                            <?php foreach ($items as $item) : ?>

                                <?php
                                $product_id = isset($item['product_id'])
                                    ? absint($item['product_id'])
                                    : 0;

                                $quantity = isset($item['quantity'])
                                    ? max(1, absint($item['quantity']))
                                    : 1;

                                $product = wc_get_product($product_id);

                                if (!$product) {
                                    continue;
                                }

                                $product_url = get_permalink($product_id);

                                $relation_type = isset(
                                    $item['relation']['type']
                                )
                                    ? sanitize_key(
                                        $item['relation']['type']
                                    )
                                    : 'primary';

                                $remove_url = wp_nonce_url(
                                    add_query_arg(
                                        'hsb_remove_preinvoice_item',
                                        $product_id,
                                        wc_get_cart_url()
                                    ),
                                    'hsb_remove_preinvoice_' . $product_id
                                );

                                $sku = trim((string) $product->get_sku());

                                $part_number = trim(
                                    (string) get_post_meta(
                                        $product_id,
                                        '_mpn_part_number',
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

                                $bearing_seal = trim(
                                    (string) get_post_meta(
                                        $product_id,
                                        '_bearing_seal',
                                        true
                                    )
                                );

                                $bearing_clearance = trim(
                                    (string) get_post_meta(
                                        $product_id,
                                        '_bearing_clearance',
                                        true
                                    )
                                );

                                $bearing_precision = trim(
                                    (string) get_post_meta(
                                        $product_id,
                                        '_bearing_precision',
                                        true
                                    )
                                );

                                $bearing_cage = trim(
                                    (string) get_post_meta(
                                        $product_id,
                                        '_bearing_cage',
                                        true
                                    )
                                );

                                $brand_name = '';

                                if (
                                    function_exists(
                                        'hsb_get_product_brand_data'
                                    )
                                ) {
                                    $brand_data =
                                        hsb_get_product_brand_data(
                                            $product_id
                                        );

                                    if (
                                        is_array($brand_data) &&
                                        !empty($brand_data['name'])
                                    ) {
                                        $brand_name = trim(
                                            (string) $brand_data['name']
                                        );
                                    }
                                }

                                if ('' === $brand_name) {
                                    $brand_terms = get_the_terms(
                                        $product_id,
                                        'product_brand'
                                    );

                                    if (
                                        !empty($brand_terms) &&
                                        !is_wp_error($brand_terms)
                                    ) {
                                        $brand_name = trim(
                                            (string)
                                            $brand_terms[0]->name
                                        );
                                    }
                                }

                                $format_mm = static function ($value) {
                                    $value = trim((string) $value);

                                    if ('' === $value) {
                                        return '';
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

                                $spec_items = array(
                                    array(
                                        'label' => 'برند',
                                        'value' => $brand_name,
                                        'icon'  => 'fa-tag',
                                    ),
                                    array(
                                        'label' => 'کشور سازنده',
                                        'value' => $country,
                                        'icon'  => 'fa-earth-asia',
                                    ),
                                    array(
                                        'label' => 'قطر داخلی',
                                        'value' => $format_mm(
                                            $inner_diameter
                                        ),
                                        'icon'  => 'fa-circle-dot',
                                    ),
                                    array(
                                        'label' => 'قطر خارجی',
                                        'value' => $format_mm(
                                            $outer_diameter
                                        ),
                                        'icon'  => 'fa-circle',
                                    ),
                                    array(
                                        'label' => 'عرض',
                                        'value' => $format_mm(
                                            $bearing_width
                                        ),
                                        'icon'  => 'fa-arrows-left-right',
                                    ),
                                    array(
                                        'label' => 'آب‌بندی',
                                        'value' => $bearing_seal,
                                        'icon'  => 'fa-shield-halved',
                                    ),
                                    array(
                                        'label' => 'لقی',
                                        'value' => $bearing_clearance,
                                        'icon'  => 'fa-up-right-and-down-left-from-center',
                                    ),
                                    array(
                                        'label' => 'کلاس دقت',
                                        'value' => $bearing_precision,
                                        'icon'  => 'fa-crosshairs',
                                    ),
                                    array(
                                        'label' => 'قفسه',
                                        'value' => $bearing_cage,
                                        'icon'  => 'fa-layer-group',
                                    ),
                                );

                                $spec_items = array_values(
                                    array_filter(
                                        $spec_items,
                                        static function ($spec) {
                                            return !empty(
                                                trim(
                                                    (string)
                                                    $spec['value']
                                                )
                                            );
                                        }
                                    )
                                );
                                ?>

                                <article
                                    class="preinvoice-product-card <?php echo 'required' === $relation_type ? 'is-required-product' : ''; ?>">

                                    <a
                                        href="<?php echo esc_url($remove_url); ?>"
                                        class="preinvoice-product-card__remove"
                                        aria-label="حذف محصول از پیش‌فاکتور"
                                        title="حذف از پیش‌فاکتور">

                                        <i class="fa-regular fa-trash-can"></i>

                                    </a>


                                    <div class="preinvoice-product-card__top">

                                        <a
                                            href="<?php echo esc_url($product_url); ?>"
                                            class="preinvoice-product-card__image">

                                            <?php
                                            echo wp_kses_post(
                                                $product->get_image(
                                                    'woocommerce_thumbnail'
                                                )
                                            );
                                            ?>

                                        </a>


                                        <div class="preinvoice-product-card__main">

                                            <div class="preinvoice-product-card__labels">

                                                <?php
                                                if (
                                                    'required' ===
                                                    $relation_type
                                                ) :
                                                ?>

                                                    <span class="preinvoice-required-badge">
                                                        همراه اجباری
                                                    </span>

                                                <?php else : ?>

                                                    <span class="preinvoice-primary-badge">
                                                        محصول انتخابی
                                                    </span>

                                                <?php endif; ?>

                                            </div>


                                            <h2 class="preinvoice-product-card__title">
                                                <a
                                                    href="<?php echo esc_url($product_url); ?>">

                                                    <?php
                                                    echo esc_html(
                                                        $product->get_name()
                                                    );
                                                    ?>

                                                </a>
                                            </h2>


                                            <div class="preinvoice-product-card__codes">

                                                <?php if ($part_number) : ?>

                                                    <div class="preinvoice-product-code">
                                                        <span>
                                                            Part Number
                                                        </span>

                                                        <strong dir="ltr">
                                                            <?php
                                                            echo esc_html(
                                                                $part_number
                                                            );
                                                            ?>
                                                        </strong>
                                                    </div>

                                                <?php endif; ?>


                                                <?php if ($sku) : ?>

                                                    <div class="preinvoice-product-code">
                                                        <span>
                                                            SKU
                                                        </span>

                                                        <strong dir="ltr">
                                                            <?php
                                                            echo esc_html(
                                                                $sku
                                                            );
                                                            ?>
                                                        </strong>
                                                    </div>

                                                <?php endif; ?>

                                            </div>

                                        </div>

                                    </div>


                                    <?php if (!empty($spec_items)) : ?>

                                        <div class="preinvoice-product-specs">

                                            <?php
                                            foreach (
                                                $spec_items as $spec
                                            ) :
                                            ?>

                                                <div class="preinvoice-product-spec">

                                                    <span class="preinvoice-product-spec__icon">
                                                        <i
                                                            class="fa-solid <?php echo esc_attr($spec['icon']); ?>">
                                                        </i>
                                                    </span>

                                                    <div>
                                                        <small>
                                                            <?php
                                                            echo esc_html(
                                                                $spec['label']
                                                            );
                                                            ?>
                                                        </small>

                                                        <strong>
                                                            <?php
                                                            echo esc_html(
                                                                $spec['value']
                                                            );
                                                            ?>
                                                        </strong>
                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endif; ?>


                                    <div class="preinvoice-product-card__footer">

                                        <div class="preinvoice-product-card__qty-group">

                                            <span class="preinvoice-product-card__qty-label">
                                                تعداد موردنیاز
                                            </span>

                                            <div class="cart-page-items__quantity">

                                                <div class="quantity">

                                                    <button
                                                        type="button"
                                                        class="cart-page-items__qty-btn cart-page-items__qty-btn--minus"
                                                        aria-label="کاهش تعداد">
                                                        −
                                                    </button>

                                                    <input
                                                        type="number"
                                                        class="cart-page-items__qty-input qty"
                                                        name="preinvoice_qty[<?php echo esc_attr($product_id); ?>]"
                                                        value="<?php echo esc_attr($quantity); ?>"
                                                        min="1"
                                                        step="1"
                                                        inputmode="numeric">

                                                    <button
                                                        type="button"
                                                        class="cart-page-items__qty-btn cart-page-items__qty-btn--plus"
                                                        aria-label="افزایش تعداد">
                                                        +
                                                    </button>

                                                </div>

                                            </div>

                                        </div>


                                        <a
                                            href="<?php echo esc_url($product_url); ?>"
                                            class="preinvoice-product-card__details-link">

                                            مشاهده مشخصات کامل محصول

                                            <i class="fa-solid fa-arrow-left"></i>

                                        </a>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </div>


                        <div class="cart-page-items__footer-actions">

                            <button
                                type="submit"
                                class="cart-page-items__update-btn"
                                name="hsb_preinvoice_update"
                                value="1">
                                به‌روزرسانی پیش‌فاکتور
                            </button>

                            <a
                                href="<?php echo esc_url($shop_url); ?>"
                                class="cart-page-items__continue-link">
                                ادامه انتخاب محصولات
                            </a>

                            <?php
                            wp_nonce_field(
                                'hsb_update_preinvoice',
                                'hsb_preinvoice_nonce'
                            );
                            ?>

                        </div>

                    </div>

                </div>

            </form>

        <?php endif; ?>

    </div>
</section>
