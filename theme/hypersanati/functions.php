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
       ABOUT US PAGE (FIXED)
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
            'hypersanati-single-post',
            get_template_directory_uri() . '/assets/css/single-article.css',
            ['hypersanati-style'],
            filemtime(get_template_directory() . '/assets/css/single-article.css')
        );

        wp_enqueue_script(
            'hypersanati-single-post',
            get_template_directory_uri() . '/assets/js/single-article.js',
            [],
            filemtime(get_template_directory() . '/assets/js/single-article.js'),
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