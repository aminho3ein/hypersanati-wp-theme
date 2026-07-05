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

  
    <h4 class="shop-name">فروشگاه محصولات صنعتی الفت</h4>
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
        <div class="approximate-search-mobile">
          <div class="approximate-paragraphs">
            <p>فیلتر دسترسی به محصولات</p>
          </div>
    <!-- بخش دوم: جستجوی تقریبی (بازه اندازه) -->
    <section class="new-search-section">
        <h2 class="new-section-title">جستجوی تقریبی (بازه اندازه)</h2>
        <div class="new-range-grid">
            
            <!-- بازه قطر داخلی -->
            <div class="new-range-card" id="range-inner">
                <span class="new-card-title">بازه قطر داخلی</span>
                <div class="new-slider-wrapper">
                    <div class="new-dual-slider">
                        <!-- دایره حداقل (Min) همراه با بالون -->
                        <div class="new-slider-handle min-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">از</span>
                                <input type="number" class="new-handle-input" value="15" min="10" max="100">
                            </div>
                        </div>
                        <!-- دایره حداکثر (Max) همراه با بالون -->
                        <div class="new-slider-handle max-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">تا</span>
                                <input type="number" class="new-handle-input" value="45" min="10" max="100">
                            </div>
                        </div>
                        <div class="new-slider-track"></div>
                        <div class="new-slider-range-bar"></div>
                    </div>
                </div>
            </div>

            <!-- بازه قطر خارجی -->
            <div class="new-range-card" id="range-outer">
                <span class="new-card-title">بازه قطر خارجی</span>
                <div class="new-slider-wrapper">
                    <div class="new-dual-slider">
                        <div class="new-slider-handle min-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">از</span>
                                <input type="number" class="new-handle-input" value="20" min="10" max="100">
                            </div>
                        </div>
                        <div class="new-slider-handle max-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">تا</span>
                                <input type="number" class="new-handle-input" value="60" min="10" max="100">
                            </div>
                        </div>
                        <div class="new-slider-track"></div>
                        <div class="new-slider-range-bar"></div>
                    </div>
                </div>
            </div>

            <!-- بازه ارتفاع -->
            <div class="new-range-card" id="range-height">
                <span class="new-card-title">بازه ارتفاع</span>
                <div class="new-slider-wrapper">
                    <div class="new-dual-slider">
                        <div class="new-slider-handle min-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">از</span>
                                <input type="number" class="new-handle-input" value="10" min="10" max="100">
                            </div>
                        </div>
                        <div class="new-slider-handle max-handle">
                            <div class="new-tooltip-bubble">
                                <span class="new-tooltip-label">تا</span>
                                <input type="number" class="new-handle-input" value="30" min="10" max="100">
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
        <h4 class="category-header">یک دسته محصول را انتخاب کنید</h4>
        <hr class="sub-section-hr" />

      <div class="category" id="sidebar-category"></div>

      </div>
<div class="col-8 products-section">

  <?php if (!empty($_GET['dimension_search'])) : ?>
    <div class="dimension-search-banner">
      <h4>نتایج جستجو بر اساس ابعاد</h4>
      <p><?php echo esc_html(hypersanati_get_dimension_search_label($_GET)); ?></p>
      <button type="button" id="reset-dimension-search" class="new-btn-search">پاک کردن فیلتر ابعاد</button>
    </div>
  <?php else : ?>
    <h4>همه دسته محصولات</h4>
  <?php endif; ?>
  <hr class="sub-section-hr" />

  <div id="shop-container"></div>

  <div class="shop-loader">در حال بارگذاری...</div>

</div>

    </div>
<?php get_footer(); ?>