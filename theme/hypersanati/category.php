<?php get_header(); ?>
<?php
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
          <a href="<?php echo home_url(); ?>">صفحه اصلی</a>
          <span>/</span>
          <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">مجله</a>
          <span>/</span>
          <span class="is-current"><?php single_cat_title(); ?></span>
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

<!-- article-category-sectio -->
<section class="article-category-section">
  <div class="container">
    <div class="article-category-layout">

      <!-- Sidebar -->
      <aside class="article-category-sidebar">

        <div class="sidebar-info-card">
          <?php if (is_active_sidebar('sidebar-1')) {
              dynamic_sidebar('sidebar-1');
          } ?>
        </div>
        <a href="#" alt="">

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



        </a>
      </aside>
      

      <!-- Main Content -->
      <main class="article-category-main">
        <?php
        $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $posts = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 8,
          'paged' => $paged
        ]);
        ?>

        <!-- Articles -->
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



        <!-- Pagination -->
        <?php
        $total_pages = $posts->max_num_pages;
        $current = max(1, get_query_var('paged'));
        ?>

        <nav class="article-pagination" aria-label="Pagination">

          <!-- First -->
          <a href="#" class="article-pagination-btn" data-page="1">ابتدا</a>

          <!-- Prev -->
          <a href="#" class="article-pagination-btn" data-page="<?php echo max(1, $current - 1); ?>">قبلی</a>

          <?php
          $start = max(1, $current - 4);
          $end = min($total_pages, $start + 9);

          for ($i = $start; $i <= $end; $i++) :
          ?>
            <a href="#" class="article-pagination-btn <?php echo ($i == $current) ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>

          <!-- Next -->
          <a href="#" class="article-pagination-btn" data-page="<?php echo min($total_pages, $current + 1); ?>">بعدی</a>

          <!-- Last -->
          <a href="#" class="article-pagination-btn" data-page="<?php echo $total_pages; ?>">انتها</a>

        </nav>
      </main>

    </div>
  </div>
</section>



<?php get_footer(); ?>