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
                
                // اضافه کردن فیلترهای ابعادی
                $meta_query = [];
                
                if (!empty($inner_min)) {
                    $meta_query[] = [
                        'key' => '_inner_diameter',
                        'value' => floatval($inner_min),
                        'compare' => '>=',
                        'type' => 'DECIMAL',
                    ];
                }
                
                if (!empty($inner_max)) {
                    $meta_query[] = [
                        'key' => '_inner_diameter',
                        'value' => floatval($inner_max),
                        'compare' => '<=',
                        'type' => 'DECIMAL',
                    ];
                }
                
                if (!empty($outer_min)) {
                    $meta_query[] = [
                        'key' => '_outer_diameter',
                        'value' => floatval($outer_min),
                        'compare' => '>=',
                        'type' => 'DECIMAL',
                    ];
                }
                
                if (!empty($outer_max)) {
                    $meta_query[] = [
                        'key' => '_outer_diameter',
                        'value' => floatval($outer_max),
                        'compare' => '<=',
                        'type' => 'DECIMAL',
                    ];
                }
                
                if (!empty($height_min)) {
                    $meta_query[] = [
                        'key' => '_bearing_width',
                        'value' => floatval($height_min),
                        'compare' => '>=',
                        'type' => 'DECIMAL',
                    ];
                }
                
                if (!empty($height_max)) {
                    $meta_query[] = [
                        'key' => '_bearing_width',
                        'value' => floatval($height_max),
                        'compare' => '<=',
                        'type' => 'DECIMAL',
                    ];
                }
                
                if (!empty($meta_query)) {
                    $product_args['meta_query'] = $meta_query;
                    if (count($meta_query) > 1) {
                        $product_args['meta_query']['relation'] = 'AND';
                    }
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




// Start Maziyat Reghabati Singpe Product -----------------------------------