<?php get_header(); ?>


    <!-- search-feat -->
    <div class="search-area">
      <form class="search-input" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
        <div class="search-input">
          <h5>عنوان محصول</h5>
          <div class="big-input-division">
            <input 
              type="search" 
              name="s" 
              placeholder="مثلا بلبرینگ تماس زاویه ای"
              value="<?php echo get_search_query(); ?>" 
            />
            <input type="hidden" name="post_type" value="product" />
            <button class="btn" type="submit">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
        </div>
      </form>


      <div class="accurate-search-input">
        <div class="search-text">
          <p>اگر اندازه ی دقیق قطعه مورد نظرتان را می دانید درمقابل بنویسید.</p>
          <p id="mm-paragraph">اندازه دقیق (میلی متر mm)</p>
        </div>
        <div class="search-inputs">
          <div class="accurate-inputs">
            <h6>قطر داخلی</h6>
            <input type="search" placeholder="مثلا 20" />
          </div>
          <div class="accurate-inputs">
            <h6>قطر خارجی</h6>
            <input type="search" placeholder="مثلا 20" />
          </div>
          <div class="accurate-inputs">
            <h6>ارتفاع</h6>
            <input type="search" placeholder="مثلا 20" />
          </div>
        </div>
      </div>
      <div class="two-section-for-serch">
        <div class="approximate-stuff">
          <div class="approximate-search">
            <div class="approximate-paragraphs">
              <p>
                اگر اندازه ی دقیق قطعه مورد نظرتان را نمی دانید، می توانید در
                بخش زیر به صورت تقریبی بگویید.
              </p>
              <p id="mm-paragraph">اندازه تقریبی (میلی متر mm)</p>
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
          <div class="search-btn">
            <div class="mag-sec">
              <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="search-name"><p>جستجو</p></div>
          </div>
        </div>
      </div>
    </div>

    <!-- hero-section -->
    <div class="best-product-of-month">
      <div>
        <h3>محصولات ویژه</h3>
      </div>
      <div class="best-products-row">
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/01.png" alt="" />
            </div>
            <div class="best-product-detail">
              <p>یاتاقان غلتکی مخروطی</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/02.png" alt="" /></div>
            </div>
            <div class="best-product-detail">
              <p>بلبرینگ توپی چرخ</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/03.png" alt="" /></div>
            </div>
            <div class="best-product-detail">
              <p>یاتاقان غلتکی مخروطی</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/04.png" alt="" /></div>
            </div>
            <div class="best-product-detail">
              <p>بلبرینگ کلاچ</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="best-products-row">
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/05.png" alt="" />
            </div>
            <div class="best-product-detail">
              <p>بلبرینگ توپی چرخ کامیون</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/06.png" alt="" /></div>
            </div>
            <div class="best-product-detail">
              <p>بلبرینگ غلتکی خود تنظیم</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/07.png" alt="" /></div>
            </div>
            <div class="best-product-detail">
              <p>یاتاقان غلتکی استوانه ای</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="single-product-container">
          <div class="main-contains">
            <div class="best-product-container">
              <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/08.png" alt="" /></div>
            </div>
            <div class="best-product-detail">
              <p>بلبرینگ های خطی</p>
              <div class="icon-frame">
                <i class="fa-solid fa-arrow-left"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- news-blog-posts -->

    <div class="news-blog-sect"></div>

    <!-- brand-section -->

    <div class="brand-section">
      <h3>برند های بلبرینگ وارداتی</h3>
      <div class="brand-groups">
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (2).jpg" alt="" />
        </div>
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (5).png" alt="" />
        </div>
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (1).png" alt="" />
        </div>
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (3).jpg" alt="" />
        </div>
      </div>
      <div class="brand-groups">
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (1).jpg" alt="" />
        </div>
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (3).png" alt="" />
        </div>
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (2).png" alt="" />
        </div>
        <div class="brand-frame">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/brand (4).png" alt="" />
        </div>
      </div>
    </div>

    <!-- new-post-cards-section -->

    <div class="card-boxes">
      <div class="name-and-controll-section">
    <div>
      <button class="handle-frame-section" id="scrollRightBtn" type="button">
        <i class="fa-solid fa-angle-right"></i>
      </button>
    </div>
        <div><h3>آخرین به روز رسانی وبلاگ</h3></div>
        <div>
          <button class="handle-frame-section" id="scrollLeftBtn" type="button">
            <i class="fa-solid fa-angle-left"></i>
          </button>
        </div>
      </div>
      <div class="cards-sectoins" id="blogScroll">
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/90d9043dc033721bcc40dd40e24d3e4b.webp" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              راهنمای جامع انتخاب محصولات صنعتی بر اساس استانداردهای
              بین‌المللی(ISO, DIN, ASTM)
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/01.png" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              تحلیل فنی و اقتصادی مقایسه محصولات صنعتی داخلی و وارداتی در صنایع
              سنگین
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/05.png" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              راهنمای جامع انتخاب محصولات صنعتی بر اساس استانداردهای
              بین‌المللی(ISO, DIN, ASTM)
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/07.png" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              راهنمای جامع انتخاب محصولات صنعتی بر اساس استانداردهای
              بین‌المللی(ISO, DIN, ASTM)
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/90d9043dc033721bcc40dd40e24d3e4b.webp" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              راهنمای جامع انتخاب محصولات صنعتی بر اساس استانداردهای
              بین‌المللی(ISO, DIN, ASTM)
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/90d9043dc033721bcc40dd40e24d3e4b.webp" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              راهنمای جامع انتخاب محصولات صنعتی بر اساس استانداردهای
              بین‌المللی(ISO, DIN, ASTM)
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
        <div class="blog-card">
          <div class="blog-card-img-frame">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/90d9043dc033721bcc40dd40e24d3e4b.webp" alt="" />
          </div>
          <div class="blog-card-title">
            <h6>
              راهنمای جامع انتخاب محصولات صنعتی بر اساس استانداردهای
              بین‌المللی(ISO, DIN, ASTM)
            </h6>
          </div>
          <div class="blog-card-description">
            <p>
              لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
              استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله
              در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد
              نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد. ...
            </p>
          </div>
          <div class="blog-card-detailes">
            <div class="blog-detail-sect">
              <div class="time-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar 3.svg" alt="" />
                <p>13 آبان</p>
              </div>
              <div class="auther-frame">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="" />
                <p>امین حسینی</p>
              </div>
            </div>
            <div class="rea-more-frame">
              <button class="read-more-btn">مطالعه</button>
            </div>
          </div>
        </div>
      </div>
    </div>


    <?php get_footer(); ?>