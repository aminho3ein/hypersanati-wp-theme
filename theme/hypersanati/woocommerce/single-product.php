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

    <!-- breadcrumb and product name section -->
    <div class="name-rate-sec">
        <div class="rate-sec-2">
            <div class="bread-crumb">
                <?php woocommerce_breadcrumb(); ?>
            </div>
            <div class="product-title">
                <h4><?php the_title(); ?></h4>
            </div>
        </div>
        <!-- Start TOP RATING FROM DATA--------- -->

        <?php
        global $product;

        if (!$product) {
            $product = wc_get_product(get_the_ID());
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

        if (!function_exists('theme_wc_rating_stars')) {
            function theme_wc_rating_stars($rating, $class = '') {
                $rating = (float) $rating;

                ob_start();
                ?>
                <div class="theme-rating-stars <?php echo esc_attr($class); ?>"
                    aria-label="<?php echo esc_attr(theme_fa_digits(number_format_i18n($rating, 2)) . ' از ۵'); ?>">

                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <?php
                        if ($rating >= $i) {
                            $icon_class = 'fa-solid fa-star is-active';
                        } elseif ($rating >= ($i - 0.5)) {
                            $icon_class = 'fa-solid fa-star-half-stroke is-active is-half';
                        } else {
                            $icon_class = 'fa-regular fa-star';
                        }
                        ?>

                        <i class="<?php echo esc_attr($icon_class); ?>"></i>
                    <?php endfor; ?>

                </div>
                <?php
                return ob_get_clean();
            }
        }
        ?>

        <div class="rate-sec-2 left-side product-top-rating">
            <div class="rate-passage">
                <h5>
                    امتیاز
                    <?php echo esc_html(theme_fa_digits(number_format_i18n($average_rating, 2))); ?>
                    از میان
                    <?php echo esc_html(theme_fa_digits(number_format_i18n($rating_count))); ?>
                    رای
                </h5>
            </div>

            <div class="star-sec">
                <div class="rate-section">
                    <?php echo theme_wc_rating_stars($average_rating, 'product-page-rating-stars'); ?>
                </div>
            </div>

            <div class="ctr-passage">
                <p>با ثبت امتیاز و نظر در خرید بعدی تخفیف بگیرید!</p>
            </div>
        </div>

        <!-- End Start TOP RATING FROM DATA--------- -->
    </div>

    <!-- product detail and price -->
<div class="product-and-price-detail product-hero" dir="rtl">

    <div class="product-price-card-sec product-hero__purchase">
        <div class="price-card">

            <div class="price-and-discont-sec">
                <?php
                    $regular_price = (float) $product->get_regular_price();
                    $sale_price    = (float) $product->get_sale_price();
                    $discount      = ($regular_price > 0 && $sale_price > 0)
                        ? round((($regular_price - $sale_price) / $regular_price) * 100)
                        : 0;
                ?>

                <?php if ($product->is_on_sale() && $discount > 0) : ?>
                    <div class="discont-sec">
                        <div class="old-price">
                            <p><?php echo wc_price($product->get_regular_price()); ?></p>
                        </div>

                        <div class="discont-sec-frame">
                            <p><?php echo esc_html($discount); ?>٪ تخفیف</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="price-label">قیمت محصول</div>

                <div class="price-sec">
                    <p><?php echo wc_price($product->get_price()); ?></p>
                </div>
            </div>

            <div class="add-to-cart-and-dropdown-sec">
                <form class="cart product-cart-form"
                      action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                    <div class="purchase-field">
                        <label for="product-count">تعداد خرید</label>

                        <select id="product-count" name="quantity" class="product-count-select">
                            <?php
                                $max_qty = $product->get_max_purchase_quantity();
                                $limit   = $max_qty > 0 ? min(10, $max_qty) : 10;
                            ?>

                            <?php for ($i = 1; $i <= $limit; $i++) : ?>
                                <option value="<?php echo esc_attr($i); ?>">
                                    <?php echo esc_html($i); ?> عدد
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit"
                            name="add-to-cart"
                            value="<?php echo esc_attr($product->get_id()); ?>"
                            class="single_add_to_cart_button add-to-cart">
                        افزودن به سبد خرید
                    </button>

                    <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                </form>

                <a href="#bulk-order" class="bulk-buy-link">
                    خرید به تعداد انبوه / استعلام قیمت
                </a>
            </div>

        </div>
    </div>

    <div class="product-images product-hero__media">
        <div class="single-image">
            <div class="single-image-frame">
                <?php if (has_post_thumbnail()) : ?>
                    <?php echo get_the_post_thumbnail($product_id, 'large', ['class' => 'main-product-image']); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
                <?php endif; ?>
            </div>

            <button class="carousel-control-prev custom-btn" type="button" id="prev-image" aria-label="تصویر قبلی">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next custom-btn" type="button" id="next-image" aria-label="تصویر بعدی">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <?php if (!empty($attachment_ids)) : ?>
            <div class="product-image-gallery">
                <?php foreach ($attachment_ids as $index => $attachment_id) : ?>
                    <div class="product-image-gallery-frame <?php echo $index === 0 ? 'active' : ''; ?>"
                         data-index="<?php echo esc_attr($index); ?>">
                        <?php echo wp_get_attachment_image($attachment_id, 'thumbnail'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="product-name-detail-sec product-hero__specs">
        <div class="product-name-detail-sec-title">
            <h5>مشخصات فنی کالا</h5>
        </div>

        <div class="product-details">
            <ul class="product-specs">
                <?php foreach ($specs as $label => $value) : ?>
                    <?php if (!empty($value)) : ?>
                        <li>
                            <span class="label"><?php echo esc_html($label); ?></span>
                            <span class="value"><?php echo esc_html($value); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>
    <div class="product-name-detail-sec-mobile">
        <div class="product-name-detail-sec-title-mobile">
            <h5>مشخصات فنی کالا</h5>
        </div>
        <div class="product-details">
            <ul class="product-specs">
                <?php foreach ($specs as $label => $value) : ?>
                    <?php if (!empty($value)) : ?>
                        <li>
                            <span class="label"><?php echo esc_html($label); ?>:</span>
                            <span class="value"><?php echo esc_html($value); ?></span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
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
        $stock_status = 'موجود';
    } elseif ($product->is_on_backorder()) {
        $stock_status = 'قابل پیش‌خرید';
    } else {
        $stock_status = 'ناموجود';
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
    function theme_get_similar_product_ids($product, $limit = 12) {
        $product_id = $product->get_id();
        $ids        = array();
        $exclude    = array($product_id);

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
        $card_product = wc_get_product($product_id);

        if (!$card_product || $card_product->get_status() !== 'publish') {
            return;
        }

        $regular_price = (float) $card_product->get_regular_price();
        $sale_price    = (float) $card_product->get_sale_price();
        $discount      = 0;

        if ($card_product->is_on_sale() && $regular_price > 0 && $sale_price > 0) {
            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
        }

        $is_simple_add_to_cart = $card_product->is_type('simple') && $card_product->is_purchasable() && $card_product->is_in_stock();

        $button_url   = $is_simple_add_to_cart ? $card_product->add_to_cart_url() : get_permalink($product_id);
        $button_text  = $is_simple_add_to_cart ? 'افزودن به سبد' : 'مشاهده محصول';
        $button_class = $is_simple_add_to_cart ? 'ajax_add_to_cart add_to_cart_button' : '';

        ?>
        <article class="sp-product-card">
            <a class="sp-card-image-link" href="<?php echo esc_url(get_permalink($product_id)); ?>">
                <div class="sp-card-image">
                    <?php echo $card_product->get_image('woocommerce_thumbnail'); ?>

                    <?php if ($discount > 0) : ?>
                        <span class="sp-sale-badge">
                            <?php echo esc_html(theme_fa_digits($discount)); ?>٪ تخفیف
                        </span>
                    <?php endif; ?>
                </div>
            </a>

            <div class="sp-card-body">
                <a class="sp-card-title" href="<?php echo esc_url(get_permalink($product_id)); ?>">
                    <?php echo esc_html($card_product->get_name()); ?>
                </a>

                <div class="sp-card-meta">
                    <?php echo theme_product_card_stars($card_product->get_average_rating()); ?>

                    <span>
                        <?php echo esc_html(theme_fa_digits(number_format_i18n($card_product->get_rating_count()))); ?>
                        رای
                    </span>
                </div>

                <div class="sp-card-price">
                    <?php echo wp_kses_post($card_product->get_price_html()); ?>
                </div>

                <a href="<?php echo esc_url($button_url); ?>"
                   data-product_id="<?php echo esc_attr($product_id); ?>"
                   data-quantity="1"
                   class="sp-card-btn <?php echo esc_attr($button_class); ?>">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        </article>
        <?php
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

$similar_product_ids = theme_get_similar_product_ids($product, 12);
$related_product_ids = theme_get_broad_related_product_ids($product, 12, $similar_product_ids);
?>

<div class="smart-product-sections">
    <?php
    theme_render_smart_products_section(
        'محصولات مرتبط',
        'پیشنهادهای مکمل و هم‌خانواده',
        $related_product_ids,
        'sp-related-products'
    );

    theme_render_smart_products_section(
        'محصولات مشابه',
        'محصولات نزدیک به انتخاب شما',
        $similar_product_ids,
        'sp-similar-products'
    );
    ?>
</div>
<?php theme_render_product_benefits_area('single_product_benefits_strong', 'soft'); ?>
<!-- <?php theme_render_product_benefits_area('single_product_benefits_strong', 'strong'); ?> -->


<?php
else :
    echo '<p>محصول یافت نشد</p>';
endif;

get_footer(); ?>