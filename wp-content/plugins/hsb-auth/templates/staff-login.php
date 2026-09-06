<?php

defined('ABSPATH') || exit;

$logo_id =
    (int) get_theme_mod(
        'custom_logo',
        0
    );

$logo_url =
    $logo_id > 0
        ? (string)
        wp_get_attachment_url(
            $logo_id
        )
        : '';

if ('' === $logo_url) {

    $logo_url =
        (string)
        get_site_icon_url(
            512
        );
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="robots"
        content="noindex,nofollow,noarchive,nosnippet"
    >

    <title>
        ورود امن مدیران
    </title>

    <?php wp_head(); ?>
</head>

<body class="hsb-staff-auth-page">

<?php wp_body_open(); ?>

<main
    class="hsb-staff-auth"
    data-hsb-staff-auth
>
    <section class="hsb-staff-auth__card">

        <?php if ('' !== $logo_url) : ?>

            <a
                class="hsb-staff-auth__logo"
                href="<?php
                    echo esc_url(
                        home_url('/')
                    );
                ?>"
                aria-label="<?php
                    echo esc_attr(
                        get_bloginfo('name')
                    );
                ?>"
            >
                <img
                    src="<?php
                        echo esc_url(
                            $logo_url
                        );
                    ?>"
                    alt="<?php
                        echo esc_attr(
                            get_bloginfo('name')
                        );
                    ?>"
                >
            </a>

        <?php endif; ?>

        <header class="hsb-staff-auth__header">

            <span>
                درگاه خصوصی کارکنان
            </span>

            <h1>
                ورود به بخش مدیریت
            </h1>

            <p>
                برای ادامه نام کاربری و رمز عبور حساب مدیریتی خود را وارد کنید.
            </p>

        </header>

        <form
            class="hsb-staff-auth__form"
            data-hsb-staff-form
            novalidate
        >

            <label for="hsb-staff-username">
                نام کاربری یا ایمیل
            </label>

            <input
                id="hsb-staff-username"
                type="text"
                autocomplete="username"
                required
                data-hsb-staff-username
            >

            <label for="hsb-staff-password">
                رمز عبور
            </label>

            <input
                id="hsb-staff-password"
                type="password"
                autocomplete="current-password"
                required
                data-hsb-staff-password
            >

            <label class="hsb-staff-auth__remember">

                <input
                    type="checkbox"
                    value="1"
                    data-hsb-staff-remember
                >

                <span>
                    مرا به خاطر بسپار
                </span>

            </label>

            <button
                type="submit"
                data-hsb-staff-submit
            >
                ورود امن
            </button>

        </form>

        <div
            class="hsb-staff-auth__message"
            aria-live="polite"
            data-hsb-staff-message
        ></div>

        <div class="hsb-staff-auth__security">
            <span aria-hidden="true">✓</span>
            مسیر ورود اختصاصی و محافظت‌شده
        </div>

    </section>
</main>

<?php wp_footer(); ?>

</body>
</html>
