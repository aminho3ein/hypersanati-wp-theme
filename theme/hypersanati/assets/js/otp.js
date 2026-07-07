document.addEventListener('DOMContentLoaded', () => {
    const modal    = document.getElementById('ui-otp-modal');
    const openBtn  = document.getElementById('ui-open-otp');
    const phoneEl  = document.getElementById('otp-phone');
    const codeEl   = document.getElementById('otp-code');
    const btn      = document.getElementById('otp-submit');
    const statusEl = document.getElementById('otp-status');

    // ------------------------------------------
    // باز کردن مودال با کلیک روی دکمه‌ی "حساب کاربری"
    // ------------------------------------------
    if (openBtn && modal) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
        });
    }

    // ------------------------------------------
    // بستن مودال (بک‌دراپ یا دکمه‌ی ضربدر)
    // ------------------------------------------
    if (modal) {
        modal.querySelectorAll('[data-close-modal]').forEach((el) => {
            el.addEventListener('click', () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            });
        });

        // بستن با کلید Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }
        });
    }

    // ------------------------------------------
    // فعال/غیرفعال کردن دکمه بر اساس اعتبار شماره موبایل
    // ------------------------------------------
    if (phoneEl && btn) {
        phoneEl.addEventListener('input', () => {
            if (btn.dataset.mode === 'send') {
                btn.disabled = !/^09\d{9}$/.test(phoneEl.value);
            }
        });
    }

    if (!btn) return;

    // ------------------------------------------
    // ارسال / تایید کد OTP
    // ------------------------------------------
    btn.addEventListener('click', async () => {
        const phone = phoneEl ? phoneEl.value : '';
        const code  = codeEl ? codeEl.value : '';
        const mode  = btn.dataset.mode; // 'send' یا 'verify'

        btn.disabled = true;
        if (statusEl) statusEl.textContent = 'در حال ارسال...';

        try {
            const response = await fetch(otp_data.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: mode === 'send' ? 'ui_send_otp' : 'ui_verify_otp',
                    nonce: otp_data.nonce,
                    phone,
                    code,
                }),
            });

            const result = await response.json();

            if (!result.success) {
                if (statusEl) statusEl.textContent = result.data.message || 'خطایی رخ داد.';
                btn.disabled = false;
                return;
            }

            if (mode === 'send') {
                btn.dataset.mode = 'verify';
                btn.textContent = 'تایید و ورود';
                btn.disabled = false;
                if (statusEl) statusEl.textContent = result.data.message || 'کد ارسال شد.';
            } else {
                if (statusEl) statusEl.textContent = result.data.message || 'در حال انتقال...';
                window.location.href = result.data.redirect;
            }
        } catch (err) {
            if (statusEl) statusEl.textContent = 'خطا در ارتباط با سرور.';
            btn.disabled = false;
        }
    });
});