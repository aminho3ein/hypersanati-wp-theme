<?php get_header(); ?>

<?php if (is_front_page() || is_home()) : ?>
    <!-- HSB HOMEPAGE PRIMARY H1 -->
    <h1 class="hsb-homepage-primary-title">
        <?php echo esc_html(get_bloginfo('name')); ?>
    </h1>
<?php endif; ?>

<?php
$home_dimension_ranges =
    hypersanati_get_dimension_ranges();

$home_inner_min =
    (float) $home_dimension_ranges['inner']['min'];
$home_inner_max =
    (float) $home_dimension_ranges['inner']['max'];

$home_outer_min =
    (float) $home_dimension_ranges['outer']['min'];
$home_outer_max =
    (float) $home_dimension_ranges['outer']['max'];

$home_height_min =
    (float) $home_dimension_ranges['height']['min'];
$home_height_max =
    (float) $home_dimension_ranges['height']['max'];
?>


<?php
/* HSB HOMEPAGE HERO RENDER */
if (
    function_exists(
        'hsb_render_homepage_hero_section'
    )
) {
    hsb_render_homepage_hero_section();
}
?>

    <!-- search-feat -->
    <div id="hsb-home-search" class="search-area">
      <?php
      if (
          function_exists(
              'hypersanati_render_search_help_modal'
          )
      ) {
          hypersanati_render_search_help_modal();
      }
      ?>

      <form class="search-input" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
        <div class="search-input">
          <h5 class="hsb-search-heading">
              <span>عنوان محصول</span>

              <button
                type="button"
                class="hsb-search-help-trigger"
                aria-label="راهنمای روش‌های جستجو"
                aria-haspopup="dialog"
              >
                <i
                    class="fa-solid fa-eye"
                    aria-hidden="true"
                ></i>
              </button>
            </h5>
          <div class="big-input-division" style="position: relative; display: flex; align-items: center;">

            <input
              type="search"
              id="index-search-term"
              name="s"
              placeholder="مثلا بلبرینگ تماس زاویه ای"
              value="<?php echo get_search_query(); ?>"
              autocomplete="off"
            />

            <input type="hidden" name="post_type" value="product" />

            <!-- دکمه ضربدر برای پاک کردن سریع متن ورودی در صفحه اصلی -->
            <button type="button" id="index-reset-search" style="<?php echo is_search() ? 'display: block;' : 'display: none;'; ?> position: absolute; left: 50px; background: none; border: none; cursor: pointer; color: #999;">
              <i class="fa-solid fa-xmark"></i>
            </button>

            <button class="btn" type="submit">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
        </div>
      </form>
<!-- Professional Search -->


<div class="new-search-container new-compact new-wide-mode">

    <!-- بخش اول: جستجوی دقیق -->
    <section class="new-search-section">
        <h2 class="new-section-title hsb-search-heading">
              <span>
                  جستجوی دقیق بر اساس اندازه (میلی‌متر)
              </span>

              <button
                  type="button"
                  class="hsb-search-help-trigger"
                  aria-label="راهنمای روش‌های جستجو"
                  aria-haspopup="dialog"
              >
                <i
                    class="fa-solid fa-eye"
                    aria-hidden="true"
                ></i>
              </button>
          </h2>
        <div class="new-exact-grid">
            <div class="new-input-group">
                <label for="new-inner-dia">قطر داخلی</label>
                <input type="number" id="new-inner-dia" placeholder="مثلاً ۲۰">
            </div>
            <div class="new-input-group">
                <label for="new-outer-dia">قطر خارجی</label>
                <input type="number" id="new-outer-dia" placeholder="مثلاً ۲۰">
            </div>
            <div class="new-input-group">
                <label for="new-height">ارتفاع</label>
                <input type="number" id="new-height" placeholder="مثلاً ۲۰">
            </div>
            <button type="button" id="exact-search-btn" class="new-btn-search">جستجو</button>
        </div>
    </section>

    <hr class="new-divider">

    <!-- بخش دوم: جستجوی تقریبی (بازه اندازه) -->
    <section class="new-search-section">
        <h2 class="new-section-title hsb-search-heading">
              <span>
                  جستجوی تقریبی (بازه اندازه)
              </span>

              <button
                  type="button"
                  class="hsb-search-help-trigger"
                  aria-label="راهنمای روش‌های جستجو"
                  aria-haspopup="dialog"
              >
                <i
                    class="fa-solid fa-eye"
                    aria-hidden="true"
                ></i>
              </button>
          </h2>
        <div class="new-range-grid">

            <!-- بازه قطر داخلی -->
            <div class="new-range-card" id="range-inner">
                <span class="new-card-title">
                      بازه قطر داخلی —
                      موجود:
                      <?php echo esc_html($home_inner_min); ?>
                      تا
                      <?php echo esc_html($home_inner_max); ?>
                      میلی‌متر
                  </span>
                <div class="new-slider-wrapper">
                    <div class="new-dual-slider">
                        <!-- دایره حداقل (Min) همراه با بالون -->
                        <div class="new-slider-handle min-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">از</span>
                                <input type="number" class="new-handle-input" value="<?php echo esc_attr($home_inner_min); ?>" min="<?php echo esc_attr($home_inner_min); ?>" max="<?php echo esc_attr($home_inner_max); ?>">
                            </div>
                        </div>
                        <!-- دایره حداکثر (Max) همراه با بالون -->
                        <div class="new-slider-handle max-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">تا</span>
                                <input type="number" class="new-handle-input" value="<?php echo esc_attr($home_inner_max); ?>" min="<?php echo esc_attr($home_inner_min); ?>" max="<?php echo esc_attr($home_inner_max); ?>">
                            </div>
                        </div>
                        <div class="new-slider-track"></div>
                        <div class="new-slider-range-bar"></div>
                    </div>
                </div>
            </div>

            <!-- بازه قطر خارجی -->
            <div class="new-range-card" id="range-outer">
                <span class="new-card-title">
                      بازه قطر خارجی —
                      موجود:
                      <?php echo esc_html($home_outer_min); ?>
                      تا
                      <?php echo esc_html($home_outer_max); ?>
                      میلی‌متر
                  </span>
                <div class="new-slider-wrapper">
                    <div class="new-dual-slider">
                        <div class="new-slider-handle min-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">از</span>
                                <input type="number" class="new-handle-input" value="<?php echo esc_attr($home_outer_min); ?>" min="<?php echo esc_attr($home_outer_min); ?>" max="<?php echo esc_attr($home_outer_max); ?>">
                            </div>
                        </div>
                        <div class="new-slider-handle max-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">تا</span>
                                <input type="number" class="new-handle-input" value="<?php echo esc_attr($home_outer_max); ?>" min="<?php echo esc_attr($home_outer_min); ?>" max="<?php echo esc_attr($home_outer_max); ?>">
                            </div>
                        </div>
                        <div class="new-slider-track"></div>
                        <div class="new-slider-range-bar"></div>
                    </div>
                </div>
            </div>

            <!-- بازه ارتفاع -->
            <div class="new-range-card" id="range-height">
                <span class="new-card-title">
                      بازه ارتفاع —
                      موجود:
                      <?php echo esc_html($home_height_min); ?>
                      تا
                      <?php echo esc_html($home_height_max); ?>
                      میلی‌متر
                  </span>
                <div class="new-slider-wrapper">
                    <div class="new-dual-slider">
                        <div class="new-slider-handle min-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">از</span>
                                <input type="number" class="new-handle-input" value="<?php echo esc_attr($home_height_min); ?>" min="<?php echo esc_attr($home_height_min); ?>" max="<?php echo esc_attr($home_height_max); ?>">
                            </div>
                        </div>
                        <div class="new-slider-handle max-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">تا</span>
                                <input type="number" class="new-handle-input" value="<?php echo esc_attr($home_height_max); ?>" min="<?php echo esc_attr($home_height_min); ?>" max="<?php echo esc_attr($home_height_max); ?>">
                            </div>
                        </div>
                        <div class="new-slider-track"></div>
                        <div class="new-slider-range-bar"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="new-range-actions">
            <button type="button" id="approximate-search-btn" class="new-btn-search">جستجو</button>
        </div>
    </section>

</div>




<!-- Professional Search -->


    </div>

    <!-- hero-section -->

<section
    class="best-product-of-month hsb-home-featured products-section"
    aria-labelledby="hsb-home-featured-title"
>
    <h3 id="hsb-home-featured-title">
        محصولات ویژه
    </h3>

    <?php
    /*
     * Get a larger random pool first, then collapse SKUs
     * sharing the same technical Part Number into the same
     * storefront family exactly like Shop.
     */
    $featured_product_ids = get_posts(
        array(
            'post_type'              => 'product',
            'post_status'            => 'publish',
            'posts_per_page'         => 64,
            'fields'                 => 'ids',
            'orderby'                => 'rand',
            'no_found_rows'          => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        )
    );

    $featured_families = array();

    if (!empty($featured_product_ids)) {
        if (
            function_exists(
                'hsb_group_product_ids_by_part_number'
            )
        ) {
            $featured_families =
                hsb_group_product_ids_by_part_number(
                    $featured_product_ids
                );
        } else {
            foreach ($featured_product_ids as $product_id) {
                $featured_families[] = array(
                    'representative_id' => absint($product_id),
                    'version_count'     => 1,
                    'brand_count'       => 0,
                    'country_count'     => 0,
                );
            }
        }
    }

    $featured_families = array_slice(
        $featured_families,
        0,
        8
    );
    ?>

    <?php if (!empty($featured_families)) : ?>

        <div class="child-category hsb-home-featured__grid">

            <?php foreach ($featured_families as $family_group) : ?>

                <?php
                hypersanati_render_product_card(
                    $family_group['representative_id'],
                    $family_group
                );
                ?>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>


<!-- news-blog-posts -->

    <div class="news-blog-sect"></div>

    <!-- brand-section -->

<?php
$brands = get_terms(
    array(
        'taxonomy'   => 'product_brand',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',

        'meta_query' => array(
            array(
                'key'     => '_hsb_show_on_homepage',
                'value'   => '1',
                'compare' => '=',
            ),
        ),
    )
);

$visible_brands = array();

if (
    !is_wp_error($brands) &&
    !empty($brands)
) {
    foreach ($brands as $brand) {
        $image_id =
            absint(
                get_term_meta(
                    $brand->term_id,
                    'thumbnail_id',
                    true
                )
            );

        $image_url =
            $image_id
                ? wp_get_attachment_image_url(
                    $image_id,
                    'medium'
                )
                : '';

        if (!$image_url) {
            continue;
        }

        $visible_brands[] = array(
            'term'      => $brand,
            'image_url' => $image_url,
        );
    }
}
?>

<?php if (!empty($visible_brands)) : ?>

<section
    class="hsb-home-brands"
    aria-labelledby="hsb-home-brands-title"
>
    <h3
        class="hsb-home-brands__title"
        id="hsb-home-brands-title"
    >
        برندهای بلبرینگ وارداتی
    </h3>

    <div class="hsb-home-brands__list">

        <?php foreach ($visible_brands as $brand_item) : ?>

            <div class="hsb-home-brands__item">
                <img
                    src="<?php echo esc_url(
                        $brand_item['image_url']
                    ); ?>"
                    alt="<?php echo esc_attr(
                        $brand_item['term']->name
                    ); ?>"
                    title="<?php echo esc_attr(
                        $brand_item['term']->name
                    ); ?>"
                    loading="lazy"
                >
            </div>

        <?php endforeach; ?>

    </div>
</section>

<?php endif; ?>


<!-- new-post-cards-section -->

<section class="hsb-home-blog" aria-labelledby="hsb-home-blog-title">

  <div class="hsb-home-blog__header">

    <button
      class="hsb-home-blog__control"
      id="hsbHomeBlogPrev"
      type="button"
      aria-label="مقاله قبلی"
    >
      <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
    </button>

    <h3
      class="hsb-home-blog__title"
      id="hsb-home-blog-title"
    >
      آخرین به‌روزرسانی وبلاگ
    </h3>

    <button
      class="hsb-home-blog__control"
      id="hsbHomeBlogNext"
      type="button"
      aria-label="مقاله بعدی"
    >
      <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
    </button>

  </div>

  <div
    class="hsb-home-blog__track"
    id="hsbHomeBlogTrack"
  >
    <?php
    $blog_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
    );

    $blog_query = new WP_Query($blog_args);

    if ($blog_query->have_posts()) :
        while ($blog_query->have_posts()) :
            $blog_query->the_post();
    ?>

      <article class="hsb-home-blog__card">

        <a
          class="hsb-home-blog__image"
          href="<?php the_permalink(); ?>"
          aria-label="<?php the_title_attribute(); ?>"
        >
          <?php if (has_post_thumbnail()) : ?>

            <img
              src="<?php the_post_thumbnail_url('medium'); ?>"
              alt="<?php the_title_attribute(); ?>"
              loading="lazy"
            />

          <?php else : ?>

            <img
              src="<?php echo esc_url(
                  get_template_directory_uri() .
                  '/assets/images/default-blog.webp'
              ); ?>"
              alt="<?php the_title_attribute(); ?>"
              loading="lazy"
            />

          <?php endif; ?>
        </a>

        <h4 class="hsb-home-blog__card-title">
          <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
          </a>
        </h4>

        <p class="hsb-home-blog__excerpt">
          <?php
          echo esc_html(
              wp_trim_words(
                  get_the_excerpt(),
                  20,
                  ' ...'
              )
          );
          ?>
        </p>

        <div class="hsb-home-blog__footer">

          <div class="hsb-home-blog__meta">

            <div class="hsb-home-blog__meta-row">
              <img
                src="<?php echo esc_url(
                    get_template_directory_uri() .
                    '/assets/images/calendar 3.svg'
                ); ?>"
                alt=""
                aria-hidden="true"
              />

              <span>
                <?php
                echo esc_html(
                    strtr(
                        get_the_date('j F'),
                        array(
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
                        )
                    )
                );
                ?>
              </span>
            </div>

            <div class="hsb-home-blog__meta-row">
              <img
                src="<?php echo esc_url(
                    get_template_directory_uri() .
                    '/assets/images/user.svg'
                ); ?>"
                alt=""
                aria-hidden="true"
              />

              <span
                class="hsb-home-blog__author"
                title="<?php echo esc_attr(get_the_author()); ?>"
              >
                <?php echo esc_html(get_the_author()); ?>
              </span>
            </div>

          </div>

          <a
            class="hsb-home-blog__read-more"
            href="<?php the_permalink(); ?>"
          >
            مطالعه
          </a>

        </div>

      </article>

    <?php
        endwhile;

        wp_reset_postdata();

    else :
    ?>

      <p class="hsb-home-blog__empty">
        مقاله‌ای یافت نشد.
      </p>

    <?php endif; ?>

  </div>

</section>


<?php get_footer(); ?>
