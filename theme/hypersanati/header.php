
<!doctype html>
<html lang="fa">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />

    <title>همگام صنعت برتر</title>

    <?php wp_head(); ?>

  </head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- header -->
<div class="header">
  <div class="top-of-nav">
    <a href="#">ارسال ۷ الی ۱۰ روزکاری</a>
    <a href="#">پیگیری سفارش</a>
  </div>

  <div class="my-navbar">
    <!-- Logo / Brand mobile -->
    <div class="navbar-brand">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <span>هایپرصنعتی الفت</span>
      </a>
    </div>

    <!-- Desktop nav links -->
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary_menu',
        'container'      => false,
        'menu_class'     => 'nav-links',
        'fallback_cb'    => false,
        'depth'          => 1,
    ));
    ?>

    <?php
    $cart_count = 0;

    if (function_exists('WC') && WC()->cart) {
        $cart_count = WC()->cart->get_cart_contents_count();
    }

    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#';

    $account_url = function_exists('wc_get_page_permalink')
        ? wc_get_page_permalink('myaccount')
        : wp_login_url();

    if (!$account_url) {
        $account_url = wp_login_url();
    }
    ?>

    <!-- Desktop nav actions -->
    <ul class="nav-actions">
      <div class="sell-number">
        <p><?php echo esc_html(number_format_i18n($cart_count)); ?></p>
      </div>

      <div class="cart">
        <a href="<?php echo esc_url($cart_url); ?>">
          <i class="fa-solid fa-cart-shopping"></i>
        </a>
      </div>

      <li>
        <button class="ui-btn ui-btn-account " type="button" id="ui-open-otp">
           حساب کاربری
        </button>
        <!-- <a class="my-profile" href="<?php //echo esc_url($account_url); ?>">
          حساب کاربری
        </a> -->
      </li>
    </ul>

    <!-- Hamburger button mobile only -->
    <div class="menu-and-cart">
      <ul class="mobile-nav-actions">
        <div class="sell-number">
          <p><?php echo esc_html(number_format_i18n($cart_count)); ?></p>
        </div>

        <div class="cart">
          <a href="<?php echo esc_url($cart_url); ?>">
            <i class="fa-solid fa-cart-shopping"></i>
          </a>
        </div>

        <li>
          <a class="my-profile" href="<?php echo esc_url($account_url); ?>">
            حساب کاربری
          </a>
        </li>
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
    <?php
    $mobile_account_item = '<li><a href="' . esc_url($account_url) . '" onclick="closeMobileMenu()">حساب کاربری</a></li>';

    wp_nav_menu(array(
        'theme_location' => 'primary_menu',
        'container'      => false,
        'menu_class'     => 'mobile-nav-links',
        'fallback_cb'    => false,
        'depth'          => 1,
        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s' . $mobile_account_item . '</ul>',
    ));
    ?>
  </div>

  <div
    class="mobile-overlay"
    id="mobileOverlay"
    onclick="closeMobileMenu()"
  ></div>
</div>