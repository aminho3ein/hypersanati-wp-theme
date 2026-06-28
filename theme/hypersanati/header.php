
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="./css/bootstrap.rtl.min.css" />

    <link rel="stylesheet" href="./css/fontawesome.min.css" />
    <link rel="stylesheet" href="./css/brands.min.css" />
    <link rel="stylesheet" href="./css/solid.min.css" />

    <link rel="stylesheet" href="./css/main.css" />

    <link
      rel="stylesheet"
      href="./css/responsive/tablet-responsive.css"
      media="(min-width:768px)"
    />

    <link
      rel="stylesheet"
      href="./css/responsive/laptop-responsive.css"
      media="(min-width:992px)"
    />

    <link
      rel="stylesheet"
      href="./css/responsive/desktop-responsive.css"
      media="(min-width:1200px)"
    />

    <title>همگام صنعت برتر</title>

    <?php wp_head(); ?>

  </head>

  <body <?php body_class(); ?>>
  <body>
    <!-- header -->
    <div class="header">
      <div class="top-of-nav">
        <a href="#">ارسال ۷ الی ۱۰ روزکاری</a>
        <a href="#">پیگیری سفارش</a>
      </div>
      <div class="my-navbar">
        <!-- Logo / Brand (mobile) -->
        <div class="navbar-brand">
          <span>هایپرصنعتی الفت</span>
        </div>

        <!-- Desktop nav links -->
        <ul class="nav-links">
          <li><a class="home-btn" href="#">صفحه اصلی</a></li>
          <li><a href="#">فروشگاه</a></li>
          <li><a href="#">مجله</a></li>
          <li><a href="#">درباره ما</a></li>
          <li><a class="contact-btn" href="#">ارتباط با ما</a></li>
        </ul>

        <!-- Desktop nav actions -->
        <ul class="nav-actions">
          <div class="sell-number"><p>۷</p></div>
          <div class="cart">
            <i class="fa-solid fa-cart-shopping"></i>
          </div>
          <li><a class="my-profile" href="#">حساب کاربری</a></li>
        </ul>

        <!-- Hamburger button (mobile only) -->
        <div class="menu-and-cart">
          <ul class="mobile-nav-actions">
            <div class="sell-number"><p>۷</p></div>
            <div class="cart">
              <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <li><a class="my-profile" href="#">حساب کاربری</a></li>
          </ul>
          <button
            class="hamburger"
            id="hamburgerBtn"
            aria-label="منو"
            aria-expanded="false"
          >
            <span class="ham-line"></span>
            <span class="ham-line"></span>
            <span class="ham-line"></span>
          </button>
        </div>
      </div>

      <!-- Mobile Drawer Menu -->
      <div class="mobile-menu" id="mobileMenu">
        <ul class="mobile-nav-links">
          <li><a href="#" onclick="closeMobileMenu()">صفحه اصلی</a></li>
          <li><a href="#" onclick="closeMobileMenu()">فروشگاه</a></li>
          <li><a href="#" onclick="closeMobileMenu()">مجله</a></li>
          <li><a href="#" onclick="closeMobileMenu()">درباره ما</a></li>
          <li><a href="#" onclick="closeMobileMenu()">ارتباط با ما</a></li>
          <li><a href="#" onclick="closeMobileMenu()">حساب کاربری</a></li>
        </ul>
      </div>
      <div
        class="mobile-overlay"
        id="mobileOverlay"
        onclick="closeMobileMenu()"
      ></div>
    </div>