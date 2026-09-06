<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hide the default WordPress authentication surface.
 */
final class HSB_Auth_Access_Guard {


    public static function boot() {

        add_filter(
            'login_url',
            [self::class, 'filter_login_url'],
            20,
            3
        );

        add_action(
            'init',
            [self::class, 'guard_wp_admin'],
            0
        );

        add_action(
            'init',
            [self::class, 'guard_wp_login'],
            1
        );
    }


    private static function enabled() {

        return
            HSB_Staff_Login
                ::access_guard_enabled();
    }


    public static function filter_login_url(
        $login_url,
        $redirect,
        $force_reauth
    ) {

        unset(
            $login_url,
            $force_reauth
        );

        $url =
            self::account_url();

        $redirect =
            is_string($redirect)
                ? trim($redirect)
                : '';

        if ('' !== $redirect) {

            $url =
                add_query_arg(
                    'redirect_to',
                    $redirect,
                    $url
                );
        }

        return $url;
    }


    public static function guard_wp_login() {

        if (
            !self::enabled() ||
            self::should_bypass()
        ) {
            return;
        }

        $script =
            isset($_SERVER['SCRIPT_NAME'])
                ? basename(
                    sanitize_text_field(
                        wp_unslash(
                            $_SERVER['SCRIPT_NAME']
                        )
                    )
                )
                : '';

        if ('wp-login.php' !== $script) {
            return;
        }

        $action =
            isset($_REQUEST['action'])
                ? sanitize_key(
                    wp_unslash(
                        $_REQUEST['action']
                    )
                )
                : 'login';

        /*
         * Preserve only WordPress system actions that must
         * remain functional.
         */
        $allowed_actions = [
            'logout',
            'lostpassword',
            'retrievepassword',
            'rp',
            'resetpass',
            'postpass',
            'enter_recovery_mode',
        ];

        if (
            in_array(
                $action,
                $allowed_actions,
                true
            ) ||
            isset($_GET['checkemail'])
        ) {
            return;
        }

        self::render_not_found();
    }


    public static function guard_wp_admin() {

        if (
            !self::enabled() ||
            !is_admin()
        ) {
            return;
        }

        if (
            self::should_bypass() ||
            self::is_allowed_admin_script()
        ) {
            return;
        }

        if (!is_user_logged_in()) {

            self::render_not_found();
        }

        $user =
            wp_get_current_user();

        if (
            HSB_Staff_Login
                ::is_staff_user(
                    $user
                )
        ) {
            return;
        }

        wp_safe_redirect(
            self::account_url()
        );

        exit;
    }


    private static function account_url() {

        if (
            function_exists(
                'wc_get_page_permalink'
            )
        ) {

            $url =
                (string)
                wc_get_page_permalink(
                    'myaccount'
                );

            if ('' !== $url) {
                return $url;
            }
        }

        return home_url(
            '/my-account/'
        );
    }


    private static function should_bypass() {

        if (
            defined('WP_CLI') &&
            WP_CLI
        ) {
            return true;
        }

        if (
            defined('DOING_CRON') &&
            DOING_CRON
        ) {
            return true;
        }

        return false;
    }


    private static function is_allowed_admin_script() {

        $script =
            isset($_SERVER['SCRIPT_NAME'])
                ? basename(
                    sanitize_text_field(
                        wp_unslash(
                            $_SERVER['SCRIPT_NAME']
                        )
                    )
                )
                : '';

        return in_array(
            $script,
            [
                'admin-ajax.php',
                'admin-post.php',
                'async-upload.php',
                'load-scripts.php',
                'load-styles.php',
            ],
            true
        );
    }


    private static function render_not_found() {

        status_header(404);
        nocache_headers();

        header(
            'X-Robots-Tag: noindex, nofollow, noarchive, nosnippet',
            true
        );

        global $wp_query;

        if (
            $wp_query &&
            method_exists(
                $wp_query,
                'set_404'
            )
        ) {
            $wp_query->set_404();
        }

        $template =
            get_404_template();

        if (
            is_string($template) &&
            '' !== $template &&
            file_exists($template)
        ) {

            include $template;
            exit;
        }

        wp_die(
            esc_html(
                'صفحه موردنظر پیدا نشد.'
            ),
            esc_html(
                'صفحه پیدا نشد'
            ),
            [
                'response' => 404,
            ]
        );
    }
}
