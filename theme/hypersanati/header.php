
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

<?php
$hsb_order_tracking_url = function_exists(
    'wc_get_account_endpoint_url'
)
    ? wc_get_account_endpoint_url('orders')
    : '';

if (!$hsb_order_tracking_url) {

    $hsb_order_tracking_url = function_exists(
        'wc_get_page_permalink'
    )
        ? wc_get_page_permalink('myaccount')
        : wp_login_url();
}

if (!$hsb_order_tracking_url) {
    $hsb_order_tracking_url = wp_login_url();
}
?>

<!-- header -->
<div class="header">
  <div class="top-of-nav">

    <div class="hsb-top-nav-inner">

      <a href="#">
        ارسال ۷ الی ۱۰ روزکاری
      </a>

      <a
        href="<?php echo esc_url(
            $hsb_order_tracking_url
        ); ?>"
      >
        پیگیری سفارش
      </a>

    </div>

  </div>

  <div class="my-navbar">

    <div class="hsb-navbar-inner">
    <!-- Site Logo / Brand -->
    <div class="navbar-brand">

      <?php if (has_custom_logo()) : ?>

        <?php the_custom_logo(); ?>

      <?php else : ?>

        <a
          class="hsb-navbar-brand-fallback"
          href="<?php echo esc_url(home_url('/')); ?>"
        >
          <span>
            <?php echo esc_html(
                get_bloginfo('name')
                ?: 'هایپرصنعتی الفت'
            ); ?>
          </span>
        </a>

      <?php endif; ?>

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

    if (function_exists('hsb_get_preinvoice_items')) {
        $preinvoice_items = hsb_get_preinvoice_items();

        foreach ($preinvoice_items as $preinvoice_item) {
            $cart_count += isset($preinvoice_item['quantity'])
                ? absint($preinvoice_item['quantity'])
                : 0;
        }
    }

    $cart_url = function_exists('wc_get_cart_url')
        ? wc_get_cart_url()
        : '#';

    $preinvoice_cart_url = $cart_url;

    $account_url = function_exists('wc_get_page_permalink')
        ? wc_get_page_permalink('myaccount')
        : wp_login_url();

    if (!$account_url) {
        $account_url = wp_login_url();
    }

    $profile_url = function_exists(
        'wc_get_account_endpoint_url'
    )
        ? wc_get_account_endpoint_url(
            'edit-account'
        )
        : $account_url;

    $preinvoices_url = add_query_arg(
        'dashboard_tab',
        'preinvoices',
        $account_url
    ) . '#tab-preinvoices';

    $logout_url = function_exists(
        'wc_logout_url'
    )
        ? wc_logout_url($account_url)
        : wp_logout_url($account_url);
    ?>

    <!-- Desktop nav actions -->
    <ul class="nav-actions">

      <div class="cart preinvoice-cart">
        <a
          class="preinvoice-cart-link"
          href="<?php echo esc_url($preinvoice_cart_url); ?>"
          aria-label="پیش‌فاکتور من"
          title="پیش‌فاکتور من"
        >
          <i class="fa-solid fa-cart-shopping"></i>

          <span
            class="preinvoice-count-badge<?php echo $cart_count > 0 ? '' : ' is-empty'; ?>"
            aria-label="<?php echo esc_attr($cart_count); ?> قلم در پیش‌فاکتور"
          >
            <?php echo esc_html(number_format_i18n($cart_count)); ?>
          </span>
        </a>
      </div>

      <li>
        <?php if (is_user_logged_in()) : ?>

          <div class="hsb-account-menu">

            <a
              class="ui-btn ui-btn-account hsb-account-link hsb-account-icon"
              href="<?php echo esc_url($account_url); ?>"
              aria-label="حساب کاربری"
              title="حساب کاربری"
            >
              <i class="fa-solid fa-user"></i>
            </a>

            <div class="hsb-account-dropdown">

              <a href="<?php echo esc_url($profile_url); ?>">
                <i class="fa-regular fa-user"></i>
                <span>مشاهده پروفایل</span>
              </a>

              <a href="<?php echo esc_url($preinvoices_url); ?>">
                <i class="fa-solid fa-file-invoice"></i>
                <span>پیش‌فاکتورهای من</span>
              </a>

              <a
                class="hsb-account-dropdown__logout"
                href="<?php echo esc_url($logout_url); ?>"
              >
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>خروج از حساب</span>
              </a>

            </div>

          </div>

        <?php else : ?>

          <button
            class="ui-btn ui-btn-account hsb-account-icon"
            type="button"
            id="ui-open-otp"
            aria-label="ورود به حساب کاربری"
            title="ورود به حساب کاربری"
          >
            <i class="fa-solid fa-user"></i>
          </button>

        <?php endif; ?>
      </li>

    </ul>

    <!-- Hamburger button mobile only -->
    <div class="menu-and-cart">
      <ul class="mobile-nav-actions">

        <div class="cart preinvoice-cart">
          <a
            class="preinvoice-cart-link"
            href="<?php echo esc_url($preinvoice_cart_url); ?>"
            aria-label="پیش‌فاکتور من"
            title="پیش‌فاکتور من"
          >
            <i class="fa-solid fa-cart-shopping"></i>

            <span
              class="preinvoice-count-badge<?php echo $cart_count > 0 ? '' : ' is-empty'; ?>"
            >
              <?php echo esc_html(number_format_i18n($cart_count)); ?>
            </span>
          </a>
        </div>

        <li>
          <a
            class="my-profile hsb-account-icon"
            href="<?php echo esc_url($account_url); ?>"
            aria-label="حساب کاربری"
            title="حساب کاربری"
          >
            <i class="fa-solid fa-user"></i>
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

  </div>

  <!-- Mobile Drawer Menu -->
  <div
    class="mobile-menu"
    id="mobileMenu"
    aria-label="منوی موبایل"
  >

    <div class="hsb-mobile-menu__header">

      <div class="hsb-mobile-menu__brand">

        <?php if (has_custom_logo()) : ?>

          <?php the_custom_logo(); ?>

        <?php else : ?>

          <a
            class="hsb-mobile-menu__brand-fallback"
            href="<?php echo esc_url(home_url('/')); ?>"
          >
            <?php echo esc_html(
                get_bloginfo('name')
                ?: 'هایپرصنعتی الفت'
            ); ?>
          </a>

        <?php endif; ?>

      </div>

      <button
        class="hsb-mobile-menu__close"
        type="button"
        aria-label="بستن منو"
        title="بستن منو"
        onclick="closeMobileMenu()"
      >
        <i
          class="fa-solid fa-xmark"
          aria-hidden="true"
        ></i>
      </button>

    </div>

    <div class="hsb-mobile-menu__body">

    <?php
    $mobile_account_class =
        function_exists('is_account_page') &&
        is_account_page()
            ? ' class="current-menu-item"'
            : '';

    $mobile_account_item =
        '<li' .
        $mobile_account_class .
        '><a href="' .
        esc_url($account_url) .
        '" onclick="closeMobileMenu()">حساب کاربری</a></li>';

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

  </div>

  <div
    class="mobile-overlay"
    id="mobileOverlay"
    onclick="closeMobileMenu()"
  ></div>
</div>
