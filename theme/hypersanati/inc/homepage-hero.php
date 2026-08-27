<?php

defined('ABSPATH') || exit;

/* =========================================================
   HSB HOMEPAGE HERO
   ========================================================= */

function hsb_get_homepage_hero_defaults() {
    return array(
        'enabled'       => '0',
        'show_stats'    => '1',
        'eyebrow'       => 'مرجع تخصصی بلبرینگ و رولبرینگ صنعتی',
        'title'         => 'بلبرینگ موردنظرتان را دقیق‌تر و سریع‌تر پیدا کنید',
        'description'   => 'میان %PRODUCT_COUNT_LABEL% از برندها و سری‌های مختلف جستجو کنید. محصول را با پارت‌نامبر، نام، برند یا ابعاد فنی پیدا کنید و برای انتخاب دقیق‌تر از جستجوی ابعادی استفاده کنید.',
        'button_text'   => 'جستجوی محصول',
        'button_target' => '#hsb-home-search',
        'image_id'      => 0,
        'logo_image_id' => 0,
        'logo_target'   => '/shop/',
    );
}


function hsb_get_homepage_hero_options() {
    $saved = get_option(
        'hsb_homepage_hero_options',
        array()
    );

    if (!is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args(
        $saved,
        hsb_get_homepage_hero_defaults()
    );
}


function hsb_get_homepage_product_count() {
    $counts = wp_count_posts('product');

    if (
        !is_object($counts) ||
        !isset($counts->publish)
    ) {
        return 0;
    }

    return max(
        0,
        absint($counts->publish)
    );
}


function hsb_get_homepage_product_count_label(
    $count = null
) {
    if (null === $count) {
        $count =
            hsb_get_homepage_product_count();
    }

    $count = max(
        0,
        absint($count)
    );

    if ($count >= 1000) {
        $rounded =
            ((int) floor($count / 1000)) * 1000;

        $prefix =
            $count > $rounded
                ? 'بیش از '
                : '';

        return
            $prefix .
            number_format_i18n($rounded) .
            ' محصول';
    }

    if ($count > 0) {
        return
            number_format_i18n($count) .
            ' محصول';
    }

    return 'هزاران محصول صنعتی';
}


function hsb_sanitize_homepage_hero_options(
    $input
) {
    $defaults =
        hsb_get_homepage_hero_defaults();

    if (!is_array($input)) {
        return $defaults;
    }

    $output = array();

    $output['enabled'] =
        !empty($input['enabled'])
            ? '1'
            : '0';

    $output['show_stats'] =
        !empty($input['show_stats'])
            ? '1'
            : '0';

    $output['image_id'] =
        isset($input['image_id'])
            ? absint($input['image_id'])
            : 0;

        $output['logo_image_id'] =
        isset($input['logo_image_id'])
            ? absint($input['logo_image_id'])
            : 0;

$output['eyebrow'] =
        isset($input['eyebrow'])
            ? sanitize_text_field(
                wp_unslash(
                    $input['eyebrow']
                )
            )
            : $defaults['eyebrow'];

    $output['title'] =
        isset($input['title'])
            ? sanitize_text_field(
                wp_unslash(
                    $input['title']
                )
            )
            : $defaults['title'];

    $output['description'] =
        isset($input['description'])
            ? sanitize_textarea_field(
                wp_unslash(
                    $input['description']
                )
            )
            : $defaults['description'];

    $output['button_text'] =
        isset($input['button_text'])
            ? sanitize_text_field(
                wp_unslash(
                    $input['button_text']
                )
            )
            : $defaults['button_text'];

    $target =
        isset($input['button_target'])
            ? trim(
                wp_unslash(
                    $input['button_target']
                )
            )
            : $defaults['button_target'];

    if (str_starts_with($target, '#')) {
        $anchor =
            sanitize_html_class(
                ltrim($target, '#')
            );

        $output['button_target'] =
            $anchor
                ? '#' . $anchor
                : '#hsb-home-search';
    } else {
        $output['button_target'] =
            esc_url_raw($target);
    }

    if (!$output['button_target']) {
        $output['button_target'] =
            '#hsb-home-search';
    }

    $logo_target =
        isset($input['logo_target'])
            ? trim(
                wp_unslash(
                    $input['logo_target']
                )
            )
            : $defaults['logo_target'];

    $output['logo_target'] =
        '' !== $logo_target
            ? esc_url_raw($logo_target)
            : '/shop/';

    return $output;
}


add_action(
    'admin_init',
    function () {
        register_setting(
            'hsb_homepage_hero_group',
            'hsb_homepage_hero_options',
            array(
                'type'              => 'array',
                'sanitize_callback' =>
                    'hsb_sanitize_homepage_hero_options',
                'default' =>
                    hsb_get_homepage_hero_defaults(),
            )
        );
    }
);


add_action(
    'admin_menu',
    function () {
        add_theme_page(
            'هیرو صفحه اصلی',
            'هیرو صفحه اصلی',
            'manage_options',
            'hsb-homepage-hero',
            'hsb_render_homepage_hero_admin_page'
        );
    }
);


add_action(
    'admin_enqueue_scripts',
    function ($hook_suffix) {
        if (
            'appearance_page_hsb-homepage-hero'
            !== $hook_suffix
        ) {
            return;
        }

        wp_enqueue_media();
    }
);


function hsb_render_homepage_hero_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $options =
        hsb_get_homepage_hero_options();

    $image_url = '';

    if (!empty($options['image_id'])) {
        $image_url =
            wp_get_attachment_image_url(
                absint($options['image_id']),
                'large'
            );
    }

    $logo_image_url = '';

    if (!empty($options['logo_image_id'])) {
        $logo_image_url =
            wp_get_attachment_image_url(
                absint($options['logo_image_id']),
                'medium'
            );
    }

    $product_count =
        hsb_get_homepage_product_count();

    $product_label =
        hsb_get_homepage_product_count_label(
            $product_count
        );
    ?>
    <div class="wrap">
        <h1>هیرو صفحه اصلی</h1>

        <p>
            تعداد فعلی محصولات منتشرشده:
            <strong>
                <?php
                echo esc_html(
                    number_format_i18n(
                        $product_count
                    )
                );
                ?>
            </strong>
            —
            در Hero به‌صورت
            <strong>
                <?php
                echo esc_html(
                    $product_label
                );
                ?>
            </strong>
            نمایش داده می‌شود.
        </p>

        <form
            method="post"
            action="options.php"
        >
            <?php
            settings_fields(
                'hsb_homepage_hero_group'
            );
            ?>

            <table
                class="form-table"
                role="presentation"
            >
                <tbody>

                <tr>
                    <th scope="row">
                        وضعیت Hero
                    </th>

                    <td>
                        <label>
                            <input
                                type="checkbox"
                                name="hsb_homepage_hero_options[enabled]"
                                value="1"
                                <?php
                                checked(
                                    $options['enabled'],
                                    '1'
                                );
                                ?>
                            >

                            نمایش در صفحه اصلی
                        </label>

                        <p class="description">
                            پیش‌فرض غیرفعال است.
                            هر زمان تصویر آماده شد
                            این گزینه را فعال کنید.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        آمار و روش‌های جستجو
                    </th>

                    <td>
                        <label>
                            <input
                                type="checkbox"
                                name="hsb_homepage_hero_options[show_stats]"
                                value="1"
                                <?php
                                checked(
                                    $options['show_stats'],
                                    '1'
                                );
                                ?>
                            >

                            نمایش سه باکس اطلاعاتی
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        عنوان کوچک
                    </th>

                    <td>
                        <input
                            type="text"
                            class="large-text"
                            name="hsb_homepage_hero_options[eyebrow]"
                            value="<?php
                            echo esc_attr(
                                $options['eyebrow']
                            );
                            ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        عنوان اصلی
                    </th>

                    <td>
                        <input
                            type="text"
                            class="large-text"
                            name="hsb_homepage_hero_options[title]"
                            value="<?php
                            echo esc_attr(
                                $options['title']
                            );
                            ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        توضیحات
                    </th>

                    <td>
                        <textarea
                            class="large-text"
                            rows="5"
                            name="hsb_homepage_hero_options[description]"
                        ><?php
                        echo esc_textarea(
                            $options['description']
                        );
                        ?></textarea>

                        <p class="description">
                            عبارت
                            <code>%PRODUCT_COUNT_LABEL%</code>
                            به‌صورت خودکار با تعداد
                            فعلی محصولات جایگزین می‌شود.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        متن دکمه
                    </th>

                    <td>
                        <input
                            type="text"
                            class="regular-text"
                            name="hsb_homepage_hero_options[button_text]"
                            value="<?php
                            echo esc_attr(
                                $options['button_text']
                            );
                            ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        مقصد دکمه
                    </th>

                    <td>
                        <input
                            type="text"
                            class="regular-text code"
                            dir="ltr"
                            name="hsb_homepage_hero_options[button_target]"
                            value="<?php
                            echo esc_attr(
                                $options['button_target']
                            );
                            ?>"
                        >

                        <p class="description">
                            مقدار پیش‌فرض
                            <code>#hsb-home-search</code>
                            کاربر را به بخش جستجو می‌برد.
                        </p>
                    </td>
                </tr>

                  <tr>
                      <th scope="row">
                          لوگوی مربعی Hero
                      </th>

                      <td>
                          <input
                              type="hidden"
                              id="hsb-homepage-hero-logo-image-id"
                              name="hsb_homepage_hero_options[logo_image_id]"
                              value="<?php echo esc_attr($options['logo_image_id']); ?>"
                          >

                          <div
                              id="hsb-homepage-hero-logo-preview"
                              style="
                                  width:280px;
                                  max-width:100%;
                                  aspect-ratio:1;
                                  margin-bottom:14px;
                                  display:flex;
                                  align-items:center;
                                  justify-content:center;
                                  overflow:hidden;
                                  border:1px solid #dcdcde;
                                  border-radius:14px;
                                  background:#fff;
                              "
                          >
                              <?php if ($logo_image_url) : ?>
                                  <img
                                      src="<?php echo esc_url($logo_image_url); ?>"
                                      alt=""
                                      style="
                                          display:block;
                                          width:100%;
                                          height:100%;
                                          object-fit:contain;
                                      "
                                  >
                              <?php else : ?>
                                  <span style="color:#646970;text-align:center;padding:20px;">
                                      لوگویی انتخاب نشده است
                                  </span>
                              <?php endif; ?>
                          </div>

                          <button
                              type="button"
                              class="button button-secondary"
                              id="hsb-homepage-hero-logo-select"
                          >
                              انتخاب / تغییر لوگو
                          </button>

                          <button
                              type="button"
                              class="button"
                              id="hsb-homepage-hero-logo-remove"
                          >
                              حذف لوگو
                          </button>

                          <p class="description">
                              ترجیحاً PNG مربعی و باکیفیت انتخاب کنید.
                              اگر لوگویی انتخاب نشود، این بخش در Hero نمایش داده نمی‌شود.
                          </p>

                          <p style="margin:16px 0 6px;">
                              <strong>لینک لوگو</strong>
                          </p>

                          <input
                              type="text"
                              class="regular-text code"
                              dir="ltr"
                              name="hsb_homepage_hero_options[logo_target]"
                              value="<?php echo esc_attr($options['logo_target']); ?>"
                              placeholder="/shop/"
                          >

                          <p class="description">
                              مثال:
                              <code>/shop/</code>
                              یا آدرس کامل صفحه مقصد.
                          </p>
                      </td>
                  </tr>

                <tr>
                    <th scope="row">
                        تصویر بزرگ Hero
                    </th>

                    <td>
                        <input
                            type="hidden"
                            id="hsb-homepage-hero-image-id"
                            name="hsb_homepage_hero_options[image_id]"
                            value="<?php
                            echo esc_attr(
                                $options['image_id']
                            );
                            ?>"
                        >

                        <div
                            id="hsb-homepage-hero-preview"
                            style="
                                width:min(900px,100%);
                                margin-bottom:14px;
                            "
                        >
                            <?php if ($image_url) : ?>
                                <img
                                    src="<?php
                                    echo esc_url(
                                        $image_url
                                    );
                                    ?>"
                                    alt=""
                                    style="
                                        display:block;
                                        width:100%;
                                        max-height:420px;
                                        object-fit:cover;
                                        border-radius:12px;
                                    "
                                >
                            <?php endif; ?>
                        </div>

                        <button
                            type="button"
                            class="button button-secondary"
                            id="hsb-homepage-hero-select"
                        >
                            انتخاب / تغییر تصویر
                        </button>

                        <button
                            type="button"
                            class="button"
                            id="hsb-homepage-hero-remove"
                        >
                            حذف تصویر
                        </button>

                        <p class="description">
                            تصویر افقی بسیار بزرگ انتخاب کنید.
                            تصویر زیر متن Hero و تقریباً
                            در عرض کامل مانیتور نمایش داده می‌شود.
                        </p>
                    </td>
                </tr>

                </tbody>
            </table>

            <?php
            submit_button(
                'ذخیره تنظیمات Hero'
            );
            ?>
        </form>
    </div>

    <script>
    document.addEventListener(
        "DOMContentLoaded",
        function () {
            const selectButton =
                document.getElementById(
                    "hsb-homepage-hero-select"
                );

            const removeButton =
                document.getElementById(
                    "hsb-homepage-hero-remove"
                );

            const imageInput =
                document.getElementById(
                    "hsb-homepage-hero-image-id"
                );

            const preview =
                document.getElementById(
                    "hsb-homepage-hero-preview"
                );

            if (
                !selectButton ||
                !removeButton ||
                !imageInput ||
                !preview ||
                typeof wp === "undefined" ||
                !wp.media
            ) {
                return;
            }

            let frame = null;

            selectButton.addEventListener(
                "click",
                function (event) {
                    event.preventDefault();

                    if (frame) {
                        frame.open();
                        return;
                    }

                    frame = wp.media({
                        title:
                            "انتخاب تصویر Hero",
                        button: {
                            text:
                                "استفاده از این تصویر"
                        },
                        library: {
                            type: "image"
                        },
                        multiple: false
                    });

                    frame.on(
                        "select",
                        function () {
                            const attachment =
                                frame
                                    .state()
                                    .get("selection")
                                    .first()
                                    .toJSON();

                            imageInput.value =
                                attachment.id;

                            let imageUrl =
                                attachment.url;

                            if (
                                attachment.sizes &&
                                attachment.sizes.large
                            ) {
                                imageUrl =
                                    attachment
                                        .sizes
                                        .large
                                        .url;
                            }

                            preview.innerHTML =
                                '<img src="' +
                                imageUrl +
                                '" alt="" style="' +
                                'display:block;' +
                                'width:100%;' +
                                'max-height:420px;' +
                                'object-fit:cover;' +
                                'border-radius:12px;' +
                                '">';
                        }
                    );

                    frame.open();
                }
            );

            removeButton.addEventListener(
                "click",
                function (event) {
                    event.preventDefault();

                    imageInput.value = "";
                    preview.innerHTML = "";
                }
            );
        }
    );
    </script>

      <script>
      document.addEventListener("DOMContentLoaded", function () {
          const select =
              document.getElementById(
                  "hsb-homepage-hero-logo-select"
              );

          const remove =
              document.getElementById(
                  "hsb-homepage-hero-logo-remove"
              );

          const input =
              document.getElementById(
                  "hsb-homepage-hero-logo-image-id"
              );

          const preview =
              document.getElementById(
                  "hsb-homepage-hero-logo-preview"
              );

          if (
              !select ||
              !remove ||
              !input ||
              !preview ||
              typeof wp === "undefined" ||
              !wp.media
          ) {
              return;
          }

          let frame = null;

          select.addEventListener(
              "click",
              function (event) {
                  event.preventDefault();

                  if (frame) {
                      frame.open();
                      return;
                  }

                  frame = wp.media({
                      title: "انتخاب لوگوی Hero",
                      button: {
                          text: "استفاده از این لوگو"
                      },
                      library: {
                          type: "image"
                      },
                      multiple: false
                  });

                  frame.on("select", function () {
                      const attachment =
                          frame
                              .state()
                              .get("selection")
                              .first()
                              .toJSON();

                      input.value =
                          attachment.id;

                      let url =
                          attachment.url;

                      if (
                          attachment.sizes &&
                          attachment.sizes.medium
                      ) {
                          url =
                              attachment
                                  .sizes
                                  .medium
                                  .url;
                      }

                      preview.innerHTML =
                          '<img src="' +
                          url +
                          '" alt="" style="' +
                          'display:block;' +
                          'width:100%;' +
                          'height:100%;' +
                          'object-fit:contain;' +
                          '">';
                  });

                  frame.open();
              }
          );

          remove.addEventListener(
              "click",
              function (event) {
                  event.preventDefault();

                  input.value = "";

                  preview.innerHTML =
                      '<span style="' +
                      'color:#646970;' +
                      'text-align:center;' +
                      'padding:20px;' +
                      '">' +
                      'لوگویی انتخاب نشده است' +
                      '</span>';
              }
          );
      });
      </script>
    <?php
}


function hsb_render_homepage_hero_section() {
    if (!is_front_page() && !is_home()) {
        return;
    }

    $options =
        hsb_get_homepage_hero_options();

    if (
        empty($options['enabled']) ||
        '1' !== $options['enabled']
    ) {
        return;
    }

    $product_count =
        hsb_get_homepage_product_count();

    $product_label =
        hsb_get_homepage_product_count_label(
            $product_count
        );

    $description =
        str_replace(
            '%PRODUCT_COUNT_LABEL%',
            $product_label,
            $options['description']
        );

    $button_target =
        !empty($options['button_target'])
            ? $options['button_target']
            : '#hsb-home-search';

    $image_id =
        !empty($options['image_id'])
            ? absint($options['image_id'])
            : 0;
        $logo_image_id =
        !empty($options['logo_image_id'])
            ? absint($options['logo_image_id'])
            : 0;

    $logo_target =
        !empty($options['logo_target'])
            ? trim($options['logo_target'])
            : '/shop/';

    if (
        $logo_target &&
        str_starts_with($logo_target, '/')
    ) {
        $logo_target =
            home_url($logo_target);
    }

?>

    <section
        class="hsb-home-hero<?php
        echo $logo_image_id
            ? ' has-brand-logo'
            : '';
        ?>"
        aria-labelledby="hsb-home-hero-title"
    >
        <div class="hsb-home-hero__content">

            <?php
            if (!empty($options['eyebrow'])) :
                ?>
                <p class="hsb-home-hero__eyebrow">
                    <?php
                    echo esc_html(
                        $options['eyebrow']
                    );
                    ?>
                </p>
            <?php endif; ?>

            <h2
                id="hsb-home-hero-title"
                class="hsb-home-hero__title"
            >
                <?php
                echo esc_html(
                    $options['title']
                );
                ?>
            </h2>

            <p class="hsb-home-hero__description">
                <?php
                echo esc_html(
                    $description
                );
                ?>
            </p>

            <?php
            if (
                !empty($options['show_stats']) &&
                '1' === $options['show_stats']
            ) :
                ?>
                <div class="hsb-home-hero__stats">

                    <article class="hsb-home-hero__stat">
                        <strong>
                            <?php
                            echo esc_html(
                                $product_label
                            );
                            ?>
                        </strong>

                        <span>
                            تنوع گسترده بلبرینگ
                            و رولبرینگ صنعتی
                        </span>
                    </article>

                    <article class="hsb-home-hero__stat">
                        <strong>
                            جستجوی تخصصی ابعادی
                        </strong>

                        <span>
                            قطر داخلی، قطر خارجی
                            و ارتفاع
                        </span>
                    </article>

                    <article class="hsb-home-hero__stat">
                        <strong>
                            جستجو با پارت‌نامبر
                        </strong>

                        <span>
                            دسترسی سریع
                            به مدل دقیق
                        </span>
                    </article>

                </div>
            <?php endif; ?>

            <?php
            if (!empty($options['button_text'])) :
                ?>
                <div class="hsb-home-hero__actions">
                    <a
                        class="hsb-home-hero__button"
                        href="<?php
                        echo esc_url(
                            $button_target
                        );
                        ?>"
                    >
                        <?php
                        echo esc_html(
                            $options['button_text']
                        );
                        ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>

        <?php if ($logo_image_id) : ?>
            <a
                class="hsb-home-hero__brand-logo"
                href="<?php echo esc_url($logo_target); ?>"
                aria-label="مشاهده فروشگاه"
            >
                <?php
                echo wp_get_attachment_image(
                    $logo_image_id,
                    'full',
                    false,
                    array(
                        'class' =>
                            'hsb-home-hero__brand-logo-image',
                        'loading' =>
                            'eager',
                        'decoding' =>
                            'async',
                    )
                );
                ?>
            </a>
        <?php endif; ?>

        <?php if ($image_id) : ?>
            <div class="hsb-home-hero__media">
                <?php
                echo wp_get_attachment_image(
                    $image_id,
                    'full',
                    false,
                    array(
                        'class'         =>
                            'hsb-home-hero__image',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                        'decoding'      => 'async',
                    )
                );
                ?>
            </div>
        <?php endif; ?>

    </section>

    <?php
}
