<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Private username/password gateway for site staff.
 *
 * Customer accounts are intentionally rejected here.
 * Customer authentication remains on the public website.
 */
final class HSB_Staff_Login {

    private const QUERY_VAR =
        'hsb_staff_gateway';

    private const SLUG_OPTION =
        'hsb_staff_login_slug_v1';

    private const REWRITE_PENDING_OPTION =
        'hsb_staff_login_rewrite_pending';

    private const NONCE_ACTION =
        'hsb-staff-auth';

    private const ATTEMPT_PREFIX =
        'hsb_staff_login_attempt_';

    private const ATTEMPT_LIMIT = 5;

    private const ATTEMPT_WINDOW =
        15 * MINUTE_IN_SECONDS;


    public static function boot() {

        self::ensure_gateway_slug();

        add_action(
            'init',
            [self::class, 'register_rewrite'],
            4
        );

        add_filter(
            'query_vars',
            [self::class, 'register_query_var']
        );

        add_action(
            'init',
            [self::class, 'maybe_flush_rewrite'],
            20
        );

        add_action(
            'template_redirect',
            [self::class, 'render_gateway'],
            1
        );

        add_action(
            'wp_enqueue_scripts',
            [self::class, 'enqueue_assets'],
            70
        );

        add_action(
            'wp_ajax_nopriv_hsb_staff_credentials',
            [self::class, 'verify_credentials']
        );

        add_action(
            'wp_ajax_hsb_staff_credentials',
            [self::class, 'verify_credentials']
        );

        add_action(
            'wp_ajax_nopriv_hsb_staff_verify_otp',
            [self::class, 'verify_otp']
        );

        add_action(
            'admin_notices',
            [self::class, 'render_admin_notice']
        );
    }


    /**
     * Emergency kill switch:
     *
     * define('HSB_AUTH_DISABLE_ACCESS_GUARD', true);
     */
    public static function access_guard_enabled() {

        return !(
            defined('HSB_AUTH_DISABLE_ACCESS_GUARD') &&
            HSB_AUTH_DISABLE_ACCESS_GUARD
        );
    }


    /**
     * Private slug can optionally be forced from wp-config.php:
     *
     * define('HSB_STAFF_LOGIN_SLUG', 'your-private-slug');
     */
    public static function gateway_slug() {

        if (defined('HSB_STAFF_LOGIN_SLUG')) {

            $configured =
                sanitize_title(
                    (string) HSB_STAFF_LOGIN_SLUG
                );

            if ('' !== $configured) {
                return $configured;
            }
        }

        return sanitize_title(
            (string) get_option(
                self::SLUG_OPTION,
                ''
            )
        );
    }


    public static function gateway_url() {

        $slug =
            self::gateway_slug();

        if ('' === $slug) {
            return '';
        }

        return home_url(
            '/' . $slug . '/'
        );
    }


    public static function ensure_gateway_slug() {

        if (
            defined('HSB_STAFF_LOGIN_SLUG') &&
            '' !== sanitize_title(
                (string) HSB_STAFF_LOGIN_SLUG
            )
        ) {
            return;
        }

        $current =
            sanitize_title(
                (string) get_option(
                    self::SLUG_OPTION,
                    ''
                )
            );

        if ('' !== $current) {
            return;
        }

        $random =
            strtolower(
                wp_generate_password(
                    28,
                    false,
                    false
                )
            );

        $slug =
            'hsb-staff-' . $random;

        update_option(
            self::SLUG_OPTION,
            $slug,
            false
        );

        update_option(
            self::REWRITE_PENDING_OPTION,
            '1',
            false
        );
    }


    public static function register_rewrite() {

        $slug =
            self::gateway_slug();

        if ('' === $slug) {
            return;
        }

        add_rewrite_rule(
            '^' . preg_quote($slug, '#') . '/?$',
            'index.php?' . self::QUERY_VAR . '=1',
            'top'
        );
    }


    public static function maybe_flush_rewrite() {

        if (
            '1' !==
            (string) get_option(
                self::REWRITE_PENDING_OPTION,
                ''
            )
        ) {
            return;
        }

        flush_rewrite_rules(false);

        delete_option(
            self::REWRITE_PENDING_OPTION
        );
    }


    public static function register_query_var($vars) {

        $vars[] =
            self::QUERY_VAR;

        return $vars;
    }


    public static function is_gateway() {

        return
            '1' ===
            (string) get_query_var(
                self::QUERY_VAR
            );
    }


    public static function is_staff_user($user) {

        if (
            !($user instanceof WP_User) ||
            $user->ID <= 0
        ) {
            return false;
        }

        if (
            is_super_admin($user->ID) ||
            user_can($user, 'manage_options')
        ) {
            return true;
        }

        return false;
    }


    public static function redirect_for_user($user) {

        $default =
            admin_url();

        $redirect =
            apply_filters(
                'hsb_staff_login_redirect',
                $default,
                $user
            );

        if (
            !is_string($redirect) ||
            '' === trim($redirect)
        ) {
            return $default;
        }

        return wp_validate_redirect(
            $redirect,
            $default
        );
    }


    public static function render_gateway() {

        if (!self::is_gateway()) {
            return;
        }

        global $wp_query;

        if ($wp_query) {
            $wp_query->is_404 = false;
        }

        status_header(200);
        nocache_headers();

        header(
            'X-Robots-Tag: noindex, nofollow, noarchive, nosnippet',
            true
        );

        header(
            'Referrer-Policy: no-referrer',
            true
        );

        if (is_user_logged_in()) {

            $user =
                wp_get_current_user();

            if (
                self::is_staff_user(
                    $user
                )
            ) {

                wp_safe_redirect(
                    self::redirect_for_user(
                        $user
                    )
                );

                exit;
            }

            status_header(403);

            wp_die(
                esc_html(
                    'این حساب اجازه ورود به بخش مدیریت را ندارد.'
                ),
                esc_html(
                    'دسترسی غیرمجاز'
                ),
                [
                    'response' => 403,
                ]
            );
        }

        $template =
            HSB_AUTH_PATH .
            'templates/staff-login.php';

        if (!file_exists($template)) {

            status_header(500);

            wp_die(
                esc_html(
                    'قالب ورود مدیران در دسترس نیست.'
                )
            );
        }

        require $template;

        exit;
    }


    public static function enqueue_assets() {

        if (!self::is_gateway()) {
            return;
        }

        $style_file =
            HSB_AUTH_PATH .
            'assets/css/staff-login.css';

        $script_file =
            HSB_AUTH_PATH .
            'assets/js/staff-login.js';

        wp_enqueue_style(
            'hsb-secure-staff-login',
            HSB_AUTH_URL .
            'assets/css/staff-login.css',
            [],
            file_exists($style_file)
                ? (string) filemtime($style_file)
                : HSB_AUTH_VERSION
        );

        wp_enqueue_script(
            'hsb-secure-staff-login',
            HSB_AUTH_URL .
            'assets/js/staff-login.js',
            [],
            file_exists($script_file)
                ? (string) filemtime($script_file)
                : HSB_AUTH_VERSION,
            true
        );

        wp_localize_script(
            'hsb-secure-staff-login',
            'HSBStaffAuth',
            [
                'ajaxUrl' =>
                    admin_url(
                        'admin-ajax.php'
                    ),

                'nonce' =>
                    wp_create_nonce(
                        self::NONCE_ACTION
                    ),
            ]
        );
    }


    private static function ip() {

        if (class_exists('WC_Geolocation')) {

            return (string)
                WC_Geolocation::get_ip_address();
        }

        return isset($_SERVER['REMOTE_ADDR'])
            ? sanitize_text_field(
                wp_unslash(
                    $_SERVER['REMOTE_ADDR']
                )
            )
            : 'unknown';
    }


    private static function attempt_key($login) {

        return
            self::ATTEMPT_PREFIX .
            substr(
                hash_hmac(
                    'sha256',
                    strtolower(
                        trim(
                            (string) $login
                        )
                    ) .
                    '|' .
                    self::ip(),
                    wp_salt('auth')
                ),
                0,
                40
            );
    }


    private static function get_attempt_state(
        $login
    ) {

        $state =
            get_transient(
                self::attempt_key(
                    $login
                )
            );

        if (!is_array($state)) {

            return [
                'started_at' => time(),
                'count'      => 0,
            ];
        }

        if (
            time() -
            absint(
                $state['started_at'] ?? 0
            )
            >= self::ATTEMPT_WINDOW
        ) {

            return [
                'started_at' => time(),
                'count'      => 0,
            ];
        }

        return $state;
    }


    private static function is_rate_limited(
        $login
    ) {

        $state =
            self::get_attempt_state(
                $login
            );

        return
            absint(
                $state['count'] ?? 0
            )
            >= self::ATTEMPT_LIMIT;
    }


    private static function record_failure(
        $login
    ) {

        $state =
            self::get_attempt_state(
                $login
            );

        $state['count'] =
            absint(
                $state['count'] ?? 0
            ) + 1;

        set_transient(
            self::attempt_key(
                $login
            ),
            $state,
            self::ATTEMPT_WINDOW
        );
    }


    private static function clear_failures(
        $login
    ) {

        delete_transient(
            self::attempt_key(
                $login
            )
        );
    }


    public static function verify_credentials() {

        check_ajax_referer(
            self::NONCE_ACTION,
            'nonce'
        );

        if (!self::access_guard_enabled()) {

            wp_send_json_error(
                [
                    'message' =>
                        'درگاه امنیتی در حال حاضر غیرفعال است.',
                ],
                503
            );
        }

        $login =
            isset($_POST['username'])
                ? trim(
                    sanitize_text_field(
                        wp_unslash(
                            $_POST['username']
                        )
                    )
                )
                : '';

        $password =
            isset($_POST['password'])
                ? (string) wp_unslash(
                    $_POST['password']
                )
                : '';

        $remember =
            !empty(
                $_POST['remember']
            );

        if (
            '' === $login ||
            '' === $password
        ) {

            wp_send_json_error(
                [
                    'message' =>
                        'نام کاربری و رمز عبور را وارد کنید.',
                ],
                422
            );
        }

        if (
            self::is_rate_limited(
                $login
            )
        ) {

            wp_send_json_error(
                [
                    'message' =>
                        'تعداد تلاش‌های ورود بیش از حد مجاز است. چند دقیقه بعد دوباره تلاش کنید.',
                ],
                429
            );
        }

        $user =
            wp_authenticate(
                $login,
                $password
            );

        if (
            is_wp_error($user) ||
            !($user instanceof WP_User) ||
            !self::is_staff_user($user)
        ) {

            self::record_failure(
                $login
            );

            wp_send_json_error(
                [
                    'message' =>
                        'نام کاربری یا رمز عبور صحیح نیست.',
                ],
                401
            );
        }

        self::clear_failures(
            $login
        );

        $mobile =
            get_user_meta(
                $user->ID,
                'billing_phone',
                true
            );

        if (empty($mobile)) {
            wp_send_json_error(
                [
                    'message' =>
                        'شماره موبایل برای این حساب ثبت نشده است.',
                ],
                422
            );
        }

        $otp =
            HSB_Login_Controller::request_otp(
                $mobile
            );

        if (is_wp_error($otp) || false === $otp) {
            wp_send_json_error(
                [
                    'message' =>
                        'ارسال کد تایید انجام نشد.',
                ],
                500
            );
        }

        set_transient(
            'hsb_staff_pending_' . $user->ID,
            [
                'user_id' => $user->ID,
                'mobile'  => $mobile,
                'remember'=> $remember,
            ],
            10 * MINUTE_IN_SECONDS
        );

        wp_send_json_success(
            [
                'message' =>
                    'کد تایید ارسال شد.',
                'user_id' =>
                    $user->ID,
                'mobile_masked' =>
                    "‎" .
                    substr($mobile, 0, 3) .
                    '******' .
                    substr($mobile, -2) .
                    "‎",
            ]
        );
    }



    public static function verify_otp() {

        check_ajax_referer(
            self::NONCE_ACTION,
            'nonce'
        );

        $user_id =
            absint(
                $_POST['user_id'] ?? 0
            );

        $code =
            sanitize_text_field(
                wp_unslash(
                    $_POST['code'] ?? ''
                )
            );

        $pending =
            get_transient(
                'hsb_staff_pending_' . $user_id
            );

        if (
            empty($pending) ||
            empty($pending['mobile'])
        ) {
            wp_send_json_error(
                [
                    'message' =>
                        'درخواست ورود منقضی شده است.',
                ],
                422
            );
        }

        if (
            !HSB_Login_Controller::verify_otp_code(
                $pending['mobile'],
                $code
            )
        ) {
            wp_send_json_error(
                [
                    'message' =>
                        'کد تایید صحیح نیست.',
                ],
                401
            );
        }

        wp_set_current_user(
            $pending['user_id']
        );

        wp_set_auth_cookie(
            $pending['user_id'],
            !empty($pending['remember']),
            is_ssl()
        );

        delete_transient(
            'hsb_staff_pending_' . $user_id
        );

        wp_send_json_success(
            [
                'redirect' =>
                    self::redirect_for_user(
                        get_user_by(
                            'id',
                            $pending['user_id']
                        )
                    ),
            ]
        );
    }


    public static function render_admin_notice() {

        if (
            !current_user_can(
                'manage_options'
            )
        ) {
            return;
        }

        $url =
            self::gateway_url();

        if ('' === $url) {
            return;
        }

        ?>
        <div class="notice notice-info">
            <p>
                <strong>
                    HSB Auth:
                </strong>

                آدرس خصوصی ورود مدیران:

                <code><?php
                    echo esc_html($url);
                ?></code>

                —
                این آدرس را ذخیره کنید و در اختیار عموم قرار ندهید.
            </p>
        </div>
        <?php
    }
}
