

<div class="ui-modal" id="ui-otp-modal" aria-hidden="true">
      <div class="ui-modal__backdrop" data-close-modal="true"></div>

      <div
        class="ui-modal__panel"
        role="dialog"
        aria-modal="true"
        aria-labelledby="ui-otp-title"
      >
        <div class="ui-modal__header">
          <h2 id="ui-otp-title" class="ui-modal__title">ثبت‌نام / تایید کد</h2>
          <button
            class="ui-modal__close"
            type="button"
            data-close-modal="true"
            aria-label="بستن"
          >
            ×
          </button>
        </div>

        <div class="ui-modal__body">
          <div class="ui-form">
            <div class="ui-form__group">
              <label class="ui-label" for="otp-phone">شماره تلفن</label>
              <input
                id="otp-phone"
                class="ui-input"
                type="tel"
                inputmode="numeric"
                autocomplete="tel"
                placeholder="مثال: 09xxxxxxxxx"
              />
              <div class="ui-help" id="otp-phone-hint"></div>
            </div>

            <div class="ui-form__group">
              <label class="ui-label" for="otp-code">کد تایید</label>
              <input
                id="otp-code"
                class="ui-input"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                placeholder="کد 6 رقمی"
                maxlength="6"
              />
            </div>

            <div class="ui-form__actions">
<button id="otp-submit" data-mode="send" class="ui-btn ui-btn-primary" type="button" disabled>
                ارسال کد تایید
              </button>

              <div
                class="ui-ajax-status"
                id="otp-status"
                aria-live="polite"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
