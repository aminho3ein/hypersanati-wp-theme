<?php
if (!defined('ABSPATH')) exit;

/* =========================================================
   ENQUEUE STYLES
========================================================= */
function hypersanati_enqueue_all_css() {

    $base_url = get_template_directory_uri() . '/assets/css';
    $base_dir = get_template_directory() . '/assets/css';

    // Core styles
    wp_enqueue_style('bootstrap-rtl', $base_url . '/bootstrap.rtl.min.css', [], filemtime($base_dir . '/bootstrap.rtl.min.css'));
    wp_enqueue_style('fontawesome', $base_url . '/fontawesome.min.css', [], filemtime($base_dir . '/fontawesome.min.css'));
    wp_enqueue_style('fontawesome-solid', $base_url . '/solid.min.css', ['fontawesome'], filemtime($base_dir . '/solid.min.css'));
    wp_enqueue_style('fontawesome-brands', $base_url . '/brands.min.css', ['fontawesome'], filemtime($base_dir . '/brands.min.css'));
    wp_enqueue_style('site-fonts', $base_url . '/font.css', [], filemtime($base_dir . '/font.css'));

    // Main
    wp_enqueue_style('main-css', $base_url . '/main.css', ['bootstrap-rtl'], filemtime($base_dir . '/main.css'));

    // Responsive
    wp_enqueue_style('tablet-responsive', $base_url . '/responsive/tablet-responsive.css', ['main-css'], filemtime($base_dir . '/responsive/tablet-responsive.css'), '(min-width:768px)');
    wp_enqueue_style('laptop-responsive', $base_url . '/responsive/laptop-responsive.css', ['main-css'], filemtime($base_dir . '/responsive/laptop-responsive.css'), '(min-width:992px)');
    wp_enqueue_style('desktop-responsive', $base_url . '/responsive/desktop-responsive.css', ['main-css'], filemtime($base_dir . '/responsive/desktop-responsive.css'), '(min-width:1200px)');
}
add_action('wp_enqueue_scripts', 'hypersanati_enqueue_all_css');


/* =========================================================
   ENQUEUE SCRIPTS
========================================================= */
function hypersanati_enqueue_scripts() {

    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/functions.js',
        [],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'hypersanati_enqueue_scripts');


/* =========================================================
   CONDITIONAL ASSETS + SEARCH COMPACT
========================================================= */
function hypersanati_enqueue_assets() {

    // ۱. فراخوانی استایل اصلی پوسته
    wp_enqueue_style(
        'hypersanati-style',
        get_stylesheet_uri(),
        [],
        filemtime(get_template_directory() . '/assets/css/main.css')
    );

    // ۲. فراخوانی فایل main.js به صورت عمومی در فوتر (حل مشکل شما)
    if (file_exists(get_template_directory() . '/assets/js/main.js')) {
        wp_enqueue_script(
            'hypersanati-main',
            get_template_directory_uri() . '/assets/js/main.js',
            [], // اگر از جی‌کوئری استفاده نمی‌کنید خالی بگذارید، در غیر این صورت: ['jquery']
            filemtime(get_template_directory() . '/assets/js/main.js'),
            true // مقدار true باعث می‌شود اسکریپت در فوتر لود شود
        );
    }

    /* =========================================================
       404 PAGE
    ========================================================= */
    if (is_404()) {
        wp_enqueue_style(
            'hypersanati-404',
            get_template_directory_uri() . '/assets/css/404.css',
            ['hypersanati-style'],
            filemtime(get_template_directory() . '/assets/css/404.css')
        );

        wp_enqueue_script(
            'hypersanati-404',
            get_template_directory_uri() . '/assets/js/404.js',
            [],
            filemtime(get_template_directory() . '/assets/js/404.js'),
            true
        );
    }

    /* =========================================================
       ABOUT PAGE
    ========================================================= */
    if (is_page('about-us') || is_page_template('page-about-us.php')) {
        wp_enqueue_style(
            'hypersanati-about-us',
            get_template_directory_uri() . '/assets/css/about-us.css',
            ['hypersanati-style'],
            filemtime(get_template_directory() . '/assets/css/about-us.css')
        );

        wp_enqueue_script(
            'hypersanati-about-us',
            get_template_directory_uri() . '/assets/js/about-us.js',
            [],
            filemtime(get_template_directory() . '/assets/js/about-us.js'),
            true
        );
    }

    /* =========================================================
       CATEGORY PAGE
    ========================================================= */
    if (is_category() || is_archive()) {
        wp_enqueue_style(
            'hypersanati-category',
            get_template_directory_uri() . '/assets/css/article-category.css',
            ['hypersanati-style'],
            filemtime(get_template_directory() . '/assets/css/article-category.css')
        );

        wp_enqueue_script(
            'hypersanati-category',
            get_template_directory_uri() . '/assets/js/article-category.js',
            [],
            filemtime(get_template_directory() . '/assets/js/article-category.js'),
            true
        );
    }

    /* =========================================================
       SINGLE POST
    ========================================================= */
    if (is_single()) {
        wp_enqueue_style(
            'single-article-css',
            get_template_directory_uri() . '/assets/css/single-article.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/single-article.css')
        );

        wp_enqueue_script(
            'single-article-js',
            get_template_directory_uri() . '/assets/js/single-article.js',
            array('jquery'),
            filemtime(get_template_directory() . '/assets/js/single-article.js'),
            true
        );
    }
    /* =========================================================
       Shop Page
    ========================================================= */
    if (is_shop() || is_post_type_archive('product')) {
        wp_enqueue_style(
            'shop-css',
            get_template_directory_uri() . '/assets/css/shop.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/shop.css')
        );

        wp_enqueue_script(
            'shop-js',
            get_template_directory_uri() . '/assets/js/shop.js',
            array('jquery'),
            filemtime(get_template_directory() . '/assets/js/shop.js'),
            true
        );

        wp_localize_script('shop-js', 'hypersanatiSearch', [
            'shopUrl' => hypersanati_get_shop_url(),
        ]);
    }

    /* =========================================================
       Front Page (Dimension Search)
    ========================================================= */
    if (is_front_page() || is_home()) {
        wp_enqueue_script(
            'index-search-js',
            get_template_directory_uri() . '/assets/js/index-search.js',
            [],
            filemtime(get_template_directory() . '/assets/js/index-search.js'),
            true
        );

        wp_localize_script('index-search-js', 'hypersanatiSearch', [
            'shopUrl' => hypersanati_get_shop_url(),
        ]);
    }

    /* =========================================================
       Single Product
    ========================================================= */
    if (is_product()) {
        wp_enqueue_style(
            'single-product-css',
            get_template_directory_uri() . '/assets/css/single-product.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/single-product.css')
        );

        wp_enqueue_script(
            'single-product-js',
            get_template_directory_uri() . '/assets/js/single-product.js',
            array('jquery'),
            filemtime(get_template_directory() . '/assets/js/single-product.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hypersanati_enqueue_assets');

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

function hypersanati_render_product_card() {
    ?>
    <a href="<?php the_permalink(); ?>" class="product-section" style="text-decoration: none; color: inherit; display: block;">
        <div class="product-cat-frame">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="<?php the_title_attribute(); ?>" />
            <?php endif; ?>
        </div>
        <div class="product-name">
            <p><?php the_title(); ?></p>
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


// آژاکس بارگذاری محصولات و دسته‌ها (نسخه نهایی همراه با لینک محصول)
add_action('wp_ajax_load_shop_categories', 'load_shop_categories');
add_action('wp_ajax_nopriv_load_shop_categories', 'load_shop_categories');

function load_shop_categories() {
    $offset = isset($_GET['index']) ? intval($_GET['index']) : 0;
    $selected_cat_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
    
    // دریافت کلمه کلیدی جستجو از آژاکس
    $search_keyword = isset($_GET['search_keyword']) ? sanitize_text_field($_GET['search_keyword']) : '';

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

    ob_start();
    $has_product = false;
    ?>
    <div class="category" data-id="<?php echo esc_attr($cat->term_id); ?>">
        <div class="category-name">
            <h5><?php echo esc_html($cat->name); ?></h5>
        </div>
        <div class="sub-category">
            <?php foreach ($subcats as $sub) : ?>
                <?php
                // آرایه اصلی کوئری محصولات
                $product_args = [
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'tax_query'      => [
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $sub->term_id
                        ]
                    ]
                ];

                // اضافه کردن فیلتر جستجوی متنی در صورت وجود کلمه کلیدی
                if (!empty($search_keyword)) {
                    $product_args['s'] = $search_keyword;
                }

                $products = new WP_Query($product_args);
                ?>
                <?php if ($products->have_posts()) : $has_product = true; ?>
                    <div class="sub-category-name">
                        <p class="sub-cat-name"><?php echo esc_html($sub->name); ?></p>
                    </div>
                    <div class="child-category">
                        <?php while ($products->have_posts()) : $products->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="product-section" style="text-decoration: none; color: inherit; display: block;">
                                <div class="product-cat-frame">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <div class="product-name">
                                    <p><?php the_title(); ?></p>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php endif; wp_reset_postdata(); ?>
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
    $page = isset($_GET['index']) ? max(0, intval($_GET['index'])) : 0;
    $per_page = 12;

    $meta_query = hypersanati_build_dimension_meta_query($_GET);
    if (empty($meta_query)) {
        wp_die();
    }

    $products = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'offset'         => $page * $per_page,
        'meta_query'     => $meta_query,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);

    if (!$products->have_posts()) {
        wp_die();
    }

    ob_start();
    ?>
    <div class="category dimension-search-results">
        <div class="sub-category">
            <div class="child-category">
                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php hypersanati_render_product_card(); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
    echo ob_get_clean();
    wp_reset_postdata();
    wp_die();
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
    $dimension_fields = [
        '_inner_diameter',
        '_outer_diameter',
        '_bearing_width',
    ];

    foreach ($dimension_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }
}
// ==========================================
// Hypersanati WooCommerce & Account / OTP Module
// ==========================================


/* =============================================================
   Hypersanati OTP Login / Register Module
   محل فایل‌ها طبق ساختار پروژه:
   - assets/css/otp.css
   - assets/js/otp.js
   - woocommerce/myaccount/form-otp-login.php
   - woocommerce/myaccount/dashboard.php
   این ماژول کاملاً مستقل و درون functions.php است؛
   نیازی به inc/enqueue.php ندارد.
============================================================= */

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


/* -------------------------------------------------------------
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