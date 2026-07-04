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
   CONDITIONAL ASSETS
========================================================= */
function hypersanati_enqueue_assets() {

    wp_enqueue_style(
        'hypersanati-style',
        get_stylesheet_uri(),
        [],
        filemtime(get_template_directory() . '/assets/css/main.css')
    );

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
       CATEGORY + ARCHIVE
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