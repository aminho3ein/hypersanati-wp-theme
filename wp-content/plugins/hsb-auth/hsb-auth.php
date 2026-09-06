<?php
/**
 * Plugin Name: HSB Auth
 * Plugin URI: https://olfatbearing.com
 * Description: Customer authentication and profile management for HyperSanati Industrial Shop.
 * Version: 0.2.0
 * Author: HyperSanati
 * Text Domain: hsb-auth
 */

if (!defined('ABSPATH')) {
    exit;
}

define('HSB_AUTH_VERSION', '0.2.0');
define('HSB_AUTH_PATH', plugin_dir_path(__FILE__));
define('HSB_AUTH_URL', plugin_dir_url(__FILE__));

require_once HSB_AUTH_PATH . 'includes/class-config.php';
require_once HSB_AUTH_PATH . 'includes/class-sms-provider.php';
require_once HSB_AUTH_PATH . 'includes/class-sms-disabled-provider.php';
require_once HSB_AUTH_PATH . 'includes/class-verify-rate-limit.php';
require_once HSB_AUTH_PATH . 'includes/class-otp-rate-limit.php';
require_once HSB_AUTH_PATH . 'includes/class-otp-storage.php';
require_once HSB_AUTH_PATH . 'includes/class-otp-service.php';
require_once HSB_AUTH_PATH . 'includes/class-otp.php';
require_once HSB_AUTH_PATH . 'includes/class-login-controller.php';
require_once HSB_AUTH_PATH . 'includes/class-staff-login.php';
require_once HSB_AUTH_PATH . 'includes/class-access-guard.php';
require_once HSB_AUTH_PATH . 'includes/class-sms-factory.php';
require_once HSB_AUTH_PATH . 'includes/class-sms-kavenegar.php';
require_once HSB_AUTH_PATH . 'includes/class-sms-test-provider.php';
require_once HSB_AUTH_PATH . 'includes/class-user-profile.php';
require_once HSB_AUTH_PATH . 'includes/class-register-controller.php';
require_once HSB_AUTH_PATH . 'includes/class-password-login-controller.php';
require_once HSB_AUTH_PATH . 'includes/class-auth.php';
require_once HSB_AUTH_PATH . 'includes/class-validator.php';
require_once HSB_AUTH_PATH . 'includes/class-auth-meta.php';
require_once HSB_AUTH_PATH . 'includes/class-mobile.php';
require_once HSB_AUTH_PATH . 'includes/class-customer-meta.php';
require_once HSB_AUTH_PATH . 'includes/class-assets.php';
require_once HSB_AUTH_PATH . 'includes/class-shortcodes.php';
require_once HSB_AUTH_PATH . 'includes/class-rest-profile.php';
require_once HSB_AUTH_PATH . 'includes/class-rest-api.php';
require_once HSB_AUTH_PATH . 'includes/class-api.php';
require_once HSB_AUTH_PATH . 'includes/class-hooks.php';
require_once HSB_AUTH_PATH . 'includes/class-woocommerce-account.php';

function hsb_auth_init() {

    HSB_Staff_Login::boot();
    HSB_Auth_Access_Guard::boot();

    new HSB_Auth();
    new HSB_Auth_Hooks();
    new HSB_REST_API();
    new HSB_REST_Profile();
    new HSB_Shortcodes();
    new HSB_Auth_Assets();
    new HSB_WooCommerce_Account();

}

add_action('plugins_loaded', 'hsb_auth_init');




