<?php
defined('ABSPATH') || exit;
?>

<div class="hsb-account-shell">

    <div class="hsb-account-switch">
        <button type="button" class="hsb-account-switch-btn is-active" data-target="login">
            ورود
        </button>

        <button type="button" class="hsb-account-switch-btn" data-target="register">
            ثبت نام
        </button>
    </div>

    <div class="hsb-account-panel is-active" data-panel="login">
        <?php echo do_shortcode('[hsb_auth_login]'); ?>
    </div>

    <div class="hsb-account-panel" data-panel="register">
        <?php echo do_shortcode('[hsb_auth_register]'); ?>
    </div>

</div>
