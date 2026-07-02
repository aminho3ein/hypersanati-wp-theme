<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue all CSS files in assets/css directory
 * (adds English comments for each file)
 */
function hypersanati_enqueue_all_css() {

    $base_url = get_template_directory_uri() . '/assets/css';
    $base_dir = get_template_directory() . '/assets/css';

    // Bootstrap RTL
    wp_enqueue_style(
        'bootstrap-rtl',
        $base_url . '/bootstrap.rtl.min.css',
        array(),
        filemtime($base_dir . '/bootstrap.rtl.min.css')
    );

    // FontAwesome Core
    wp_enqueue_style(
        'fontawesome',
        $base_url . '/fontawesome.min.css',
        array(),
        filemtime($base_dir . '/fontawesome.min.css')
    );

    // FontAwesome Solid
    wp_enqueue_style(
        'fontawesome-solid',
        $base_url . '/solid.min.css',
        array('fontawesome'),
        filemtime($base_dir . '/solid.min.css')
    );

    // FontAwesome Brands
    wp_enqueue_style(
        'fontawesome-brands',
        $base_url . '/brands.min.css',
        array('fontawesome'),
        filemtime($base_dir . '/brands.min.css')
    );

    // Fonts
    wp_enqueue_style(
        'site-fonts',
        $base_url . '/font.css',
        array(),
        filemtime($base_dir . '/font.css')
    );

    // Main CSS
    wp_enqueue_style(
        'main-css',
        $base_url . '/main.css',
        array('bootstrap-rtl'),
        filemtime($base_dir . '/main.css')
    );

    // Tablet
    wp_enqueue_style(
        'tablet-responsive',
        $base_url . '/responsive/tablet-responsive.css',
        array('main-css'),
        filemtime($base_dir . '/responsive/tablet-responsive.css'),
        '(min-width:768px)'
    );

    // Laptop
    wp_enqueue_style(
        'laptop-responsive',
        $base_url . '/responsive/laptop-responsive.css',
        array('main-css'),
        filemtime($base_dir . '/responsive/laptop-responsive.css'),
        '(min-width:992px)'
    );

    // Desktop
    wp_enqueue_style(
        'desktop-responsive',
        $base_url . '/responsive/desktop-responsive.css',
        array('main-css'),
        filemtime($base_dir . '/responsive/desktop-responsive.css'),
        '(min-width:1200px)'
    );
}

add_action('wp_enqueue_scripts', 'hypersanati_enqueue_all_css');

/**
 * Enqueue remaining assets (JS + specific responsive folder if needed)
 */
function hypersanati_enqueue_scripts() {

    // JS
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/functions.js',
        array(),
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hypersanati_enqueue_scripts' );

// Load theme assets conditionally
function hypersanati_enqueue_assets() {

    // Main Style
    wp_enqueue_style(
        'hypersanati-style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_template_directory() . '/assets/css/main.css')
    );

    // 404 page
    if (is_404()) {

        wp_enqueue_style(
            'hypersanati-404',
            get_template_directory_uri() . '/assets/css/404.css',
            array('hypersanati-style'),
            filemtime(get_template_directory() . '/assets/css/404.css')
        );

        wp_enqueue_script(
            'hypersanati-404',
            get_template_directory_uri() . '/assets/js/404.js',
            array(),
            filemtime(get_template_directory() . '/assets/js/404.js'),
            true
        );
    }

    // About page
    if (is_page('about-us') || is_page_template('page-about-us.php')) {

        wp_enqueue_style(
            'hypersanati-about-us',
            get_template_directory_uri() . '/assets/css/about-us.css',
            array('hypersanati-style'),
            filemtime(get_template_directory() . '/assets/css/about-us.css')
        );

        wp_enqueue_script(
            'hypersanati-about-us',
            get_template_directory_uri() . '/assets/js/about-us.js',
            array(),
            filemtime(get_template_directory() . '/assets/js/about-us.js'),
            true
        );
    }

    // Category page (CSS)
    if (is_category() || is_archive()) {

        wp_enqueue_style(
            'hypersanati-category',
            get_template_directory_uri() . '/assets/css/article-category.css',
            array('hypersanati-style'),
            filemtime(get_template_directory() . '/assets/css/article-category.css')
        );
    }

    // Category page (JS)
    if (is_category() || is_archive()) {

        wp_enqueue_script(
            'hypersanati-category',
            get_template_directory_uri() . '/assets/js/article-category.js',
            array(),
            filemtime(get_template_directory() . '/assets/js/article-category.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hypersanati_enqueue_assets');

// add menu in theme
function hypersanati_register_menus() {
    register_nav_menus(array(
        'primary_menu' => 'فهرست اصلی',
        'footer_menu'  => 'فهرست فوتر',
    ));
}
add_action('after_setup_theme', 'hypersanati_register_menus');

function hypersanati_nav_link_classes($atts, $item, $args, $depth) {
    $classes = isset($atts['class']) ? explode(' ', $atts['class']) : array();

    $item_url = isset($item->url) ? untrailingslashit($item->url) : '';
    $home_url = untrailingslashit(home_url('/'));

    if (
        isset($args->theme_location) &&
        $args->theme_location === 'primary_menu'
    ) {
        if ($item_url === $home_url || in_array('menu-item-home', $item->classes, true)) {
            $classes[] = 'home-btn';
        }

        if (trim($item->title) === 'ارتباط با ما') {
            $classes[] = 'contact-btn';
        }
    }

    if (
        isset($args->menu_class) &&
        $args->menu_class === 'mobile-nav-links'
    ) {
        $atts['onclick'] = 'closeMobileMenu()';
    }

    if (!empty($classes)) {
        $atts['class'] = implode(' ', array_unique($classes));
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'hypersanati_nav_link_classes', 10, 4);




// Article Hero Sectiop
function add_featured_meta_box() {
    add_meta_box(
        'featured_post',
        'مقاله شاخص',
        'featured_meta_box_callback',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'add_featured_meta_box');

function featured_meta_box_callback($post) {
    $value = get_post_meta($post->ID, '_is_featured', true);

    ?>
    <label>
        <input type="checkbox" name="is_featured" value="1" <?php checked($value, '1'); ?> />
        نمایش در اسلایدر / Hero
    </label>
    <?php
}

function save_featured_meta($post_id) {
    if (isset($_POST['is_featured'])) {
        update_post_meta($post_id, '_is_featured', '1');
    } else {
        delete_post_meta($post_id, '_is_featured');
    }
}
add_action('save_post', 'save_featured_meta');



// Category Sticky Note Widget
class Sidebar_Info_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'sidebar_info_widget',
            'Sidebar Info Card',
            array('description' => 'نمایش عنوان و متن در سایدبار')
        );
    }

    // view in sidebar
    public function widget($args, $instance) {

        $title = !empty($instance['title']) ? $instance['title'] : '';
        $text  = !empty($instance['text']) ? $instance['text'] : '';

        echo '<div class="sidebar-info-card">';

        if ($title) {
            echo '<h3 class="sidebar-info-title">' . esc_html($title) . '</h3>';
        }

        if ($text) {
            echo '<p class="sidebar-info-text">' . esc_html($text) . '</p>';
        }

        echo '</div>';
    }

    // Form in Panel
    public function form($instance) {

        $title = !empty($instance['title']) ? $instance['title'] : '';
        $text  = !empty($instance['text']) ? $instance['text'] : '';
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

    // ذخیره اطلاعات
    public function update($new_instance, $old_instance) {

        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['text']  = (!empty($new_instance['text'])) ? strip_tags($new_instance['text']) : '';

        return $instance;
    }
}

// register widget
function register_sidebar_info_widget() {
    register_widget('Sidebar_Info_Widget');
}
add_action('widgets_init', 'register_sidebar_info_widget');

function register_theme_sidebars() {
    register_sidebar([
        'name' => 'Main Sidebar',
        'id' => 'sidebar-1',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ]);
}
add_action('widgets_init', 'register_theme_sidebars');



// Create The Add Product In Sticky sidebar Category
add_action('woocommerce_product_options_general_product_data', function() {

    woocommerce_wp_checkbox([
        'id' => '_is_discount_featured',
        'label' => 'تخفیف ویژه هفته (تبلیغ)',
        'description' => 'این محصول در اسلایدر تخفیف ویژه نمایش داده شود'
    ]);

});

add_action('woocommerce_process_product_meta', function($post_id) {

    $value = isset($_POST['_is_discount_featured']) ? 'yes' : 'no';
    update_post_meta($post_id, '_is_discount_featured', $value);

});


// main post section
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

    $posts_html = ob_get_clean();

    // pagination جدا ساخته میشه
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