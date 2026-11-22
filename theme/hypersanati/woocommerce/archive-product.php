<?php get_header(); ?>

<?php
$shop_dimension_ranges =
    hypersanati_get_dimension_ranges();

$shop_inner_bound_min =
    (float) $shop_dimension_ranges['inner']['min'];
$shop_inner_bound_max =
    (float) $shop_dimension_ranges['inner']['max'];

$shop_outer_bound_min =
    (float) $shop_dimension_ranges['outer']['min'];
$shop_outer_bound_max =
    (float) $shop_dimension_ranges['outer']['max'];

$shop_height_bound_min =
    (float) $shop_dimension_ranges['height']['min'];
$shop_height_bound_max =
    (float) $shop_dimension_ranges['height']['max'];
?>

    <!-- search-feat -->

<?php
if (
    function_exists(
        'hypersanati_render_search_help_modal'
    )
) {
    hypersanati_render_search_help_modal();
}
?>

<form class="search-input" id="ajax-search-form" onsubmit="return false;">
    <h5 class="hsb-search-heading">
        <span>عنوان محصول</span>

        <button
            type="button"
            class="hsb-search-help-trigger"
            aria-label="نمایش راهنمای جستجو"
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
            id="search-term" aria-label="جستجوی عنوان محصول"
            name="s"
            placeholder="مثلا بلبرینگ تماس زاویه ای"
            value="<?php echo get_search_query(); ?>"
            autocomplete="off"
        />

        <!-- دکمه ضربدر برای پاک کردن جستجو (ابتدا مخفی است) -->
        <button type="button" class="clear-search" id="reset-search" aria-label="پاک کردن جستجو" style="display: none; position: absolute; left: 50px; background: none; border: none; cursor: pointer; color: #999;">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>

        <button class="btn" type="submit" id="trigger-search" aria-label="جستجو">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        </button>
    </div>
</form>

<?php
/* مقادیر انتخاب‌شده کاربر؛ در نبود فیلتر، کل بازه واقعی محصولات */
$shop_inner_min =
    isset($_GET['inner_min']) &&
    is_numeric($_GET['inner_min'])
        ? floatval($_GET['inner_min'])
        : $shop_inner_bound_min;

$shop_inner_max =
    isset($_GET['inner_max']) &&
    is_numeric($_GET['inner_max'])
        ? floatval($_GET['inner_max'])
        : $shop_inner_bound_max;

$shop_outer_min =
    isset($_GET['outer_min']) &&
    is_numeric($_GET['outer_min'])
        ? floatval($_GET['outer_min'])
        : $shop_outer_bound_min;

$shop_outer_max =
    isset($_GET['outer_max']) &&
    is_numeric($_GET['outer_max'])
        ? floatval($_GET['outer_max'])
        : $shop_outer_bound_max;

$shop_height_min =
    isset($_GET['height_min']) &&
    is_numeric($_GET['height_min'])
        ? floatval($_GET['height_min'])
        : $shop_height_bound_min;

$shop_height_max =
    isset($_GET['height_max']) &&
    is_numeric($_GET['height_max'])
        ? floatval($_GET['height_max'])
        : $shop_height_bound_max;
?>

<!-- جستجوی تقریبی بر اساس بازه ابعاد -->
 <div class="container">
  <div class="new-search-container new-compact new-wide-mode shop-dim-search">

      <section class="new-search-section">
          <h2
            class="new-section-title hsb-search-heading"
            id="hsb-shop-approx-help"
        >
            <span>
                جستجوی تقریبی (بازه اندازه)
            </span>

            <button
                type="button"
                class="hsb-search-help-trigger"
                aria-label="نمایش راهنمای جستجو"
            >
                <i
                    class="fa-solid fa-eye"
                    aria-hidden="true"
                ></i>
            </button>
        </h2>

          <div class="new-range-grid">

              <!-- بازه قطر داخلی -->
              <div class="new-range-card" id="shop-range-inner">
                  <span class="new-card-title">
                        بازه قطر داخلی —
                        موجود:
                        <?php echo esc_html($shop_inner_bound_min); ?>
                        تا
                        <?php echo esc_html($shop_inner_bound_max); ?>
                        میلی‌متر
                    </span>
                  <div class="new-slider-wrapper">
                      <div class="new-dual-slider">
                          <div class="new-slider-handle min-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">از</span>
                                  <input type="number" aria-label="مقدار بازه اندازه" class="new-handle-input" value="<?php echo esc_attr($shop_inner_min); ?>" min="<?php echo esc_attr($shop_inner_bound_min); ?>" max="<?php echo esc_attr($shop_inner_bound_max); ?>">
                              </div>
                          </div>
                          <div class="new-slider-handle max-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">تا</span>
                                  <input type="number" aria-label="مقدار بازه اندازه" class="new-handle-input" value="<?php echo esc_attr($shop_inner_max); ?>" min="<?php echo esc_attr($shop_inner_bound_min); ?>" max="<?php echo esc_attr($shop_inner_bound_max); ?>">
                              </div>
                          </div>
                          <div class="new-slider-track"></div>
                          <div class="new-slider-range-bar"></div>
                      </div>
                  </div>
              </div>

              <!-- بازه قطر خارجی -->
              <div class="new-range-card" id="shop-range-outer">
                  <span class="new-card-title">
                        بازه قطر خارجی —
                        موجود:
                        <?php echo esc_html($shop_outer_bound_min); ?>
                        تا
                        <?php echo esc_html($shop_outer_bound_max); ?>
                        میلی‌متر
                    </span>
                  <div class="new-slider-wrapper">
                      <div class="new-dual-slider">
                          <div class="new-slider-handle min-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">از</span>
                                  <input type="number" aria-label="مقدار بازه اندازه" class="new-handle-input" value="<?php echo esc_attr($shop_outer_min); ?>" min="<?php echo esc_attr($shop_outer_bound_min); ?>" max="<?php echo esc_attr($shop_outer_bound_max); ?>">
                              </div>
                          </div>
                          <div class="new-slider-handle max-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">تا</span>
                                  <input type="number" aria-label="مقدار بازه اندازه" class="new-handle-input" value="<?php echo esc_attr($shop_outer_max); ?>" min="<?php echo esc_attr($shop_outer_bound_min); ?>" max="<?php echo esc_attr($shop_outer_bound_max); ?>">
                              </div>
                          </div>
                          <div class="new-slider-track"></div>
                          <div class="new-slider-range-bar"></div>
                      </div>
                  </div>
              </div>

              <!-- بازه ارتفاع -->
              <div class="new-range-card" id="shop-range-height">
                  <span class="new-card-title">
                        بازه ارتفاع —
                        موجود:
                        <?php echo esc_html($shop_height_bound_min); ?>
                        تا
                        <?php echo esc_html($shop_height_bound_max); ?>
                        میلی‌متر
                    </span>
                  <div class="new-slider-wrapper">
                      <div class="new-dual-slider">
                          <div class="new-slider-handle min-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">از</span>
                                  <input type="number" aria-label="مقدار بازه اندازه" class="new-handle-input" value="<?php echo esc_attr($shop_height_min); ?>" min="<?php echo esc_attr($shop_height_bound_min); ?>" max="<?php echo esc_attr($shop_height_bound_max); ?>">
                              </div>
                          </div>
                          <div class="new-slider-handle max-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">تا</span>
                                  <input type="number" aria-label="مقدار بازه اندازه" class="new-handle-input" value="<?php echo esc_attr($shop_height_max); ?>" min="<?php echo esc_attr($shop_height_bound_min); ?>" max="<?php echo esc_attr($shop_height_bound_max); ?>">
                              </div>
                          </div>
                          <div class="new-slider-track"></div>
                          <div class="new-slider-range-bar"></div>
                      </div>
                  </div>
              </div>

          </div>

          <div class="new-range-actions">
              <button type="button" id="approximate-search-btn" class="new-btn-search" aria-label="جستجو بر اساس ابعاد">
                  <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                  جستجو بر اساس ابعاد
              </button>

              <button type="button" id="reset-dimension-search" class="new-btn-search" aria-label="پاک کردن فیلتر ابعاد">
                  <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                  پاک کردن فیلتر ابعاد
              </button>
          </div>
      </section>

  </div>
</div>

    <hr class="main-hr" />

    <!-- Mobile category filter -->
    <details class="hsb-mobile-category-filter">

        <summary>

            <span>
                <i class="fa-solid fa-sliders"></i>
                فیلتر دسته‌بندی محصولات
            </span>

            <i
                class="fa-solid fa-chevron-down hsb-mobile-filter-arrow">
            </i>

        </summary>

        <div
            id="mobile-sidebar-category"
            class="hsb-mobile-category-filter__body">
        </div>

    </details>


    <div class="shop-container">
      <div class="col-4 side-bar">
        <div class="approximate-search">
          <div class="approximate-paragraphs">
            <p>فیلتر دسترسی به محصولات</p>
          </div>
          <div class="accurate-inputs">
            <div class="accurate-search-boxes">
              <div class="search-approx-box">
                <div class="approximate-input-name-mobile">
                  <h5>قطر داخلی</h5>
                </div>
                <div class="approximate-inputs-mobile">
                  <div class="more-than-sec">
                    <p>بیشتر از مثلا</p>
                    <input type="text" placeholder="مثلا 20" />
                  </div>
                  <p id="va">و</p>
                  <div class="less-than-sec">
                    <p>کمتر از</p>
                    <input type="text" placeholder="مثلا 20" />
                  </div>
                </div>
              </div>
              <div class="search-approx-box">
                <div class="approximate-input-name-mobile">
                  <h5>قطر داخلی</h5>
                </div>
                <div class="approximate-inputs-mobile">
                  <div class="more-than-sec">
                    <p>بیشتر از مثلا</p>
                    <input type="text" placeholder="مثلا 20" />
                  </div>
                  <p id="va">و</p>
                  <div class="less-than-sec">
                    <p>کمتر از</p>
                    <input type="text" placeholder="مثلا 20" />
                  </div>
                </div>
              </div>
              <div class="search-approx-box">
                <div class="approximate-input-name-mobile">
                  <h5>قطر داخلی</h5>
                </div>
                <div class="approximate-inputs-mobile">
                  <div class="more-than-sec">
                    <p>بیشتر از مثلا</p>
                    <input type="text" placeholder="مثلا 20" />
                  </div>
                  <p id="va">و</p>
                  <div class="less-than-sec">
                    <p>کمتر از</p>
                    <input type="text" placeholder="مثلا 20" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

<!--
        <div class="approximate-search-mobile">
          <div class="approximate-paragraphs">
            <p>فیلتر دسترسی به محصولات</p>
          </div>
          <div class="accurate-inputs">
            <div class="accurate-search-boxes">
              <div class="search-approx-box">
                <div class="approximate-input-name">
                  <h5>قطر داخلی</h5>
                </div>
                <div class="approximate-inputs">
                  <p>بیشتر از مثلا</p>
                  <input type="text" placeholder="مثلا 20" />
                  <p id="va">و</p>
                  <p>کمتر از</p>
                  <input type="text" placeholder="مثلا 20" />
                </div>
              </div>
              <div class="search-approx-box">
                <div class="approximate-input-name">
                  <h5>قطر خارجی</h5>
                </div>
                <div class="approximate-inputs">
                  <p>بیشتر از مثلا</p>
                  <input type="text" placeholder="مثلا 20" />
                  <p id="va">و</p>
                  <p>کمتر از</p>
                  <input type="text" placeholder="مثلا 20" />
                </div>
              </div>
              <div class="search-approx-box">
                <div class="approximate-input-name">
                  <h5>ارتفاع</h5>
                </div>
                <div class="approximate-inputs">
                  <p>بیشتر از مثلا</p>
                  <input type="text" placeholder="مثلا 20" />
                  <p id="va">و</p>
                  <p>کمتر از</p>
                  <input type="text" placeholder="مثلا 20" />
                </div>
              </div>
            </div>
          </div>
        </div>
-->
        <h4 class="category-header">یک دسته محصول را انتخاب کنید</h4>
        <hr class="sub-section-hr" />

      <div class="category" id="sidebar-category"></div>

      </div>
<div class="col-8 products-section">

  <div id="products-section-header">
  <?php if (!empty($_GET['dimension_search'])) : ?>
    <div class="dimension-search-banner">
      <h4>نتایج جستجو بر اساس ابعاد</h4>
      <p id="dim-search-label"><?php echo esc_html(hypersanati_get_dimension_search_label($_GET)); ?></p>
    </div>
  <?php else : ?>
    <h4 id="all-products-title">همه دسته محصولات</h4>
  <?php endif; ?>
  </div>
  <hr class="sub-section-hr" />

  <?php
/* HSB ONLINE SALE SHOP DISPLAY V1 */

$hsb_online_groups = array();
$hsb_online_term_id = function_exists('hsb_online_sale_category_id')
    ? hsb_online_sale_category_id(false)
    : 0;

if ($hsb_online_term_id && empty($_GET['dimension_search'])) {

    $hsb_online_ids = get_posts(array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 120,
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $hsb_online_term_id,
            ),
        ),
    ));

    $hsb_expanded_ids = array();

    foreach ($hsb_online_ids as $hsb_id) {
        $hsb_product = wc_get_product($hsb_id);

        if (
            !$hsb_product ||
            !function_exists('hsb_dual_is_direct') ||
            !hsb_dual_is_direct($hsb_product)
        ) {
            continue;
        }

        $hsb_family_ids = function_exists('hsb_get_product_family_ids')
            ? hsb_get_product_family_ids($hsb_id)
            : array($hsb_id);

        $hsb_family_ids = array_map(
            'absint',
            (array) $hsb_family_ids
        );

        if (!in_array((int) $hsb_id, $hsb_family_ids, true)) {
            $hsb_family_ids[] = (int) $hsb_id;
        }

        $hsb_expanded_ids = array_merge(
            $hsb_expanded_ids,
            $hsb_family_ids
        );
    }

    $hsb_expanded_ids = array_values(
        array_unique(array_filter($hsb_expanded_ids))
    );

    if (
        $hsb_expanded_ids &&
        function_exists('hsb_group_product_ids_by_part_number')
    ) {
        $hsb_online_groups = hsb_group_product_ids_by_part_number(
            $hsb_expanded_ids
        );
    }
}
?>

<?php if (!empty($hsb_online_groups)) : ?>

<section class="category hsb-online-sale-section"
         aria-labelledby="hsb-online-sale-heading">

    <div class="category-name">
        <h5 id="hsb-online-sale-heading">
            <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
            فروش آنلاین
        </h5>
    </div>

    <p class="hsb-online-sale-description">
        محصولات دارای قیمت و موجودی، آماده ثبت سفارش آنلاین
    </p>

    <div class="child-category hsb-online-sale-grid">
        <?php foreach (array_slice($hsb_online_groups, 0, 8) as $hsb_group) : ?>
            <?php
            hypersanati_render_product_card(
                $hsb_group['representative_id'],
                $hsb_group
            );
            ?>
        <?php endforeach; ?>
    </div>

    <?php if (count($hsb_online_groups) > 8) : ?>
        <div class="hsb-online-sale-more">
            <a href="<?php echo esc_url(
                get_term_link($hsb_online_term_id, 'product_cat')
            ); ?>">
                مشاهده همه محصولات فروش آنلاین
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>
    <?php endif; ?>

</section>

<hr class="sub-section-hr" />

<?php endif; ?>

<div id="shop-container"></div>

  <div class="shop-loader">در حال بارگذاری...</div>

</div>

    </div>
<?php get_footer(); ?>
