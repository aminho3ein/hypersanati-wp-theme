document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('otp-submit');
    if (!btn) return;

    btn.addEventListener('click', async () => {
        const phone = document.getElementById('otp-phone').value;
        const code = document.getElementById('otp-code').value;
        const mode = btn.dataset.mode; // 'send' or 'verify'

        const response = await fetch(otp_data.ajax_url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: mode === 'send' ? 'ui_send_otp' : 'ui_verify_otp',
                nonce: otp_data.nonce,
                phone,
                code
            })
        });

        const result = await response.json();
        if (!result.success) return alert(result.data);

        if (mode === 'send') {
            btn.dataset.mode = 'verify';
            btn.textContent = 'تایید و ورود';
        } else {
            window.location.href = result.data.redirect;
        }
    });
});
