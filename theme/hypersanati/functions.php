<?php
if (!defined('ABSPATH')) exit;

require_once get_template_directory() . '/inc/footer-admin.php';

/* =========================================================
   ASSET VERSION HELPER
========================================================= */
if (!function_exists('hypersanati_asset_version')) {
    function hypersanati_asset_version($relative_path) {
        $file_path = get_template_directory() . $relative_path;

        if (file_exists($file_path)) {
            return filemtime($file_path);
        }

        return '1.0.0';
    }
}

/* =========================================================
   SAFE ENQUEUE HELPERS
========================================================= */
if (!function_exists('hypersanati_enqueue_theme_style')) {
    function hypersanati_enqueue_theme_style($handle, $relative_path, $deps = array(), $media = 'all') {
        $file_path = get_template_directory() . $relative_path;

        if (!file_exists($file_path)) {
            return false;
        }

        wp_enqueue_style(
            $handle,
            get_template_directory_uri() . $relative_path,
            $deps,
            hypersanati_asset_version($relative_path),
            $media
        );

        return true;
    }
}

if (!function_exists('hypersanati_enqueue_theme_script')) {
    function hypersanati_enqueue_theme_script($handle, $relative_path, $deps = array(), $in_footer = true) {
        $file_path = get_template_directory() . $relative_path;

        if (!file_exists($file_path)) {
            return false;
        }

        wp_enqueue_script(
            $handle,
            get_template_directory_uri() . $relative_path,
            $deps,
            hypersanati_asset_version($relative_path),
            $in_footer
        );

        return true;
    }
}

/* =========================================================
   ENQUEUE ALL THEME ASSETS
========================================================= */
function hypersanati_enqueue_assets() {

    /* =========================================================
       CORE CSS
    ========================================================= */

    $bootstrap_loaded = hypersanati_enqueue_theme_style(
        'hypersanati-bootstrap-rtl',
        '/assets/css/bootstrap.rtl.min.css'
    );

    hypersanati_enqueue_theme_style(
        'hypersanati-fontawesome',
        '/assets/css/fontawesome.min.css'
    );

    hypersanati_enqueue_theme_style(
        'hypersanati-fontawesome-solid',
        '/assets/css/solid.min.css',
        array('hypersanati-fontawesome')
    );

    hypersanati_enqueue_theme_style(
        'hypersanati-fontawesome-brands',
        '/assets/css/brands.min.css',
        array('hypersanati-fontawesome')
    );

    hypersanati_enqueue_theme_style(
        'hypersanati-site-fonts',
        '/assets/css/font.css'
    );

    /* =========================================================
       MAIN CSS
       فقط یک بار لود می‌شود
    ========================================================= */

    $main_deps = array();

    if ($bootstrap_loaded) {
        $main_deps[] = 'hypersanati-bootstrap-rtl';
    }

    if (wp_style_is('hypersanati-site-fonts', 'enqueued')) {
        $main_deps[] = 'hypersanati-site-fonts';
    }

    hypersanati_enqueue_theme_style(
        'hypersanati-style',
        '/assets/css/main.css',
        $main_deps
    );

    /* =========================================================
       RESPONSIVE CSS
    ========================================================= */

    $page_css_deps = array('hypersanati-style');

    $tablet_loaded = hypersanati_enqueue_theme_style(
        'hypersanati-responsive-tablet',
        '/assets/css/responsive/tablet-responsive.css',
        array('hypersanati-style'),
        '(min-width:768px)'
    );

    if ($tablet_loaded) {
        $page_css_deps[] = 'hypersanati-responsive-tablet';
    }

    $laptop_loaded = hypersanati_enqueue_theme_style(
        'hypersanati-responsive-laptop',
        '/assets/css/responsive/laptop-responsive.css',
        array('hypersanati-style'),
        '(min-width:992px)'
    );

    if ($laptop_loaded) {
        $page_css_deps[] = 'hypersanati-responsive-laptop';
    }

    $desktop_loaded = hypersanati_enqueue_theme_style(
        'hypersanati-responsive-desktop',
        '/assets/css/responsive/desktop-responsive.css',
        array('hypersanati-style'),
        '(min-width:1200px)'
    );

    if ($desktop_loaded) {
        $page_css_deps[] = 'hypersanati-responsive-desktop';
    }

    /* =========================================================
       CORE JS
    ========================================================= */

    $bootstrap_js_loaded = hypersanati_enqueue_theme_script(
        'hypersanati-bootstrap-bundle',
        '/assets/js/bootstrap.bundle.min.js',
        array(),
        true
    );

    $functions_js_deps = array();

    if ($bootstrap_js_loaded) {
        $functions_js_deps[] = 'hypersanati-bootstrap-bundle';
    }

    $functions_js_loaded = hypersanati_enqueue_theme_script(
        'hypersanati-functions',
        '/assets/js/functions.js',
        $functions_js_deps,
        true
    );

    $main_js_deps = array();

    if ($functions_js_loaded) {
        $main_js_deps[] = 'hypersanati-functions';
    }

    hypersanati_enqueue_theme_script(
        'hypersanati-main-js',
        '/assets/js/main.js',
        $main_js_deps,
        true
    );

    /* =========================================================
       CHECKOUT PAGE
    ========================================================= */

    if (function_exists('is_checkout') && is_checkout() && !is_order_received_page()) {

        hypersanati_enqueue_theme_style(
            'hsb-checkout-page',
            '/assets/css/check-out.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'hsb-checkout-page',
            '/assets/js/check-out.js',
            array('jquery'),
            true
        );
    }

    /* =========================================================
       THANK YOU PAGE + SALES INVOICE
    ========================================================= */

    if (function_exists('is_order_received_page') && is_order_received_page()) {

        $thank_you_loaded = hypersanati_enqueue_theme_style(
            'hsb-thank-you-page',
            '/assets/css/thank-you-page.css',
            $page_css_deps
        );

        $invoice_css_deps = $page_css_deps;

        if ($thank_you_loaded) {
            $invoice_css_deps[] = 'hsb-thank-you-page';
        }

        hypersanati_enqueue_theme_style(
            'hsb-sales-invoice',
            '/assets/css/sales-invoice.css',
            $invoice_css_deps
        );

        $html2pdf_loaded = hypersanati_enqueue_theme_script(
            'hsb-html2pdf',
            '/assets/js/html2pdf.bundle.min.js',
            array(),
            true
        );

        $invoice_js_deps = array();

        if ($html2pdf_loaded) {
            $invoice_js_deps[] = 'hsb-html2pdf';
        }

        $invoice_js_loaded = hypersanati_enqueue_theme_script(
            'hsb-sales-invoice',
            '/assets/js/sales-invoice.js',
            $invoice_js_deps,
            true
        );

        $thank_you_js_deps = array();

        if ($invoice_js_loaded) {
            $thank_you_js_deps[] = 'hsb-sales-invoice';
        }

        hypersanati_enqueue_theme_script(
            'hsb-thank-you-page',
            '/assets/js/thank-you-page.js',
            $thank_you_js_deps,
            true
        );
    }

    /* =========================================================
       404 PAGE
    ========================================================= */

    if (is_404()) {

        hypersanati_enqueue_theme_style(
            'hypersanati-404',
            '/assets/css/404.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'hypersanati-404',
            '/assets/js/404.js',
            array(),
            true
        );
    }

    /* =========================================================
       CART PAGE
    ========================================================= */

    if (function_exists('is_cart') && is_cart()) {

        hypersanati_enqueue_theme_style(
            'hsb-cart-page-items',
            '/assets/css/cart-page-items.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'hsb-cart-page-items',
            '/assets/js/cart-page-items.js',
            array(),
            true
        );
    }

    /* =========================================================
       CONTACT US PAGE
    ========================================================= */

    if (is_page('contact-us') || is_page_template('page-contact-us.php')) {

        hypersanati_enqueue_theme_style(
            'hypersanati-contact-us',
            '/assets/css/contact-us.css',
            $page_css_deps
        );

        $contact_js_loaded = hypersanati_enqueue_theme_script(
            'hypersanati-contact-us',
            '/assets/js/contact-us.js',
            array(),
            true
        );

        if ($contact_js_loaded) {
            wp_localize_script(
                'hypersanati-contact-us',
                'HypersanatiContactAjax',
                array(
                    'ajax_url'     => admin_url('admin-ajax.php'),
                    'sending_text' => 'در حال ارسال...',
                )
            );
        }
    }

    /* =========================================================
       ABOUT PAGE
    ========================================================= */

    if (is_page('about-us') || is_page_template('page-about-us.php')) {

        hypersanati_enqueue_theme_style(
            'hypersanati-about-us',
            '/assets/css/about-us.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'hypersanati-about-us',
            '/assets/js/about-us.js',
            array(),
            true
        );
    }

    /* =========================================================
       ARTICLE CATEGORY / TAG / ARCHIVE PAGE
    ========================================================= */

    if (is_category() || is_tag() || is_date() || is_author()) {

        hypersanati_enqueue_theme_style(
            'hypersanati-category',
            '/assets/css/article-category.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'hypersanati-category',
            '/assets/js/article-category.js',
            array(),
            true
        );
    }

    /* =========================================================
       SINGLE ARTICLE
    ========================================================= */

    if (is_singular('post')) {

        hypersanati_enqueue_theme_style(
            'single-article-css',
            '/assets/css/single-article.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'single-article-js',
            '/assets/js/single-article.js',
            array('jquery'),
            true
        );
    }

    /* =========================================================
       SHOP PAGE / PRODUCT ARCHIVE
    ========================================================= */

    if (
        function_exists('is_shop') &&
        (
            is_shop() ||
            is_post_type_archive('product') ||
            (function_exists('is_product_category') && is_product_category()) ||
            (function_exists('is_product_tag') && is_product_tag())
        )
    ) {

        hypersanati_enqueue_theme_style(
            'shop-css',
            '/assets/css/shop.css',
            $page_css_deps
        );

        $shop_js_loaded = hypersanati_enqueue_theme_script(
            'shop-js',
            '/assets/js/shop.js',
            array('jquery'),
            true
        );

        if ($shop_js_loaded && function_exists('hypersanati_get_shop_url')) {
            wp_localize_script(
                'shop-js',
                'hypersanatiSearch',
                array(
                    'shopUrl' => hypersanati_get_shop_url(),
                )
            );
        }
    }

    /* =========================================================
       FRONT PAGE
    ========================================================= */

    if (is_front_page() || is_home()) {

        $index_search_loaded = hypersanati_enqueue_theme_script(
            'index-search-js',
            '/assets/js/index-search.js',
            array(),
            true
        );

        if ($index_search_loaded && function_exists('hypersanati_get_shop_url')) {
            wp_localize_script(
                'index-search-js',
                'hypersanatiSearch',
                array(
                    'shopUrl' => hypersanati_get_shop_url(),
                )
            );
        }
    }

    /* =========================================================
       DASHBOARD / MY ACCOUNT
    ========================================================= */

    if (function_exists('is_account_page') && is_account_page()) {

        hypersanati_enqueue_theme_style(
            'dashboard-css',
            '/assets/css/dashboard.css',
            $page_css_deps
        );

        $dashboard_js_loaded = hypersanati_enqueue_theme_script(
            'dashboard-js',
            '/assets/js/dashboard.js',
            array('jquery'),
            true
        );

        if ($dashboard_js_loaded) {
            wp_localize_script(
                'dashboard-js',
                'dashboardData',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('dashboard_ajax'),
                )
            );
        }
    }

    /* =========================================================
       SINGLE PRODUCT
    ========================================================= */

    if (function_exists('is_product') && is_product()) {

        hypersanati_enqueue_theme_style(
            'single-product-css',
            '/assets/css/single-product.css',
            $page_css_deps
        );

        hypersanati_enqueue_theme_script(
            'single-product-js',
            '/assets/js/single-product.js',
            array('jquery'),
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'hypersanati_enqueue_assets', 20);

/* =========================================================
   MENUS
========================================================= */
function hypersanati_register_menus() {
    register_nav_menus([
        'primary_menu' => 'فهرست اصلی',
        'footer_menu'  => 'فهرست فوتر',
    ]);
}
add_action('after_setup_theme', 'hypersanati_register_menus');


/* =========================================================
   MENU CLASSES
========================================================= */
function hypersanati_nav_link_classes($atts, $item, $args, $depth) {

    $classes = isset($atts['class']) ? explode(' ', $atts['class']) : [];

    $item_url = isset($item->url) ? untrailingslashit($item->url) : '';
    $home_url = untrailingslashit(home_url('/'));

    if ($args->theme_location === 'primary_menu') {

        if ($item_url === $home_url || in_array('menu-item-home', $item->classes, true)) {
            $classes[] = 'home-btn';
        }

        if (trim($item->title) === 'ارتباط با ما') {
            $classes[] = 'contact-btn';
        }
    }

    if ($args->menu_class === 'mobile-nav-links') {
        $atts['onclick'] = 'closeMobileMenu()';
    }

    if (!empty($classes)) {
        $atts['class'] = implode(' ', array_unique($classes));
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'hypersanati_nav_link_classes', 10, 4);


/* =========================================================
   SIDEBAR WIDGET
========================================================= */
class Sidebar_Info_Widget extends WP_Widget {

    function __construct() {
        parent::__construct('sidebar_info_widget', 'Sidebar Info Card');
    }

    public function widget($args, $instance) {

        $title = $instance['title'] ?? '';
        $text  = $instance['text'] ?? '';

        echo '<div class="sidebar-info-card">';
        if ($title) echo '<h3 class="sidebar-info-title">' . esc_html($title) . '</h3>';
        if ($text) echo '<p class="sidebar-info-text">' . esc_html($text) . '</p>';
        echo '</div>';
    }

    public function form($instance) {
        $title = $instance['title'] ?? '';
        $text  = $instance['text'] ?? '';
        ?>

        <p>
            <label>عنوان:</label>
            <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label>متن:</label>
            <textarea class="widefat" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea($text); ?></textarea>
        </p>

        <?php
    }

    public function update($new, $old) {
        return [
            'title' => sanitize_text_field($new['title'] ?? ''),
            'text'  => sanitize_textarea_field($new['text'] ?? '')
        ];
    }
}

add_action('widgets_init', function () {
    register_widget('Sidebar_Info_Widget');
});

register_sidebar([
    'name' => 'Main Sidebar',
    'id' => 'sidebar-1'
]);

// Add seo_summary_box for Any
function hypersanati_add_seo_summary_box() {

    add_meta_box(
        'seo_summary_box',
        'خلاصه سئو مقاله (SEO Summary)',
        'hypersanati_seo_summary_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'hypersanati_add_seo_summary_box');


function hypersanati_seo_summary_box_callback($post) {

    $value = get_post_meta($post->ID, '_seo_summary', true);

    wp_nonce_field('save_seo_summary', 'seo_summary_nonce');
    ?>

    <textarea
        style="width:100%;height:120px;font-size:14px;line-height:1.6"
        name="seo_summary_field"
        placeholder="خلاصه سئو مقاله را اینجا بنویسید..."
        required
    ><?php echo esc_textarea($value); ?></textarea>

    <p style="margin-top:8px;color:#666">
        ⚠ این بخش برای نمایش در هیرو و سئو الزامی است
    </p>

    <?php
}


function hypersanati_save_seo_summary($post_id) {

    if (!isset($_POST['seo_summary_nonce'])) return;

    if (!wp_verify_nonce($_POST['seo_summary_nonce'], 'save_seo_summary')) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['seo_summary_field'])) {
        update_post_meta(
            $post_id,
            '_seo_summary',
            sanitize_textarea_field($_POST['seo_summary_field'])
        );
    }
}
add_action('save_post', 'hypersanati_save_seo_summary');


/* =========================================================
   WOOCOMMERCE META
========================================================= */
add_action('woocommerce_product_options_general_product_data', function () {

    woocommerce_wp_checkbox([
        'id' => '_is_discount_featured',
        'label' => 'تخفیف ویژه هفته'
    ]);
});

add_action('woocommerce_process_product_meta', function ($post_id) {

    update_post_meta(
        $post_id,
        '_is_discount_featured',
        isset($_POST['_is_discount_featured']) ? 'yes' : 'no'
    );
});


/* =========================================================
   AJAX POSTS
========================================================= */
add_action('wp_ajax_load_posts', 'load_posts_ajax');
add_action('wp_ajax_nopriv_load_posts', 'load_posts_ajax');

function load_posts_ajax() {

    $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

    $query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 8,
        'paged' => $paged
    ]);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>

            <div class="article-category-item">

                <div class="article-category-tag">
                    <?php
                    $cat = get_the_category();
                    echo $cat[0]->name ?? 'مقاله';
                    ?>
                </div>

                <a href="<?php the_permalink(); ?>" class="article-card">

                    <div class="article-card-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>

                    <h4 class="article-card-title"><?php the_title(); ?></h4>

                    <p class="article-card-text">
                        <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                    </p>

                </a>
            </div>

        <?php endwhile;
    endif;

    wp_reset_postdata();

    $posts_html = ob_get_clean();

    // pagination
    ob_start();

    $total_pages = $query->max_num_pages;
    $current = $paged;
    ?>

    <a href="#" class="article-pagination-btn" data-page="1">1</a>

    <a href="#" class="article-pagination-btn" data-page="<?php echo max(1, $current - 1); ?>">قبلی</a>

    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
        <a href="#" class="article-pagination-btn <?php echo ($i == $current) ? 'active' : ''; ?>"
           data-page="<?php echo $i; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <a href="#" class="article-pagination-btn" data-page="<?php echo min($total_pages, $current + 1); ?>">بعدی</a>

    <a href="#" class="article-pagination-btn" data-page="<?php echo $total_pages; ?>">انتها</a>

    <?php

    $pagination_html = ob_get_clean();

    wp_send_json([
        'posts' => $posts_html,
        'pagination' => $pagination_html
    ]);

    wp_die();
}

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});


/* =========================================================
   BEARING DIMENSION SEARCH
========================================================= */
function hypersanati_get_shop_url() {
    if (function_exists('wc_get_page_permalink')) {
        return wc_get_page_permalink('shop');
    }

    return home_url('/shop/');
}

/**
 * Return the real available bearing-dimension bounds from
 * published WooCommerce products.
 *
 * Empty, invalid, zero and negative values are ignored.
 * Results are cached using the existing shop-family cache
 * version so product changes automatically invalidate them.
 */
function hypersanati_get_dimension_ranges() {
    global $wpdb;

    $cache_version =
        function_exists(
            'hsb_get_shop_family_cache_version'
        )
            ? hsb_get_shop_family_cache_version()
            : max(
                1,
                absint(
                    get_option(
                        'hsb_shop_family_cache_version',
                        1
                    )
                )
            );

    $cache_key =
        'hsb_dimension_ranges_v' .
        $cache_version;

    $cached =
        get_transient(
            $cache_key
        );

    if (
        is_array($cached) &&
        isset(
            $cached['inner'],
            $cached['outer'],
            $cached['height']
        )
    ) {
        return $cached;
    }

    $map = array(
        '_inner_diameter' => 'inner',
        '_outer_diameter' => 'outer',
        '_bearing_width'  => 'height',
    );

    $ranges = array(
        'inner' => array(
            'min' => null,
            'max' => null,
        ),
        'outer' => array(
            'min' => null,
            'max' => null,
        ),
        'height' => array(
            'min' => null,
            'max' => null,
        ),
    );

    $rows =
        $wpdb->get_results(
            "
            SELECT
                pm.meta_key,
                MIN(
                    CAST(
                        pm.meta_value
                        AS DECIMAL(20, 6)
                    )
                ) AS min_value,
                MAX(
                    CAST(
                        pm.meta_value
                        AS DECIMAL(20, 6)
                    )
                ) AS max_value
            FROM {$wpdb->postmeta} AS pm
            INNER JOIN {$wpdb->posts} AS p
                ON p.ID = pm.post_id
            WHERE
                p.post_type = 'product'
                AND p.post_status = 'publish'
                AND pm.meta_key IN (
                    '_inner_diameter',
                    '_outer_diameter',
                    '_bearing_width'
                )
                AND TRIM(pm.meta_value) <> ''
                AND CAST(
                    pm.meta_value
                    AS DECIMAL(20, 6)
                ) > 0
            GROUP BY pm.meta_key
            "
        );

    foreach ($rows as $row) {
        if (
            !isset(
                $map[$row->meta_key]
            )
        ) {
            continue;
        }

        $key =
            $map[$row->meta_key];

        $ranges[$key] = array(
            'min' =>
                (float) $row->min_value,

            'max' =>
                (float) $row->max_value,
        );
    }

    set_transient(
        $cache_key,
        $ranges,
        DAY_IN_SECONDS
    );

    return $ranges;
}


function hypersanati_build_dimension_meta_query($params) {
    $meta_query = [];
    $mode = isset($params['dimension_search']) ? sanitize_text_field($params['dimension_search']) : '';

    $map = [
        'inner'  => '_inner_diameter',
        'outer'  => '_outer_diameter',
        'height' => '_bearing_width',
    ];

    if ($mode === 'exact') {
        foreach ($map as $key => $meta_key) {
            if (isset($params[$key]) && $params[$key] !== '' && is_numeric($params[$key])) {
                $meta_query[] = [
                    'key'     => $meta_key,
                    'value'   => floatval($params[$key]),
                    'compare' => '=',
                    'type'    => 'DECIMAL',
                ];
            }
        }
    } elseif ($mode === 'approx') {
        foreach ($map as $key => $meta_key) {
            $min = (isset($params[$key . '_min']) && $params[$key . '_min'] !== '' && is_numeric($params[$key . '_min']))
                ? floatval($params[$key . '_min'])
                : null;
            $max = (isset($params[$key . '_max']) && $params[$key . '_max'] !== '' && is_numeric($params[$key . '_max']))
                ? floatval($params[$key . '_max'])
                : null;

            if ($min === null && $max === null) {
                continue;
            }

            if ($min !== null && $max !== null) {
                if ($min > $max) {
                    [$min, $max] = [$max, $min];
                }

                $meta_query[] = [
                    'key'     => $meta_key,
                    'value'   => [$min, $max],
                    'compare' => 'BETWEEN',
                    'type'    => 'DECIMAL',
                ];
            } elseif ($min !== null) {
                $meta_query[] = [
                    'key'     => $meta_key,
                    'value'   => $min,
                    'compare' => '>=',
                    'type'    => 'DECIMAL',
                ];
            } else {
                $meta_query[] = [
                    'key'     => $meta_key,
                    'value'   => $max,
                    'compare' => '<=',
                    'type'    => 'DECIMAL',
                ];
            }
        }
    }

    if (empty($meta_query)) {
        return [];
    }

    if (count($meta_query) > 1) {
        $meta_query['relation'] = 'AND';
    }

    return $meta_query;
}


function hsb_render_product_card_rating(
    $rating,
    $rating_count
) {
    $rating = (float) $rating;
    $rating_count = absint($rating_count);

    ?>
    <div
        class="hsb-card-rating"
        aria-label="<?php echo esc_attr(
            number_format_i18n(
                $rating,
                1
            ) . ' از ۵'
        ); ?>">

        <div class="hsb-card-rating__stars">

            <?php
            for ($i = 1; $i <= 5; $i++) :

                if ($rating >= $i) {
                    $class =
                        'fa-solid fa-star is-active';
                } elseif (
                    $rating >= ($i - 0.5)
                ) {
                    $class =
                        'fa-solid fa-star-half-stroke is-active';
                } else {
                    $class =
                        'fa-regular fa-star';
                }
            ?>

                <i
                    class="<?php echo esc_attr(
                        $class
                    ); ?>">
                </i>

            <?php endfor; ?>

        </div>

        <span>
            <?php
            echo esc_html(
                number_format_i18n(
                    $rating_count
                )
            );
            ?>
            رأی
        </span>

    </div>
    <?php
}


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
                <i class="fa-solid fa-file-invoice"></i>

                <span>
                    قیمت پس از بررسی واحد فروش
                </span>
            </div>


            <div class="hsb-family-card-action">

                <span>
                    <?php
                    echo $is_family
                        ? 'مشاهده و انتخاب برند'
                        : 'مشاهده محصول';
                    ?>
                </span>

                <i class="fa-solid fa-arrow-left"></i>

            </div>

        </div>

    </a>

    <?php
}


function hypersanati_get_dimension_search_label($params) {
    $mode = isset($params['dimension_search']) ? sanitize_text_field($params['dimension_search']) : '';
    $parts = [];

    if ($mode === 'exact') {
        if (!empty($params['inner'])) {
            $parts[] = 'قطر داخلی: ' . sanitize_text_field($params['inner']) . ' mm';
        }
        if (!empty($params['outer'])) {
            $parts[] = 'قطر خارجی: ' . sanitize_text_field($params['outer']) . ' mm';
        }
        if (!empty($params['height'])) {
            $parts[] = 'ارتفاع: ' . sanitize_text_field($params['height']) . ' mm';
        }
    } elseif ($mode === 'approx') {
        if (isset($params['inner_min'], $params['inner_max'])) {
            $parts[] = 'قطر داخلی: ' . sanitize_text_field($params['inner_min']) . ' تا ' . sanitize_text_field($params['inner_max']) . ' mm';
        }
        if (isset($params['outer_min'], $params['outer_max'])) {
            $parts[] = 'قطر خارجی: ' . sanitize_text_field($params['outer_min']) . ' تا ' . sanitize_text_field($params['outer_max']) . ' mm';
        }
        if (isset($params['height_min'], $params['height_max'])) {
            $parts[] = 'ارتفاع: ' . sanitize_text_field($params['height_min']) . ' تا ' . sanitize_text_field($params['height_max']) . ' mm';
        }
    }

    return implode(' | ', $parts);
}


// آژاکس بارگذاری محصولات و دسته‌ها (گزینه نهایی همراه با لینک محصول)
add_action('wp_ajax_load_shop_categories', 'load_shop_categories');
add_action('wp_ajax_nopriv_load_shop_categories', 'load_shop_categories');

function load_shop_categories() {
    $offset = isset($_GET['index']) ? intval($_GET['index']) : 0;
    $selected_cat_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

    // دریافت کلمه کلیدی جستجو از آژاکس
    $search_keyword = isset($_GET['search_keyword']) ? sanitize_text_field($_GET['search_keyword']) : '';

    // دریافت فیلترهای ابعادی
    $inner_min = isset($_GET['inner_min']) ? sanitize_text_field($_GET['inner_min']) : '';
    $inner_max = isset($_GET['inner_max']) ? sanitize_text_field($_GET['inner_max']) : '';
    $outer_min = isset($_GET['outer_min']) ? sanitize_text_field($_GET['outer_min']) : '';
    $outer_max = isset($_GET['outer_max']) ? sanitize_text_field($_GET['outer_max']) : '';
    $height_min = isset($_GET['height_min']) ? sanitize_text_field($_GET['height_min']) : '';
    $height_max = isset($_GET['height_max']) ? sanitize_text_field($_GET['height_max']) : '';

    $uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
    $exclude_ids = $uncategorized ? array($uncategorized->term_id) : array();

    $args = [
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'exclude'    => $exclude_ids,
        'orderby'    => 'name',
        'order'      => 'ASC'
    ];

    if ($selected_cat_id > 0) {
        $args['include'] = [$selected_cat_id];
    } else {
        $args['parent']  = 0;
    }

    $categories = get_terms($args);
    $categories = array_values($categories);

    if (!isset($categories[$offset])) {
        wp_die();
    }

    $cat = $categories[$offset];

    if ($selected_cat_id > 0 && $cat->parent != 0) {
        $subcats = [$cat];
    } else {
        $subcats = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $cat->term_id
        ]);
        if (empty($subcats)) {
            $subcats = [$cat];
        }
    }

    /*
     * In progressive mode the first request only renders
     * subcategory shells/sentinels. Actual products arrive
     * through hsb_load_shop_family_batch(), so existence of
     * subcategories is enough to return the structure.
     */
    $has_product = !empty($subcats);

    ob_start();
    ?>
    <div class="category" data-id="<?php echo esc_attr($cat->term_id); ?>">
        <div class="category-name">
            <h5><?php echo esc_html($cat->name); ?></h5>
        </div>
        <div class="sub-category">
            <?php foreach ($subcats as $sub) : ?>

                <section
                    class="hsb-shop-lazy-subcategory"
                    data-subcategory-id="<?php echo esc_attr(
                        $sub->term_id
                    ); ?>">

                    <div class="sub-category-name">

                        <p class="sub-cat-name">
                            <?php
                            echo esc_html(
                                $sub->name
                            );
                            ?>
                        </p>

                    </div>


                    <div
                        class="child-category hsb-shop-lazy-grid"
                        data-subcategory-id="<?php echo esc_attr(
                            $sub->term_id
                        ); ?>">
                    </div>


                    <div
                        class="hsb-shop-lazy-sentinel"
                        data-subcategory-id="<?php echo esc_attr(
                            $sub->term_id
                        ); ?>"
                        data-page="0"
                        data-loading="0"
                        data-complete="0">

                        <span class="hsb-shop-lazy-status">
                            <i
                                class="fa-solid fa-circle-notch fa-spin">
                            </i>

                            <span>
                                در حال آماده‌سازی محصولات...
                            </span>
                        </span>

                    </div>

                </section>

            <?php endforeach; ?>
        </div>
    </div>
    <hr class="sub-section-hr" />
    <?php
    $html = ob_get_clean();
    if ($has_product) {
        echo $html;
    }
    wp_die();
}

add_action('wp_ajax_search_products_by_dimensions', 'hypersanati_search_products_by_dimensions');
add_action('wp_ajax_nopriv_search_products_by_dimensions', 'hypersanati_search_products_by_dimensions');

function hypersanati_search_products_by_dimensions() {
    $page = isset($_GET['index'])
        ? max(0, intval($_GET['index']))
        : 0;

    $per_page = 12;

    $meta_query =
        hypersanati_build_dimension_meta_query(
            $_GET
        );

    if (empty($meta_query)) {
        wp_die();
    }

    /*
     * Query IDs only, group matching SKUs by Part Number,
     * then paginate the visible families.
     *
     * This prevents page 2 from showing a sibling that was
     * already represented on page 1.
     */
    $all_matching_ids = get_posts(array(
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'fields'                 => 'ids',
        'meta_query'             => $meta_query,
        'orderby'                => 'title',
        'order'                  => 'ASC',
        'no_found_rows'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
    ));

    if (empty($all_matching_ids)) {
        wp_die();
    }

    $family_groups =
        function_exists(
            'hsb_group_product_ids_by_part_number'
        )
            ? hsb_group_product_ids_by_part_number(
                $all_matching_ids
            )
            : array_map(
                static function ($id) {
                    return array(
                        'representative_id' =>
                            absint($id),
                        'version_count' => 1,
                        'brand_count'   => 0,
                        'country_count' => 0,
                    );
                },
                $all_matching_ids
            );

    $offset = $page * $per_page;

    $page_families = array_slice(
        $family_groups,
        $offset,
        $per_page
    );

    if (empty($page_families)) {
        wp_die();
    }

    ob_start();
    ?>

    <div class="category dimension-search-results">

        <div class="sub-category">

            <div class="child-category">

                <?php
                foreach (
                    $page_families as
                    $family_group
                ) :

                    hypersanati_render_product_card(
                        $family_group[
                            'representative_id'
                        ],
                        $family_group
                    );

                endforeach;
                ?>

            </div>

        </div>

    </div>

    <?php
    echo ob_get_clean();

    wp_die();
}



/* ============================================================
 * HSB PROGRESSIVE SHOP FAMILY LOADING
 * ============================================================ */

/**
 * Small cache version used for shop-family queries.
 * Whenever a product changes, future requests automatically
 * use a fresh cache namespace.
 */
function hsb_get_shop_family_cache_version() {
    return max(
        1,
        absint(
            get_option(
                'hsb_shop_family_cache_version',
                1
            )
        )
    );
}


/**
 * Invalidate cached shop-family collections after a product
 * is changed.
 */
function hsb_bump_shop_family_cache_version($post_id = 0) {
    if (
        $post_id &&
        'product' !== get_post_type($post_id)
    ) {
        return;
    }

    update_option(
        'hsb_shop_family_cache_version',
        hsb_get_shop_family_cache_version() + 1,
        false
    );
}

add_action(
    'save_post_product',
    'hsb_bump_shop_family_cache_version',
    50
);

add_action(
    'before_delete_post',
    function ($post_id) {
        if ('product' === get_post_type($post_id)) {
            hsb_bump_shop_family_cache_version(
                $post_id
            );
        }
    }
);


/**
 * Return grouped product families for one subcategory.
 *
 * The expensive grouping work is cached, while presentation
 * remains paginated in small batches.
 */
function hsb_get_shop_subcategory_family_groups(
    $subcategory_id,
    $search_keyword = ''
) {
    $subcategory_id = absint(
        $subcategory_id
    );

    $search_keyword = trim(
        sanitize_text_field(
            $search_keyword
        )
    );

    if ($subcategory_id < 1) {
        return array();
    }

    $cache_version =
        hsb_get_shop_family_cache_version();

    $cache_key =
        'hsb_shop_family_' .
        $cache_version . '_' .
        $subcategory_id . '_' .
        md5($search_keyword);

    $cached = get_transient($cache_key);

    if (is_array($cached)) {
        return $cached;
    }

    $args = array(
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'fields'                 => 'ids',
        'orderby'                => 'title',
        'order'                  => 'ASC',
        'no_found_rows'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
        'tax_query'              => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $subcategory_id,
            ),
        ),
    );

    if ('' !== $search_keyword) {
        $args['s'] = $search_keyword;
    }

    $ids = get_posts($args);

    $groups = function_exists(
        'hsb_group_product_ids_by_part_number'
    )
        ? hsb_group_product_ids_by_part_number(
            $ids
        )
        : array();

    /*
     * Ten minutes is enough to make repeated browsing fast
     * without leaving catalog changes stale for long.
     */
    set_transient(
        $cache_key,
        $groups,
        10 * MINUTE_IN_SECONDS
    );

    return $groups;
}


/**
 * AJAX: progressively return one small family batch.
 */
add_action(
    'wp_ajax_hsb_load_shop_family_batch',
    'hsb_load_shop_family_batch'
);

add_action(
    'wp_ajax_nopriv_hsb_load_shop_family_batch',
    'hsb_load_shop_family_batch'
);

function hsb_load_shop_family_batch() {
    $subcategory_id = isset(
        $_GET['subcategory_id']
    )
        ? absint(
            $_GET['subcategory_id']
        )
        : 0;

    $page = isset($_GET['page'])
        ? max(
            0,
            absint($_GET['page'])
        )
        : 0;

    $search_keyword = isset(
        $_GET['search_keyword']
    )
        ? sanitize_text_field(
            wp_unslash(
                $_GET['search_keyword']
            )
        )
        : '';

    if ($subcategory_id < 1) {
        wp_send_json_error(
            array(
                'message' =>
                    'زیر‌دسته معتبر نیست.',
            ),
            400
        );
    }

    /*
     * Eight visible families at a time gives a useful
     * desktop row while keeping each request light.
     */
    $per_page = 8;

    $groups =
        hsb_get_shop_subcategory_family_groups(
            $subcategory_id,
            $search_keyword
        );

    if (empty($groups)) {
        wp_send_json_success(
            array(
                'html'      => '',
                'has_more'  => false,
                'next_page' => $page,
                'count'     => 0,
            )
        );
    }

    $offset = $page * $per_page;

    $batch = array_slice(
        $groups,
        $offset,
        $per_page
    );

    ob_start();

    foreach ($batch as $family_group) {
        hypersanati_render_product_card(
            $family_group[
                'representative_id'
            ],
            $family_group
        );
    }

    $html = ob_get_clean();

    $next_offset =
        $offset + count($batch);

    wp_send_json_success(
        array(
            'html' => $html,

            'has_more' =>
                $next_offset <
                count($groups),

            'next_page' =>
                $page + 1,

            'count' =>
                count($batch),

            'total' =>
                count($groups),
        )
    );
}


// ۲. آژاکس ساخت ساختار درختی دسته‌ها و زیردسته‌ها در سایدبار
add_action('wp_ajax_get_sidebar_categories', 'get_sidebar_categories');
add_action('wp_ajax_nopriv_get_sidebar_categories', 'get_sidebar_categories');

function get_sidebar_categories() {
    $uncategorized = get_term_by('slug', 'uncategorized', 'product_cat');
    $exclude_ids = $uncategorized ? array($uncategorized->term_id) : array();

    // گرفتن دسته‌های اصلی (والد)
    $main_categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'exclude'    => $exclude_ids,
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC'
    ]);

    ob_start();
    ?>
    <!-- گزینه پیش‌فرض نمایش همه -->
    <div class="sidebar-cat-group">
        <label class="sidebar-category-item main-cat">
            <input type="radio" name="product_category" value="0" checked>
            <span>همه دسته محصولات</span>
        </label>
    </div>

    <?php
    foreach ($main_categories as $main_cat) {
        ?>
        <div class="sidebar-cat-group" style="margin-bottom: 12px;">
            <!-- دسته اصلی -->
            <label class="sidebar-category-item main-cat" style="display: block; font-weight: bold; cursor: pointer;">
                <input type="radio" name="product_category" value="<?php echo $main_cat->term_id; ?>">
                <span><?php echo esc_html($main_cat->name); ?></span>
            </label>

            <?php
            // گرفتن زیردسته‌های مربوط به این دسته اصلی
            $sub_categories = get_terms([
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => $main_cat->term_id,
                'orderby'    => 'name',
                'order'      => 'ASC'
            ]);

            if (!empty($sub_categories)) {
                echo '<div class="sidebar-subcats-wrapper" style="padding-right: 15px; margin-top: 5px;">';
                foreach ($sub_categories as $sub_cat) {
                    ?>
                    <!-- زیر دسته (با کمی فاصله از راست) -->
                    <label class="sidebar-category-item sub-cat" style="display: block; margin-bottom: 4px; font-size: 0.9em; opacity: 0.85; cursor: pointer;">
                        <input type="radio" name="product_category" value="<?php echo $sub_cat->term_id; ?>">
                        <span><?php echo esc_html($sub_cat->name); ?></span>
                    </label>
                    <?php
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }
    echo ob_get_clean();
    wp_die();
}







// ذخیره اطلاعات تصویر برند به همراه پاکسازی دقیق
add_action('edited_product_brand', 'save_brand_image', 10, 2);
add_action('create_product_brand', 'save_brand_image', 10, 2);
function save_brand_image($term_id, $tt_id) {
    if (isset($_POST['brand_image_id'])) {
        $image_id = sanitize_text_field($_POST['brand_image_id']);
        update_term_meta($term_id, 'brand_image_id', $image_id);
    }
}





// Product Attributes for Woocommerce
add_action('woocommerce_product_data_tabs', 'bearing_technical_specs_tab');

function bearing_technical_specs_tab($tabs) {

    $tabs['bearing_specs'] = array(
        'label'    => __('مشخصات فنی بلبرینگ', 'woocommerce'),
        'target'   => 'bearing_specs_data_panel',
        'class'    => array('show_if_simple', 'show_if_variable'),
        'priority' => 21,
    );

    return $tabs;
}

add_action('woocommerce_product_data_panels', 'bearing_technical_specs_fields');

function bearing_technical_specs_fields() {
    ?>
    <div id="bearing_specs_data_panel" class="panel woocommerce_options_panel hidden">

        <div class="options_group">
            <h3>مشخصات ابعادی و ساختاری</h3>

            <?php
            woocommerce_wp_text_input([
                'id'    => '_mpn_part_number',
                'label' => __('شماره فنی / پارت نامبر', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_inner_diameter',
                'label' => __('قطر داخلی', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_outer_diameter',
                'label' => __('قطر خارجی', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_width',
                'label' => __('عرض', 'woocommerce'),
            ]);
            ?>
        </div>

        <div class="options_group">
            <h3>مشخصات فیزیکی</h3>

            <?php
            woocommerce_wp_text_input([
                'id'    => '_bearing_seal',
                'label' => __('نوع آب‌بندی', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_clearance',
                'label' => __('لقی', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_precision',
                'label' => __('کلاس دقت', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_material',
                'label' => __('جنس', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_cage',
                'label' => __('قفسه', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_lubrication',
                'label' => __('روانکاری', 'woocommerce'),
            ]);
            ?>
        </div>

        <div class="options_group">
            <h3>عملکرد</h3>

            <?php
            woocommerce_wp_text_input([
                'id'    => '_dynamic_load',
                'label' => __('بار دینامیکی', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_static_load',
                'label' => __('بار استاتیکی', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_max_rpm',
                'label' => __('حداکثر دور', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_country_origin',
                'label' => __('کشور سازنده', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_usage',
                'label' => __('کاربرد', 'woocommerce'),
            ]);

            woocommerce_wp_text_input([
                'id'    => '_bearing_industry',
                'label' => __('صنعت', 'woocommerce'),
            ]);
            ?>
        </div>

        <div class="options_group">
            <h3>مستندات</h3>

            <?php
            woocommerce_wp_text_input([
                'id'    => '_datasheet_link',
                'label' => __('لینک دیتاشیت', 'woocommerce'),
            ]);

            woocommerce_wp_textarea_input([
                'id'          => '_equivalent_codes',
                'label'       => __('کدهای معادل', 'woocommerce'),
                'description' => __('با | جدا کنید'),
            ]);
            ?>
        </div>

    </div>
    <?php
}

add_action('woocommerce_process_product_meta', 'hypersanati_save_bearing_specs', 20);

function hypersanati_save_bearing_specs($post_id) {
    $spec_fields = [
        '_mpn_part_number',
        '_inner_diameter',
        '_outer_diameter',
        '_bearing_width',
        '_bearing_seal',
        '_bearing_clearance',
        '_bearing_precision',
        '_bearing_material',
        '_bearing_cage',
        '_bearing_lubrication',
        '_dynamic_load',
        '_static_load',
        '_max_rpm',
        '_country_origin',
        '_bearing_usage',
        '_bearing_industry',
        '_datasheet_link',
        '_equivalent_codes',
    ];

    foreach ($spec_fields as $field) {
        if (isset($_POST[$field])) {
            if ($field === '_equivalent_codes') {
                update_post_meta($post_id, $field, sanitize_textarea_field(wp_unslash($_POST[$field])));
            } else {
                update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
            }
        }
    }
}




// NEXT SECTION PORSESH VA PASOKH
defined('ABSPATH') || exit;

/**
 * تبدیل اعداد انگلیسی به فارسی
 */
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

/**
 * گرفتن تعداد پرسش‌های تایید شده محصول
 */
if (!function_exists('theme_get_product_question_count')) {
    function theme_get_product_question_count($product_id) {
        return (int) get_comments(array(
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'product_question',
            'parent'  => 0,
            'count'   => true,
        ));
    }
}

/**
 * خروجی HTML لیست پرسش و پاسخ محصول
 */
if (!function_exists('theme_render_product_qa_list')) {
    function theme_render_product_qa_list($product_id, $qa_sort = 'newest') {
        $product_id = absint($product_id);
        $qa_sort    = $qa_sort === 'popular' ? 'popular' : 'newest';

        $question_args = array(
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'product_question',
            'parent'  => 0,
            'number'  => 50,
        );

        if ($qa_sort === 'popular') {
            $question_args['meta_key'] = 'qa_same_count';
            $question_args['orderby']  = 'meta_value_num';
            $question_args['order']    = 'DESC';
        } else {
            $question_args['orderby'] = 'comment_date_gmt';
            $question_args['order']   = 'DESC';
        }

        $questions = get_comments($question_args);

        ob_start();
        ?>

        <?php if (!empty($questions)) : ?>

            <?php foreach ($questions as $question) : ?>
                <?php
                $same_count = (int) get_comment_meta($question->comment_ID, 'qa_same_count', true);

                $is_buyer = false;
                if (function_exists('wc_customer_bought_product')) {
                    $is_buyer = wc_customer_bought_product(
                        $question->comment_author_email,
                        (int) $question->user_id,
                        $product_id
                    );
                }

                $answers = get_comments(array(
                    'post_id' => $product_id,
                    'status'  => 'approve',
                    'type'    => 'product_answer',
                    'parent'  => $question->comment_ID,
                    'orderby' => 'comment_date_gmt',
                    'order'   => 'ASC',
                ));
                ?>

                <div class="qa-thread">

                    <article class="qa-item question" id="question-<?php echo esc_attr($question->comment_ID); ?>">
                        <div class="qa-header">
                            <i class="fa-regular fa-circle-user user-icon"></i>

                            <div class="qa-meta">
                                <span class="qa-name">
                                    <?php echo esc_html(get_comment_author($question)); ?>
                                </span>

                                <?php if ($is_buyer) : ?>
                                    <span class="qa-role">خریدار</span>
                                <?php else : ?>
                                    <span class="qa-role">کاربر سایت</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="qa-text">
                            <?php echo wp_kses_post(wpautop(get_comment_text($question))); ?>
                        </div>

                        <div class="qa-footer">
                            <span class="qa-date">
                                <?php echo esc_html(theme_fa_digits(get_comment_date(get_option('date_format'), $question))); ?>
                            </span>

                            <form class="same-question-form">
                                <input type="hidden" name="question_id" value="<?php echo esc_attr($question->comment_ID); ?>">

                                <button class="same-question" type="submit">
                                    <i class="fa-regular fa-thumbs-up"></i>
                                    سوال منم همین بود

                                    <span class="same-question-count">
                                        <?php echo esc_html(theme_fa_digits($same_count)); ?>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </article>

                    <?php if (!empty($answers)) : ?>
                        <?php foreach ($answers as $answer) : ?>
                            <article class="qa-item answer seller" id="answer-<?php echo esc_attr($answer->comment_ID); ?>">
                                <div class="qa-header">
                                    <i class="fa-solid fa-store seller-icon"></i>

                                    <div class="qa-meta">
                                        <span class="qa-name">
                                            <?php echo esc_html(get_bloginfo('name')); ?>
                                        </span>
                                        <span class="qa-role">واحد پشتیبانی</span>
                                    </div>
                                </div>

                                <div class="qa-text">
                                    <?php echo wp_kses_post(wpautop(get_comment_text($answer))); ?>
                                </div>

                                <div class="qa-footer">
                                    <span class="qa-date">
                                        <?php echo esc_html(theme_fa_digits(get_comment_date(get_option('date_format'), $answer))); ?>
                                    </span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="qa-no-answer">
                            هنوز پاسخی برای این پرسش ثبت نشده است.
                        </div>
                    <?php endif; ?>

                    <?php if (current_user_can('edit_post', $product_id) || current_user_can('manage_woocommerce')) : ?>
                        <details class="qa-answer-details">
                            <summary>ثبت پاسخ فروشنده</summary>

                            <form class="qa-answer-form">
                                <input type="hidden" name="question_id" value="<?php echo esc_attr($question->comment_ID); ?>">

                                <textarea name="answer_text" rows="4" required placeholder="پاسخ خود را بنویسید..."></textarea>

                                <button type="submit" class="reply-btn">
                                    ثبت پاسخ
                                </button>
                            </form>
                        </details>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>

        <?php else : ?>

            <div class="qa-empty-state">
                <i class="fa-regular fa-message"></i>
                <h4>هنوز پرسشی ثبت نشده است</h4>
                <p>اولین نفری باشید که درباره این محصول پرسش ثبت می‌کند.</p>
            </div>

        <?php endif; ?>

        <?php
        return ob_get_clean();
    }
}

/**
 * enqueue اسکریپت AJAX فقط در صفحه محصول
 */
add_action('wp_enqueue_scripts', 'theme_enqueue_product_qa_ajax_script');

function theme_enqueue_product_qa_ajax_script() {
    if (!is_product()) {
        return;
    }

    wp_enqueue_script(
        'theme-product-qa-ajax',
        get_stylesheet_directory_uri() . '/assets/js/product-qa-ajax.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_localize_script('theme-product-qa-ajax', 'themeProductQa', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ));
}

/**
 * AJAX: مرتب‌سازی و دریافت لیست پرسش‌ها
 */
add_action('wp_ajax_theme_load_product_qa', 'theme_ajax_load_product_qa');
add_action('wp_ajax_nopriv_theme_load_product_qa', 'theme_ajax_load_product_qa');

function theme_ajax_load_product_qa() {
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $qa_sort    = isset($_POST['qa_sort']) ? sanitize_text_field(wp_unslash($_POST['qa_sort'])) : 'newest';
    $nonce      = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

    if (!$product_id || !wp_verify_nonce($nonce, 'product_qa_ajax_' . $product_id)) {
        wp_send_json_error(array(
            'message' => 'درخواست نامعتبر است.',
        ));
    }

    wp_send_json_success(array(
        'html'     => theme_render_product_qa_list($product_id, $qa_sort),
        'count'    => theme_get_product_question_count($product_id),
        'count_fa' => theme_fa_digits(number_format_i18n(theme_get_product_question_count($product_id))),
    ));
}

/**
 * AJAX: ثبت پرسش
 */
add_action('wp_ajax_theme_submit_product_question', 'theme_ajax_submit_product_question');
add_action('wp_ajax_nopriv_theme_submit_product_question', 'theme_ajax_submit_product_question');

function theme_ajax_submit_product_question() {
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $nonce      = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

    if (!$product_id || !wp_verify_nonce($nonce, 'product_qa_ajax_' . $product_id)) {
        wp_send_json_error(array(
            'message' => 'درخواست نامعتبر است.',
        ));
    }

    $question_text = isset($_POST['question_text']) ? sanitize_textarea_field(wp_unslash($_POST['question_text'])) : '';

    if (empty($question_text)) {
        wp_send_json_error(array(
            'message' => 'متن پرسش نمی‌تواند خالی باشد.',
        ));
    }

    $user_id = get_current_user_id();

    if ($user_id) {
        $user         = wp_get_current_user();
        $author_name  = $user->display_name;
        $author_email = $user->user_email;
    } else {
        $author_name  = isset($_POST['question_author']) ? sanitize_text_field(wp_unslash($_POST['question_author'])) : '';
        $author_email = isset($_POST['question_email']) ? sanitize_email(wp_unslash($_POST['question_email'])) : '';

        if (empty($author_name) || empty($author_email)) {
            wp_send_json_error(array(
                'message' => 'نام و ایمیل خود را وارد کنید.',
            ));
        }
    }

    $approved = get_option('comment_moderation') ? 0 : 1;

    $comment_id = wp_insert_comment(array(
        'comment_post_ID'      => $product_id,
        'comment_author'       => $author_name,
        'comment_author_email' => $author_email,
        'comment_content'      => $question_text,
        'comment_type'         => 'product_question',
        'comment_parent'       => 0,
        'comment_approved'     => $approved,
        'user_id'              => $user_id,
    ));

    if (!$comment_id) {
        wp_send_json_error(array(
            'message' => 'ثبت پرسش انجام نشد. دوباره تلاش کنید.',
        ));
    }

    add_comment_meta($comment_id, 'qa_same_count', 0, true);

    $message = $approved
        ? 'پرسش شما با موفقیت ثبت شد.'
        : 'پرسش شما ثبت شد و پس از تایید نمایش داده می‌شود.';

    wp_send_json_success(array(
        'message'  => $message,
        'html'     => theme_render_product_qa_list($product_id, 'newest'),
        'count'    => theme_get_product_question_count($product_id),
        'count_fa' => theme_fa_digits(number_format_i18n(theme_get_product_question_count($product_id))),
    ));
}

/**
 * AJAX: ثبت پاسخ مدیر / فروشنده
 */
add_action('wp_ajax_theme_submit_product_answer', 'theme_ajax_submit_product_answer');

function theme_ajax_submit_product_answer() {
    $product_id  = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $question_id = isset($_POST['question_id']) ? absint($_POST['question_id']) : 0;
    $nonce       = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

    if (!$product_id || !wp_verify_nonce($nonce, 'product_qa_ajax_' . $product_id)) {
        wp_send_json_error(array(
            'message' => 'درخواست نامعتبر است.',
        ));
    }

    if (!current_user_can('edit_post', $product_id) && !current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => 'شما اجازه ثبت پاسخ ندارید.',
        ));
    }

    $answer_text = isset($_POST['answer_text']) ? sanitize_textarea_field(wp_unslash($_POST['answer_text'])) : '';

    if (!$question_id || empty($answer_text)) {
        wp_send_json_error(array(
            'message' => 'متن پاسخ نمی‌تواند خالی باشد.',
        ));
    }

    $parent_question = get_comment($question_id);

    if (!$parent_question || (int) $parent_question->comment_post_ID !== $product_id) {
        wp_send_json_error(array(
            'message' => 'پرسش معتبر نیست.',
        ));
    }

    $user = wp_get_current_user();

    wp_insert_comment(array(
        'comment_post_ID'      => $product_id,
        'comment_author'       => $user->display_name ? $user->display_name : get_bloginfo('name'),
        'comment_author_email' => $user->user_email,
        'comment_content'      => $answer_text,
        'comment_type'         => 'product_answer',
        'comment_parent'       => $question_id,
        'comment_approved'     => 1,
        'user_id'              => get_current_user_id(),
    ));

    wp_send_json_success(array(
        'message'  => 'پاسخ با موفقیت ثبت شد.',
        'html'     => theme_render_product_qa_list($product_id, 'newest'),
        'count'    => theme_get_product_question_count($product_id),
        'count_fa' => theme_fa_digits(number_format_i18n(theme_get_product_question_count($product_id))),
    ));
}

/**
 * AJAX: سوال منم همین بود
 */
add_action('wp_ajax_theme_same_product_question', 'theme_ajax_same_product_question');
add_action('wp_ajax_nopriv_theme_same_product_question', 'theme_ajax_same_product_question');

function theme_ajax_same_product_question() {
    $product_id  = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $question_id = isset($_POST['question_id']) ? absint($_POST['question_id']) : 0;
    $nonce       = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

    if (!$product_id || !$question_id || !wp_verify_nonce($nonce, 'product_qa_ajax_' . $product_id)) {
        wp_send_json_error(array(
            'message' => 'درخواست نامعتبر است.',
        ));
    }

    $cookie_key = 'qa_same_' . $question_id;

    if (!empty($_COOKIE[$cookie_key])) {
        wp_send_json_error(array(
            'message' => 'قبلاً این پرسش را تایید کرده‌اید.',
        ));
    }

    $current_count = (int) get_comment_meta($question_id, 'qa_same_count', true);
    $new_count     = $current_count + 1;

    update_comment_meta($question_id, 'qa_same_count', $new_count);

    setcookie($cookie_key, '1', time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

    wp_send_json_success(array(
        'message'  => 'درخواست شما ثبت شد.',
        'count'    => $new_count,
        'count_fa' => theme_fa_digits($new_count),
    ));
}

// END -------------------------




// Start Maziyat Reghabati Singpe Product -----------------------------------
defined('ABSPATH') || exit;

/**
 * Register product benefit widget areas
 */
add_action('widgets_init', 'theme_register_product_benefit_sidebars');

function theme_register_product_benefit_sidebars() {
    register_sidebar(array(
        'name'          => 'مزیت‌های محصول - کم‌رنگ',
        'id'            => 'single_product_benefits_soft',
        'description'   => 'این ابزارک‌ها در سینگل محصول با حالت کم‌رنگ نمایش داده می‌شوند.',
        'before_widget' => '<div id="%1$s" class="pb-usp-item %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));

    register_sidebar(array(
        'name'          => 'مزیت‌های محصول - پررنگ',
        'id'            => 'single_product_benefits_strong',
        'description'   => 'این ابزارک‌ها در سینگل محصول با حالت پررنگ و جذاب نمایش داده می‌شوند.',
        'before_widget' => '<div id="%1$s" class="pb-usp-item %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));

    register_widget('Theme_Product_Benefit_Widget');
}

/**
 * Product benefit item widget
 */
class Theme_Product_Benefit_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'theme_product_benefit_widget',
            'آیتم مزیت رقابتی محصول',
            array(
                'description' => 'نمایش یک مزیت رقابتی با تصویر یا آیکون در صفحه محصول',
            )
        );
    }

    public function widget($args, $instance) {
        $title       = !empty($instance['title']) ? $instance['title'] : '';
        $subtitle    = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $image_url   = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $icon_class  = !empty($instance['icon_class']) ? $instance['icon_class'] : 'fa-solid fa-circle-check';
        $link_url    = !empty($instance['link_url']) ? $instance['link_url'] : '';
        $open_new    = !empty($instance['open_new']);

        echo $args['before_widget'];

        $tag = !empty($link_url) ? 'a' : 'div';

        $attrs = '';

        if (!empty($link_url)) {
            $attrs .= ' href="' . esc_url($link_url) . '"';

            if ($open_new) {
                $attrs .= ' target="_blank" rel="noopener noreferrer"';
            }
        }

        ?>
        <<?php echo esc_html($tag); ?> class="pb-usp-card"<?php echo $attrs; ?>>
            <span class="pb-usp-icon">
                <?php if (!empty($image_url)) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                <?php else : ?>
                    <i class="<?php echo esc_attr($icon_class); ?>"></i>
                <?php endif; ?>
            </span>

            <span class="pb-usp-content">
                <?php if (!empty($title)) : ?>
                    <strong><?php echo esc_html($title); ?></strong>
                <?php endif; ?>

                <?php if (!empty($subtitle)) : ?>
                    <small><?php echo esc_html($subtitle); ?></small>
                <?php endif; ?>
            </span>
        </<?php echo esc_html($tag); ?>>
        <?php

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title      = !empty($instance['title']) ? $instance['title'] : '';
        $subtitle   = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $image_url  = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $icon_class = !empty($instance['icon_class']) ? $instance['icon_class'] : '';
        $link_url   = !empty($instance['link_url']) ? $instance['link_url'] : '';
        $open_new   = !empty($instance['open_new']);
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">عنوان</label>
            <input class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>"
                   placeholder="مثلاً امکان تحویل اکسپرس">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('subtitle')); ?>">توضیح کوتاه</label>
            <input class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('subtitle')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('subtitle')); ?>"
                   type="text"
                   value="<?php echo esc_attr($subtitle); ?>"
                   placeholder="مثلاً ارسال سریع به سراسر کشور">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('image_url')); ?>">آدرس تصویر / آیکون آپلودی</label>
            <input class="widefat pb-usp-image-url"
                   id="<?php echo esc_attr($this->get_field_id('image_url')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('image_url')); ?>"
                   type="text"
                   value="<?php echo esc_url($image_url); ?>"
                   placeholder="لینک تصویر از رسانه وردپرس">
            <button type="button" class="button pb-usp-upload-btn" style="margin-top:8px;">انتخاب تصویر</button>
            <button type="button" class="button pb-usp-remove-btn" style="margin-top:8px;">حذف تصویر</button>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_class')); ?>">کلاس آیکون FontAwesome</label>
            <input class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('icon_class')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('icon_class')); ?>"
                   type="text"
                   value="<?php echo esc_attr($icon_class); ?>"
                   placeholder="مثلاً fa-solid fa-truck-fast">
            <small>اگر تصویر وارد شود، تصویر نمایش داده می‌شود؛ اگر تصویر خالی باشد، آیکون FontAwesome نمایش داده می‌شود.</small>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('link_url')); ?>">لینک اختیاری</label>
            <input class="widefat"
                   id="<?php echo esc_attr($this->get_field_id('link_url')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('link_url')); ?>"
                   type="url"
                   value="<?php echo esc_url($link_url); ?>"
                   placeholder="مثلاً لینک صفحه ضمانت">
        </p>

        <p>
            <input id="<?php echo esc_attr($this->get_field_id('open_new')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('open_new')); ?>"
                   type="checkbox"
                   value="1"
                <?php checked($open_new); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('open_new')); ?>">
                باز شدن لینک در تب جدید
            </label>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();

        $instance['title']      = sanitize_text_field($new_instance['title'] ?? '');
        $instance['subtitle']   = sanitize_text_field($new_instance['subtitle'] ?? '');
        $instance['image_url']  = esc_url_raw($new_instance['image_url'] ?? '');
        $instance['icon_class'] = sanitize_text_field($new_instance['icon_class'] ?? '');
        $instance['link_url']   = esc_url_raw($new_instance['link_url'] ?? '');
        $instance['open_new']   = !empty($new_instance['open_new']) ? 1 : 0;

        return $instance;
    }
}

/**
 * Media uploader for widget image field
 */
add_action('admin_enqueue_scripts', 'theme_product_benefit_widget_admin_assets');

function theme_product_benefit_widget_admin_assets($hook) {
    if ($hook !== 'widgets.php' && $hook !== 'customize.php') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('jquery');

    $js = "
    jQuery(document).on('click', '.pb-usp-upload-btn', function(e) {
        e.preventDefault();

        var button = jQuery(this);
        var input = button.closest('p').find('.pb-usp-image-url');

        var frame = wp.media({
            title: 'انتخاب تصویر مزیت',
            button: {
                text: 'استفاده از این تصویر'
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url).trigger('change');
        });

        frame.open();
    });

    jQuery(document).on('click', '.pb-usp-remove-btn', function(e) {
        e.preventDefault();

        var button = jQuery(this);
        var input = button.closest('p').find('.pb-usp-image-url');

        input.val('').trigger('change');
    });
    ";

    wp_add_inline_script('jquery', $js);
}

/**
 * Render product benefits widget area
 */
if (!function_exists('theme_render_product_benefits_area')) {
    function theme_render_product_benefits_area($sidebar_id, $style = 'soft') {
        if (!is_active_sidebar($sidebar_id)) {
            return;
        }

        $style = $style === 'strong' ? 'strong' : 'soft';
        ?>
        <section class="pb-usp-strip pb-usp-strip--<?php echo esc_attr($style); ?>">
            <div class="pb-usp-inner">
                <?php dynamic_sidebar($sidebar_id); ?>
            </div>
        </section>
        <?php
    }
}
// END Maziyat Reghabati Singpe Product -----------------------------------



// ==========================================
// Hypersanati WooCommerce & Account / OTP Module
// ==========================================

/* ============================================================
   Hypersanati OTP Login / Register Module
   محل فایل‌ها طبق ساختار پروژه:
   - assets/css/otp.css
   - assets/js/otp.js
   - woocommerce/myaccount/form-otp-login.php
   - woocommerce/myaccount/dashboard.php
   این ماژول کاملاً مستقل و درون functions.php است؛
   نیازی به inc/enqueue.php ندارد.
*/
if (!defined('ABSPATH')) exit;

define('HYPERSANATI_OTP_LENGTH', 5);        // تعداد ارقام کد تایید (۴ یا ۵)
define('HYPERSANATI_OTP_TTL', 300);         // مدت اعتبار کد به ثانیه (۵ دقیقه)
define('HYPERSANATI_OTP_RESEND_WAIT', 60);  // حداقل فاصله بین دو درخواست کد (ثانیه)


/* -------------------------------------------------------------
   ۱. Enqueue استایل و اسکریپت
   otp.css / otp.js سراسری هستند چون دکمه‌ی باز کردن مودال
   داخل header.php و روی همه‌ی صفحات سایت است.
   profile.css فقط در خودِ صفحه‌ی حساب کاربری لازم است.
------------------------------------------------------------- */
add_action('wp_enqueue_scripts', 'hypersanati_enqueue_otp_assets', 20);

function hypersanati_enqueue_otp_assets()
{
    $css_dir = get_template_directory() . '/assets/css';
    $js_dir  = get_template_directory() . '/assets/js';
    $css_url = get_template_directory_uri() . '/assets/css';
    $js_url  = get_template_directory_uri() . '/assets/js';

    // otp.css (سراسری)
    $otp_css_path = $css_dir . '/otp.css';
    if (file_exists($otp_css_path)) {
        wp_enqueue_style('hypersanati-otp', $css_url . '/otp.css', [], filemtime($otp_css_path));
    }

    // otp.js (سراسری)
    $otp_js_path = $js_dir . '/otp.js';
    if (file_exists($otp_js_path)) {
        wp_enqueue_script('hypersanati-otp', $js_url . '/otp.js', [], filemtime($otp_js_path), true);

        wp_localize_script('hypersanati-otp', 'otp_data', [
            'ajax_url'    => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce('hypersanati_otp_action'),
            'code_length' => HYPERSANATI_OTP_LENGTH,
            'resend_wait' => HYPERSANATI_OTP_RESEND_WAIT,
            'is_logged_in'=> is_user_logged_in(),
        ]);
    }

    // profile.css (فقط در my-account)
    if (function_exists('is_account_page') && is_account_page()) {
        $profile_css_path = $css_dir . '/profile.css';
        if (file_exists($profile_css_path)) {
            wp_enqueue_style('hypersanati-profile', $css_url . '/profile.css', ['hypersanati-otp'], filemtime($profile_css_path));
        }
    }
}


 /*-------------------------------------------------------------
   ۲. تزریق مودال OTP قبل از </body>
   فقط برای کاربران مهمان (کاربر لاگین‌شده نیازی به این فرم ندارد)
------------------------------------------------------------- */
add_action('wp_footer', 'hypersanati_render_otp_modal');

function hypersanati_render_otp_modal()
{
    if (is_user_logged_in()) {
        return;
    }

    $modal_path = get_template_directory() . '/woocommerce/myaccount/form-otp-login.php';
    if (file_exists($modal_path)) {
        include $modal_path;
    }
}


/* -------------------------------------------------------------
   ۳. ریدایرکت صفحه‌ی حساب کاربری ووکامرس به قالب اختصاصی
------------------------------------------------------------- */
add_filter('template_include', 'hypersanati_use_custom_dashboard_template');

function hypersanati_use_custom_dashboard_template($template)
{
    if (is_page() && function_exists('is_account_page') && is_account_page()) {
        $custom_dashboard = get_template_directory() . '/woocommerce/myaccount/dashboard.php';
        if (file_exists($custom_dashboard)) {
            return $custom_dashboard;
        }
    }
    return $template;
}


/* -------------------------------------------------------------
   ۴. AJAX: ارسال کد تایید
------------------------------------------------------------- */
add_action('wp_ajax_ui_send_otp', 'hypersanati_ajax_send_otp');
add_action('wp_ajax_nopriv_ui_send_otp', 'hypersanati_ajax_send_otp');

function hypersanati_ajax_send_otp()
{
    check_ajax_referer('hypersanati_otp_action', 'nonce');

    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';

    if (!preg_match('/^09\d{9}$/', $phone)) {
        wp_send_json_error(['message' => 'شماره موبایل معتبر نیست. مثال صحیح: 09123456789']);
    }

    // جلوگیری از ارسال مکرر/اسپم
    $wait_key = 'hs_otp_wait_' . $phone;
    if (get_transient($wait_key)) {
        wp_send_json_error(['message' => 'لطفاً کمی صبر کنید و دوباره تلاش کنید.']);
    }

    try {
        $min  = (int) pow(10, HYPERSANATI_OTP_LENGTH - 1);
        $max  = (int) pow(10, HYPERSANATI_OTP_LENGTH) - 1;
        $code = (string) random_int($min, $max);
    } catch (Exception $e) {
        wp_send_json_error(['message' => 'خطا در تولید کد تایید.']);
    }

    set_transient('hs_otp_code_' . $phone, $code, HYPERSANATI_OTP_TTL);
    set_transient($wait_key, 1, HYPERSANATI_OTP_RESEND_WAIT);

    // TODO: اتصال به وب‌سرویس پیامک واقعی
    // hypersanati_send_sms($phone, $code);

    wp_send_json_success([
        'message'     => 'کد تایید ارسال شد.',
        'resend_wait' => HYPERSANATI_OTP_RESEND_WAIT,
    ]);
}


/* -------------------------------------------------------------
   ۵. AJAX: تایید کد و ورود / ثبت‌نام خودکار کاربر
------------------------------------------------------------- */
add_action('wp_ajax_ui_verify_otp', 'hypersanati_ajax_verify_otp');
add_action('wp_ajax_nopriv_ui_verify_otp', 'hypersanati_ajax_verify_otp');

function hypersanati_ajax_verify_otp()
{
    check_ajax_referer('hypersanati_otp_action', 'nonce');

    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $code  = isset($_POST['code'])  ? sanitize_text_field(wp_unslash($_POST['code']))  : '';

    if (!preg_match('/^09\d{9}$/', $phone)) {
        wp_send_json_error(['message' => 'شماره موبایل نامعتبر است.']);
    }

    $pattern = '/^\d{' . HYPERSANATI_OTP_LENGTH . '}$/';
    if (!preg_match($pattern, $code)) {
        wp_send_json_error(['message' => 'کد تایید باید ' . HYPERSANATI_OTP_LENGTH . ' رقمی باشد.']);
    }

    $saved_code = get_transient('hs_otp_code_' . $phone);

    if ($saved_code === false) {
        wp_send_json_error(['message' => 'کد منقضی شده است. لطفاً دوباره درخواست دهید.']);
    }

    if (!hash_equals((string) $saved_code, $code)) {
        wp_send_json_error(['message' => 'کد وارد شده نادرست است.']);
    }

    // پیدا کردن کاربر یا ساخت خودکار (ثبت‌نام یکپارچه با ورود)
    $user = get_user_by('login', $phone);

    if (!$user) {
        if (!function_exists('wc_create_new_customer')) {
            wp_send_json_error(['message' => 'ووکامرس در دسترس نیست.']);
        }

        $user_id = wc_create_new_customer(
            $phone . '@hypersanati.com',
            $phone,
            wp_generate_password(20, true, true)
        );

        if (is_wp_error($user_id)) {
            wp_send_json_error(['message' => $user_id->get_error_message()]);
        }

        $user = get_user_by('id', $user_id);

        if (!$user) {
            wp_send_json_error(['message' => 'کاربر ساخته شد اما بارگذاری نشد.']);
        }

        update_user_meta($user->ID, 'billing_phone', $phone);
    }

    // پاک‌سازی کد استفاده‌شده
    delete_transient('hs_otp_code_' . $phone);
    delete_transient('hs_otp_wait_' . $phone);

    // ورود رسمی کاربر با توابع آماده‌ی وردپرس/ووکامرس
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);

    if (function_exists('wc_set_customer_auth_cookie')) {
        wc_set_customer_auth_cookie($user->ID);
    }

    do_action('wp_login', $user->user_login, $user);

    wp_send_json_success([
        'message'  => 'ورود با موفقیت انجام شد.',
        'redirect' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/'),
    ]);
}





// START TAMAS BA MA --------------------------------------------------

// Contact page dynamic info in WordPress Customizer
add_action( 'customize_register', 'hypersanati_contact_customizer_settings' );

function hypersanati_contact_customizer_settings( $wp_customize ) {

    $wp_customize->add_section(
        'hypersanati_contact_info_section',
        array(
            'title'    => 'اطلاعات تماس فروشگاه',
            'priority' => 160,
        )
    );

    $wp_customize->add_setting(
        'hypersanati_contact_address',
        array(
            'default'           => 'تهران، خیابان سعدی جنوبی، خیابان اکباتان، کوچه ناظم الاطبا شمالی، پاساژ امام حسین، زیر همکف، پلاک 32 بلبرینگ همگام صنعت برتر',
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'hypersanati_contact_address',
        array(
            'label'   => 'آدرس فروشگاه',
            'section' => 'hypersanati_contact_info_section',
            'type'    => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'hypersanati_contact_phone',
        array(
            'default'           => '۰۲۱-۳۳۹۸۹۹۳۰ - ۰۲۱-۳۳۹۸۹۹۴۰',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'hypersanati_contact_phone',
        array(
            'label'   => 'شماره تماس',
            'section' => 'hypersanati_contact_info_section',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'hypersanati_contact_fax',
        array(
            'default'           => '۰۲۱-۳۳۹۸۹۹۴۰',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'hypersanati_contact_fax',
        array(
            'label'   => 'فکس',
            'section' => 'hypersanati_contact_info_section',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'hypersanati_contact_email',
        array(
            'default'           => 'info@hamgamsanatbartar.com',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'hypersanati_contact_email',
        array(
            'label'   => 'ایمیل',
            'section' => 'hypersanati_contact_info_section',
            'type'    => 'email',
        )
    );

    $wp_customize->add_setting(
        'hypersanati_contact_terms_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'hypersanati_contact_terms_url',
        array(
            'label'   => 'لینک قوانین و مقررات',
            'section' => 'hypersanati_contact_info_section',
            'type'    => 'url',
        )
    );
}


// Register contact messages post type
add_action( 'init', 'hypersanati_register_contact_message_post_type' );

function hypersanati_register_contact_message_post_type() {

    register_post_type(
        'hs_contact_message',
        array(
            'labels' => array(
                'name'               => 'پیام‌های تماس',
                'singular_name'      => 'پیام تماس',
                'menu_name'          => 'پیام‌های تماس',
                'add_new_item'       => 'افزودن پیام تماس',
                'edit_item'          => 'مشاهده پیام تماس',
                'view_item'          => 'نمایش پیام تماس',
                'search_items'       => 'جستجوی پیام‌ها',
                'not_found'          => 'پیامی پیدا نشد',
                'not_found_in_trash' => 'پیامی در زباله‌دان پیدا نشد',
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-email-alt2',
            'supports'            => array( 'title', 'editor' ),
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'exclude_from_search' => true,
        )
    );
}


// Save contact form - Ajax + normal fallback
add_action( 'admin_post_hypersanati_contact_form', 'hypersanati_handle_contact_form' );
add_action( 'admin_post_nopriv_hypersanati_contact_form', 'hypersanati_handle_contact_form' );

add_action( 'wp_ajax_hypersanati_contact_form', 'hypersanati_handle_contact_form' );
add_action( 'wp_ajax_nopriv_hypersanati_contact_form', 'hypersanati_handle_contact_form' );

function hypersanati_handle_contact_form() {

    $is_ajax = wp_doing_ajax();

    if (
        ! isset( $_POST['hypersanati_contact_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['hypersanati_contact_nonce'] ) ),
            'hypersanati_contact_form_action'
        )
    ) {
        hypersanati_contact_response(
            'security',
            $is_ajax,
            false,
            'درخواست معتبر نیست. لطفاً صفحه را دوباره بارگذاری کنید.',
            403
        );
    }

    $subject      = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
    $phone        = isset( $_POST['contact_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) : '';
    $country_code = isset( $_POST['country_code'] ) ? sanitize_text_field( wp_unslash( $_POST['country_code'] ) ) : '+98';
    $message      = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';
    $terms        = isset( $_POST['terms'] ) ? 'accepted' : '';

    if ( empty( $subject ) || empty( $phone ) || empty( $message ) || empty( $terms ) ) {
        hypersanati_contact_response(
            'required',
            $is_ajax,
            false,
            'لطفاً موضوع، شماره همراه، پیام و پذیرش قوانین را کامل کنید.',
            422
        );
    }

    $attachment_url = '';
    $attachment_id  = 0;

    if ( ! empty( $_FILES['contact_attachment']['name'] ) ) {

        $file_size = isset( $_FILES['contact_attachment']['size'] ) ? absint( $_FILES['contact_attachment']['size'] ) : 0;

        if ( $file_size > 5 * 1024 * 1024 ) {
            hypersanati_contact_response(
                'file_size',
                $is_ajax,
                false,
                'حجم فایل نباید بیشتر از ۵ مگابایت باشد.',
                422
            );
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $allowed_mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
            'webp'         => 'image/webp',
            'pdf'          => 'application/pdf',
            'doc'          => 'application/msword',
            'docx'         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'          => 'application/vnd.ms-excel',
            'xlsx'         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'zip'          => 'application/zip',
        );

        $upload = wp_handle_upload(
            $_FILES['contact_attachment'],
            array(
                'test_form' => false,
                'mimes'     => $allowed_mimes,
            )
        );

        if ( isset( $upload['error'] ) ) {
            hypersanati_contact_response(
                'file_error',
                $is_ajax,
                false,
                'فایل انتخاب‌شده قابل بارگذاری نیست. لطفاً فایل دیگری انتخاب کنید.',
                422
            );
        }

        if ( ! empty( $upload['file'] ) ) {
            $file_type = wp_check_filetype( basename( $upload['file'] ), null );

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title'     => sanitize_file_name( basename( $upload['file'] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );

            $attachment_id = wp_insert_attachment( $attachment, $upload['file'] );

            if ( ! is_wp_error( $attachment_id ) ) {
                $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
                wp_update_attachment_metadata( $attachment_id, $attachment_data );
                $attachment_url = wp_get_attachment_url( $attachment_id );
            }
        }
    }

    $post_id = wp_insert_post(
        array(
            'post_type'    => 'hs_contact_message',
            'post_status'  => 'private',
            'post_title'   => $subject,
            'post_content' => $message,
        )
    );

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        hypersanati_contact_response(
            'save_error',
            $is_ajax,
            false,
            'در ذخیره پیام مشکلی پیش آمد. لطفاً چند لحظه دیگر دوباره تلاش کنید.',
            500
        );
    }

    update_post_meta( $post_id, '_contact_phone', $phone );
    update_post_meta( $post_id, '_contact_country_code', $country_code );
    update_post_meta( $post_id, '_contact_terms', $terms );
    update_post_meta( $post_id, '_contact_attachment_id', $attachment_id );
    update_post_meta( $post_id, '_contact_attachment_url', $attachment_url );
    update_post_meta(
        $post_id,
        '_contact_ip',
        isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : ''
    );

    $admin_email = get_option( 'admin_email' );

    $email_body  = "موضوع: {$subject}\n";
    $email_body .= "شماره تماس: {$country_code} {$phone}\n\n";
    $email_body .= "پیام:\n{$message}\n\n";

    if ( $attachment_url ) {
        $email_body .= "فایل پیوست:\n{$attachment_url}\n";
    }

    wp_mail(
        $admin_email,
        'پیام جدید از فرم تماس سایت',
        $email_body
    );

    hypersanati_contact_response(
        'success',
        $is_ajax,
        true,
        'پیام شما با موفقیت ثبت شد. همکاران ما در حال بررسی هستند و در سریع‌ترین زمان ممکن با شما تماس می‌گیرند.'
    );
}

function hypersanati_contact_response( $status, $is_ajax, $success, $message, $http_code = 200 ) {

    if ( $is_ajax ) {
        if ( $success ) {
            wp_send_json_success(
                array(
                    'status'  => $status,
                    'message' => $message,
                ),
                $http_code
            );
        }

        wp_send_json_error(
            array(
                'status'  => $status,
                'message' => $message,
            ),
            $http_code
        );
    }

    $redirect_url = isset( $_POST['redirect_to'] )
        ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) )
        : home_url( '/contact-us/' );

    wp_safe_redirect(
        add_query_arg(
            'contact_status',
            sanitize_key( $status ),
            $redirect_url
        )
    );

    exit;
}


// Contact message meta box
add_action( 'add_meta_boxes', 'hypersanati_contact_message_meta_boxes' );

function hypersanati_contact_message_meta_boxes() {
    add_meta_box(
        'hypersanati_contact_message_info',
        'اطلاعات پیام',
        'hypersanati_contact_message_meta_box_html',
        'hs_contact_message',
        'normal',
        'high'
    );
}

function hypersanati_contact_message_meta_box_html( $post ) {

    $phone          = get_post_meta( $post->ID, '_contact_phone', true );
    $country_code   = get_post_meta( $post->ID, '_contact_country_code', true );
    $attachment_url = get_post_meta( $post->ID, '_contact_attachment_url', true );
    $ip             = get_post_meta( $post->ID, '_contact_ip', true );

    ?>
    <table class="widefat striped">
        <tbody>
            <tr>
                <th style="width: 180px;">شماره تماس</th>
                <td><?php echo esc_html( $country_code . ' ' . $phone ); ?></td>
            </tr>

            <tr>
                <th>فایل پیوست</th>
                <td>
                    <?php if ( $attachment_url ) : ?>
                        <a href="<?php echo esc_url( $attachment_url ); ?>" target="_blank" rel="noopener">
                            مشاهده / دانلود فایل
                        </a>
                    <?php else : ?>
                        فایلی ارسال نشده است.
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <th>IP ارسال‌کننده</th>
                <td><?php echo esc_html( $ip ); ?></td>
            </tr>
        </tbody>
    </table>
    <?php
}


// Admin list columns
add_filter( 'manage_hs_contact_message_posts_columns', 'hypersanati_contact_message_columns' );

function hypersanati_contact_message_columns( $columns ) {

    $new_columns = array();

    $new_columns['cb']            = $columns['cb'];
    $new_columns['title']         = 'موضوع';
    $new_columns['contact_phone'] = 'شماره تماس';
    $new_columns['contact_file']  = 'فایل';
    $new_columns['date']          = 'تاریخ';

    return $new_columns;
}

add_action( 'manage_hs_contact_message_posts_custom_column', 'hypersanati_contact_message_column_content', 10, 2 );

function hypersanati_contact_message_column_content( $column, $post_id ) {

    if ( 'contact_phone' === $column ) {
        $phone        = get_post_meta( $post_id, '_contact_phone', true );
        $country_code = get_post_meta( $post_id, '_contact_country_code', true );

        echo esc_html( $country_code . ' ' . $phone );
    }

    if ( 'contact_file' === $column ) {
        $attachment_url = get_post_meta( $post_id, '_contact_attachment_url', true );

        if ( $attachment_url ) {
            echo '<a href="' . esc_url( $attachment_url ) . '" target="_blank" rel="noopener">مشاهده فایل</a>';
        } else {
            echo '—';
        }
    }
}
// PAYAN TAMAS BA MA --------------------------------------------------





// Start -- AJAX - EZAFE KARDANE MAHSUL BA DOKME AFZODAN BE SABADE KHARID ------------------------->
add_action('wp_enqueue_scripts', 'hsb_enqueue_cart_ajax_popup_assets');
function hsb_enqueue_cart_ajax_popup_assets() {
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    $css_path = get_template_directory() . '/assets/css/cart-ajax-pop-up.css';
    $js_path  = get_template_directory() . '/assets/js/cart-ajax-pop-up.js';

    wp_enqueue_style(
        'hsb-cart-ajax-popup',
        get_template_directory_uri() . '/assets/css/cart-ajax-pop-up.css',
        array(),
        file_exists($css_path) ? filemtime($css_path) : '1.0.0'
    );

    wp_enqueue_script(
        'hsb-cart-ajax-popup',
        get_template_directory_uri() . '/assets/js/cart-ajax-pop-up.js',
        array(),
        file_exists($js_path) ? filemtime($js_path) : '1.0.0',
        true
    );

    wp_localize_script('hsb-cart-ajax-popup', 'hsbCartPopup', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('hsb_cart_popup_nonce'),
    ));
}

// مرحله ۳: پاپ‌آپ را با wp_footer چاپ کن
add_action('wp_footer', 'hsb_render_cart_ajax_popup');
function hsb_render_cart_ajax_popup() {
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    if (!function_exists('WC')) {
        return;
    }
    ?>

    <section class="cart-ajax-popup-section is-hidden" id="cartAjaxPopupSection" aria-hidden="true">
        <div class="cart-ajax-popup-overlay" id="cartAjaxPopupOverlay"></div>

        <div class="cart-ajax-popup" id="cartAjaxPopup" role="dialog" aria-modal="true">
            <button class="cart-ajax-popup__close" id="cartAjaxPopupClose" type="button" aria-label="بستن">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="cart-ajax-popup__content">
                <div class="cart-ajax-popup__top">
                    <div class="cart-ajax-popup__summary">
                        <div class="cart-ajax-popup__summary-box">
                            <p class="cart-ajax-popup__summary-title" id="cartAjaxPopupSummaryTitle">
                                محصول به سبد خرید اضافه شد.
                            </p>

                            <div class="cart-ajax-popup__prices">
                                <p>خرید جزء: <span id="cartAjaxPopupSubtotal">۰ تومان</span></p>
                                <p>مالیات: <span id="cartAjaxPopupTax">۰ تومان</span></p>
                                <p class="cart-ajax-popup__total">جمع خرید: <span id="cartAjaxPopupTotal">۰ تومان</span></p>
                            </div>

                            <div class="cart-ajax-popup__actions">
                                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-ajax-popup__btn" id="cartAjaxPopupCartUrl">
                                    ویرایش سبد خرید
                                </a>

                                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="cart-ajax-popup__btn" id="cartAjaxPopupCheckoutUrl">
                                    رفتن به تسویه حساب
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="cart-ajax-popup__product">
                        <div class="cart-ajax-popup__notice">
                            <div class="cart-ajax-popup__notice-icon">
                                <i class="fa-solid fa-check"></i>
                            </div>

                            <div class="cart-ajax-popup__notice-text">
                                <p class="cart-ajax-popup__notice-title">
                                    شما <span id="cartAjaxPopupProductNoticeTitle">این محصول</span>
                                </p>
                                <p class="cart-ajax-popup__notice-subtitle">
                                    را به سبد خرید خود اضافه کردید.
                                </p>
                            </div>
                        </div>

                        <div class="cart-ajax-popup__product-box">
                            <div class="cart-ajax-popup__product-text" id="cartAjaxPopupProductText">
                                <p>محصول</p>
                            </div>

                            <div class="cart-ajax-popup__product-image">
                                <img id="cartAjaxPopupProductImage"
                                     src="<?php echo esc_url(wc_placeholder_img_src()); ?>"
                                     alt="product" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cart-ajax-popup__bottom">
                    <p class="cart-ajax-popup__bottom-title">
                        مشتری‌های ما همراه با محصول انتخابی شما، این محصولات را هم سفارش داده‌اند.
                    </p>

                    <div class="cart-ajax-popup__suggestions" id="cartAjaxPopupSuggestions"></div>

                    <div class="cart-ajax-popup__continue">
                        <button type="button" class="cart-ajax-popup__btn cart-ajax-popup__btn--single" id="cartAjaxPopupContinue">
                            ادامه خرید
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
}



// مرحله ۴: AJAX افزودن به سبد خرید را بساز
add_action('wp_ajax_hsb_ajax_add_to_cart', 'hsb_ajax_add_to_cart');
add_action('wp_ajax_nopriv_hsb_ajax_add_to_cart', 'hsb_ajax_add_to_cart');

function hsb_ajax_add_to_cart() {
    check_ajax_referer('hsb_cart_popup_nonce', 'nonce');

    if (!function_exists('WC')) {
        wp_send_json_error(array(
            'message' => 'ووکامرس فعال نیست.'
        ));
    }

    if (null === WC()->cart) {
        wc_load_cart();
    }

    $product_id = 0;

    if (!empty($_POST['product_id'])) {
        $product_id = absint($_POST['product_id']);
    } elseif (!empty($_POST['add-to-cart'])) {
        $product_id = absint($_POST['add-to-cart']);
    }

    $variation_id = !empty($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;

    if (!$product_id && $variation_id) {
        $product_id = wp_get_post_parent_id($variation_id);
    }

    $quantity = !empty($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    $variation = array();

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'attribute_') === 0) {
            $variation[sanitize_title(wp_unslash($key))] = wc_clean(wp_unslash($value));
        }
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error(array(
            'message' => 'محصول پیدا نشد.'
        ));
    }

    $passed_validation = apply_filters(
        'woocommerce_add_to_cart_validation',
        true,
        $product_id,
        $quantity,
        $variation_id,
        $variation
    );

    if (!$passed_validation) {
        wp_send_json_error(array(
            'message' => 'امکان افزودن این محصول به سبد خرید وجود ندارد.'
        ));
    }

    try {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

        if (!$cart_item_key) {
            wp_send_json_error(array(
                'message' => 'محصول به سبد خرید اضافه نشد. لطفاً دوباره تلاش کنید.'
            ));
        }

        WC()->cart->calculate_totals();

        $display_product = $variation_id ? wc_get_product($variation_id) : $product;

        $image_url = '';

        if ($display_product && $display_product->get_image_id()) {
            $image_url = wp_get_attachment_image_url($display_product->get_image_id(), 'woocommerce_thumbnail');
        }

        if (!$image_url && $product->get_image_id()) {
            $image_url = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
        }

        if (!$image_url) {
            $image_url = wc_placeholder_img_src();
        }

        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        $fragments = apply_filters('woocommerce_add_to_cart_fragments', array(
            'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
            '.sell-number p' => '<p>' . esc_html(WC()->cart->get_cart_contents_count()) . '</p>',
        ));

        wp_send_json_success(array(
            'product_title'    => $product->get_name(),
            'product_image'    => $image_url,
            'cart_count'       => WC()->cart->get_cart_contents_count(),
            'subtotal'         => wp_strip_all_tags(WC()->cart->get_cart_subtotal()),
            'tax'              => wp_strip_all_tags(wc_price(WC()->cart->get_taxes_total())),
            'total'            => wp_strip_all_tags(WC()->cart->get_total()),
            'cart_url'         => wc_get_cart_url(),
            'checkout_url'     => wc_get_checkout_url(),
            'suggestions_html' => hsb_cart_ajax_popup_suggestions_html($product_id),
            'fragments'        => $fragments,
        ));
    } catch (Exception $e) {
        wp_send_json_error(array(
            'message' => $e->getMessage()
        ));
    }
}


// مرحله ۵: محصولات پیشنهادی داخل پاپ‌آپ
function hsb_cart_ajax_popup_suggestions_html($product_id) {
    $product = wc_get_product($product_id);

    if (!$product) {
        return '';
    }

    $suggested_ids = $product->get_cross_sell_ids();

    if (empty($suggested_ids)) {
        $suggested_ids = wc_get_related_products($product_id, 4);
    }

    if (empty($suggested_ids)) {
        $suggested_ids = wc_get_products(array(
            'status'  => 'publish',
            'limit'   => 4,
            'exclude' => array($product_id),
            'orderby' => 'date',
            'order'   => 'DESC',
            'return'  => 'ids',
        ));
    }

    if (empty($suggested_ids)) {
        return '';
    }

    ob_start();

    foreach (array_slice($suggested_ids, 0, 4) as $suggested_id) {
        $suggested_product = wc_get_product($suggested_id);

        if (!$suggested_product) {
            continue;
        }
        ?>

        <a href="<?php echo esc_url(get_permalink($suggested_id)); ?>" class="cart-ajax-popup__suggestion-card">
            <?php echo $suggested_product->get_image('woocommerce_thumbnail'); ?>
        </a>

        <?php
    }

    return ob_get_clean();
}
// End -- AJAX - EZAFE KARDANE MAHSUL BA DOKME AFZODAN BE SABADE KHARID ------------------------->



// Start Checkout Page -------------------------------------------------------------------------->
/* =========================================================
   CUSTOM CHECKOUT FIELDS
========================================================= */


add_filter('woocommerce_checkout_fields', 'hsb_custom_checkout_fields');
function hsb_custom_checkout_fields($fields) {

    $fields['billing']['billing_gender'] = array(
        'type'     => 'text',
        'label'    => 'جنسیت',
        'required' => false,
        'priority' => 5,
    );

    $fields['billing']['billing_plaque'] = array(
        'type'     => 'text',
        'label'    => 'پلاک',
        'required' => true,
        'priority' => 75,
    );

    $fields['billing']['billing_national_code'] = array(
        'type'     => 'text',
        'label'    => 'کد ملی',
        'required' => true,
        'priority' => 120,
    );

    $fields['billing']['billing_birth_date'] = array(
        'type'     => 'text',
        'label'    => 'تاریخ تولد',
        'required' => false,
        'priority' => 130,
    );

    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['required'] = false;
    }

    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['label'] = 'نام فروشگاه یا کارگاه';
        $fields['billing']['billing_company']['required'] = false;
    }

    return $fields;
}

add_action('woocommerce_checkout_create_order', 'hsb_save_custom_checkout_fields', 10, 2);
function hsb_save_custom_checkout_fields($order, $data) {
    $custom_fields = array(
        'billing_gender'        => 'جنسیت',
        'billing_plaque'        => 'پلاک',
        'billing_national_code' => 'کد ملی',
        'billing_birth_date'    => 'تاریخ تولد',
    );

    foreach ($custom_fields as $field_key => $field_label) {
        if (isset($_POST[$field_key])) {
            $order->update_meta_data('_' . $field_key, sanitize_text_field(wp_unslash($_POST[$field_key])));
        }
    }
}

add_action('woocommerce_after_checkout_validation', 'hsb_validate_checkout_password_confirmation', 10, 2);
function hsb_validate_checkout_password_confirmation($data, $errors) {
    if (
        isset($_POST['account_password'], $_POST['confirm_password']) &&
        !empty($_POST['account_password']) &&
        sanitize_text_field(wp_unslash($_POST['account_password'])) !== sanitize_text_field(wp_unslash($_POST['confirm_password']))
    ) {
        $errors->add('password_confirmation_error', 'رمز عبور و تکرار رمز عبور یکسان نیستند.');
    }
}
// End Checkout Page -------------------------------------------------------------------------->


// Start Email Invoice to Customer ----------------------------------------------------------->
if (!function_exists('hsb_add_invoice_link_to_customer_email')) {
    add_action('woocommerce_email_after_order_table', 'hsb_add_invoice_link_to_customer_email', 20, 4);

    function hsb_add_invoice_link_to_customer_email($order, $sent_to_admin, $plain_text, $email) {
        if ($sent_to_admin || !($order instanceof WC_Order)) {
            return;
        }

        $allowed_emails = array(
            'customer_processing_order',
            'customer_completed_order',
            'customer_on_hold_order',
        );

        if (!isset($email->id) || !in_array($email->id, $allowed_emails, true)) {
            return;
        }

        $invoice_url = $order->get_checkout_order_received_url() . '#salesInvoiceSection';

        if ($plain_text) {
            echo "\n";
            echo "مشاهده فاکتور خرید: " . esc_url($invoice_url) . "\n";
            return;
        }
        ?>

        <div style="margin:24px 0;padding:18px;border:1px solid #e5e5e5;border-radius:10px;background:#fafafa;text-align:right;direction:rtl;">
            <h2 style="margin:0 0 10px;font-size:18px;">فاکتور خرید شما</h2>

            <p style="margin:0 0 14px;line-height:1.8;">
                برای مشاهده و دانلود فاکتور سفارش خود روی دکمه زیر کلیک کنید.
            </p>

            <a href="<?php echo esc_url($invoice_url); ?>"
               style="display:inline-block;padding:10px 18px;background:#9f8561;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;">
                مشاهده فاکتور خرید
            </a>
        </div>

        <?php
    }
}
// End Email Invoice to Customer ----------------------------------------------------------->



// Start Dashboard AJAX Handlers ---------------------------------------------------------------
/**
 * Update User Profile - AJAX Handler
 */
add_action('wp_ajax_update_user_profile', 'hypersanati_update_user_profile');
function hypersanati_update_user_profile() {
    check_ajax_referer('dashboard_ajax', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'شما وارد نشده‌اید'));
    }

    $user_id = get_current_user_id();
    $customer = new WC_Customer($user_id);

    // Validate required fields
    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    $billing_phone = isset($_POST['billing_phone']) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';

    if (empty($first_name) || empty($last_name)) {
        wp_send_json_error(array('message' => 'نام و نام خانوادگی الزامی است'));
    }

    // Update user data
    wp_update_user(array(
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
    ));

    // Update billing information
    if (isset($_POST['billing_email'])) {
        $customer->set_billing_email(sanitize_email(wp_unslash($_POST['billing_email'])));
    }

    if (!empty($billing_phone)) {
        $customer->set_billing_phone($billing_phone);
    }

    if (isset($_POST['billing_company'])) {
        $customer->set_billing_company(sanitize_text_field(wp_unslash($_POST['billing_company'])));
    }

    if (isset($_POST['billing_national_code'])) {
        update_user_meta($user_id, '_billing_national_code', sanitize_text_field(wp_unslash($_POST['billing_national_code'])));
    }

    $customer->save();

    wp_send_json_success(array(
        'message' => 'اطلاعات با موفقیت به‌روزرسانی شد',
        'user' => array(
            'first_name' => $first_name,
            'last_name' => $last_name,
        )
    ));
}

/**
 * Change User Password - AJAX Handler
 */
add_action('wp_ajax_change_user_password', 'hypersanati_change_user_password');
function hypersanati_change_user_password() {
    check_ajax_referer('dashboard_ajax', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'شما وارد نشده‌اید'));
    }

    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);

    // Validate current password
    $current_password = isset($_POST['current_password']) ? wp_unslash($_POST['current_password']) : '';
    $new_password = isset($_POST['new_password']) ? wp_unslash($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? wp_unslash($_POST['confirm_password']) : '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        wp_send_json_error(array('message' => 'تمام فیلدها الزامی است'));
    }

    // Check if current password is correct
    if (!wp_check_password($current_password, $user->user_pass, $user_id)) {
        wp_send_json_error(array('message' => 'رمز عبور فعلی صحیح نیست'));
    }

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        wp_send_json_error(array('message' => 'رمز عبور جدید و تکرار آن یکسان نیستند'));
    }

    // Validate password strength
    if (strlen($new_password) < 6) {
        wp_send_json_error(array('message' => 'رمز عبور باید حداقل ۶ کاراکتر باشد'));
    }

    // Update password
    wp_set_password($new_password, $user_id);

    // Re-authenticate user
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    wp_send_json_success(array(
        'message' => 'رمز عبور با موفقیت تغییر یافت'
    ));
}

/**
 * Handle Profile Form Submission (Non-AJAX fallback)
 */
add_action('template_redirect', 'hypersanati_handle_profile_update');
function hypersanati_handle_profile_update() {
    if (!isset($_POST['update_profile']) || !isset($_POST['profile_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['profile_nonce'])), 'update_user_profile')) {
        return;
    }

    if (!is_user_logged_in()) {
        return;
    }

    $user_id = get_current_user_id();
    $customer = new WC_Customer($user_id);

    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';

    if (empty($first_name) || empty($last_name)) {
        wc_add_notice('نام و نام خانوادگی الزامی است', 'error');
        return;
    }

    wp_update_user(array(
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
    ));

    if (isset($_POST['billing_email'])) {
        $customer->set_billing_email(sanitize_email(wp_unslash($_POST['billing_email'])));
    }

    if (isset($_POST['billing_phone'])) {
        $customer->set_billing_phone(sanitize_text_field(wp_unslash($_POST['billing_phone'])));
    }

    if (isset($_POST['billing_company'])) {
        $customer->set_billing_company(sanitize_text_field(wp_unslash($_POST['billing_company'])));
    }

    if (isset($_POST['billing_national_code'])) {
        update_user_meta($user_id, '_billing_national_code', sanitize_text_field(wp_unslash($_POST['billing_national_code'])));
    }

    $customer->save();

    wc_add_notice('اطلاعات با موفقیت به‌روزرسانی شد', 'success');
    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;
}

/**
 * Handle Password Change (Non-AJAX fallback)
 */
add_action('template_redirect', 'hypersanati_handle_password_change');
function hypersanati_handle_password_change() {
    if (!isset($_POST['change_password']) || !isset($_POST['password_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['password_nonce'])), 'change_user_password')) {
        return;
    }

    if (!is_user_logged_in()) {
        return;
    }

    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);

    $current_password = isset($_POST['current_password']) ? wp_unslash($_POST['current_password']) : '';
    $new_password = isset($_POST['new_password']) ? wp_unslash($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? wp_unslash($_POST['confirm_password']) : '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        wc_add_notice('تمام فیلدها الزامی است', 'error');
        return;
    }

    if (!wp_check_password($current_password, $user->user_pass, $user_id)) {
        wc_add_notice('رمز عبور فعلی صحیح نیست', 'error');
        return;
    }

    if ($new_password !== $confirm_password) {
        wc_add_notice('رمز عبور جدید و تکرار آن یکسان نیستند', 'error');
        return;
    }

    if (strlen($new_password) < 6) {
        wc_add_notice('رمز عبور باید حداقل ۶ کاراکتر باشد', 'error');
        return;
    }

    wp_set_password($new_password, $user_id);

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    wc_add_notice('رمز عبور با موفقیت تغییر یافت', 'success');
    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;
}
// End Dashboard AJAX Handlers -----------------------------------------------------------------

/* ===============================================================
   TICKET SYSTEM HANDLERS
   سیستم تیکتینگ پشتیبانی
=============================================================== */

/**
 * Create New Ticket Handler
 */
add_action('template_redirect', 'hypersanati_handle_create_ticket');
function hypersanati_handle_create_ticket() {
    if (!isset($_POST['create_ticket']) || !isset($_POST['ticket_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ticket_nonce'])), 'create_support_ticket')) {
        wc_add_notice('درخواست نامعتبر است', 'error');
        return;
    }

    if (!is_user_logged_in()) {
        wc_add_notice('برای ایجاد تیکت باید وارد شوید', 'error');
        return;
    }

    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);

    // Sanitize and validate input
    $category = isset($_POST['ticket_category']) ? sanitize_text_field(wp_unslash($_POST['ticket_category'])) : '';
    $subject = isset($_POST['ticket_subject']) ? sanitize_text_field(wp_unslash($_POST['ticket_subject'])) : '';
    $message = isset($_POST['ticket_message']) ? sanitize_textarea_field(wp_unslash($_POST['ticket_message'])) : '';
    $order_id = isset($_POST['ticket_order_id']) ? sanitize_text_field(wp_unslash($_POST['ticket_order_id'])) : '';

    if (empty($category) || empty($subject) || empty($message)) {
        wc_add_notice('لطفاً تمام فیلدهای الزامی را پر کنید', 'error');
        return;
    }

    // Create ticket as comment
    $ticket_id = wp_insert_comment(array(
        'comment_post_ID' => 0,
        'comment_author' => $user->display_name,
        'comment_author_email' => $user->user_email,
        'comment_content' => $message,
        'comment_type' => 'support_ticket',
        'comment_parent' => 0,
        'comment_approved' => '1',
        'user_id' => $user_id,
    ));

    if (!$ticket_id) {
        wc_add_notice('خطا در ایجاد تیکت. لطفاً دوباره تلاش کنید', 'error');
        return;
    }

    // Save ticket metadata
    add_comment_meta($ticket_id, 'ticket_status', 'open', true);
    add_comment_meta($ticket_id, 'ticket_category', $category, true);
    add_comment_meta($ticket_id, 'ticket_subject', $subject, true);

    if (!empty($order_id)) {
        add_comment_meta($ticket_id, 'ticket_order_id', $order_id, true);
    }

    // Send email notification to admin
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    $email_subject = 'تیکت جدید پشتیبانی - ' . $subject;
    $email_message = "تیکت جدیدی در سایت {$site_name} ثبت شده است:\n\n";
    $email_message .= "کاربر: {$user->display_name} ({$user->user_email})\n";
    $email_message .= "موضوع: {$subject}\n";
    $email_message .= "دسته‌بندی: {$category}\n";

    if (!empty($order_id)) {
        $email_message .= "شماره سفارش: {$order_id}\n";
    }

    $email_message .= "\nمتن تیکت:\n{$message}\n";

    wp_mail($admin_email, $email_subject, $email_message);

    wc_add_notice('تیکت شما با موفقیت ثبت شد', 'success');
    wp_safe_redirect(add_query_arg('tab', 'support', wc_get_page_permalink('myaccount')));
    exit;
}

/**
 * Reply to Ticket Handler
 */
add_action('template_redirect', 'hypersanati_handle_reply_ticket');
function hypersanati_handle_reply_ticket() {
    if (!isset($_POST['reply_ticket']) || !isset($_POST['ticket_id'])) {
        return;
    }

    $ticket_id = absint($_POST['ticket_id']);

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['reply_nonce'])), 'reply_ticket_' . $ticket_id)) {
        wc_add_notice('درخواست نامعتبر است', 'error');
        return;
    }

    if (!is_user_logged_in()) {
        wc_add_notice('برای پاسخ به تیکت باید وارد شوید', 'error');
        return;
    }

    $user_id = get_current_user_id();
    $user = get_user_by('id', $user_id);

    // Verify ticket belongs to user
    $ticket = get_comment($ticket_id);

    if (!$ticket || (int)$ticket->user_id !== $user_id) {
        wc_add_notice('دسترسی غیرمجاز', 'error');
        return;
    }

    $reply_message = isset($_POST['reply_message']) ? sanitize_textarea_field(wp_unslash($_POST['reply_message'])) : '';

    if (empty($reply_message)) {
        wc_add_notice('لطفاً متن پاسخ را وارد کنید', 'error');
        return;
    }

    // Create reply
    $reply_id = wp_insert_comment(array(
        'comment_post_ID' => 0,
        'comment_author' => $user->display_name,
        'comment_author_email' => $user->user_email,
        'comment_content' => $reply_message,
        'comment_type' => 'ticket_reply',
        'comment_parent' => $ticket_id,
        'comment_approved' => '1',
        'user_id' => $user_id,
    ));

    if (!$reply_id) {
        wc_add_notice('خطا در ارسال پاسخ', 'error');
        return;
    }

    // Update ticket status to open (user replied)
    update_comment_meta($ticket_id, 'ticket_status', 'open');
    add_comment_meta($reply_id, 'is_support_reply', '0', true);

    // Send email to admin
    $admin_email = get_option('admin_email');
    $ticket_subject = get_comment_meta($ticket_id, 'ticket_subject', true);

    $email_subject = 'پاسخ جدید به تیکت #' . $ticket_id;
    $email_message = "کاربر {$user->display_name} به تیکت پاسخ داده است:\n\n";
    $email_message .= "موضوع: {$ticket_subject}\n";
    $email_message .= "شماره تیکت: #{$ticket_id}\n\n";
    $email_message .= "پاسخ کاربر:\n{$reply_message}\n";

    wp_mail($admin_email, $email_subject, $email_message);

    wc_add_notice('پاسخ شما ارسال شد', 'success');
    wp_safe_redirect(add_query_arg('tab', 'support', wc_get_page_permalink('myaccount')));
    exit;
}

/**
 * Close Ticket Handler
 */
add_action('template_redirect', 'hypersanati_handle_close_ticket');
function hypersanati_handle_close_ticket() {
    if (!isset($_POST['close_ticket']) || !isset($_POST['ticket_id'])) {
        return;
    }

    $ticket_id = absint($_POST['ticket_id']);

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['close_nonce'])), 'close_ticket_' . $ticket_id)) {
        wc_add_notice('درخواست نامعتبر است', 'error');
        return;
    }

    if (!is_user_logged_in()) {
        wc_add_notice('برای بستن تیکت باید وارد شوید', 'error');
        return;
    }

    $user_id = get_current_user_id();

    // Verify ticket belongs to user
    $ticket = get_comment($ticket_id);

    if (!$ticket || (int)$ticket->user_id !== $user_id) {
        wc_add_notice('دسترسی غیرمجاز', 'error');
        return;
    }

    // Update ticket status
    update_comment_meta($ticket_id, 'ticket_status', 'closed');

    wc_add_notice('تیکت با موفقیت بسته شد', 'success');
    wp_safe_redirect(add_query_arg('tab', 'support', wc_get_page_permalink('myaccount')));
    exit;
}

/**
 * Admin Reply to Ticket (for support staff)
 */
add_action('wp_ajax_admin_reply_ticket', 'hypersanati_admin_reply_ticket');
function hypersanati_admin_reply_ticket() {
    check_ajax_referer('dashboard_ajax', 'nonce');

    if (!current_user_can('manage_woocommerce') && !current_user_can('edit_others_posts')) {
        wp_send_json_error(array('message' => 'دسترسی غیرمجاز'));
    }

    $ticket_id = isset($_POST['ticket_id']) ? absint($_POST['ticket_id']) : 0;
    $reply_message = isset($_POST['reply_message']) ? sanitize_textarea_field(wp_unslash($_POST['reply_message'])) : '';

    if (!$ticket_id || empty($reply_message)) {
        wp_send_json_error(array('message' => 'اطلاعات ناقص است'));
    }

    $ticket = get_comment($ticket_id);

    if (!$ticket || $ticket->comment_type !== 'support_ticket') {
        wp_send_json_error(array('message' => 'تیکت معتبر نیست'));
    }

    $current_user = wp_get_current_user();

    // Create support reply
    $reply_id = wp_insert_comment(array(
        'comment_post_ID' => 0,
        'comment_author' => $current_user->display_name,
        'comment_author_email' => $current_user->user_email,
        'comment_content' => $reply_message,
        'comment_type' => 'ticket_reply',
        'comment_parent' => $ticket_id,
        'comment_approved' => '1',
        'user_id' => get_current_user_id(),
    ));

    if (!$reply_id) {
        wp_send_json_error(array('message' => 'خطا در ارسال پاسخ'));
    }

    // Mark as support reply
    add_comment_meta($reply_id, 'is_support_reply', '1', true);

    // Update ticket status
    update_comment_meta($ticket_id, 'ticket_status', 'answered');

    // Send email to customer
    $customer = get_user_by('id', $ticket->user_id);

    if ($customer) {
        $ticket_subject = get_comment_meta($ticket_id, 'ticket_subject', true);

        $email_subject = 'پاسخ تیکت پشتیبانی #' . $ticket_id;
        $email_message = "سلام {$customer->display_name}\n\n";
        $email_message .= "تیکت شما پاسخ داده شده است:\n\n";
        $email_message .= "موضوع: {$ticket_subject}\n";
        $email_message .= "شماره تیکت: #{$ticket_id}\n\n";
        $email_message .= "پاسخ پشتیبانی:\n{$reply_message}\n\n";
        $email_message .= "برای مشاهده و ادامه گفتگو به داشبورد خود مراجعه کنید.\n";

        wp_mail($customer->user_email, $email_subject, $email_message);
    }

    wp_send_json_success(array('message' => 'پاسخ با موفقیت ارسال شد'));
}




/* ============================================================
 * HSB PRODUCT COUNTRY FLAGS
 * ============================================================ */

add_action('init', function () {
    register_taxonomy(
        'product_country',
        array('product'),
        array(
            'labels' => array(
                'name'          => 'کشورهای سازنده',
                'singular_name' => 'کشور سازنده',
                'menu_name'     => 'کشورهای سازنده',
                'add_new_item'  => 'افزودن کشور سازنده',
                'edit_item'     => 'ویرایش کشور سازنده',
            ),
            'public'            => false,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => false,
            'hierarchical'      => false,
            'rewrite'           => false,
            'query_var'         => false,
        )
    );
}, 20);


function hsb_country_flag_add_field() {
    ?>
    <div class="form-field">
        <label>پرچم کشور</label>

        <input
            type="hidden"
            name="country_flag_image_id"
            class="hsb-country-flag-id"
            value="">

        <div class="hsb-country-flag-preview"></div>

        <button
            type="button"
            class="button hsb-country-flag-upload">
            انتخاب پرچم
        </button>

        <button
            type="button"
            class="button hsb-country-flag-remove">
            حذف پرچم
        </button>
    </div>
    <?php
}
add_action(
    'product_country_add_form_fields',
    'hsb_country_flag_add_field'
);


function hsb_country_flag_edit_field($term) {
    $image_id = absint(
        get_term_meta(
            $term->term_id,
            'country_flag_image_id',
            true
        )
    );

    $image_url = $image_id
        ? wp_get_attachment_image_url(
            $image_id,
            'thumbnail'
        )
        : '';
    ?>
    <tr class="form-field">
        <th scope="row">
            <label>پرچم کشور</label>
        </th>

        <td>
            <input
                type="hidden"
                name="country_flag_image_id"
                class="hsb-country-flag-id"
                value="<?php echo esc_attr($image_id); ?>">

            <div
                class="hsb-country-flag-preview"
                style="margin-bottom:10px;">
                <?php if ($image_url) : ?>
                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        style="width:80px;height:55px;object-fit:contain;border:1px solid #ddd;background:#fff;">
                <?php endif; ?>
            </div>

            <button
                type="button"
                class="button hsb-country-flag-upload">
                انتخاب پرچم
            </button>

            <button
                type="button"
                class="button hsb-country-flag-remove">
                حذف پرچم
            </button>
        </td>
    </tr>
    <?php
}
add_action(
    'product_country_edit_form_fields',
    'hsb_country_flag_edit_field'
);


function hsb_save_country_flag($term_id) {
    if (!isset($_POST['country_flag_image_id'])) {
        return;
    }

    update_term_meta(
        $term_id,
        'country_flag_image_id',
        absint($_POST['country_flag_image_id'])
    );
}

add_action(
    'created_product_country',
    'hsb_save_country_flag'
);

add_action(
    'edited_product_country',
    'hsb_save_country_flag'
);


add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();

    if (
        !$screen ||
        'edit-product_country' !== $screen->id
    ) {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script(
        'jquery-core',
        <<<'JS'
jQuery(function ($) {

    $(document).on(
        'click',
        '.hsb-country-flag-upload',
        function (event) {
            event.preventDefault();

            const button = $(this);
            const wrapper = button.closest(
                '.form-field, td'
            );

            const frame = wp.media({
                title: 'انتخاب پرچم کشور',
                button: {
                    text: 'استفاده از این تصویر'
                },
                multiple: false
            });

            frame.on('select', function () {
                const attachment =
                    frame.state()
                        .get('selection')
                        .first()
                        .toJSON();

                wrapper
                    .find('.hsb-country-flag-id')
                    .val(attachment.id);

                wrapper
                    .find('.hsb-country-flag-preview')
                    .html(
                        '<img src="' +
                        attachment.url +
                        '" style="width:80px;height:55px;object-fit:contain;border:1px solid #ddd;background:#fff;">'
                    );
            });

            frame.open();
        }
    );

    $(document).on(
        'click',
        '.hsb-country-flag-remove',
        function (event) {
            event.preventDefault();

            const wrapper = $(this).closest(
                '.form-field, td'
            );

            wrapper
                .find('.hsb-country-flag-id')
                .val('');

            wrapper
                .find('.hsb-country-flag-preview')
                .empty();
        }
    );
});
JS
    );
});


function hsb_get_country_flag_data($country_name) {
    $country_name = trim(
        (string) $country_name
    );

    if ('' === $country_name) {
        return array(
            'id'        => 0,
            'term_id'   => 0,
            'image_id'  => 0,
            'image_url' => '',
        );
    }

    $term = get_term_by(
        'name',
        $country_name,
        'product_country'
    );

    if (!$term || is_wp_error($term)) {
        $term = get_term_by(
            'slug',
            sanitize_title($country_name),
            'product_country'
        );
    }

    if (!$term || is_wp_error($term)) {
        return array(
            'id'        => 0,
            'term_id'   => 0,
            'image_id'  => 0,
            'image_url' => '',
        );
    }

    $image_id = absint(
        get_term_meta(
            $term->term_id,
            'country_flag_image_id',
            true
        )
    );

    return array(
        'id'        => (int) $term->term_id,
        'term_id'   => (int) $term->term_id,
        'image_id'  => $image_id,
        'image_url' => $image_id
            ? (string) wp_get_attachment_image_url(
                $image_id,
                'thumbnail'
            )
            : '',
    );
}


/*
 * Create country terms once from existing imported
 * _country_origin values. Products continue using the
 * existing meta field, so imports remain compatible.
 */
add_action('admin_init', function () {

    if (
        get_option(
            'hsb_country_terms_seeded_v1'
        )
    ) {
        return;
    }

    global $wpdb;

    $countries = $wpdb->get_col(
        $wpdb->prepare(
            "
            SELECT DISTINCT meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s
              AND meta_value <> ''
            ORDER BY meta_value ASC
            ",
            '_country_origin'
        )
    );

    foreach ((array) $countries as $country) {
        $country = trim((string) $country);

        if ('' === $country) {
            continue;
        }

        if (
            !term_exists(
                $country,
                'product_country'
            )
        ) {
            wp_insert_term(
                $country,
                'product_country'
            );
        }
    }

    update_option(
        'hsb_country_terms_seeded_v1',
        1,
        false
    );
});

/* ============================================================
 * HSB INDUSTRIAL PRODUCT RELATIONS
 * ============================================================ */

/**
 * Get the brand assigned to a WooCommerce product.
 */
function hsb_get_product_brand_data($product_id) {
    $terms = wp_get_post_terms(
        absint($product_id),
        'product_brand',
        array('number' => 1)
    );

    if (is_wp_error($terms) || empty($terms)) {
        return array(
            'id'        => 0,
            'name'      => '',
            'slug'      => '',
            'image_id'  => 0,
            'image_url' => '',
        );
    }

    $term = reset($terms);

    /*
     * WooCommerce brand image is stored in thumbnail_id.
     * Keep brand_image_id only as legacy fallback.
     */
    $brand_image_id = absint(
        get_term_meta(
            $term->term_id,
            'thumbnail_id',
            true
        )
    );

    if (!$brand_image_id) {
        $brand_image_id = absint(
            get_term_meta(
                $term->term_id,
                'brand_image_id',
                true
            )
        );
    }

    return array(
        'id'        => (int) $term->term_id,
        'name'      => (string) $term->name,
        'slug'      => (string) $term->slug,
        'image_id'  => $brand_image_id,
        'image_url' => $brand_image_id
            ? (string) wp_get_attachment_image_url(
                $brand_image_id,
                'thumbnail'
            )
            : '',
    );
}

/**
 * Products having exactly the same technical Part Number.
 *
 * Each SKU remains an independent WooCommerce product.
 * This only creates the navigation/relation layer between them.
 */
function hsb_get_same_part_number_products($product_id, $limit = 30) {
    $product_id = absint($product_id);
    $part_number = trim(
        (string) get_post_meta($product_id, '_mpn_part_number', true)
    );

    if ($product_id < 1 || $part_number === '') {
        return array();
    }

    $ids = get_posts(array(
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => absint($limit),
        'post__not_in'           => array($product_id),
        'fields'                 => 'ids',
        'orderby'                => 'ID',
        'order'                  => 'ASC',
        'no_found_rows'          => true,
        'update_post_meta_cache' => true,
        'update_post_term_cache' => true,
        'meta_query'             => array(
            array(
                'key'     => '_mpn_part_number',
                'value'   => $part_number,
                'compare' => '=',
            ),
        ),
    ));

    if (!is_array($ids)) {
        return array();
    }

    return array_values(array_map('absint', $ids));
}

/**
 * Build usable data for same-code product choices.
 */
function hsb_get_product_alternatives($product_id) {
    $product_id = absint($product_id);

    if ($product_id < 1) {
        return array();
    }

    $all_ids = array_merge(
        array($product_id),
        hsb_get_same_part_number_products($product_id)
    );

    $items = array();

    foreach (array_unique($all_ids) as $id) {
        $product = wc_get_product($id);

        if (!$product || 'publish' !== $product->get_status()) {
            continue;
        }

        $brand   = hsb_get_product_brand_data($id);
        $country = trim(
            (string) get_post_meta($id, '_country_origin', true)
        );

        $live_brand_data =
        function_exists('hsb_get_product_brand_data')
            ? hsb_get_product_brand_data($id)
            : array();

    $live_country_flag =
        function_exists('hsb_get_country_flag_data')
            ? hsb_get_country_flag_data($country)
            : array();

    $items[] = array(
            'product_id'  => $id,
            'name'        => $product->get_name(),
            'sku'         => $product->get_sku(),
            'part_number' => get_post_meta($id, '_mpn_part_number', true),
            'brand'       => $brand['name'],
            'brand_slug'  => $brand['slug'],
            'country'     => $country,
            'url'         => get_permalink($id),
            'current'     => $id === $product_id,
        );
    }

    usort($items, function ($a, $b) use ($product_id) {
        if ($a['product_id'] === $product_id) {
            return -1;
        }

        if ($b['product_id'] === $product_id) {
            return 1;
        }

        return strcasecmp(
            (string) $a['brand'],
            (string) $b['brand']
        );
    });

    return $items;
}

/**
 * Build lightweight front-end data for live switching between
 * real WooCommerce products sharing the same Part Number.
 *
 * Metadata is preloaded with the page. Images themselves are
 * fetched by the browser only when needed.
 */
function hsb_get_product_family_live_data($product_id) {
    $product_id = absint($product_id);

    if ($product_id < 1) {
        return array();
    }

    $alternatives = hsb_get_product_alternatives($product_id);

    if (empty($alternatives)) {
        return array();
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

    $items = array();

    foreach ($alternatives as $alternative) {
        $id = absint($alternative['product_id'] ?? 0);

        if (!$id) {
            continue;
        }

        $product = wc_get_product($id);

        if (
            !$product ||
            'publish' !== $product->get_status()
        ) {
            continue;
        }

        $part_number = trim(
            (string) get_post_meta(
                $id,
                '_mpn_part_number',
                true
            )
        );

        $country = trim(
            (string) get_post_meta(
                $id,
                '_country_origin',
                true
            )
        );

        $raw_specs = array(
            'شماره فنی / پارت نامبر' => $part_number,
            'قطر داخلی' => get_post_meta(
                $id,
                '_inner_diameter',
                true
            ),
            'قطر خارجی' => get_post_meta(
                $id,
                '_outer_diameter',
                true
            ),
            'عرض' => get_post_meta(
                $id,
                '_bearing_width',
                true
            ),
            'نوع آب‌بندی' => get_post_meta(
                $id,
                '_bearing_seal',
                true
            ),
            'لقی' => get_post_meta(
                $id,
                '_bearing_clearance',
                true
            ),
            'کلاس دقت' => get_post_meta(
                $id,
                '_bearing_precision',
                true
            ),
            'جنس' => get_post_meta(
                $id,
                '_bearing_material',
                true
            ),
            'قفسه' => get_post_meta(
                $id,
                '_bearing_cage',
                true
            ),
            'روانکاری' => get_post_meta(
                $id,
                '_bearing_lubrication',
                true
            ),
            'بار دینامیکی' => get_post_meta(
                $id,
                '_dynamic_load',
                true
            ),
            'بار استاتیکی' => get_post_meta(
                $id,
                '_static_load',
                true
            ),
            'حداکثر دور' => get_post_meta(
                $id,
                '_max_rpm',
                true
            ),
            'کشور سازنده' => $country,
            'کاربرد' => get_post_meta(
                $id,
                '_bearing_usage',
                true
            ),
            'صنعت' => get_post_meta(
                $id,
                '_bearing_industry',
                true
            ),
            'کدهای معادل' => get_post_meta(
                $id,
                '_equivalent_codes',
                true
            ),
        );

        $full_specs = array();

        foreach ($raw_specs as $label => $value) {
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

            $full_specs[] = array(
                'label' => $label,
                'value' => $value,
            );
        }

        $key_specs = array(
            array(
                'label'  => 'قطر داخلی',
                'value'  => $format_mm(
                    get_post_meta(
                        $id,
                        '_inner_diameter',
                        true
                    )
                ),
                'icon'   => 'fa-circle-dot',
                'detail' => false,
            ),
            array(
                'label'  => 'قطر خارجی',
                'value'  => $format_mm(
                    get_post_meta(
                        $id,
                        '_outer_diameter',
                        true
                    )
                ),
                'icon'   => 'fa-circle',
                'detail' => false,
            ),
            array(
                'label'  => 'عرض',
                'value'  => $format_mm(
                    get_post_meta(
                        $id,
                        '_bearing_width',
                        true
                    )
                ),
                'icon'   => 'fa-arrows-left-right',
                'detail' => false,
            ),
            array(
                'label'  => 'آب‌بندی',
                'value'  => trim(
                    (string) get_post_meta(
                        $id,
                        '_bearing_seal',
                        true
                    )
                ),
                'icon'   => 'fa-shield-halved',
                'detail' => true,
            ),
            array(
                'label'  => 'لقی',
                'value'  => trim(
                    (string) get_post_meta(
                        $id,
                        '_bearing_clearance',
                        true
                    )
                ),
                'icon'   => 'fa-up-right-and-down-left-from-center',
                'detail' => true,
            ),
            array(
                'label'  => 'کلاس دقت',
                'value'  => trim(
                    (string) get_post_meta(
                        $id,
                        '_bearing_precision',
                        true
                    )
                ),
                'icon'   => 'fa-crosshairs',
                'detail' => true,
            ),
        );

        $key_specs = array_values(
            array_filter(
                $key_specs,
                static function ($spec) {
                    return '' !== trim(
                        (string) ($spec['value'] ?? '')
                    );
                }
            )
        );

        $image_id = $product->get_image_id();

        $gallery_ids = array_values(
            array_unique(
                array_filter(
                    array_merge(
                        $image_id
                            ? array($image_id)
                            : array(),
                        $product->get_gallery_image_ids()
                    )
                )
            )
        );

        $images = array();

        foreach ($gallery_ids as $attachment_id) {
            $full = wp_get_attachment_image_url(
                $attachment_id,
                'large'
            );

            $thumb = wp_get_attachment_image_url(
                $attachment_id,
                'thumbnail'
            );

            if (!$full) {
                continue;
            }

            $alt = trim(
                (string) get_post_meta(
                    $attachment_id,
                    '_wp_attachment_image_alt',
                    true
                )
            );

            if ('' === $alt) {
                $alt = $product->get_name();
            }

            $images[] = array(
                'full' => $full,
                'thumb' => $thumb ?: $full,
                'srcset' => (string)
                    wp_get_attachment_image_srcset(
                        $attachment_id,
                        'large'
                    ),
                'alt' => $alt,
            );
        }

        $has_real_image = !empty($images);

        if (!$has_real_image) {
            $placeholder = wc_placeholder_img_src();

            $images[] = array(
                'full'   => $placeholder,
                'thumb'  => $placeholder,
                'srcset' => '',
                'alt'    => $product->get_name(),
            );
        }

        $required_ids = function_exists(
            'hsb_get_required_product_ids'
        )
            ? hsb_get_required_product_ids($id)
            : array();

        $live_brand_data =
            function_exists('hsb_get_product_brand_data')
                ? hsb_get_product_brand_data($id)
                : array();

        $live_country_flag =
            function_exists('hsb_get_country_flag_data')
                ? hsb_get_country_flag_data($country)
                : array();

        $items[] = array(
            'product_id' => $id,
            'name'       => $product->get_name(),
            'sku'        => (string) $product->get_sku(),
            'part_number'=> $part_number,
            'brand'      => (string) (
                $alternative['brand'] ?? ''
            ),
            'brand_image_url' => (string) (
                $live_brand_data['image_url'] ?? ''
            ),
            'brand_slug' => (string) (
                $alternative['brand_slug'] ?? ''
            ),
            'country'    => $country,
            'country_flag_url' => (string) (
                $live_country_flag['image_url'] ?? ''
            ),
            'url'        => (string) get_permalink($id),

            'rating' => (float)
                $product->get_average_rating(),

            'rating_count' => (int)
                $product->get_rating_count(),

            'key_specs'  => $key_specs,
            'full_specs' => $full_specs,

            'images' => $images,

            'has_real_image' => $has_real_image,

            'required_count' => count($required_ids),
        );
    }

    return $items;
}


/**
 * Return every published WooCommerce product belonging
 * to the same technical Part Number family.
 */
function hsb_get_product_family_ids($product_id) {
    $product_id = absint($product_id);

    if ($product_id < 1) {
        return array();
    }

    $items = hsb_get_product_alternatives($product_id);
    $ids   = array();

    foreach ($items as $item) {
        if (!empty($item['product_id'])) {
            $ids[] = absint($item['product_id']);
        }
    }

    return array_values(
        array_unique(
            array_filter($ids)
        )
    );
}


/**
 * Group a list of WooCommerce product IDs into storefront
 * families based on technical Part Number.
 *
 * Each SKU remains a real independent product. This helper
 * only controls how products are presented in catalog lists.
 */
function hsb_group_product_ids_by_part_number($ids) {
    $groups = array();

    foreach ((array) $ids as $id) {
        $id = absint($id);

        if (!$id) {
            continue;
        }

        $product = wc_get_product($id);

        if (
            !$product ||
            'publish' !== $product->get_status()
        ) {
            continue;
        }

        $part_number = trim(
            (string) get_post_meta(
                $id,
                '_mpn_part_number',
                true
            )
        );

        if ('' !== $part_number) {
            $normalized_part = mb_strtoupper(
                preg_replace(
                    '/\s+/u',
                    '',
                    $part_number
                ),
                'UTF-8'
            );

            $family_key =
                'part:' . $normalized_part;
        } else {
            /*
             * Products without Part Number must never be
             * collapsed together.
             */
            $family_key =
                'product:' . $id;
        }

        if (!isset($groups[$family_key])) {
            $groups[$family_key] = array(
                'family_key'        => $family_key,
                'representative_id' => $id,
                'part_number'       => $part_number,
                'ids'               => array(),
                'brands'            => array(),
                'countries'         => array(),
            );
        }

        $groups[$family_key]['ids'][] = $id;

        $brand = function_exists(
            'hsb_get_product_brand_data'
        )
            ? hsb_get_product_brand_data($id)
            : array();

        $brand_name = trim(
            (string) (
                $brand['name'] ?? ''
            )
        );

        if ('' !== $brand_name) {
            $groups[$family_key]['brands'][
                mb_strtolower(
                    $brand_name,
                    'UTF-8'
                )
            ] = $brand_name;
        }

        $country = trim(
            (string) get_post_meta(
                $id,
                '_country_origin',
                true
            )
        );

        if ('' !== $country) {
            $groups[$family_key]['countries'][
                mb_strtolower(
                    $country,
                    'UTF-8'
                )
            ] = $country;
        }
    }

    $result = array();

    foreach ($groups as $group) {
        $group['ids'] = array_values(
            array_unique(
                array_map(
                    'absint',
                    $group['ids']
                )
            )
        );

        $group['brands'] =
            array_values(
                $group['brands']
            );

        $group['countries'] =
            array_values(
                $group['countries']
            );

        $group['version_count'] =
            count($group['ids']);

        $group['brand_count'] =
            count($group['brands']);

        $group['country_count'] =
            count($group['countries']);

        $result[] = $group;
    }

    return $result;
}


/**
 * Keep only one representative product from each
 * technical Part Number family.
 *
 * The individual WooCommerce products remain untouched.
 * This helper is only for storefront/list presentation.
 */
function hsb_dedupe_product_ids_by_part_number($ids, $limit = 12) {
    $clean = array();
    $seen  = array();
    $limit = max(1, absint($limit));

    foreach ((array) $ids as $id) {
        $id = absint($id);

        if (!$id) {
            continue;
        }

        $product = wc_get_product($id);

        if (
            !$product ||
            'publish' !== $product->get_status()
        ) {
            continue;
        }

        $part_number = trim(
            (string) get_post_meta(
                $id,
                '_mpn_part_number',
                true
            )
        );

        if ('' !== $part_number) {
            $family_key = 'part:' . mb_strtolower(
                preg_replace('/\s+/u', '', $part_number),
                'UTF-8'
            );
        } else {
            $family_key = 'product:' . $id;
        }

        if (isset($seen[$family_key])) {
            continue;
        }

        $seen[$family_key] = true;
        $clean[] = $id;

        if (count($clean) >= $limit) {
            break;
        }
    }

    return $clean;
}


/**
 * Required companion products.
 */
function hsb_get_required_product_ids($product_id) {
    $ids = get_post_meta(
        absint($product_id),
        '_hsb_required_product_ids',
        true
    );

    if (!is_array($ids)) {
        $ids = array_filter(
            array_map(
                'absint',
                preg_split(
                    '/[\s,|]+/',
                    (string) $ids
                )
            )
        );
    }

    $ids = array_values(
        array_unique(
            array_filter(
                array_map('absint', $ids)
            )
        )
    );

    return array_values(
        array_filter($ids, function ($id) use ($product_id) {
            if ($id === absint($product_id)) {
                return false;
            }

            $product = wc_get_product($id);

            return $product &&
                   'publish' === $product->get_status();
        })
    );
}


/* ------------------------------------------------------------
 * Required-products field in WooCommerce product admin.
 * ------------------------------------------------------------ */

add_action(
    'woocommerce_product_options_related',
    'hsb_required_products_admin_field'
);

function hsb_required_products_admin_field() {
    global $post;

    if (!$post) {
        return;
    }

    $selected = hsb_get_required_product_ids($post->ID);
    ?>
    <p class="form-field hsb_required_product_ids_field">
        <label for="_hsb_required_product_ids">
            <?php esc_html_e('محصولات همراه اجباری', 'hypersanati'); ?>
        </label>

        <select
            class="wc-product-search"
            multiple="multiple"
            style="width: 50%;"
            id="_hsb_required_product_ids"
            name="_hsb_required_product_ids[]"
            data-placeholder="<?php esc_attr_e('جستجوی محصول...', 'hypersanati'); ?>"
            data-action="woocommerce_json_search_products_and_variations">

            <?php foreach ($selected as $selected_id) : ?>
                <?php $selected_product = wc_get_product($selected_id); ?>

                <?php if ($selected_product) : ?>
                    <option
                        value="<?php echo esc_attr($selected_id); ?>"
                        selected="selected">
                        <?php
                        echo esc_html(
                            wp_strip_all_tags(
                                $selected_product->get_formatted_name()
                            )
                        );
                        ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <?php
        echo wc_help_tip(
            'محصولاتی که باید هنگام درخواست پیش‌فاکتور این کالا به‌صورت خودکار همراه آن اضافه شوند.'
        );
        ?>
    </p>
    <?php
}


add_action(
    'woocommerce_process_product_meta',
    'hsb_save_required_products',
    30
);

function hsb_save_required_products($product_id) {
    $ids = isset($_POST['_hsb_required_product_ids'])
        ? (array) wp_unslash($_POST['_hsb_required_product_ids'])
        : array();

    $ids = array_values(
        array_unique(
            array_filter(
                array_map('absint', $ids)
            )
        )
    );

    $ids = array_values(
        array_filter($ids, function ($id) use ($product_id) {
            return $id !== absint($product_id);
        })
    );

    if (empty($ids)) {
        delete_post_meta(
            $product_id,
            '_hsb_required_product_ids'
        );
        return;
    }

    update_post_meta(
        $product_id,
        '_hsb_required_product_ids',
        $ids
    );
}


/**
 * Add one item into the current preinvoice session array.
 */
function hsb_preinvoice_put_item(&$items, $product_id, $quantity, $context = array()) {
    $product_id = absint($product_id);
    $quantity   = max(1, absint($quantity));

    $product = wc_get_product($product_id);

    if (!$product || 'publish' !== $product->get_status()) {
        return false;
    }

    $item_key = (string) $product_id;

    if (isset($items[$item_key])) {
        $items[$item_key]['quantity'] += $quantity;
    } else {
        $items[$item_key] = array(
            'product_id' => $product_id,
            'quantity'   => $quantity,
            'added_at'   => time(),
        );
    }

    if (!empty($context)) {
        $items[$item_key]['relation'] = $context;
    }

    return true;
}



/* ============================================================
 * HSB PERSISTENT PREINVOICE ORDERS
 * ============================================================ */

/**
 * Register permanent preinvoice order statuses.
 */
add_action('init', 'hsb_register_preinvoice_order_statuses');

function hsb_register_preinvoice_order_statuses() {

    register_post_status('wc-preinvoice-review', array(
        'label'                     => 'درخواست پیش‌فاکتور',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop(
            'درخواست پیش‌فاکتور <span class="count">(%s)</span>',
            'درخواست‌های پیش‌فاکتور <span class="count">(%s)</span>',
            'hypersanati'
        ),
    ));

    register_post_status('wc-preinv-approved', array(
        'label'                     => 'پیش‌فاکتور تأیید شده',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop(
            'پیش‌فاکتور تأیید شده <span class="count">(%s)</span>',
            'پیش‌فاکتورهای تأیید شده <span class="count">(%s)</span>',
            'hypersanati'
        ),
    ));
}


add_filter('wc_order_statuses', 'hsb_add_preinvoice_order_statuses');

function hsb_add_preinvoice_order_statuses($statuses) {

    $new_statuses = array();

    foreach ($statuses as $key => $label) {

        $new_statuses[$key] = $label;

        if ('wc-pending' === $key) {
            $new_statuses['wc-preinvoice-review'] =
                'درخواست پیش‌فاکتور';

            $new_statuses['wc-preinv-approved'] =
                'پیش‌فاکتور تأیید شده';
        }
    }

    return $new_statuses;
}


/**
 * Convert current preinvoice session into permanent WooCommerce order.
 */
function hsb_create_preinvoice_order_from_session() {

    if (!function_exists('WC')) {
        return new WP_Error(
            'woocommerce_missing',
            'ووکامرس در دسترس نیست.'
        );
    }

    if (!is_user_logged_in()) {
        return new WP_Error(
            'login_required',
            'برای ارسال پیش‌فاکتور ابتدا وارد حساب کاربری شوید.'
        );
    }

    $items = hsb_get_preinvoice_items();

    if (empty($items)) {
        return new WP_Error(
            'empty_preinvoice',
            'پیش‌فاکتور شما خالی است.'
        );
    }

    $customer_id = get_current_user_id();

    try {

        $order = wc_create_order(array(
            'customer_id' => $customer_id,
            'created_via' => 'hsb-preinvoice',
        ));

        if (is_wp_error($order)) {
            return $order;
        }

        $customer = new WC_Customer($customer_id);

        if ($customer->get_billing_first_name()) {

            $order->set_address(array(
                'first_name' => $customer->get_billing_first_name(),
                'last_name'  => $customer->get_billing_last_name(),
                'company'    => $customer->get_billing_company(),
                'address_1'  => $customer->get_billing_address_1(),
                'address_2'  => $customer->get_billing_address_2(),
                'city'       => $customer->get_billing_city(),
                'state'      => $customer->get_billing_state(),
                'postcode'   => $customer->get_billing_postcode(),
                'country'    => $customer->get_billing_country(),
                'email'      => $customer->get_billing_email(),
                'phone'      => $customer->get_billing_phone(),
            ), 'billing');
        }

        foreach ($items as $item) {

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

            /*
             * Price is intentionally zero until sales team approves it.
             */
            $order_item_id = $order->add_product(
                $product,
                $quantity,
                array(
                    'subtotal' => 0,
                    'total'    => 0,
                )
            );

            if (!$order_item_id) {
                continue;
            }

            $order_item = $order->get_item($order_item_id);

            if (!$order_item) {
                continue;
            }

            $relation_type = isset(
                $item['relation']['type']
            )
                ? sanitize_key(
                    $item['relation']['type']
                )
                : 'primary';

            $order_item->add_meta_data(
                '_hsb_relation_type',
                $relation_type,
                true
            );

            if (
                isset(
                    $item['relation']['parent_product_id']
                )
            ) {
                $order_item->add_meta_data(
                    '_hsb_parent_product_id',
                    absint(
                        $item['relation']['parent_product_id']
                    ),
                    true
                );
            }

            $order_item->save();
        }

        if (0 === count($order->get_items())) {

            $order->delete(true);

            return new WP_Error(
                'no_valid_items',
                'هیچ کالای معتبری برای ثبت پیش‌فاکتور پیدا نشد.'
            );
        }

        $order->update_meta_data(
            '_hsb_is_preinvoice',
            'yes'
        );

        $order->update_meta_data(
            '_hsb_preinvoice_submitted_at',
            current_time('mysql')
        );

        $order->set_status('preinvoice-review');

        $order->add_order_note(
            'درخواست پیش‌فاکتور توسط مشتری ثبت شد و منتظر بررسی واحد فروش است.'
        );

        $order->calculate_totals(false);
        $order->save();

        /*
         * Current draft is finished.
         */
        if (WC()->session) {
            WC()->session->set(
                'hsb_preinvoice_items',
                array()
            );

            WC()->session->set(
                'hsb_last_preinvoice_order_id',
                $order->get_id()
            );
        }

        return $order;

    } catch (Throwable $e) {

        return new WP_Error(
            'preinvoice_create_failed',
            $e->getMessage()
        );
    }
}



/* ============================================================
 * HSB PREINVOICE SALES APPROVAL
 * ============================================================ */

/**
 * Allow sales staff to edit line-item prices while
 * preinvoice is under review.
 */
add_filter(
    'wc_order_is_editable',
    'hsb_preinvoice_order_is_editable',
    20,
    2
);

function hsb_preinvoice_order_is_editable($editable, $order) {

    if (!$order instanceof WC_Order) {
        return $editable;
    }

    if (
        'yes' === $order->get_meta('_hsb_is_preinvoice') &&
        'preinvoice-review' === $order->get_status()
    ) {
        return true;
    }

    return $editable;
}


/**
 * Add approval action to WooCommerce order actions.
 */
add_filter(
    'woocommerce_order_actions',
    'hsb_add_preinvoice_admin_actions',
    20,
    2
);

function hsb_add_preinvoice_admin_actions($actions, $order) {

    if (!$order instanceof WC_Order) {
        return $actions;
    }

    if (
        'yes' === $order->get_meta('_hsb_is_preinvoice') &&
        'preinvoice-review' === $order->get_status()
    ) {
        $actions['hsb_approve_preinvoice'] =
            'تأیید و آماده‌سازی پیش‌فاکتور برای پرداخت';
    }

    return $actions;
}


/**
 * Approve priced preinvoice.
 */
add_action(
    'woocommerce_order_action_hsb_approve_preinvoice',
    'hsb_approve_preinvoice'
);

function hsb_approve_preinvoice($order) {

    if (!$order instanceof WC_Order) {
        return;
    }

    if (
        'yes' !== $order->get_meta('_hsb_is_preinvoice') ||
        'preinvoice-review' !== $order->get_status()
    ) {
        return;
    }

    /*
     * Recalculate totals after sales staff edits
     * individual line-item prices.
     */
    $order->calculate_totals(false);

    if ((float) $order->get_total() <= 0) {

        $order->add_order_note(
            'تأیید پیش‌فاکتور انجام نشد: مبلغ نهایی هنوز صفر است.'
        );

        $order->save();

        return;
    }

    $order->update_meta_data(
        '_hsb_preinvoice_approved_at',
        current_time('mysql')
    );

    $order->update_meta_data(
        '_hsb_preinvoice_approved_by',
        get_current_user_id()
    );

    $order->set_status(
        'preinv-approved',
        'پیش‌فاکتور توسط واحد فروش قیمت‌گذاری و تأیید شد.'
    );

    $order->save();

    /*
     * SMS integration hook.
     * Actual SMS provider will be connected separately.
     */
    do_action(
        'hsb_preinvoice_approved_sms',
        $order->get_id(),
        $order
    );
}


/**
 * Allow approved preinvoice to be paid.
 */
add_filter(
    'woocommerce_valid_order_statuses_for_payment',
    'hsb_preinvoice_valid_payment_status',
    20,
    2
);

function hsb_preinvoice_valid_payment_status($statuses, $order) {

    if (
        $order instanceof WC_Order &&
        'yes' === $order->get_meta('_hsb_is_preinvoice')
    ) {
        $statuses[] = 'preinv-approved';
    }

    return array_values(array_unique($statuses));
}


/**
 * Allow payment without forcing status to pending first.
 */
add_filter(
    'woocommerce_order_needs_payment',
    'hsb_preinvoice_needs_payment',
    20,
    3
);

function hsb_preinvoice_needs_payment(
    $needs_payment,
    $order,
    $valid_order_statuses
) {

    if (
        $order instanceof WC_Order &&
        'yes' === $order->get_meta('_hsb_is_preinvoice') &&
        'preinv-approved' === $order->get_status() &&
        (float) $order->get_total() > 0
    ) {
        return true;
    }

    return $needs_payment;
}


/* ============================================================
 * HSB PREINVOICE ENGINE
 * Products may be added even without price or stock.
 * ============================================================ */

add_action('wp_ajax_hsb_preinvoice_add_item', 'hsb_preinvoice_add_item');
add_action('wp_ajax_nopriv_hsb_preinvoice_add_item', 'hsb_preinvoice_add_item');

function hsb_preinvoice_add_item() {

    check_ajax_referer('hsb_preinvoice_nonce', 'nonce');

    if (!function_exists('WC')) {
        wp_send_json_error(array(
            'message' => 'ووکامرس در دسترس نیست.'
        ));
    }

    if (null === WC()->session) {
        WC()->initialize_session();
    }

    if (null === WC()->session) {
        wp_send_json_error(array(
            'message' => 'امکان ایجاد پیش‌فاکتور وجود ندارد.'
        ));
    }

    $product_id = isset($_POST['product_id'])
        ? absint($_POST['product_id'])
        : 0;

    $quantity = isset($_POST['quantity'])
        ? absint($_POST['quantity'])
        : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    $product = wc_get_product($product_id);

    if (!$product || 'publish' !== $product->get_status()) {
        wp_send_json_error(array(
            'message' => 'محصول موردنظر پیدا نشد.'
        ));
    }

    /*
     * Important:
     * Do NOT check:
     * is_purchasable()
     * is_in_stock()
     * get_price()
     *
     * Preinvoice requests must work regardless of
     * current WooCommerce price and stock.
     */

    $items = WC()->session->get('hsb_preinvoice_items', array());

    if (!is_array($items)) {
        $items = array();
    }

    $item_key = (string) $product_id;

    /*
     * Add primary requested product.
     */
    hsb_preinvoice_put_item(
        $items,
        $product_id,
        $quantity,
        array(
            'type' => 'primary',
        )
    );

    /*
     * Automatically add required companion products.
     */
    $required_product_ids = hsb_get_required_product_ids($product_id);
    $required_added = array();

    foreach ($required_product_ids as $required_product_id) {

        $added = hsb_preinvoice_put_item(
            $items,
            $required_product_id,
            $quantity,
            array(
                'type'              => 'required',
                'parent_product_id' => $product_id,
            )
        );

        if (!$added) {
            continue;
        }

        $required_product = wc_get_product($required_product_id);

        $required_added[] = array(
            'product_id' => $required_product_id,
            'name'       => $required_product
                ? $required_product->get_name()
                : '',
            'quantity'   => $quantity,
        );
    }

    WC()->session->set('hsb_preinvoice_items', $items);

    $total_quantity = 0;

    foreach ($items as $item) {
        $total_quantity += isset($item['quantity'])
            ? absint($item['quantity'])
            : 0;
    }

    $response_message = empty($required_added)
        ? 'محصول به پیش‌فاکتور اضافه شد.'
        : 'محصول و اقلام همراه اجباری به پیش‌فاکتور اضافه شدند.';

    wp_send_json_success(array(
        'message'           => $response_message,
        'product_id'        => $product_id,
        'product_name'      => $product->get_name(),
        'quantity'          => $items[$item_key]['quantity'],
        'required_products' => $required_added,
        'items_count'       => count($items),
        'total_quantity'    => $total_quantity,
    ));
}


/**
 * Return current preinvoice items.
 */
function hsb_get_preinvoice_items() {

    if (
        !function_exists('WC') ||
        null === WC()->session
    ) {
        return array();
    }

    $items = WC()->session->get(
        'hsb_preinvoice_items',
        array()
    );

    return is_array($items)
        ? $items
        : array();
}
