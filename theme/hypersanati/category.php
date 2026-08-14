<?php get_header(); ?>
<?php
$is_magazine_landing =
  is_page('magazine') ||
  is_page_template('page-magazine.php');

$category_id = $is_magazine_landing
  ? 0
  : absint(get_queried_object_id());

$featured = new WP_Query([
  'post_type' => 'post',
  'posts_per_page' => 1,
  'meta_key' => '_is_featured',
  'meta_value' => '1'
]);
?>


<?php if ($featured->have_posts()) : $featured->the_post(); ?>

<section class="article-category-hero">
  <div class="container">
    <div class="article-category-hero__inner">

      <div class="article-category-hero__content">

        <p class="article-category-hero__label">
          پربیننده ترین مقاله‌ی هفته
        </p>

        <!-- H1 LINK -->
        <h1 class="article-category-hero__title">
          <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
          </a>
        </h1>

        <nav class="article-category-hero__breadcrumb" aria-label="breadcrumb">
          <a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a>
          <span>/</span>

          <?php if ($is_magazine_landing) : ?>
            <span class="is-current">مجله</span>
          <?php else : ?>
            <a href="<?php echo esc_url(home_url('/magazine/')); ?>">مجله</a>
            <span>/</span>
            <span class="is-current"><?php single_cat_title(); ?></span>
          <?php endif; ?>
        </nav>

        <p class="article-category-hero__description">
          <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
        </p>

      </div>

      <!-- IMAGE LINK -->
      <div class="article-category-hero__media">

        <a href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) : ?>
            <img
              src="<?php echo get_the_post_thumbnail_url(); ?>"
              class="article-category-hero__image"
              alt="<?php the_title_attribute(); ?>"
            />
          <?php endif; ?>
        </a>

      </div>

    </div>
  </div>
</section>

<?php wp_reset_postdata(); endif; ?>

<?php
/* HSB MAGAZINE CATEGORY NAV */

$magazine_category_exclude = array(
    absint(get_option('default_category')),
);

$legacy_magazine_category =
    get_category_by_slug('magazine');

if (
    $legacy_magazine_category instanceof WP_Term
) {
    $magazine_category_exclude[] =
        absint($legacy_magazine_category->term_id);
}

$magazine_categories = get_categories(
    array(
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'exclude'    => array_values(
            array_unique(
                array_filter(
                    $magazine_category_exclude
                )
            )
        ),
        'orderby'    => 'name',
        'order'      => 'ASC',
    )
);
?>

<?php if (!empty($magazine_categories)) : ?>
<section
  class="magazine-category-nav"
  aria-label="دسته‌بندی‌های مجله"
>
  <div class="container">
    <div class="magazine-category-nav__inner">

      <span class="magazine-category-nav__title">
        دسته‌بندی‌ها
      </span>

      <div class="magazine-category-nav__list">

        <a
          href="<?php echo esc_url(home_url('/magazine/')); ?>"
          class="magazine-category-nav__item<?php echo $is_magazine_landing ? ' is-active' : ''; ?>"
          <?php if ($is_magazine_landing) : ?>
            aria-current="page"
          <?php endif; ?>
        >
          <span>همه مقالات</span>
          <span class="magazine-category-nav__count">
            <?php
            $magazine_post_counts = wp_count_posts('post');
            echo esc_html(
                number_format_i18n(
                    isset($magazine_post_counts->publish)
                        ? $magazine_post_counts->publish
                        : 0
                )
            );
            ?>
          </span>
        </a>

        <?php foreach ($magazine_categories as $magazine_category) : ?>
          <?php
          $is_current_category =
              !$is_magazine_landing &&
              absint($category_id) ===
              absint($magazine_category->term_id);
          ?>

          <a
            href="<?php echo esc_url(get_category_link($magazine_category->term_id)); ?>"
            class="magazine-category-nav__item<?php echo $is_current_category ? ' is-active' : ''; ?>"
            <?php if ($is_current_category) : ?>
              aria-current="page"
            <?php endif; ?>
          >
            <span><?php echo esc_html($magazine_category->name); ?></span>
            <span class="magazine-category-nav__count">
              <?php echo esc_html(number_format_i18n($magazine_category->count)); ?>
            </span>
          </a>
        <?php endforeach; ?>

      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- article-category-sectio -->
<section
  class="article-category-section"
  data-category-id="<?php echo esc_attr($category_id); ?>"
  data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
>
    <div class="container">
    <div class="article-category-layout">

      <!-- Sidebar -->
      <aside class="article-category-sidebar">

        <div class="sidebar-info-card">
          <?php if (is_active_sidebar('sidebar-1')) {
              dynamic_sidebar('sidebar-1');
          } ?>
        </div>
<?php
$discount_products = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 5,
    'meta_key' => '_is_discount_featured',
    'meta_value' => 'yes'
]);
?>

<div class="discount-slider" id="discountSlider">

<?php if ($discount_products->have_posts()) : ?>
    <?php while ($discount_products->have_posts()) : $discount_products->the_post();
        $product = wc_get_product(get_the_ID());
    ?>

        <a href="<?php the_permalink(); ?>" class="discount-item">

            <div class="discount-product-card">

                <div class="discount-product-label">
                    تخفیف ویژه هفته (تبلیغ)
                </div>

                <div class="discount-product-frame">
                    <?php the_post_thumbnail('medium'); ?>
                </div>

                <p class="discount-product-desc">
                    <?php the_title(); ?>
                </p>

                <div class="discount-product-old-price-row">

                    <span class="discount-product-old-price">
                        <?php echo wc_price($product->get_regular_price()); ?>
                    </span>

                    <span class="discount-product-badge">
                        <?php
                        if ($product->get_regular_price() && $product->get_sale_price()) {
                            echo round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100) . '%';
                        }
                        ?>
                    </span>

                </div>

                <div class="discount-product-new-price">
                    <?php echo wc_price($product->get_sale_price()); ?>
                </div>

            </div>

        </a>

    <?php endwhile; ?>
<?php endif; wp_reset_postdata(); ?>

</div>
      </aside>


      <!-- Main Content -->
                        <main class="article-category-main">

                        <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


                        $post_query_args = [
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'posts_per_page' => 8,
                            'paged'          => $paged
                        ];

                        if ($category_id > 0) {
                            $post_query_args['cat'] = $category_id;
                        }

                        $posts = new WP_Query($post_query_args);
                        ?>

                        <div class="posts-wrapper">

                        <?php if ($posts->have_posts()) : ?>
                            <?php while ($posts->have_posts()) : $posts->the_post(); ?>

                                <div class="article-category-item">

                                    <div class="article-category-tag">
                                        <?php
                                        $categories = get_the_category();
                                        echo $categories[0]->name ?? 'مقاله';
                                        ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>" class="article-card">

                                        <div class="article-card-image">
                                            <?php the_post_thumbnail('large'); ?>
                                        </div>

                                        <h4 class="article-card-title">
                                            <?php the_title(); ?>
                                        </h4>

                                        <p class="article-card-text">
                                            <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                                        </p>

                                    </a>
                                </div>

                            <?php endwhile; ?>
                        <?php endif; wp_reset_postdata(); ?>

                        </div>


                        <!-- Pagination -->
                        <?php
                        $total_pages = $posts->max_num_pages;
                        $current = max(1, $paged);
                        ?>

                        <nav class="article-pagination" aria-label="Pagination">

                            <a href="#" class="article-pagination-btn" data-page="1">ابتدا</a>

                            <a href="#" class="article-pagination-btn" data-page="<?php echo max(1, $current - 1); ?>">قبلی</a>

                            <?php
                            $start = max(1, $current - 4);
                            $end = min($total_pages, $start + 9);

                            for ($i = $start; $i <= $end; $i++) :
                            ?>
                                <a href="#" class="article-pagination-btn <?php echo ($i == $current) ? 'active' : ''; ?>"
                                  data-page="<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <a href="#" class="article-pagination-btn" data-page="<?php echo min($total_pages, $current + 1); ?>">بعدی</a>

                            <a href="#" class="article-pagination-btn" data-page="<?php echo $total_pages; ?>">انتها</a>

                        </nav>

                        </main>
         </div>
    </div>
</section>
<?php get_footer(); ?>