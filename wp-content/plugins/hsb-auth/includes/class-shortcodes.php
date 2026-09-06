<?php

if (!defined('ABSPATH')) {
    exit;
}

class HSB_Shortcodes {


    public function __construct() {

        add_shortcode(
            'hsb_auth_login',
            [$this, 'login_form']
        );

        add_shortcode(
            'hsb_auth_register',
            [$this, 'register_form']
        );

    }



    public function login_form() {


        ob_start();

        ?>

        <div class="hsb-auth-login">

            <div class="hsb-auth-message"></div>

            <div class="hsb-auth-mobile">

                <label>
                    شماره موبایل
                </label>

                <input
                    type="text"
                    name="hsb_mobile"
                >

                <button type="button">
                    دریافت کد
                </button>

            </div>


            <div class="hsb-auth-otp">

                <label>
                    کد تایید
                </label>

                <input
                    type="text"
                    name="hsb_otp"
                >

                <button type="button">
                    ورود
                </button>

            </div>


        </div>

        <?php


        return ob_get_clean();

    }


    public function register_form() {

        ob_start();

        ?>

        <div class="hsb-auth-register">

            <input type="text" name="username" placeholder="نام کاربری">

            <input type="email" name="email" placeholder="ایمیل">

            <input type="text" name="mobile" placeholder="موبایل">

            <input type="password" name="password" placeholder="رمز عبور">


            <input type="text" name="company_name" placeholder="نام شرکت">

            <input type="text" name="national_id" placeholder="شناسه ملی">

            <input type="text" name="registration_number" placeholder="شماره ثبت">

            <input type="text" name="industry" placeholder="حوزه فعالیت">


            <button type="button">
                ثبت نام
            </button>

        </div>

        <?php

        return ob_get_clean();

    }


}
