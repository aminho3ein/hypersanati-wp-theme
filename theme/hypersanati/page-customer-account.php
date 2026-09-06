<?php
/*
Template Name: حساب مشتری
*/

get_header();
?>

<section class="customer-account-section">
    <div class="container">

        <div class="customer-account-box">

            <h1 class="customer-account-title">
                ورود به حساب مشتری
            </h1>

            <div class="customer-account-login">
                <?php echo do_shortcode('[hsb_auth_login]'); ?>
            </div>

            <div class="customer-account-register">

                <h2>
                    مشتری جدید هستید؟
                </h2>

                <?php echo do_shortcode('[hsb_auth_register]'); ?>

            </div>

        </div>

    </div>
</section>

<?php
get_footer();
