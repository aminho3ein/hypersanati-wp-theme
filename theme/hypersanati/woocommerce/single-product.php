<?php get_header(); ?>

<?php
if (!function_exists('wc_get_product')) {
    echo '<p>WooCommerce is not active.</p>';
    get_footer();
    exit;
}

global $product;

if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if ($product) :
    $product_id = $product->get_id();

    // Get product gallery images
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    if ($main_image_id) {
        array_unshift($attachment_ids, $main_image_id);
    }

    // Get average rating
    $average_rating = $product->get_average_rating();
    $rating_count = $product->get_rating_count();

    // Get technical specs from custom fields
    $specs = [
        'شماره فنی / پارت نامبر' => get_post_meta($product_id, '_mpn_part_number', true),
        'قطر داخلی' => get_post_meta($product_id, '_inner_diameter', true),
        'قطر خارجی' => get_post_meta($product_id, '_outer_diameter', true),
        'عرض' => get_post_meta($product_id, '_bearing_width', true),
        'نوع آب‌بندی' => get_post_meta($product_id, '_bearing_seal', true),
        'لقی' => get_post_meta($product_id, '_bearing_clearance', true),
        'کلاس دقت' => get_post_meta($product_id, '_bearing_precision', true),
        'جنس' => get_post_meta($product_id, '_bearing_material', true),
        'قفسه' => get_post_meta($product_id, '_bearing_cage', true),
        'روانکاری' => get_post_meta($product_id, '_bearing_lubrication', true),
        'بار دینامیکی' => get_post_meta($product_id, '_dynamic_load', true),
        'بار استاتیکی' => get_post_meta($product_id, '_static_load', true),
        'حداکثر دور' => get_post_meta($product_id, '_max_rpm', true),
        'کشور سازنده' => get_post_meta($product_id, '_country_origin', true),
        'کاربرد' => get_post_meta($product_id, '_bearing_usage', true),
        'صنعت' => get_post_meta($product_id, '_bearing_industry', true),
        'کدهای معادل' => get_post_meta($product_id, '_equivalent_codes', true),
    ];
    ?>

    <?php
    /* =========================================================
     * HSB INDUSTRIAL SINGLE PRODUCT HERO
     * ========================================================= */

    $product_sku = trim((string) $product->get_sku());

    $part_number = trim(
        (string) get_post_meta(
            $product_id,
            '_mpn_part_number',
            true
        )
    );

    $country_origin = trim(
        (string) get_post_meta(
            $product_id,
            '_country_origin',
            true
        )
    );

    $brand_name = '';

    if (function_exists('hsb_get_product_brand_data')) {
        $brand_data = hsb_get_product_brand_data($product_id);

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
                (string) $brand_terms[0]->name
            );
        }
    }


    /*
     * Products with the same Part Number remain real,
     * independent WooCommerce products.
     *
     * This array provides the UX layer used for switching
     * between brand / country versions.
     */
    $product_alternatives = array();

    if (
        function_exists(
            'hsb_get_product_alternatives'
        )
    ) {
        $product_alternatives =
            hsb_get_product_alternatives(
                $product_id
            );
    }


    /*
     * Lightweight metadata for no-reload family switching.
     * Images themselves are not aggressively preloaded.
     */
    $product_family_live_data = function_exists(
        'hsb_get_product_family_live_data'
    )
        ? hsb_get_product_family_live_data(
            $product_id
        )
        : array();


    /*
     * Required companion products.
     */
    $required_product_ids = array();

    if (
        function_exists(
            'hsb_get_required_product_ids'
        )
    ) {
        $required_product_ids =
            hsb_get_required_product_ids(
                $product_id
            );
    }


    /*
     * Key buyer-facing technical specs.
     */
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

    $key_specs = array(
        array(
            'label' => 'قطر داخلی',
            'value' => $format_mm(
                get_post_meta(
                    $product_id,
                    '_inner_diameter',
                    true
                )
            ),
            'icon' => 'fa-circle-dot',
        ),
        array(
            'label' => 'قطر خارجی',
            'value' => $format_mm(
                get_post_meta(
                    $product_id,
                    '_outer_diameter',
                    true
                )
            ),
            'icon' => 'fa-circle',
        ),
        array(
            'label' => 'عرض',
            'value' => $format_mm(
                get_post_meta(
                    $product_id,
                    '_bearing_width',
                    true
                )
            ),
            'icon' => 'fa-arrows-left-right',
        ),
        array(
            'label' => 'آب‌بندی',
            'value' => trim(
                (string) get_post_meta(
                    $product_id,
                    '_bearing_seal',
                    true
                )
            ),
            'icon' => 'fa-shield-halved',
        ),
        array(
            'label' => 'لقی',
            'value' => trim(
                (string) get_post_meta(
                    $product_id,
                    '_bearing_clearance',
                    true
                )
            ),
            'icon' => 'fa-up-right-and-down-left-from-center',
        ),
        array(
            'label' => 'کلاس دقت',
            'value' => trim(
                (string) get_post_meta(
                    $product_id,
                    '_bearing_precision',
                    true
                )
            ),
            'icon' => 'fa-crosshairs',
        ),
    );

    $key_specs = array_values(
        array_filter(
            $key_specs,
            static function ($spec) {
                return '' !== trim(
                    (string) $spec['value']
                );
            }
        )
    );

    $full_specs = array();

    foreach ($specs as $label => $value) {
        $value = trim((string) $value);

        if ('' === $value) {
            continue;
        }

        if (
            in_array(
                $label,
                array(
                    'قطر داخلی',
                    'قطر خارجی',
                    'عرض',
                ),
                true
            )
        ) {
            $value = $format_mm($value);
        }

        $full_specs[$label] = $value;
    }

    $gallery_count = count($attachment_ids);
    ?>


    <?php
$hero_brand_image_url = trim(
    (string) (
        $brand_data['image_url']
        ?? ''
    )
);

$hero_country_flag_data =
    function_exists('hsb_get_country_flag_data')
        ? hsb_get_country_flag_data($country_origin)
        : array();

$hero_country_flag_url = trim(
    (string) (
        $hero_country_flag_data['image_url']
        ?? ''
    )
);
?>

<main class="hsb-sp-page" dir="rtl">

        <!-- Breadcrumb -->
        <div class="hsb-sp-breadcrumb">
            <?php woocommerce_breadcrumb(); ?>
        </div>


        <!-- Main industrial product hero -->
        <section class="hsb-sp-hero">

            <!-- =========================================
                 PRODUCT IDENTITY / TECHNICAL DECISION
                 ========================================= -->
            <div class="hsb-sp-identity">

                <div class="hsb-sp-kicker">

                <?php if ($hero_brand_image_url) : ?>
                    <span
                        class="hsb-sp-brand-badge"
                        title="<?php echo esc_attr($brand_name); ?>">
                        <img
                            src="<?php echo esc_url($hero_brand_image_url); ?>"
                            alt="<?php echo esc_attr($brand_name); ?>">
                    </span>
                <?php endif; ?>

                <?php if ($hero_country_flag_url) : ?>
                    <span
                        class="hsb-sp-country-badge"
                        title="<?php echo esc_attr($country_origin); ?>">
                        <img
                            src="<?php echo esc_url($hero_country_flag_url); ?>"
                            alt="<?php echo esc_attr($country_origin); ?>">
                    </span>
                <?php endif; ?>

            </div>

            <h1 class="hsb-sp-title">
                    <?php the_title(); ?>
                </h1>


                <div class="hsb-sp-product-codes">

                    <?php if ($part_number) : ?>

                        <div class="hsb-sp-code-box">
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


                    <?php if ($product_sku) : ?>

                        <div class="hsb-sp-code-box">
                            <span>شناسه یکتای محصول</span>

                            <strong dir="ltr">
                                <?php
                                echo esc_html(
                                    $product_sku
                                );
                                ?>
                            </strong>
                        </div>

                    <?php endif; ?>

                </div>


                <!-- Rating -->
                <div class="hsb-sp-rating">

                    <div class="hsb-sp-stars"
                        aria-label="<?php echo esc_attr(
                            number_format_i18n(
                                (float) $average_rating,
                                1
                            ) . ' از ۵'
                        ); ?>">

                        <?php for ($i = 1; $i <= 5; $i++) : ?>

                            <?php
                            if (
                                (float) $average_rating >= $i
                            ) {
                                $star_class =
                                    'fa-solid fa-star is-active';
                            } elseif (
                                (float) $average_rating >=
                                ($i - 0.5)
                            ) {
                                $star_class =
                                    'fa-solid fa-star-half-stroke is-active';
                            } else {
                                $star_class =
                                    'fa-regular fa-star';
                            }
                            ?>

                            <i class="<?php echo esc_attr(
                                $star_class
                            ); ?>"></i>

                        <?php endfor; ?>

                    </div>

                    <span>
                        <?php if ($rating_count > 0) : ?>

                            <?php
                            echo esc_html(
                                number_format_i18n(
                                    $rating_count
                                )
                            );
                            ?>
                            رأی

                        <?php else : ?>

                            هنوز امتیازی ثبت نشده

                        <?php endif; ?>
                    </span>

                </div>


                <!-- Key dimensions/specs -->
                <?php if (!empty($key_specs)) : ?>

                    <div class="hsb-sp-key-specs">

                        <?php
                        foreach ($key_specs as $spec) :
                        ?>

                            <?php
                            $is_detail_spec = in_array(
                                $spec['label'],
                                array(
                                    'آب‌بندی',
                                    'لقی',
                                    'کلاس دقت',
                                ),
                                true
                            );
                            ?>

                            <div class="hsb-sp-key-spec<?php echo $is_detail_spec ? ' is-detail-spec' : ''; ?>">

                                <span class="hsb-sp-key-spec__icon">
                                    <i
                                        class="fa-solid <?php echo esc_attr(
                                            $spec['icon']
                                        ); ?>">
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


                <!-- Product family selector -->
                <?php
                if (
                    is_array($product_alternatives) &&
                    count($product_alternatives) > 1
                ) :

                    $family_brands = array();

                    foreach (
                        $product_alternatives as
                        $family_item
                    ) {
                        $family_brand = trim(
                            (string) (
                                $family_item['brand']
                                ?? ''
                            )
                        );

                        $family_brand_slug = trim(
                            (string) (
                                $family_item['brand_slug']
                                ?? ''
                            )
                        );

                        if ('' === $family_brand) {
                            $family_brand = 'بدون برند';
                        }

                        if ('' === $family_brand_slug) {
                            $family_brand_slug =
                                sanitize_title(
                                    $family_brand
                                );
                        }

                        $family_brands[
                            $family_brand_slug
                        ] = $family_brand;
                    }

                    $current_brand_slug = '';

                    foreach (
                        $product_alternatives as
                        $family_item
                    ) {
                        if (
                            !empty(
                                $family_item['current']
                            )
                        ) {
                            $current_brand_slug = trim(
                                (string) (
                                    $family_item[
                                        'brand_slug'
                                    ]
                                    ?? ''
                                )
                            );

                            if (
                                '' ===
                                $current_brand_slug
                            ) {
                                $current_brand_slug =
                                    sanitize_title(
                                        (string) (
                                            $family_item[
                                                'brand'
                                            ]
                                            ?? ''
                                        )
                                    );
                            }

                            break;
                        }
                    }
                ?>

                    <div
                        class="hsb-sp-family-switcher"
                        data-current-product="<?php echo esc_attr(
                            $product_id
                        ); ?>">

                        <div
                            class="hsb-sp-family-switcher__head">

                            <div>
                                <span>
                                    همین محصول را می‌توانید با برند و کشور سازنده دیگری هم انتخاب کنید.
                                </span>

                                <strong>
                                    انتخاب برند و کشور سازنده
                                </strong>
                            </div>

                            <i
                                class="fa-solid fa-code-compare">
                            </i>

                        </div>


                        <div class="hsb-sp-family-field">

                            <span
                                class="hsb-sp-family-field__label">
                                ۱. برند
                            </span>

                            <div
                                class="hsb-sp-family-brand-list">

                                <?php
                                foreach (
                                    $family_brands as
                                    $family_brand_slug =>
                                    $family_brand_name
                                ) :
                                ?>

                                    <?php
                                    $family_brand_term =
                                        get_term_by(
                                            'slug',
                                            $family_brand_slug,
                                            'product_brand'
                                        );

                                    $family_brand_image_id = 0;

                                    if (
                                        $family_brand_term &&
                                        !is_wp_error(
                                            $family_brand_term
                                        )
                                    ) {
                                        $family_brand_image_id =
                                            absint(
                                                get_term_meta(
                                                    $family_brand_term->term_id,
                                                    'thumbnail_id',
                                                    true
                                                )
                                            );

                                        if (
                                            !$family_brand_image_id
                                        ) {
                                            $family_brand_image_id =
                                                absint(
                                                    get_term_meta(
                                                        $family_brand_term->term_id,
                                                        'brand_image_id',
                                                        true
                                                    )
                                                );
                                        }
                                    }

                                    $family_brand_image_url =
                                        $family_brand_image_id
                                            ? wp_get_attachment_image_url(
                                                $family_brand_image_id,
                                                'thumbnail'
                                            )
                                            : '';
                                    ?>

                                    <button
                                        type="button"
                                        class="hsb-sp-family-brand<?php echo $family_brand_slug === $current_brand_slug ? ' is-active' : ''; ?>"
                                        data-family-brand="<?php echo esc_attr(
                                            $family_brand_slug
                                        ); ?>">

                                        <span class="hsb-sp-family-choice-visual hsb-sp-family-brand-visual">

                                            <?php
                                            if (
                                                $family_brand_image_url
                                            ) :
                                            ?>
                                                <img
                                                    class="hsb-sp-family-choice-icon"
                                                    src="<?php echo esc_url(
                                                        $family_brand_image_url
                                                    ); ?>"
                                                    alt="<?php echo esc_attr(
                                                        $family_brand_name
                                                    ); ?>"
                                                    loading="lazy">
                                            <?php endif; ?>

                                        </span>

                                        <span>
                                            <?php
                                            echo esc_html(
                                                $family_brand_name
                                            );
                                            ?>
                                        </span>

                                    </button>

                                <?php endforeach; ?>

                            </div>

                        </div>


                        <div class="hsb-sp-family-field">

                            <span
                                class="hsb-sp-family-field__label">
                                ۲. کشور سازنده
                            </span>

                            <div
                                class="hsb-sp-family-country-list">

                                <?php
                                foreach (
                                    $product_alternatives as
                                    $family_item
                                ) :

                                    $family_id = absint(
                                        $family_item[
                                            'product_id'
                                        ] ?? 0
                                    );

                                    if (!$family_id) {
                                        continue;
                                    }

                                    $family_item_brand_slug =
                                        trim(
                                            (string) (
                                                $family_item[
                                                    'brand_slug'
                                                ]
                                                ?? ''
                                            )
                                        );

                                    if (
                                        '' ===
                                        $family_item_brand_slug
                                    ) {
                                        $family_item_brand_slug =
                                            sanitize_title(
                                                (string) (
                                                    $family_item[
                                                        'brand'
                                                    ]
                                                    ?? ''
                                                )
                                            );
                                    }

                                    $family_country = trim(
                                        (string) (
                                            $family_item[
                                                'country'
                                            ]
                                            ?? ''
                                        )
                                    );

                                    if (
                                        '' ===
                                        $family_country
                                    ) {
                                        $family_country =
                                            'کشور نامشخص';
                                    }

                                    $family_sku = trim(
                                        (string) (
                                            $family_item[
                                                'sku'
                                            ]
                                            ?? ''
                                        )
                                    );

                                    $family_is_current =
                                        !empty(
                                            $family_item[
                                                'current'
                                            ]
                                        );

                                    $family_is_hidden =
                                        $family_item_brand_slug
                                        !==
                                        $current_brand_slug;
                                ?>

                                    <button
                                        type="button"
                                        class="hsb-sp-family-country<?php echo $family_is_current ? ' is-active' : ''; ?>"
                                        data-family-brand="<?php echo esc_attr(
                                            $family_item_brand_slug
                                        ); ?>"
                                        data-product-id="<?php echo esc_attr(
                                            $family_id
                                        ); ?>"
                                        data-product-url="<?php echo esc_url(
                                            $family_item['url']
                                        ); ?>"
                                        <?php echo $family_is_hidden ? 'hidden' : ''; ?>>

                                        <?php
                                        $family_flag =
                                            function_exists(
                                                'hsb_get_country_flag_data'
                                            )
                                                ? hsb_get_country_flag_data(
                                                    $family_country
                                                )
                                                : array();

                                        $family_flag_url =
                                            trim(
                                                (string) (
                                                    $family_flag[
                                                        'image_url'
                                                    ]
                                                    ?? ''
                                                )
                                            );
                                        ?>

                                        <span class="hsb-sp-family-choice-visual hsb-sp-family-country-visual">

                                            <?php if ($family_flag_url) : ?>
                                                <img
                                                    class="hsb-sp-family-choice-icon hsb-sp-family-country-flag"
                                                    src="<?php echo esc_url(
                                                        $family_flag_url
                                                    ); ?>"
                                                    alt="<?php echo esc_attr(
                                                        $family_country
                                                    ); ?>"
                                                    loading="lazy">
                                            <?php endif; ?>

                                        </span>

                                        <span>
                                            <?php
                                            echo esc_html(
                                                $family_country
                                            );
                                            ?>
                                        </span>

                                        <?php
                                        if ($family_sku) :
                                        ?>
                                            <small dir="ltr">
                                                <?php
                                                echo esc_html(
                                                    $family_sku
                                                );
                                                ?>
                                            </small>
                                        <?php endif; ?>

                                        <?php
                                        if ($family_is_current) :
                                        ?>
                                            <i
                                                class="fa-solid fa-check">
                                            </i>
                                        <?php endif; ?>

                                    </button>

                                <?php endforeach; ?>

                            </div>

                        </div>


                        <script
                            type="application/json"
                            class="hsb-sp-family-live-data"><?php
                            echo wp_json_encode(
                                $product_family_live_data,
                                JSON_UNESCAPED_UNICODE
                                | JSON_UNESCAPED_SLASHES
                                | JSON_HEX_TAG
                                | JSON_HEX_AMP
                                | JSON_HEX_APOS
                                | JSON_HEX_QUOT
                            );
                        ?></script>

                    </div>

                <?php endif; ?>

            </div>


            <!-- =========================================
                 PRODUCT MEDIA
                 Keep legacy gallery hooks for JS.
                 ========================================= -->
            <div class="hsb-sp-media product-images">

                <div class="hsb-sp-main-image single-image">

                    <div
                        class="hsb-sp-main-image__frame single-image-frame">

                        <?php if (has_post_thumbnail()) : ?>

                            <?php
                            echo get_the_post_thumbnail(
                                $product_id,
                                'large',
                                array(
                                    'class' =>
                                        'main-product-image',
                                )
                            );
                            ?>

                        <?php else : ?>

                            <img
                                src="<?php echo esc_url(
                                    wc_placeholder_img_src()
                                ); ?>"
                                alt="<?php echo esc_attr(
                                    get_the_title()
                                ); ?>">

                        <?php endif; ?>

                    </div>


                    <?php if ($gallery_count > 1) : ?>

                        <button
                            class="hsb-sp-gallery-arrow hsb-sp-gallery-arrow--right carousel-control-prev custom-btn"
                            type="button"
                            id="prev-image"
                            aria-label="تصویر قبلی">

                            <i
                                class="fa-solid fa-chevron-right">
                            </i>

                        </button>


                        <button
                            class="hsb-sp-gallery-arrow hsb-sp-gallery-arrow--left carousel-control-next custom-btn"
                            type="button"
                            id="next-image"
                            aria-label="تصویر بعدی">

                            <i
                                class="fa-solid fa-chevron-left">
                            </i>

                        </button>

                    <?php endif; ?>

                </div>


                <?php if (!empty($attachment_ids)) : ?>

                    <div
                        class="hsb-sp-gallery product-image-gallery">

                        <?php
                        foreach (
                            $attachment_ids as
                            $index => $attachment_id
                        ) :
                        ?>

                            <button
                                type="button"
                                class="hsb-sp-gallery-thumb product-image-gallery-frame <?php echo 0 === $index ? 'active' : ''; ?>"
                                data-index="<?php echo esc_attr(
                                    $index
                                ); ?>"
                                aria-label="نمایش تصویر <?php echo esc_attr(
                                    $index + 1
                                ); ?>">

                                <?php
                                echo wp_get_attachment_image(
                                    $attachment_id,
                                    'thumbnail'
                                );
                                ?>

                            </button>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>


                <?php if (!$main_image_id) : ?>

                    <span class="hsb-sp-media-note is-placeholder-note">
                        <i class="fa-regular fa-image"></i>
                        تصویر محصول هنوز ثبت نشده است
                    </span>

                <?php endif; ?>

            </div>


            <!-- =========================================
                 PREINVOICE / SALES REVIEW CARD
                 ========================================= -->
            <aside class="hsb-sp-inquiry">

                <div class="hsb-sp-inquiry__icon">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>

                <span class="hsb-sp-inquiry__eyebrow">
                    استعلام قیمت و موجودی
                </span>

                <h2>
                    درخواست پیش‌فاکتور
                </h2>

                <p class="hsb-sp-inquiry__intro">
                    قیمت، موجودی و زمان تأمین این کالا
                    توسط واحد فروش بررسی و تأیید می‌شود.
                </p>


                <div class="hsb-sp-review-status">

                    <span>
                        <i class="fa-solid fa-circle-check"></i>
                    </span>

                    <div>
                        <strong>
                            بررسی قبل از پرداخت
                        </strong>

                        <small>
                            در این مرحله پرداختی انجام نمی‌شود
                        </small>
                    </div>

                </div>


                <?php if (!empty($required_product_ids)) : ?>

                    <div class="hsb-sp-required-note">

                        <i class="fa-solid fa-link"></i>

                        <div>
                            <strong>
                                دارای کالای همراه اجباری
                            </strong>

                            <span>
                                هنگام افزودن،
                                <?php
                                echo esc_html(
                                    number_format_i18n(
                                        count(
                                            $required_product_ids
                                        )
                                    )
                                );
                                ?>
                                قلم همراه نیز خودکار به
                                پیش‌فاکتور اضافه می‌شود.
                            </span>
                        </div>

                    </div>

                <?php endif; ?>


                <form
                    class="product-preinvoice-form hsb-sp-preinvoice-form"
                    data-ajax-url="<?php echo esc_url(
                        admin_url('admin-ajax.php')
                    ); ?>"
                    data-nonce="<?php echo esc_attr(
                        wp_create_nonce(
                            'hsb_preinvoice_nonce'
                        )
                    ); ?>">

                    <input
                        type="hidden"
                        name="product_id"
                        value="<?php echo esc_attr(
                            $product_id
                        ); ?>">


                    <div class="purchase-field hsb-sp-qty-field">

                        <label for="product-quantity">
                            تعداد موردنیاز
                        </label>


                        <div
                            class="product-quantity-control hsb-sp-qty">

                            <button
                                type="button"
                                class="product-quantity-btn qty-minus"
                                aria-label="کاهش تعداد">
                                <i
                                    class="fa-solid fa-minus">
                                </i>
                            </button>

                            <input
                                type="number"
                                id="product-quantity"
                                name="quantity"
                                class="product-quantity-input"
                                value="1"
                                min="1"
                                step="1"
                                inputmode="numeric"
                                aria-label="تعداد موردنیاز">

                            <button
                                type="button"
                                class="product-quantity-btn qty-plus"
                                aria-label="افزایش تعداد">
                                <i
                                    class="fa-solid fa-plus">
                                </i>
                            </button>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="preinvoice-add-button hsb-sp-add-button">

                        <i class="fa-solid fa-plus"></i>

                        افزودن به پیش‌فاکتور

                    </button>


                    <div
                        class="preinvoice-add-message"
                        aria-live="polite">
                    </div>

                </form>


                <div class="hsb-sp-inquiry__footer">

                    <div>
                        <i class="fa-solid fa-headset"></i>
                        بررسی توسط واحد فروش
                    </div>

                    <div>
                        <i class="fa-solid fa-shield-halved"></i>
                        تأیید مشخصات قبل از خرید
                    </div>

                </div>

            </aside>

        </section>


        <!-- =============================================
             FULL TECHNICAL SPECIFICATION PANEL
             ============================================= -->
        <?php if (!empty($full_specs)) : ?>

            <section class="hsb-sp-spec-panel">

                <div class="hsb-sp-spec-panel__head">

                    <div>
                        <span>
                            اطلاعات فنی محصول انتخاب‌شده
                        </span>

                        <h2>
                            مشخصات فنی
                        </h2>
                    </div>

                    <div class="hsb-sp-spec-panel__code">
                        <?php if ($part_number) : ?>
                            <small>شماره محصول</small>
                            <strong dir="ltr">
                                <?php
                                echo esc_html(
                                    $part_number
                                );
                                ?>
                            </strong>
                        <?php endif; ?>
                    </div>

                </div>


                <div class="hsb-sp-full-specs">

                    <?php
                    foreach (
                        $full_specs as
                        $label => $value
                    ) :
                    ?>

                        <div class="hsb-sp-full-spec">

                            <span>
                                <?php
                                echo esc_html($label);
                                ?>
                            </span>

                            <strong>
                                <?php
                                echo esc_html($value);
                                ?>
                            </strong>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

        <?php endif; ?>

    </main>


<?php theme_render_product_benefits_area('single_product_benefits_soft', 'soft'); ?>
    <!-- product-meta-tabs -->
    <div class="product-meta-tabs">
        <nav class="product-meta-nav">
            <button type="button" data-tab="reviews">
                <i class="fa-solid fa-comments"></i>
                نقد و نظرات
            </button>
            <button type="button" class="between active" data-tab="desc">
                <i class="fa-solid fa-circle-info"></i>
                توضیحات کالا
            </button>
            <button type="button" data-tab="qa">
                <i class="fa-solid fa-circle-question"></i>
                پرسش و پاسخ
            </button>
        </nav>
    </div>

    <!-- product-meta-content -->
    <div class="product-meta-content">
<!-- Start Product Informatin And Descriprion -->
<?php
defined('ABSPATH') || exit;

global $product;

if (!$product) {
    $product = wc_get_product(get_the_ID());
}

$product_id = $product ? $product->get_id() : get_the_ID();

$product_title = get_the_title($product_id);

$product_content = apply_filters(
    'the_content',
    get_post_field('post_content', $product_id)
);

$product_short_description = $product
    ? apply_filters('woocommerce_short_description', $product->get_short_description())
    : '';

$product_sku = $product ? $product->get_sku() : '';

$product_categories = function_exists('wc_get_product_category_list')
    ? wc_get_product_category_list($product_id, '، ')
    : '';

$stock_status = '';

if ($product) {
    if ($product->is_in_stock()) {
        $stock_status = 'موجود در انبار';
    } elseif ($product->is_on_backorder()) {
        $stock_status = 'قابل سفارش';
    } else {
        $stock_status = 'نیازمند تأیید موجودی و زمان تأمین';
    }
}

$average_rating = $product ? (float) $product->get_average_rating() : 0;
$rating_count   = $product ? (int) $product->get_rating_count() : 0;

if (!function_exists('theme_fa_digits')) {
    function theme_fa_digits($text) {
        return strtr((string) $text, array(
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ));
    }
}
?>

<div class="tab-panel product-desc-panel active" id="desc">
    <div class="product-desc-layout">

        <article class="product-desc-card desc-main-card">
            <div class="desc-section-head">
                <div class="desc-section-icon">
                    <i class="fa-regular fa-file-lines"></i>
                </div>

                <div>
                    <span>معرفی محصول</span>
                    <h2><?php echo esc_html($product_title); ?></h2>
                </div>
            </div>

            <?php if (!empty(trim(wp_strip_all_tags($product_short_description)))) : ?>
                <div class="desc-short-text">
                    <?php echo wp_kses_post($product_short_description); ?>
                </div>
            <?php endif; ?>

            <div class="desc-divider"></div>

            <?php if (!empty(trim(wp_strip_all_tags($product_content)))) : ?>
                <div class="desc-content is-collapsed">
                    <?php echo wp_kses_post($product_content); ?>
                </div>

                <button type="button" class="desc-read-more" aria-expanded="false">
                    مشاهده بیشتر
                </button>
            <?php else : ?>
                <div class="desc-empty-state">
                    <i class="fa-regular fa-clipboard"></i>
                    <h3>توضیحاتی برای این محصول ثبت نشده است</h3>
                    <p>در صورت نیاز به اطلاعات بیشتر، می‌توانید از بخش پرسش و پاسخ محصول سوال خود را ثبت کنید.</p>
                </div>
            <?php endif; ?>
        </article>

        <aside class="product-desc-quick-card">
            <div class="pd-quick-head">
                <div class="pd-quick-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div class="pd-quick-title">
                    <span>خلاصه محصول</span>
                    <h3>اطلاعات سریع</h3>
                </div>
            </div>

            <ul class="pd-quick-list">
                <?php if (!empty($product_sku)) : ?>
                    <li class="pd-quick-item">
                        <span class="pd-quick-label">کد کالا</span>
                        <strong class="pd-quick-value">
                            <?php echo esc_html(theme_fa_digits($product_sku)); ?>
                        </strong>
                    </li>
                <?php endif; ?>

                <?php if (!empty($product_categories)) : ?>
                    <li class="pd-quick-item">
                        <span class="pd-quick-label">دسته‌بندی</span>
                        <strong class="pd-quick-value pd-quick-category">
                            <?php echo wp_kses_post($product_categories); ?>
                        </strong>
                    </li>
                <?php endif; ?>

                <?php if (!empty($stock_status)) : ?>
                    <li class="pd-quick-item">
                        <span class="pd-quick-label">وضعیت موجودی</span>
                        <strong class="pd-quick-value pd-quick-stock">
                            <?php echo esc_html($stock_status); ?>
                        </strong>
                    </li>
                <?php endif; ?>

                <li class="pd-quick-item">
                    <span class="pd-quick-label">امتیاز محصول</span>
                    <strong class="pd-quick-value">
                        <?php echo esc_html(theme_fa_digits(number_format_i18n($average_rating, 2))); ?>
                        از ۵
                    </strong>
                </li>

                <li class="pd-quick-item">
                    <span class="pd-quick-label">تعداد رای</span>
                    <strong class="pd-quick-value">
                        <?php echo esc_html(theme_fa_digits(number_format_i18n($rating_count))); ?>
                        رای
                    </strong>
                </li>
            </ul>

            <a href="#qa" class="pd-quick-question-link">
                سوالی درباره محصول دارید؟
            </a>
        </aside>

            </div>
        </div>
        <!-- End Product Informatin And Descriprion -->



        <!-- Start Porsesh Pasokh -->
        <?php
        defined('ABSPATH') || exit;

        global $product;

        if (!$product) {
            $product = wc_get_product(get_the_ID());
        }

        $product_id     = $product ? $product->get_id() : get_the_ID();
        $qa_nonce       = wp_create_nonce('product_qa_ajax_' . $product_id);
        $question_count = theme_get_product_question_count($product_id);
        ?>

        <div class="tab-panel product-qa-panel"
            id="qa"
            data-product-id="<?php echo esc_attr($product_id); ?>"
            data-nonce="<?php echo esc_attr($qa_nonce); ?>">

            <aside class="question-ctr-sec">
                <p>شما هم درباره این کالا پرسش ثبت کنید.</p>

                <a href="#product-qa-form" class="add-product-qestion add-product-question">
                    ثبت پرسش
                </a>

                <span class="qa-help-text">
                    پرسش شما پس از بررسی در همین بخش نمایش داده می‌شود.
                </span>
            </aside>

            <div class="question-and-answer-sec">

                <div class="question-and-answer-sec-filter">
                    <div class="question-filter">
                        <i class="fa-solid fa-filter"></i>
                        <span class="label">مرتب‌سازی:</span>

                        <button type="button" class="value active qa-sort-btn" data-sort="newest">
                            جدیدترین
                        </button>

                        <button type="button" class="value qa-sort-btn" data-sort="popular">
                            پرتکرارترین سوال
                        </button>
                    </div>

                    <div class="count-of-question">
                        <span class="value qa-count-value">
                            <?php echo esc_html(theme_fa_digits(number_format_i18n($question_count))); ?>
                        </span>
                        <span class="label">پرسش</span>
                    </div>
                </div>

                <div class="qa-ajax-message" aria-live="polite"></div>

                <div class="question-and-answer-sec-request">
                    <div class="product-qa">
                        <?php echo theme_render_product_qa_list($product_id, 'newest'); ?>
                    </div>
                </div>

                <div class="product-qa-form-card" id="product-qa-form">
                    <h3>ثبت پرسش درباره محصول</h3>

                    <form class="product-question-form">
                        <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">

                        <?php if (!is_user_logged_in()) : ?>
                            <div class="qa-form-grid">
                                <p>
                                    <label for="question_author">نام شما <span>*</span></label>
                                    <input type="text" id="question_author" name="question_author" required>
                                </p>

                                <p>
                                    <label for="question_email">ایمیل شما <span>*</span></label>
                                    <input type="email" id="question_email" name="question_email" required>
                                </p>
                            </div>
                        <?php endif; ?>

                        <p>
                            <label for="question_text">متن پرسش <span>*</span></label>
                            <textarea id="question_text" name="question_text" rows="5" required placeholder="پرسش خود را درباره این محصول بنویسید..."></textarea>
                        </p>

                        <button type="submit" class="product-question-submit">
                            ثبت پرسش
                        </button>
                    </form>
                </div>

            </div>
        </div>
        <!-- End Porsesh Pasokh -->



        <!-- Start NAGHD NAZARAT -->
        <?php
        defined('ABSPATH') || exit;

        global $product;

        if (!$product) {
            $product = wc_get_product(get_the_ID());
        }

        $product_id      = $product ? $product->get_id() : get_the_ID();
        $average_rating  = $product ? (float) $product->get_average_rating() : 0;
        $review_count    = $product ? (int) $product->get_review_count() : 0;
        $rating_counts   = $product ? $product->get_rating_counts() : array();

        if (!function_exists('theme_fa_digits')) {
            function theme_fa_digits($text) {
                return strtr((string) $text, array(
                    '0' => '۰',
                    '1' => '۱',
                    '2' => '۲',
                    '3' => '۳',
                    '4' => '۴',
                    '5' => '۵',
                    '6' => '۶',
                    '7' => '۷',
                    '8' => '۸',
                    '9' => '۹',
                ));
            }
        }

        if (!function_exists('theme_review_stars')) {
            function theme_review_stars($rating, $class = '') {
                $rating = (float) $rating;

                ob_start();
                ?>
                <div class="review-rating-stars <?php echo esc_attr($class); ?>" aria-label="<?php echo esc_attr(theme_fa_digits($rating) . ' از ۵'); ?>">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <i class="<?php echo ($rating >= $i) ? 'fa-solid' : 'fa-regular'; ?> fa-star <?php echo ($rating >= $i) ? 'is-active' : ''; ?>"></i>
                    <?php endfor; ?>
                </div>
                <?php
                return ob_get_clean();
            }
        }

        $reviews = get_comments(array(
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'review',
            'orderby' => 'comment_date_gmt',
            'order'   => 'DESC',
        ));

        $requires_verified_owner = 'yes' === get_option('woocommerce_review_rating_verification_required');
        $user_can_review = true;

        if ($requires_verified_owner) {
            $user_can_review = is_user_logged_in() && wc_customer_bought_product('', get_current_user_id(), $product_id);
        }

        $average_rating_display = $average_rating > 0
            ? theme_fa_digits(number_format_i18n($average_rating, 1))
            : '۰';

        $review_count_display = theme_fa_digits(number_format_i18n($review_count));
        ?>

        <div class="tab-panel reviews-section" id="reviews">

            <aside class="review-ctr-sec">
                <div class="review-summary-top">
                    <span class="score-number-section">
                        <p class="product-score-value"><?php echo esc_html($average_rating_display); ?></p>
                        <p class="score-total">از ۵</p>
                    </span>

                    <?php echo theme_review_stars($average_rating, 'review-average-stars'); ?>

                    <p class="review-count-text">
                        بر اساس <?php echo esc_html($review_count_display); ?> نظر کاربران
                    </p>
                </div>

                <div class="review-rating-breakdown">
                    <?php for ($star = 5; $star >= 1; $star--) : ?>
                        <?php
                        $count   = isset($rating_counts[$star]) ? (int) $rating_counts[$star] : 0;
                        $percent = $review_count > 0 ? round(($count / $review_count) * 100) : 0;
                        ?>
                        <div class="rating-breakdown-row">
                            <span class="rating-label"><?php echo esc_html(theme_fa_digits($star)); ?> ستاره</span>

                            <span class="rating-bar">
                                <span style="width: <?php echo esc_attr($percent); ?>%;"></span>
                            </span>

                            <span class="rating-count"><?php echo esc_html(theme_fa_digits($count)); ?></span>
                        </div>
                    <?php endfor; ?>
                </div>

                <p class="review-discount-text">با ثبت نظر در خرید بعدی تخفیف بگیر.</p>

                <?php if (comments_open($product_id) && $user_can_review) : ?>
                    <a href="#review-form-box" class="review-submit-btn">
                        ثبت نظر
                    </a>
                <?php else : ?>
                    <span class="review-submit-btn is-disabled">
                        ثبت نظر غیرفعال است
                    </span>
                <?php endif; ?>
            </aside>

            <div class="product-reviews">

                <div class="reviews-list-card">
                    <div class="reviews-list-title">
                        <h3>نقد و نظرات کاربران</h3>
                        <span><?php echo esc_html($review_count_display); ?> نظر</span>
                    </div>

                    <?php if (!empty($reviews)) : ?>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review) : ?>
                                <?php
                                $review_rating = (int) get_comment_meta($review->comment_ID, 'rating', true);
                                $is_verified   = function_exists('wc_review_is_from_verified_owner')
                                    ? wc_review_is_from_verified_owner($review->comment_ID)
                                    : false;

                                $user_job_title = '';
                                if (!empty($review->user_id)) {
                                    $user_job_title = get_user_meta($review->user_id, 'job_title', true);
                                }

                                $review_date = get_comment_date(get_option('date_format'), $review);
                                ?>
                                <article class="review-item" id="review-<?php echo esc_attr($review->comment_ID); ?>">
                                    <header class="review-header">
                                        <div class="review-user">
                                            <div class="review-avatar">
                                                <?php echo get_avatar($review, 48, '', '', array('class' => 'review-avatar-img')); ?>
                                            </div>

                                            <div class="user-meta">
                                                <span class="user-name">
                                                    <?php echo esc_html(get_comment_author($review)); ?>
                                                </span>

                                                <div class="user-badges">
                                                    <?php if ($is_verified) : ?>
                                                        <span class="badge buyer">خریدار تاییدشده</span>
                                                    <?php endif; ?>

                                                    <?php if (!empty($user_job_title)) : ?>
                                                        <span class="badge shop"><?php echo esc_html($user_job_title); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="review-side-info">
                                            <?php if ($review_rating > 0) : ?>
                                                <?php echo theme_review_stars($review_rating, 'single-review-stars'); ?>
                                            <?php endif; ?>

                                            <span class="review-date">
                                                <?php echo esc_html(theme_fa_digits($review_date)); ?>
                                            </span>
                                        </div>
                                    </header>

                                    <div class="review-text">
                                        <?php echo wp_kses_post(wpautop(get_comment_text($review))); ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="woocommerce-noreviews reviews-empty-state">
                            <h4>هنوز نظری ثبت نشده است</h4>
                            <p>اولین نفری باشید که تجربه خود را درباره این محصول ثبت می‌کند.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="review-form-card" id="review-form-box">
                    <?php if (!comments_open($product_id)) : ?>

                        <div class="review-form-message">
                            <h3>ثبت نظر غیرفعال است</h3>
                            <p>در حال حاضر امکان ثبت نظر برای این محصول وجود ندارد.</p>
                        </div>

                    <?php elseif (!$user_can_review) : ?>

                        <div class="review-form-message">
                            <h3>ثبت نظر فقط برای خریداران محصول فعال است</h3>
                            <p>برای ثبت نظر، باید این محصول را قبلاً از فروشگاه خریداری کرده باشید.</p>
                        </div>

                    <?php else : ?>

                        <?php
                        $rating_field = '';

                        if (wc_review_ratings_enabled()) {
                            $rating_field = '
                                <div class="comment-form-rating product-review-rating-field">
                                    <label>امتیاز شما <span class="required">*</span></label>

                                    <div class="product-review-radio-stars">
                                        <input type="radio" id="rating-5" name="rating" value="5" required>
                                        <label for="rating-5" title="عالی"><i class="fa-regular fa-star"></i></label>

                                        <input type="radio" id="rating-4" name="rating" value="4">
                                        <label for="rating-4" title="خوب"><i class="fa-regular fa-star"></i></label>

                                        <input type="radio" id="rating-3" name="rating" value="3">
                                        <label for="rating-3" title="متوسط"><i class="fa-regular fa-star"></i></label>

                                        <input type="radio" id="rating-2" name="rating" value="2">
                                        <label for="rating-2" title="ضعیف"><i class="fa-regular fa-star"></i></label>

                                        <input type="radio" id="rating-1" name="rating" value="1">
                                        <label for="rating-1" title="خیلی ضعیف"><i class="fa-regular fa-star"></i></label>
                                    </div>
                                </div>
                            ';
                        }

                        comment_form(array(
                            'title_reply'          => 'نظر خود را ثبت کنید',
                            'title_reply_to'       => 'پاسخ به نظر %s',
                            'cancel_reply_link'    => 'لغو پاسخ',
                            'label_submit'         => 'ثبت نظر',
                            'class_form'           => 'comment-form product-review-form',
                            'class_submit'         => 'submit review-form-submit',
                            'comment_notes_before' => '<p class="comment-notes">نشانی ایمیل شما منتشر نمی‌شود.</p>',
                            'comment_notes_after'  => '',
                            'logged_in_as'         => '<p class="logged-in-as">با حساب <strong>' . esc_html(wp_get_current_user()->display_name) . '</strong> وارد شده‌اید. <a href="' . esc_url(wp_logout_url(get_permalink($product_id))) . '">خروج</a></p>',
                            'must_log_in'          => '<p class="must-log-in">برای ثبت نظر باید وارد حساب کاربری خود شوید. <a href="' . esc_url(wp_login_url(get_permalink($product_id))) . '">ورود به حساب</a></p>',
                            'fields'               => array(
                                'author' => '
                                    <p class="comment-form-author">
                                        <label for="author">نام شما <span class="required">*</span></label>
                                        <input id="author" name="author" type="text" required>
                                    </p>
                                ',
                                'email' => '
                                    <p class="comment-form-email">
                                        <label for="email">ایمیل شما <span class="required">*</span></label>
                                        <input id="email" name="email" type="email" required>
                                    </p>
                                ',
                            ),
                            'comment_field'        => $rating_field . '
                                <p class="comment-form-comment">
                                    <label for="comment">متن نظر شما <span class="required">*</span></label>
                                    <textarea id="comment" name="comment" rows="6" required placeholder="تجربه خود از خرید یا استفاده از این محصول را بنویسید..."></textarea>
                                </p>
                            ',
                        ), $product_id);
                        ?>

                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- END NAGHD NAZARAT -->
    </div>

    <!-- related products -->
</div></div>
<?php
defined('ABSPATH') || exit;

global $product;

if (!$product) {
    $product = wc_get_product(get_the_ID());
}

if (!$product) {
    echo '<p>محصول یافت نشد</p>';
    get_footer();
    return;
}

$product_id = $product->get_id();

if (!function_exists('theme_fa_digits')) {
    function theme_fa_digits($text) {
        return strtr((string) $text, array(
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ));
    }
}

if (!function_exists('theme_unique_product_ids')) {
    function theme_unique_product_ids($ids, $limit = 12) {
        $clean = array();

        foreach ($ids as $id) {
            $id = absint($id);

            if (!$id || in_array($id, $clean, true)) {
                continue;
            }

            $product = wc_get_product($id);

            if (!$product || $product->get_status() !== 'publish') {
                continue;
            }

            $clean[] = $id;

            if (count($clean) >= $limit) {
                break;
            }
        }

        return $clean;
    }
}

if (!function_exists('theme_get_leaf_product_category_ids')) {
    function theme_get_leaf_product_category_ids($product_id) {
        $terms = wp_get_post_terms($product_id, 'product_cat', array(
            'hide_empty' => false,
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return array();
        }

        $leaf_ids = array();

        foreach ($terms as $term) {
            $is_parent_of_selected_term = false;

            foreach ($terms as $possible_child) {
                if ((int) $possible_child->parent === (int) $term->term_id) {
                    $is_parent_of_selected_term = true;
                    break;
                }
            }

            if (!$is_parent_of_selected_term) {
                $leaf_ids[] = (int) $term->term_id;
            }
        }

        return !empty($leaf_ids) ? $leaf_ids : wp_list_pluck($terms, 'term_id');
    }
}

if (!function_exists('theme_get_parent_product_category_ids')) {
    function theme_get_parent_product_category_ids($product_id) {
        $terms = wp_get_post_terms($product_id, 'product_cat', array(
            'hide_empty' => false,
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return array();
        }

        $parent_ids = array();

        foreach ($terms as $term) {
            if (!empty($term->parent)) {
                $parent_ids[] = (int) $term->parent;
            }
        }

        if (empty($parent_ids)) {
            $parent_ids = wp_list_pluck($terms, 'term_id');
        }

        return array_unique(array_map('absint', $parent_ids));
    }
}

if (!function_exists('theme_query_products_by_tax')) {
    function theme_query_products_by_tax($taxonomy, $term_ids, $limit = 12, $exclude = array()) {
        $term_ids = array_filter(array_map('absint', (array) $term_ids));

        if (empty($term_ids)) {
            return array();
        }

        $query = new WP_Query(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'posts_per_page' => $limit,
            'post__not_in'   => array_filter(array_map('absint', (array) $exclude)),
            'orderby'        => 'rand',
            'no_found_rows'  => true,
            'tax_query'      => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                    'operator' => 'IN',
                ),
            ),
        ));

        return $query->posts ? $query->posts : array();
    }
}

if (!function_exists('theme_get_title_keywords')) {
    function theme_get_title_keywords($title) {
        $title = wp_strip_all_tags($title);
        $title = str_replace(array('-', '/', '\\', '|', '،', ',', '(', ')', '[', ']', '{', '}'), ' ', $title);

        $words = preg_split('/\s+/u', $title);

        $stopwords = array(
            'با', 'برای', 'از', 'به', 'در', 'و', 'یا', 'یک', 'عدد',
            'مدل', 'کد', 'نوع', 'سایز', 'قطر', 'داخلی', 'خارجی',
            'اینچ', 'سانت', 'میل', 'میلی', 'متر'
        );

        $keywords = array();

        foreach ($words as $word) {
            $word = trim($word);

            if (mb_strlen($word, 'UTF-8') < 3) {
                continue;
            }

            if (is_numeric($word)) {
                continue;
            }

            if (in_array($word, $stopwords, true)) {
                continue;
            }

            if (!in_array($word, $keywords, true)) {
                $keywords[] = $word;
            }

            if (count($keywords) >= 4) {
                break;
            }
        }

        return $keywords;
    }
}

if (!function_exists('theme_query_products_by_title_keywords')) {
    function theme_query_products_by_title_keywords($product_id, $limit = 12, $exclude = array()) {
        $keywords = theme_get_title_keywords(get_the_title($product_id));

        if (empty($keywords)) {
            return array();
        }

        $query = new WP_Query(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'posts_per_page' => $limit,
            'post__not_in'   => array_filter(array_map('absint', (array) $exclude)),
            's'              => implode(' ', $keywords),
            'orderby'        => 'relevance',
            'no_found_rows'  => true,
        ));

        return $query->posts ? $query->posts : array();
    }
}

if (!function_exists('theme_get_similar_product_ids')) {
    function theme_get_similar_product_ids($product, $limit = 12, $extra_exclude = array()) {
        $product_id = $product->get_id();
        $ids        = array();

        $exclude = array_values(
            array_unique(
                array_filter(
                    array_map(
                        'absint',
                        array_merge(
                            array($product_id),
                            (array) $extra_exclude
                        )
                    )
                )
            )
        );

        $leaf_cat_ids = theme_get_leaf_product_category_ids($product_id);

        if (!empty($leaf_cat_ids)) {
            $ids = array_merge(
                $ids,
                theme_query_products_by_tax('product_cat', $leaf_cat_ids, $limit, $exclude)
            );
        }

        $exclude = array_merge($exclude, $ids);

        $tag_ids = wp_get_post_terms($product_id, 'product_tag', array(
            'fields' => 'ids',
        ));

        if (!empty($tag_ids) && !is_wp_error($tag_ids)) {
            $ids = array_merge(
                $ids,
                theme_query_products_by_tax('product_tag', $tag_ids, $limit, $exclude)
            );
        }

        $exclude = array_merge($exclude, $ids);

        if (count(array_unique($ids)) < $limit) {
            $ids = array_merge(
                $ids,
                wc_get_related_products($product_id, $limit, $exclude)
            );
        }

        $exclude = array_merge($exclude, $ids);

        if (count(array_unique($ids)) < $limit) {
            $ids = array_merge(
                $ids,
                theme_query_products_by_title_keywords($product_id, $limit, $exclude)
            );
        }

        return theme_unique_product_ids($ids, $limit);
    }
}

if (!function_exists('theme_get_broad_related_product_ids')) {
    function theme_get_broad_related_product_ids($product, $limit = 12, $extra_exclude = array()) {
        $product_id = $product->get_id();

        $ids = array();

        $manual_ids = array_merge(
            $product->get_upsell_ids(),
            $product->get_cross_sell_ids()
        );

        $ids = array_merge($ids, $manual_ids);

        $exclude = array_merge(array($product_id), $extra_exclude, $ids);

        $parent_cat_ids = theme_get_parent_product_category_ids($product_id);

        if (!empty($parent_cat_ids)) {
            $ids = array_merge(
                $ids,
                theme_query_products_by_tax('product_cat', $parent_cat_ids, $limit, $exclude)
            );
        }

        $exclude = array_merge($exclude, $ids);

        if (count(array_unique($ids)) < $limit) {
            $ids = array_merge(
                $ids,
                wc_get_related_products($product_id, $limit, $exclude)
            );
        }

        return theme_unique_product_ids($ids, $limit);
    }
}

if (!function_exists('theme_product_card_stars')) {
    function theme_product_card_stars($rating) {
        $rating = (float) $rating;

        ob_start();
?>
        <div class="sp-card-stars" aria-label="<?php echo esc_attr(theme_fa_digits(number_format_i18n($rating, 1)) . ' از ۵'); ?>">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <?php if ($rating >= $i) : ?>
                    <i class="fa-solid fa-star is-active"></i>
                <?php elseif ($rating >= ($i - 0.5)) : ?>
                    <i class="fa-solid fa-star-half-stroke is-active"></i>
                <?php else : ?>
                    <i class="fa-regular fa-star"></i>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('theme_render_smart_product_card')) {
    function theme_render_smart_product_card($product_id) {

        if (
            function_exists(
                'hypersanati_render_product_card'
            )
        ) {
            hypersanati_render_product_card(
                $product_id
            );
        }
    }
}

if (!function_exists('theme_render_smart_products_section')) {
    function theme_render_smart_products_section($title, $subtitle, $product_ids, $modifier_class = '') {
        ?>
        <section class="sp-products-section <?php echo esc_attr($modifier_class); ?>">
            <div class="sp-section-head">
                <div class="sp-section-title">
                    <span><?php echo esc_html($subtitle); ?></span>
                    <h3><?php echo esc_html($title); ?></h3>
                </div>

                <?php if (!empty($product_ids)) : ?>
                    <div class="sp-section-controls">
                        <button class="sp-scroll-btn" type="button" data-sp-scroll="prev" aria-label="قبلی">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>

                        <button class="sp-scroll-btn" type="button" data-sp-scroll="next" aria-label="بعدی">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($product_ids)) : ?>
                <div class="sp-products-row">
                    <?php foreach ($product_ids as $id) : ?>
                        <?php theme_render_smart_product_card($id); ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="sp-empty-state">
                    <i class="fa-regular fa-box-open"></i>
                    <p>فعلاً محصولی برای نمایش در این بخش پیدا نشد.</p>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}

/*
 * Do not repeat sibling products from the current Part Number
 * family inside Similar / Related sections.
 *
 * Also collapse every other repeated Part Number family to
 * a single storefront card.
 */
$family_product_ids = function_exists(
    'hsb_get_indexed_family_product_ids'
)
    ? hsb_get_indexed_family_product_ids(
        $product_id,
        true
    )
    : array($product_id);


$similar_product_ids = function_exists(
    'hsb_get_indexed_equivalent_product_ids'
)
    ? array_slice(
        hsb_get_indexed_equivalent_product_ids($product_id),
        0,
        12
    )
    : array();


$related_product_ids = function_exists(
    'hsb_get_indexed_related_product_ids'
)
    ? array_slice(
        hsb_get_indexed_related_product_ids($product_id),
        0,
        12
    )
    : array();
?>

<div class="smart-product-sections">

    <section
        class="sp-products-section sp-related-products"
        data-hsb-lazy-section="related"
        data-product-id="<?php echo esc_attr($product_id); ?>"
    >
        <div class="sp-section-head">
            <div class="sp-section-title">
                <span>پیشنهادهای مکمل و هم‌خانواده</span>
                <h3>محصولات مرتبط</h3>
            </div>
        </div>

        <div class="sp-products-row">
            <div class="sp-loading-state">
                در حال بررسی محصولات مرتبط...
            </div>
        </div>
    </section>


    <section
        class="sp-products-section sp-similar-products"
        data-hsb-lazy-section="similar"
        data-product-id="<?php echo esc_attr($product_id); ?>"
    >
        <div class="sp-section-head">
            <div class="sp-section-title">
                <span>محصولات نزدیک به انتخاب شما</span>
                <h3>محصولات مشابه</h3>
            </div>
        </div>

        <div class="sp-products-row">
            <div class="sp-loading-state">
                در حال بررسی محصولات مشابه...
            </div>
        </div>
    </section>

</div>
<?php theme_render_product_benefits_area('single_product_benefits_strong', 'soft'); ?>
<!-- <?php theme_render_product_benefits_area('single_product_benefits_strong', 'strong'); ?> -->


<?php
else :
    echo '<p>محصول یافت نشد</p>';
endif;

get_footer(); ?>
