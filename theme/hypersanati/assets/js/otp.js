document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('ui-otp-modal');
    const openBtn = document.getElementById('ui-open-otp');

    const phoneEl = document.getElementById('otp-phone');
    const boxesEl = document.getElementById('otp-boxes');

    const sendBtn = document.getElementById('otp-send-btn');
    const verifyBtn = document.getElementById('otp-verify-btn');

    const phoneStep = document.querySelector('[data-step="phone"]');
    const codeStep = document.querySelector('[data-step="code"]');

    const phoneDisplay = document.getElementById('otp-phone-display');
    const statusEl = document.getElementById('otp-status');

    let mobile = '';


    const OTP_LENGTH = 6;

    if (boxesEl) {

        for (let i = 0; i < OTP_LENGTH; i++) {

            const input = document.createElement('input');

            input.type = 'tel';
            input.inputMode = 'numeric';
            input.maxLength = 1;
            input.className = 'ui-otp-input';

            input.addEventListener('input', () => {

                if (input.value && input.nextElementSibling) {
                    input.nextElementSibling.focus();
                }


                const code = [...document.querySelectorAll('.ui-otp-input')]
                    .map(el => el.value)
                    .join('');


                if (verifyBtn) {

                    verifyBtn.disabled = code.length !== OTP_LENGTH;

                }

            });

            boxesEl.appendChild(input);

        }

    }


    const api = hsb_auth_data.rest_url;


    if (openBtn && modal) {

        openBtn.addEventListener('click', (e) => {

            e.preventDefault();

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');

        });

    }


    if (modal) {

        modal.querySelectorAll('[data-close-modal]').forEach((el) => {

            el.addEventListener('click', () => {

                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');

            });

        });

    }


    if (phoneEl && sendBtn) {

        phoneEl.addEventListener('input', () => {

            sendBtn.disabled = !/^09\d{9}$/.test(
                phoneEl.value
            );

        });

    }


    if (sendBtn) {

        sendBtn.addEventListener('click', async () => {


            mobile = phoneEl.value;


            statusEl.textContent = 'در حال ارسال...';


            const response = await fetch(
                api + 'request-otp',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': hsb_auth_data.nonce
                    },
                    body: JSON.stringify({
                        mobile
                    })
                }
            );


            const result = await response.json();


            if (result.status === 'ready') {


                phoneStep.classList.add('is-hidden');

                codeStep.classList.remove('is-hidden');


                phoneDisplay.textContent = result.mobile;


                statusEl.textContent =
                    'کد تایید ارسال شد';


            } else {

                statusEl.textContent =
                    result.message || 'خطا در ارسال کد';

                sendBtn.disabled = false;

            }


        });

    }



    if (verifyBtn) {

        verifyBtn.addEventListener('click', async () => {


            const code = [...document.querySelectorAll('.ui-otp-input')]
                .map(el => el.value)
                .join('');


            statusEl.textContent =
                'در حال بررسی...';


            const response = await fetch(
                api + 'verify-otp',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': hsb_auth_data.nonce
                    },
                    body: JSON.stringify({
                        mobile,
                        code
                    })
                }
            );


            const result = await response.json();


            if (result.status === 'success') {


                statusEl.textContent =
                    'ورود موفق';


                window.location.href =
                    hsb_auth_data.redirect_url ||
                    '/my-account/';


            } else {


                statusEl.textContent =
                    result.message || 'کد اشتباه است';


            }


        });

    }


});
