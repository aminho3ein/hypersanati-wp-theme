<?php
$footer_settings = function_exists('hypersanati_get_footer_settings')
    ? hypersanati_get_footer_settings()
    : array();

$footer_url = static function ($url) {
    $url = trim((string) $url);

    if ('' === $url) {
        return '';
    }

    if (0 === strpos($url, '/')) {
        return home_url($url);
    }

    return $url;
};

$footer_phone_href = static function ($phone) {
    return preg_replace('/[^0-9+]/', '', (string) $phone);
};

$contact_address = trim((string) ($footer_settings['contact_address'] ?? ''));
$contact_phone_1 = trim((string) ($footer_settings['contact_phone_1'] ?? ''));
$contact_phone_2 = trim((string) ($footer_settings['contact_phone_2'] ?? ''));
$contact_email   = trim((string) ($footer_settings['contact_email'] ?? ''));

$terms_url = $footer_url($footer_settings['terms_url'] ?? '');
$privacy_url = $footer_url($footer_settings['privacy_url'] ?? '');
$developer_url = $footer_url($footer_settings['developer_url'] ?? '');

$footer_links = array(
    array(
        'title' => 'فروشگاه',
        'url'   => home_url('/shop/'),
    ),
    array(
        'title' => 'درباره ما',
        'url'   => home_url('/about-us/'),
    ),
    array(
        'title' => 'تماس با ما',
        'url'   => home_url('/contact-us/'),
    ),
    array(
        'title' => 'حساب کاربری',
        'url'   => home_url('/my-account/'),
    ),
);
?>

<footer class="site-footer my-foot">

  <section class="site-footer__services">
    <div class="container">
      <div class="site-footer__services-grid">

        <article class="site-footer__service-card">
          <span class="site-footer__service-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 16.5V9.5L12 5L20 9.5V16.5L12 21L4 16.5Z" stroke="currentColor" stroke-width="1.6"/>
              <path d="M12 12L20 7.5M12 12L4 7.5M12 12V21" stroke="currentColor" stroke-width="1.6"/>
            </svg>
          </span>

          <div>
            <strong><?php echo esc_html($footer_settings['service_1_title'] ?? 'تأمین تخصصی قطعات صنعتی'); ?></strong>
            <p><?php echo esc_html($footer_settings['service_1_text'] ?? 'انتخاب تخصصی متناسب با نیاز صنعتی شما'); ?></p>
          </div>
        </article>

        <article class="site-footer__service-card">
          <span class="site-footer__service-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <circle cx="10.5" cy="10.5" r="5.5" stroke="currentColor" stroke-width="1.6"/>
              <path d="M14.5 14.5L20 20" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              <path d="M8 10.5H13M10.5 8V13" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </span>

          <div>
            <strong><?php echo esc_html($footer_settings['service_2_title'] ?? 'استعلام قیمت و موجودی'); ?></strong>
            <p><?php echo esc_html($footer_settings['service_2_text'] ?? 'بررسی قیمت و موجودی توسط واحد فروش'); ?></p>
          </div>
        </article>

        <article class="site-footer__service-card">
          <span class="site-footer__service-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M7 3.5H15L19 7.5V20.5H7V3.5Z" stroke="currentColor" stroke-width="1.6"/>
              <path d="M15 3.5V8H19M10 12H16M10 15.5H16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </span>

          <div>
            <strong><?php echo esc_html($footer_settings['service_3_title'] ?? 'پیش‌فاکتور رسمی'); ?></strong>
            <p><?php echo esc_html($footer_settings['service_3_text'] ?? 'ثبت و پیگیری پیش‌فاکتور از حساب کاربری'); ?></p>
          </div>
        </article>

        <article class="site-footer__service-card">
          <span class="site-footer__service-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M5 13V11A7 7 0 0 1 19 11V13" stroke="currentColor" stroke-width="1.6"/>
              <path d="M5 12H7.5V17H5.5C4.67 17 4 16.33 4 15.5V13.5C4 12.67 4.67 12 5.5 12Z" stroke="currentColor" stroke-width="1.6"/>
              <path d="M19 12H16.5V17H18.5C19.33 17 20 16.33 20 15.5V13.5C20 12.67 19.33 12 18.5 12Z" stroke="currentColor" stroke-width="1.6"/>
              <path d="M16.5 17C16 19 14.5 20 12 20" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </span>

          <div>
            <strong><?php echo esc_html($footer_settings['service_4_title'] ?? 'پشتیبانی فروش'); ?></strong>
            <p><?php echo esc_html($footer_settings['service_4_text'] ?? 'همراهی در انتخاب و تأمین محصول'); ?></p>
          </div>
        </article>

      </div>
    </div>
  </section>

  <section class="site-footer__main">
    <div class="container">

      <div class="site-footer__top-grid">

        <section class="site-footer__brand">

          <div class="site-footer__brand-head">
            <a class="site-footer__logo-box" href="<?php echo esc_url(home_url('/')); ?>">
              <img
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/main-logo/logo.jpg'); ?>"
                alt="<?php echo esc_attr($footer_settings['intro_title'] ?? 'هایپر صنعتی الفت'); ?>"
              >
            </a>

            <div>
              <h2>
                <?php echo esc_html($footer_settings['intro_title'] ?? 'هایپر صنعتی الفت'); ?>
              </h2>

              <span class="site-footer__brand-label">
                تأمین تخصصی قطعات صنعتی
              </span>
            </div>
          </div>

          <p class="site-footer__description">
            <?php echo esc_html(
                $footer_settings['intro_text']
                ?? 'تأمین تخصصی بلبرینگ، رولبرینگ و قطعات صنعتی با امکان استعلام قیمت و دریافت پیش‌فاکتور.'
            ); ?>
          </p>

          <div class="site-footer__cta">
            <div>
              <strong>استعلام قیمت و موجودی</strong>

              <p>
                محصولات موردنظر را به پیش‌فاکتور اضافه کنید تا قیمت،
                موجودی و شرایط تأمین توسط واحد فروش بررسی شود.
              </p>
            </div>

            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/cart/')); ?>">
              مشاهده پیش‌فاکتور
            </a>
          </div>

        </section>


        <section class="site-footer__contact-card">

          <div class="site-footer__contact-header">
            <span>تماس با ما</span>
            <small>واحد فروش و پشتیبانی</small>
          </div>

          <div class="site-footer__contact-body">

            <?php if ('' !== $contact_phone_1 || '' !== $contact_phone_2) : ?>
              <div class="site-footer__contact-row">

                <span class="site-footer__contact-label">
                  تلفن
                </span>

                <div class="site-footer__contact-value site-footer__phones">

                  <span class="site-footer__phone-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path
                        d="M7.5 4.5H5.8C5.14 4.5 4.57 4.96 4.44 5.61C3.91 8.31 4.41 11.11 5.86 13.5C7.19 15.71 9.09 17.61 11.3 18.94C13.69 20.39 16.49 20.89 19.19 20.36C19.84 20.23 20.3 19.66 20.3 19V17.3C20.3 16.8 19.96 16.37 19.48 16.25L15.9 15.36C15.48 15.26 15.04 15.37 14.72 15.66L13.4 16.86C10.94 15.65 9.15 13.86 7.94 11.4L9.14 10.08C9.43 9.76 9.54 9.32 9.44 8.9L8.55 5.32C8.43 4.84 8 4.5 7.5 4.5Z"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linejoin="round"
                      />
                    </svg>
                  </span>

                  <?php if ('' !== $contact_phone_1) : ?>
                    <a
                      dir="ltr"
                      href="tel:<?php echo esc_attr($footer_phone_href($contact_phone_1)); ?>"
                    >
                      <?php echo esc_html($contact_phone_1); ?>
                    </a>
                  <?php endif; ?>

                  <?php if ('' !== $contact_phone_1 && '' !== $contact_phone_2) : ?>
                    <span class="site-footer__phone-separator" aria-hidden="true">|</span>
                  <?php endif; ?>

                  <?php if ('' !== $contact_phone_2) : ?>
                    <a
                      dir="ltr"
                      href="tel:<?php echo esc_attr($footer_phone_href($contact_phone_2)); ?>"
                    >
                      <?php echo esc_html($contact_phone_2); ?>
                    </a>
                  <?php endif; ?>

                </div>
              </div>
            <?php endif; ?>


            <?php if ('' !== $contact_email) : ?>
              <div class="site-footer__contact-row">

                <span class="site-footer__contact-label">
                  ایمیل
                </span>

                <a
                  class="site-footer__contact-value"
                  dir="ltr"
                  href="mailto:<?php echo esc_attr($contact_email); ?>"
                >
                  <?php echo esc_html($contact_email); ?>
                </a>

              </div>
            <?php endif; ?>


            <?php if ('' !== $contact_address) : ?>
              <div class="site-footer__contact-row site-footer__contact-row--address">

                <span class="site-footer__contact-label">
                  آدرس
                </span>

                <p class="site-footer__contact-value">
                  <?php echo esc_html($contact_address); ?>
                </p>

              </div>
            <?php endif; ?>

          </div>

        </section>

      </div>


      <nav
        class="site-footer__navigation"
        aria-label="خدمات و دسترسی"
      >

        <div class="site-footer__navigation-head">
          <h3>خدمات و دسترسی</h3>
        </div>

        <ul>

          <?php foreach ($footer_links as $item) : ?>
            <li>
              <a href="<?php echo esc_url($item['url']); ?>">

                <span class="site-footer__link-text">
                  <?php echo esc_html($item['title']); ?>
                </span>

                <span class="site-footer__link-icon" aria-hidden="true">
                  ←
                </span>

              </a>
            </li>
          <?php endforeach; ?>

        </ul>

      </nav>

    </div>
  </section>

  <div class="site-footer__bottom bottom-of-footer">
    <div class="container site-footer__bottom-layout">

      <p>
        <?php echo esc_html(
            $footer_settings['copyright_text']
            ?? 'کلیه حقوق برای هایپر صنعتی الفت محفوظ است.'
        ); ?>
      </p>

      <div class="site-footer__bottom-links">
        <?php if ('' !== $terms_url) : ?>
          <a href="<?php echo esc_url($terms_url); ?>">
            <?php echo esc_html($footer_settings['terms_title'] ?? 'قوانین و مقررات'); ?>
          </a>
        <?php endif; ?>

        <?php if ('' !== $privacy_url) : ?>
          <a href="<?php echo esc_url($privacy_url); ?>">
            <?php echo esc_html($footer_settings['privacy_title'] ?? 'حریم خصوصی'); ?>
          </a>
        <?php endif; ?>
      </div>

      <?php if (!empty($footer_settings['developer_text'])) : ?>
        <p class="site-footer__developer">
          <?php if ('' !== $developer_url) : ?>
            <a href="<?php echo esc_url($developer_url); ?>">
              <?php echo esc_html($footer_settings['developer_text']); ?>
            </a>
          <?php else : ?>
            <?php echo esc_html($footer_settings['developer_text']); ?>
          <?php endif; ?>
        </p>
      <?php endif; ?>

    </div>
  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
