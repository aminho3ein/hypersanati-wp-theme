<?php
/*
Template Name: صفحه تماس با ما
*/

get_header();

$contact_address   = get_theme_mod( 'hypersanati_contact_address', 'تهران، خیابان سعدی جنوبی، خیابان اکباتان، کوچه ناظم الاطبا شمالی، پاساژ امام حسین، زیر همکف، پلاک 32 بلبرینگ همگام صنعت برتر' );
$contact_phone     = get_theme_mod( 'hypersanati_contact_phone', '۰۲۱-۳۳۹۸۹۹۳۰ - ۰۲۱-۳۳۹۸۹۹۴۰' );
$contact_fax       = get_theme_mod( 'hypersanati_contact_fax', '۰۲۱-۳۳۹۸۹۹۴۰' );
$contact_email     = get_theme_mod( 'hypersanati_contact_email', 'info@hamgamsanatbartar.com' );
$contact_terms_url = get_theme_mod( 'hypersanati_contact_terms_url', '#' );

$contact_status = isset( $_GET['contact_status'] ) ? sanitize_key( wp_unslash( $_GET['contact_status'] ) ) : '';
?>

<section class="contact-us-section">
  <div class="container">
    <div class="contact-us-layout">

      <aside class="contact-info-panel">

        <div class="contact-info-item">
          <div class="contact-info-icon" aria-hidden="true">
            <svg class="contact-info-icon-svg" viewBox="0 0 24 24" fill="none">
              <path d="M12 21C12 21 5 14.75 5 9.5A7 7 0 1 1 19 9.5C19 14.75 12 21 12 21Z" stroke="currentColor" stroke-width="1.7"/>
              <circle cx="12" cy="9.5" r="2.5" stroke="currentColor" stroke-width="1.7"/>
            </svg>
          </div>
          <div class="contact-info-content">
            <h3 class="contact-info-title">آدرس فروشگاه</h3>
            <p class="contact-info-text">
              <?php echo esc_html( $contact_address ); ?>
            </p>
          </div>
        </div>

        <div class="contact-info-divider"></div>

        <div class="contact-info-item">
          <div class="contact-info-icon" aria-hidden="true">
            <svg class="contact-info-icon-svg" viewBox="0 0 24 24" fill="none">
              <path d="M7.5 4.5H5.8C5.14 4.5 4.57 4.96 4.44 5.61C3.91 8.31 4.41 11.11 5.86 13.5C7.19 15.71 9.09 17.61 11.3 18.94C13.69 20.39 16.49 20.89 19.19 20.36C19.84 20.23 20.3 19.66 20.3 19V17.3C20.3 16.8 19.96 16.37 19.48 16.25L15.9 15.36C15.48 15.26 15.04 15.37 14.72 15.66L13.4 16.86C10.94 15.65 9.15 13.86 7.94 11.4L9.14 10.08C9.43 9.76 9.54 9.32 9.44 8.9L8.55 5.32C8.43 4.84 8 4.5 7.5 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="contact-info-content">
            <h3 class="contact-info-title">شماره تماس</h3>
            <p class="contact-info-text">
              <?php echo esc_html( $contact_phone ); ?>
            </p>
          </div>
        </div>

        <div class="contact-info-divider"></div>

        <div class="contact-info-item">
          <div class="contact-info-icon" aria-hidden="true">
            <svg class="contact-info-icon-svg" viewBox="0 0 24 24" fill="none">
              <path d="M7 8V4H17V8" stroke="currentColor" stroke-width="1.7"/>
              <path d="M6 18H18V12H6V18Z" stroke="currentColor" stroke-width="1.7"/>
              <path d="M4 8H20V14H18" stroke="currentColor" stroke-width="1.7"/>
              <path d="M9 15H15" stroke="currentColor" stroke-width="1.7"/>
            </svg>
          </div>
          <div class="contact-info-content">
            <h3 class="contact-info-title">فکس</h3>
            <p class="contact-info-text">
              <?php echo esc_html( $contact_fax ); ?>
            </p>
          </div>
        </div>

        <div class="contact-info-divider"></div>

        <div class="contact-info-item">
          <div class="contact-info-icon" aria-hidden="true">
            <svg class="contact-info-icon-svg" viewBox="0 0 24 24" fill="none">
              <rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.7"/>
              <path d="M4 7L12 13L20 7" stroke="currentColor" stroke-width="1.7"/>
            </svg>
          </div>
          <div class="contact-info-content">
            <h3 class="contact-info-title">ایمیل ما</h3>
            <p class="contact-info-text">
              <?php echo esc_html( $contact_email ); ?>
            </p>
          </div>
        </div>

      </aside>

      <div class="contact-form-panel">
        <h2 class="contact-form-heading">ارتباط با ما</h2>

        <?php if ( 'success' === $contact_status ) : ?>
          <div class="contact-form-alert contact-form-alert-success">
            پیام شما با موفقیت ارسال شد.
          </div>
        <?php elseif ( ! empty( $contact_status ) ) : ?>
          <div class="contact-form-alert contact-form-alert-error">
            ارسال پیام با خطا مواجه شد. لطفاً فیلدهای الزامی را بررسی کنید.
          </div>
        <?php endif; ?>

        <form class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data">

          <input type="hidden" name="action" value="hypersanati_contact_form">
          <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
          <?php wp_nonce_field( 'hypersanati_contact_form_action', 'hypersanati_contact_nonce' ); ?>

          <div class="contact-form-row">
            <label for="contact-subject" class="contact-label">موضوع</label>
            <div class="contact-field-wrap">
              <input
                type="text"
                id="contact-subject"
                name="contact_subject"
                class="contact-input"
                placeholder="عنوان پیام را وارد کنید"
                required
              >
            </div>
          </div>

          <div class="contact-form-row">
            <label for="contact-phone" class="contact-label">شماره همراه</label>
            <div class="contact-field-wrap">
              <div class="phone-field-group">
                <input
                  type="tel"
                  id="contact-phone"
                  name="contact_phone"
                  class="contact-input phone-number-input"
                  placeholder="شماره تماس را وارد کنید"
                  required
                >

                <select name="country_code" class="contact-select country-code-select">
                  <option value="+98">+98</option>
                  <option value="+971">+971</option>
                  <option value="+90">+90</option>
                  <option value="+1">+1</option>
                  <option value="+44">+44</option>
                </select>
              </div>
            </div>
          </div>

          <div class="contact-form-row">
            <label class="contact-label">افزودن فایل</label>
            <div class="contact-field-wrap">
              <div class="file-upload-group">

                <input
                  type="text"
                  class="contact-input file-name-display"
                  id="file-name-display"
                  placeholder="فایلی انتخاب نشده است"
                  readonly
                >

                <label for="contact-file" class="file-upload-btn">انتخاب فایل</label>
                <input
                  type="file"
                  id="contact-file"
                  name="contact_attachment"
                  class="contact-file-input"
                  accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                >
              </div>
            </div>
          </div>

          <div class="contact-form-row contact-form-row-textarea">
            <label for="contact-message" class="contact-label">پیام شما</label>
            <div class="contact-field-wrap">
              <textarea
                id="contact-message"
                name="contact_message"
                class="contact-textarea"
                placeholder="پیام خود را بنویسید"
                required
              ></textarea>
            </div>
          </div>

          <div class="contact-form-agreement">
            <label class="agreement-label">
              <input type="checkbox" name="terms" class="agreement-checkbox" required>
              <span class="agreement-custom-check"></span>
              <span class="agreement-text">
                قوانین و مقررات را مطالعه نموده و می‌پذیرم.
                <a href="<?php echo esc_url( $contact_terms_url ); ?>" class="agreement-link">
                  <span class="agreement-eye-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M2 12C3.8 8.5 7.3 6 12 6C16.7 6 20.2 8.5 22 12C20.2 15.5 16.7 18 12 18C7.3 18 3.8 15.5 2 12Z" stroke="currentColor" stroke-width="1.7"/>
                      <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7"/>
                    </svg>
                  </span>
                  مشاهده قوانین
                </a>
              </span>
            </label>
          </div>

          <div class="contact-form-submit">
            <button type="submit" class="contact-submit-btn">فرستادن</button>
          </div>

        </form>
      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>