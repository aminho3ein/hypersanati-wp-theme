(() => {
    "use strict";

    const root =
        document.querySelector(
            "[data-hsb-staff-auth]"
        );

    if (
        !root ||
        typeof HSBStaffAuth === "undefined"
    ) {
        return;
    }

    const form =
        root.querySelector(
            "[data-hsb-staff-form]"
        );

    const username =
        root.querySelector(
            "[data-hsb-staff-username]"
        );

    const password =
        root.querySelector(
            "[data-hsb-staff-password]"
        );

    const remember =
        root.querySelector(
            "[data-hsb-staff-remember]"
        );

    const button =
        root.querySelector(
            "[data-hsb-staff-submit]"
        );

    const message =
        root.querySelector(
            "[data-hsb-staff-message]"
        );


    let pendingUserId = 0;
    let pendingMobileMasked = "";

    const otpWrapper =
        root.querySelector(
            "[data-hsb-staff-otp-wrapper]"
        );

    const otpInput =
        root.querySelector(
            "[data-hsb-staff-otp]"
        );

    if (
        !form ||
        !username ||
        !password ||
        !button ||
        !message
    ) {
        return;
    }

    const showMessage = (
        text,
        type = ""
    ) => {
        message.textContent = text || "";
        message.className =
            "hsb-staff-auth__message";

        if (type) {
            message.classList.add(
                `is-${type}`
            );
        }
    };

    form.addEventListener(
        "submit",
        async (event) => {
            event.preventDefault();

            if (
                pendingUserId &&
                otpInput &&
                otpInput.value.trim()
            ) {

                const otpBody =
                    new FormData();

                otpBody.append(
                    "action",
                    "hsb_staff_verify_otp"
                );

                otpBody.append(
                    "nonce",
                    HSBStaffAuth.nonce
                );

                otpBody.append(
                    "user_id",
                    pendingUserId
                );

                otpBody.append(
                    "code",
                    otpInput.value.trim()
                );

                const otpResponse =
                    await fetch(
                        HSBStaffAuth.ajaxUrl,
                        {
                            method: "POST",
                            credentials: "same-origin",
                            body: otpBody,
                        }
                    );

                const otpResult =
                    await otpResponse.json();

                if (
                    !otpResult ||
                    !otpResult.success
                ) {
                    throw new Error(
                        otpResult &&
                        otpResult.data &&
                        otpResult.data.message
                            ? otpResult.data.message
                            : "کد تایید صحیح نیست."
                    );
                }

                showMessage(
                    "ورود موفق بود. در حال انتقال…",
                    "success"
                );

                window.location.assign(
                    otpResult.data.redirect
                );

                return;
            }

            const login =
                username.value.trim();

            const pass =
                password.value;

            if (!login || !pass) {
                showMessage(
                    "نام کاربری و رمز عبور را وارد کنید.",
                    "error"
                );
                return;
            }

            button.disabled = true;
            button.classList.add(
                "is-loading"
            );

            showMessage(
                "در حال بررسی اطلاعات ورود…"
            );

            const body =
                new FormData();

            body.append(
                "action",
                "hsb_staff_credentials"
            );

            body.append(
                "nonce",
                HSBStaffAuth.nonce
            );

            body.append(
                "username",
                login
            );

            body.append(
                "password",
                pass
            );

            body.append(
                "remember",
                remember &&
                remember.checked
                    ? "1"
                    : ""
            );

            try {
                const response =
                    await fetch(
                        HSBStaffAuth.ajaxUrl,
                        {
                            method: "POST",
                            credentials: "same-origin",
                            body,
                        }
                    );

                const result =
                    await response.json();

                if (
                    !result ||
                    !result.success
                ) {
                    throw new Error(
                        result &&
                        result.data &&
                        result.data.message
                            ? result.data.message
                            : "ورود انجام نشد."
                    );
                }

                if (otpWrapper) {
                    otpWrapper.hidden = false;
                }

                pendingUserId =
                    result.data.user_id || 0;

                pendingMobileMasked =
                    result.data.mobile_masked || "";

                if (otpInput) {
                    otpInput.focus();
                }

                showMessage(
                    pendingMobileMasked
                        ? `کد تایید به شماره ${pendingMobileMasked} ارسال شد.`
                        : "کد تایید ارسال شد.",
                    "success"
                );

                button.disabled = false;
                button.classList.remove(
                    "is-loading"
                );
            } catch (error) {
                showMessage(
                    error &&
                    error.message
                        ? error.message
                        : "خطایی در ورود رخ داد.",
                    "error"
                );

                button.disabled = false;
                button.classList.remove(
                    "is-loading"
                );
            }
        }
    );
})();
