<?php

// defined('ABSPATH') || exit;

/**
 * Hypersanati My Account / OTP Assets + AJAX
 */

// ------------------------------------------
// Enqueue styles & scripts only on My Account
// ------------------------------------------
add_action('wp_enqueue_scripts', 'hypersanati_enqueue_myaccount_assets', 20);

<?php
defined('ABSPATH') || exit;

/**
 * Hypersanati My Account / OTP Assets + AJAX
 */

add_action('wp_enqueue_scripts', 'hypersanati_enqueue_myaccount_assets', 20);

function hypersanati_enqueue_myaccount_assets()
{
    if (!function_exists('is_account_page') || !is_account_page()) {
        return;
    }

    $base_url = get_template_directory_uri() . '/assets';
    $base_dir = get_template_directory() . '/assets';

    $enqueue_style = function(string $handle, string $relative_path, array $deps = [], $media = 'all') use ($base_url, $base_dir) {
        $path = $base_dir . '/' . ltrim($relative_path, '/');
        $url  = $base_url . '/' . ltrim($relative_path, '/');

        if (!file_exists($path)) {
            return false;
        }

        wp_enqueue_style($handle, $url, $deps, filemtime($path), $media);
        return true;
    };

    $enqueue_script = function(string $handle, string $relative_path, array $deps = [], bool $in_footer = true) use ($base_url, $base_dir) {
        $path = $base_dir . '/' . ltrim($relative_path, '/');
        $url  = $base_url . '/' . ltrim($relative_path, '/');

        if (!file_exists($path)) {
            return false;
        }

        wp_enqueue_script($handle, $url, $deps, filemtime($path), $in_footer);
        return true;
    };

    // CSS
    $has_profile = $enqueue_style('hypersanati-profile', 'css/profile.css');
    $enqueue_style('hypersanati-otp', 'css/myaccount/otp.css', $has_profile ? ['hypersanati-profile'] : []);

    // JS
    $has_otp_js = $enqueue_script('hypersanati-otp', 'js/otp.js');

    if ($has_otp_js) {
        wp_localize_script('hypersanati-otp', 'otp_data', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('hypersanati_otp_action'),
        ]);
    }
}



// function hypersanati_enqueue_myaccount_assets()
// {
//     // فقط در صفحه حساب کاربری ووکامرس
//     if (! function_exists('is_account_page') || ! is_account_page()) {
//         return;
//     }

//     // $theme_dir = get_template_directory();
//     // $theme_uri = get_template_directory_uri();

//     $profile_css_path = get_template_directory() . '/assets/css/profile.css';
//     $profile_css_uri  = get_template_directory_uri() . '/assets/css/profile.css';

//     $otp_css_path = get_template_directory() . '/assets/css/myaccount/otp.css';
//     $otp_css_uri  = get_template_directory_uri() . '/assets/css/myaccount/otp.css';

//     $otp_js_path = get_template_directory() . '/assets/js/otp.js';
//     $otp_js_uri  = get_template_directory_uri() . '/assets/js/otp.js';

//     // profile.css
//     if (file_exists($profile_css_path)) {
//         wp_enqueue_style(
//             'hypersanati-profile',
//             $profile_css_uri,
//             [],
//             filemtime($profile_css_path)
//         );
//     }

//     // otp.css
//     if (file_exists($otp_css_path)) {
//         wp_enqueue_style(
//             'hypersanati-otp',
//             $otp_css_uri,
//             file_exists($profile_css_path) ? ['hypersanati-profile'] : [],
//             filemtime($otp_css_path)
//         );
//     }

//     // otp.js
//     if (file_exists($otp_js_path)) {
//         wp_enqueue_script(
//             'hypersanati-otp',
//             $otp_js_uri,
//             [],
//             filemtime($otp_js_path),
//             true
//         );

//         wp_localize_script('hypersanati-otp', 'otp_data', [
//             'ajax_url' => admin_url('admin-ajax.php'),
//             'nonce'    => wp_create_nonce('hypersanati_otp_action'),
//         ]);
//     }
// }

// ------------------------------------------
// 1) Send OTP
// ------------------------------------------
add_action('wp_ajax_ui_send_otp', 'ui_send_otp');
add_action('wp_ajax_nopriv_ui_send_otp', 'ui_send_otp');

function ui_send_otp()
{
    check_ajax_referer('hypersanati_otp_action', 'nonce');

    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';

    if (! preg_match('/^09\d{9}$/', $phone)) {
        wp_send_json_error([
            'message' => 'فرمت شماره اشتباه است.'
        ]);
    }

    try {
        $code = (string) random_int(100000, 999999);
    } catch (Exception $e) {
        wp_send_json_error([
            'message' => 'خطا در تولید کد تایید.'
        ]);
    }

    set_transient('otp_' . $phone, $code, 300); // 5 minutes

    // TODO: SMS API call here

    wp_send_json_success([
        'message' => 'کد ارسال شد.'
    ]);
}

// ------------------------------------------
// 2) Verify OTP & Login / Register
// ------------------------------------------
add_action('wp_ajax_ui_verify_otp', 'ui_verify_otp');
add_action('wp_ajax_nopriv_ui_verify_otp', 'ui_verify_otp');

function ui_verify_otp()
{
    check_ajax_referer('hypersanati_otp_action', 'nonce');

    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $code  = isset($_POST['code']) ? sanitize_text_field(wp_unslash($_POST['code'])) : '';

    if (! preg_match('/^09\d{9}$/', $phone)) {
        wp_send_json_error([
            'message' => 'شماره موبایل نامعتبر است.'
        ]);
    }

    if (! preg_match('/^\d{6}$/', $code)) {
        wp_send_json_error([
            'message' => 'کد تایید باید ۶ رقمی باشد.'
        ]);
    }

    $saved_code = get_transient('otp_' . $phone);

    if ($saved_code === false || $saved_code !== $code) {
        wp_send_json_error([
            'message' => 'کد نامعتبر یا منقضی شده است.'
        ]);
    }

    $user = get_user_by('login', $phone);

    if (! $user) {
        if (! function_exists('wc_create_new_customer')) {
            wp_send_json_error([
                'message' => 'تابع ایجاد کاربر ووکامرس در دسترس نیست.'
            ]);
        }

        $user_id = wc_create_new_customer(
            $phone . '@hypersanati.com',
            $phone,
            wp_generate_password(20, true, true)
        );

        if (is_wp_error($user_id)) {
            wp_send_json_error([
                'message' => $user_id->get_error_message()
            ]);
        }

        $user = get_user_by('id', $user_id);

        if (! $user) {
            wp_send_json_error([
                'message' => 'کاربر ایجاد شد اما بارگذاری نشد.'
            ]);
        }
    }

    delete_transient('otp_' . $phone);

    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);

    if (function_exists('wc_set_customer_auth_cookie')) {
        wc_set_customer_auth_cookie($user->ID);
    }

    do_action('wp_login', $user->user_login, $user);

    wp_send_json_success([
        'message'  => 'ورود با موفقیت انجام شد.',
        'redirect' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/'),
    ]);
}
