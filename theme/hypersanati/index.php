<?php get_header(); ?>

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


    <!-- search-feat -->
    <div class="search-area">
      <form class="search-input" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
        <div class="search-input">
          <h5>عنوان محصول</h5>
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
        <h2 class="new-section-title">جستجوی دقیق بر اساس اندازه (میلی‌متر)</h2>
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
        <h2 class="new-section-title">جستجوی تقریبی (بازه اندازه)</h2>
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
    <div class="best-product-of-month">
      <div>
        <h3>محصولات ویژه</h3>
      </div>


<?php
// کوئری برای دریافت ۸ محصول به صورت تصادفی
$best_products_query = new WP_Query([
    'post_type'      => 'product',
    'posts_per_page' => 8,
    'orderby'        => 'rand'
]);

if ($best_products_query->have_posts()) :
    $counter = 0;
    
    // شروع ردیف اول محصولات
    echo '<div class="best-products-row">';
    
    while ($best_products_query->have_posts()) : $best_products_query->the_post();
        $counter++;
        
        // اگر ۴ محصول اول نمایش داده شدند، ردیف اول را می‌بندیم و ردیف دوم را باز می‌کنیم
        if ($counter == 5) {
            echo '</div>'; // بستن ردیف اول (.best-products-row)
            echo '<div class="best-products-row">'; // باز کردن ردیف دوم (.best-products-row)
        }
        ?>
        
        <div class="single-product-container">
            <!-- تبدیل کلاس اصلی به تگ A جهت لینک شدن کل باکس بدون تغییر در ساختار CSS -->
            <a href="<?php the_permalink(); ?>" class="main-contains" style="text-decoration: none; color: inherit; display: block;">
                
                <div class="best-product-container">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium'); ?>
                    <?php else : ?>
                        <!-- تصویر پیش‌فرض در صورت عدم وجود تصویر محصول -->
                        <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title_attribute(); ?>" />
                    <?php endif; ?>
                </div>
                
                <div class="best-product-detail">
                    <p><?php the_title(); ?></p>
                    <div class="icon-frame">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                </div>

            </a>
        </div>

        <?php 
    endwhile; 
    
    echo '</div>'; // بستen ردیف نهایی
    
    wp_reset_postdata(); 
endif; 
?>
      

    </div>

    <!-- news-blog-posts -->

    <div class="news-blog-sect"></div>

    <!-- brand-section -->

<div class="brand-section">
    <h3>برند های بلبرینگ وارداتی</h3>
    
    <?php
    // دریافت تمام برندها
    $brands = get_terms([
        'taxonomy'   => 'product_brand',
        'hide_empty' => false,
    ]);

    if (!empty($brands) && !is_wp_error($brands)) :
        $counter = 0;
        
        // باز کردن ردیف اول
        echo '<div class="brand-groups">';
        
        foreach ($brands as $brand) :
            // ووکامرس آیدی تصویر دسته بندی/تاکسونومی را در متای thumbnail_id ذخیره می‌کند
            $image_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
            $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
            
            // اگر برند تصویر نداشت، آن را رد کن
            if (empty($image_url)) {
                continue;
            }
            
            $counter++;
            
            // مدیریت ساختار ردیف‌های ۴ تایی مطابق CSS شما
            if ($counter > 1 && ($counter - 1) % 4 == 0) {
                echo '</div>'; // بستن ردیف قبلی
                echo '<div class="brand-groups">'; // باز کردن ردیف جدید
            }
            ?>
            
            <div class="brand-frame">
                <!-- نمایش لوگوی برند -->
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($brand->name); ?>" title="<?php echo esc_attr($brand->name); ?>" />
            </div>
            
        <?php 
        endforeach;
        
        echo '</div>'; // بستن آخرین ردیف
    else:
        echo '<p style="text-align:center;">هنوز برندی ثبت نشده است.</p>';
    endif;
    ?>
</div>

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