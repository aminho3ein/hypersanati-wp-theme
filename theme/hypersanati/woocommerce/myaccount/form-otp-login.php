<?php
if (!defined('ABSPATH')) exit;
?>
<div class="ui-modal" id="ui-otp-modal" aria-hidden="true">

    <div class="ui-modal__backdrop" data-close-modal="true"></div>

    <div class="ui-modal__panel" role="dialog" aria-modal="true" aria-labelledby="ui-otp-title">

        <div class="ui-modal__header">
            <h2 id="ui-otp-title" class="ui-modal__title">ورود / ثبت‌نام</h2>
            <button class="ui-modal__close" type="button" data-close-modal="true" aria-label="بستن">×</button>
        </div>

        <div class="ui-modal__body">

            <!-- گام ۱: دریافت شماره موبایل -->
            <div class="ui-otp-step" data-step="phone">

                <div class="ui-form__group">
                    <label class="ui-label" for="otp-phone">شماره موبایل</label>
                    <input
                        id="otp-phone"
                        class="ui-input"
                        type="tel"
                        inputmode="numeric"
                        autocomplete="tel"
                        placeholder="09xxxxxxxxx"
                        maxlength="11"
                    />
                    <div class="ui-help" id="otp-phone-hint"></div>
                </div>

                <div class="ui-form__actions">
                    <button id="otp-send-btn" class="ui-btn ui-btn-primary" type="button" disabled>
                        ارسال کد تایید
                    </button>
                </div>
            </div>

            <!-- گام ۲: وارد کردن کد تایید -->
            <div class="ui-otp-step is-hidden" data-step="code">

                <p class="ui-otp-sent-to">
                    کد تایید به شماره‌ی <strong id="otp-phone-display"></strong> ارسال شد.
                    <button type="button" id="otp-edit-phone" class="ui-link-btn">ویرایش شماره</button>
                </p>

                <div class="ui-form__group">
                    <label class="ui-label">کد تایید</label>
                    <div class="ui-otp-boxes" id="otp-boxes" dir="ltr"></div>
                    <div class="ui-help" id="otp-code-hint"></div>
                </div>

                <div class="ui-form__actions">
                    <button id="otp-verify-btn" class="ui-btn ui-btn-primary" type="button" disabled>
                        تایید و ورود
                    </button>

                    <button type="button" id="otp-resend-btn" class="ui-link-btn" disabled>
                        ارسال مجدد کد (<span id="otp-resend-timer">60</span>)
                    </button>
                </div>
            </div>

            <div class="ui-ajax-status" id="otp-status" aria-live="polite"></div>

        </div>
    </div>
</div>