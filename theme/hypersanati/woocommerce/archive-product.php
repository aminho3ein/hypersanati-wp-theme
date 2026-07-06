<?php get_header(); ?>

    <!-- search-feat -->

<form class="search-input" id="ajax-search-form" onsubmit="return false;">
    <h5>عنوان محصول</h5>
    <div class="big-input-division" style="position: relative; display: flex; align-items: center;">
        
        <input 
            type="search"
            id="search-term"
            name="s"
            placeholder="مثلا بلبرینگ تماس زاویه ای"
            value="<?php echo get_search_query(); ?>"
            autocomplete="off"
        />
        
        <!-- دکمه ضربدر برای پاک کردن جستجو (ابتدا مخفی است) -->
        <button type="button" class="clear-search" id="reset-search" style="display: none; position: absolute; left: 50px; background: none; border: none; cursor: pointer; color: #999;">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <button class="btn" type="submit" id="trigger-search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</form>

<?php
/* خواندن مقادیر URL برای پر کردن پیش‌فرض اسلایدرها */
$shop_inner_min  = isset($_GET['inner_min'])  && is_numeric($_GET['inner_min'])  ? floatval($_GET['inner_min'])  : 15;
$shop_inner_max  = isset($_GET['inner_max'])  && is_numeric($_GET['inner_max'])  ? floatval($_GET['inner_max'])  : 45;
$shop_outer_min  = isset($_GET['outer_min'])  && is_numeric($_GET['outer_min'])  ? floatval($_GET['outer_min'])  : 20;
$shop_outer_max  = isset($_GET['outer_max'])  && is_numeric($_GET['outer_max'])  ? floatval($_GET['outer_max'])  : 60;
$shop_height_min = isset($_GET['height_min']) && is_numeric($_GET['height_min']) ? floatval($_GET['height_min']) : 10;
$shop_height_max = isset($_GET['height_max']) && is_numeric($_GET['height_max']) ? floatval($_GET['height_max']) : 30;
?>

<!-- جستجوی تقریبی بر اساس بازه ابعاد -->
 <div class="container">
  <div class="new-search-container new-compact new-wide-mode shop-dim-search">

      <section class="new-search-section">
          <h2 class="new-section-title">
              <!-- <i class="fa-solid fa-ruler-combined"></i> -->
              جستجوی تقریبی (بازه اندازه)
          </h2>

          <div class="new-range-grid">

              <!-- بازه قطر داخلی -->
              <div class="new-range-card" id="shop-range-inner">
                  <span class="new-card-title">بازه قطر داخلی</span>
                  <div class="new-slider-wrapper">
                      <div class="new-dual-slider">
                          <div class="new-slider-handle min-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">از</span>
                                  <input type="number" class="new-handle-input" value="<?php echo esc_attr($shop_inner_min); ?>" min="1" max="500">
                              </div>
                          </div>
                          <div class="new-slider-handle max-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">تا</span>
                                  <input type="number" class="new-handle-input" value="<?php echo esc_attr($shop_inner_max); ?>" min="1" max="500">
                              </div>
                          </div>
                          <div class="new-slider-track"></div>
                          <div class="new-slider-range-bar"></div>
                      </div>
                  </div>
              </div>

              <!-- بازه قطر خارجی -->
              <div class="new-range-card" id="shop-range-outer">
                  <span class="new-card-title">بازه قطر خارجی</span>
                  <div class="new-slider-wrapper">
                      <div class="new-dual-slider">
                          <div class="new-slider-handle min-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">از</span>
                                  <input type="number" class="new-handle-input" value="<?php echo esc_attr($shop_outer_min); ?>" min="1" max="500">
                              </div>
                          </div>
                          <div class="new-slider-handle max-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">تا</span>
                                  <input type="number" class="new-handle-input" value="<?php echo esc_attr($shop_outer_max); ?>" min="1" max="500">
                              </div>
                          </div>
                          <div class="new-slider-track"></div>
                          <div class="new-slider-range-bar"></div>
                      </div>
                  </div>
              </div>

              <!-- بازه ارتفاع -->
              <div class="new-range-card" id="shop-range-height">
                  <span class="new-card-title">بازه ارتفاع</span>
                  <div class="new-slider-wrapper">
                      <div class="new-dual-slider">
                          <div class="new-slider-handle min-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">از</span>
                                  <input type="number" class="new-handle-input" value="<?php echo esc_attr($shop_height_min); ?>" min="1" max="500">
                              </div>
                          </div>
                          <div class="new-slider-handle max-handle">
                              <div class="new-tooltip-bubble">
                                  <span class="new-tooltip-label">تا</span>
                                  <input type="number" class="new-handle-input" value="<?php echo esc_attr($shop_height_max); ?>" min="1" max="500">
                              </div>
                          </div>
                          <div class="new-slider-track"></div>
                          <div class="new-slider-range-bar"></div>
                      </div>
                  </div>
              </div>

          </div>

          <div class="new-range-actions">
              <button type="button" id="approximate-search-btn" class="new-btn-search">
                  <i class="fa-solid fa-magnifying-glass"></i>
                  جستجو بر اساس ابعاد
              </button>

              <button type="button" id="reset-dimension-search" class="new-btn-search">
                  <i class="fa-solid fa-xmark"></i>
                  پاک کردن فیلتر ابعاد
              </button>
          </div>
      </section>

  </div>
</div>
  
    <hr class="main-hr" />

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

  <div id="shop-container"></div>

  <div class="shop-loader">در حال بارگذاری...</div>

</div>

    </div>
<?php get_footer(); ?>