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
        <h4 class="category-header">یک دسته محصول را انتخاب کنید</h4>
        <hr class="sub-section-hr" />

      <div class="category" id="sidebar-category"></div>

      </div>
<div class="col-8 products-section">

  <h4>همه دسته محصولات</h4>
  <hr class="sub-section-hr" />

  <div id="shop-container"></div>

  <div class="shop-loader">در حال بارگذاری...</div>

</div>

    </div>
<?php get_footer(); ?>