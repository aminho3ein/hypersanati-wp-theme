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

        <div class="hsb-auth-box">

            <div class="hsb-auth-header">

                <span>ورود</span>

                <h2>
                    ورود به حساب کاربری
                </h2>

                <p>
                    وارد حساب خود شوید
                </p>

            </div>


            <div class="hsb-auth-message"></div>


            <div class="hsb-auth-tabs">

                <button class="active" type="button">
                    ورود با کد یکبار مصرف
                </button>

                <button>
                    ورود با رمز عبور
                </button>

            </div>


            <div class="hsb-auth-password" style="display:none">

                <label>
                    ایمیل یا نام کاربری
                </label>

                <input
                    type="text"
                    name="login"
                    placeholder="ایمیل یا نام کاربری"
                >


                <label>
                    رمز عبور
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="رمز عبور خود را وارد کنید"
                >


                <button>
                    ورود
                </button>

            </div>



            <div class="hsb-auth-mobile" style="display:block;">

                <div class="hsb-mobile-input-step">

                    <label>
                        شماره موبایل
                    </label>

                    <input
                        type="text"
                        name="hsb_mobile"
                        placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                    >

                    <div class="hsb-mobile-actions">

                        <button type="button" class="hsb-send-otp">
                            دریافت کد
                        </button>

                    </div>

                </div>


                <div class="hsb-otp-container">

                    <div class="hsb-mobile-step">

                        <span class="hsb-mobile-number"></span>

                        <span class="hsb-mobile-status waiting">
                            منتظر تأیید شماره
                        </span>

                    </div>


                    <div class="hsb-otp-boxes">

                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">

                    </div>


                    <div class="hsb-countdown">
                        <span class="hsb-timer">120</span> ثانیه
                    </div>


                    <div class="hsb-mobile-actions">

                        <button type="button" class="hsb-confirm-mobile">
                            تأیید شماره
                        </button>

                        <button type="button" class="hsb-edit-mobile" disabled>
                            تغییر شماره
                        </button>

                    </div>

                </div>

            </div>



            <div class="hsb-auth-footer">

                حساب ندارید؟
                <a href="#" class="hsb-open-register">
                    ثبت نام کنید
                </a>

            </div>


        </div>

        <?php

        return ob_get_clean();

    }



    public function register_form() {

        ob_start();

        ?>

        <div class="hsb-auth-register">

            <div class="hsb-auth-header">

                <span>ثبت نام</span>

                <h2>
                    ایجاد حساب کاربری
                </h2>

                <p>
                    اطلاعات خود را برای ساخت حساب وارد کنید
                </p>

            </div>


            <div class="hsb-auth-message"></div>


            <div class="hsb-register-type">

                <button
                    type="button"
                    class="hsb-register-type-btn active"
                    data-type="company">

                    حساب شرکتی

                </button>


                <button
                    type="button"
                    class="hsb-register-type-btn"
                    data-type="personal">

                    حساب شخصی

                </button>

            </div>



            <div class="hsb-register-company">


                <input
                    type="text"
                    name="company_name"
                    placeholder="نام شرکت">


                <input
                    type="text"
                    name="national_id"
                    placeholder="شناسه ملی">


                <input
                    type="text"
                    name="registration_number"
                    placeholder="شماره ثبت">


                <input
                    type="text"
                    name="industry"
                    placeholder="حوزه فعالیت">


                <input
                    type="text"
                    name="company_buyer_name"
                    placeholder="نام نماینده">


                <input
                    type="text"
                    name="company_position"
                    placeholder="سمت نماینده">


            </div>




            <div class="hsb-register-personal">


                <input
                    type="text"
                    name="first_name"
                    placeholder="نام">


                <input
                    type="text"
                    name="last_name"
                    placeholder="نام خانوادگی">


            </div>




            <input
                type="email"
                name="email"
                placeholder="ایمیل">


            <input
                type="password"
                name="password"
                placeholder="رمز عبور">





            <div class="hsb-register-mobile">

                <div class="hsb-mobile-input-step">

                    <label>
                        شماره موبایل
                    </label>

                    <input
                        type="text"
                        name="register_mobile"
                        placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                    >

                    <div class="hsb-mobile-actions">

                        <button type="button" class="hsb-send-otp">
                            دریافت کد
                        </button>

                    </div>

                </div>


                <div class="hsb-otp-container">

                    <div class="hsb-mobile-step">

                        <span class="hsb-mobile-number"></span>

                        <span class="hsb-mobile-status waiting">
                            منتظر تأیید شماره
                        </span>

                    </div>


                    <div class="hsb-otp-boxes">

                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">
                        <input maxlength="1">

                    </div>


                    <div class="hsb-countdown">
                        <span class="hsb-timer">120</span> ثانیه
                    </div>


                    <div class="hsb-mobile-actions">

                        <button type="button" class="hsb-confirm-mobile">
                            تأیید شماره
                        </button>

                        <button type="button" class="hsb-edit-mobile" disabled>
                            تغییر شماره
                        </button>

                    </div>

                </div>

            </div>





        </div>

        <?php

        return ob_get_clean();

    }


}
