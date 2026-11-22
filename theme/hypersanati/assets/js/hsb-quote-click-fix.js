(function () {
    "use strict";

    document.addEventListener("click", async function (event) {
        const target = event.target.closest("[data-hsb-quote-id]");

        if (!target) return;

        // Prevent the parent product-card link from navigating.
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        if (target.dataset.hsbBusy === "1") return;

        const config = window.hsbQuickQuote;

        if (!config || !config.ajaxUrl || !config.nonce) {
            console.error("HSB: quote AJAX settings unavailable.");
            return;
        }

        const productId = parseInt(target.dataset.hsbQuoteId, 10);

        if (!Number.isInteger(productId) || productId < 1) {
            console.error("HSB: invalid quote product ID.");
            return;
        }

        const label = target.textContent;
        target.dataset.hsbBusy = "1";
        target.setAttribute("aria-disabled", "true");
        target.textContent = "در حال افزودن...";

        try {
            const body = new FormData();
            body.set("action", "hsb_preinvoice_add_item");
            body.set("nonce", config.nonce);
            body.set("product_id", String(productId));
            body.set("quantity", "1");

            const response = await fetch(config.ajaxUrl, {
                method: "POST",
                credentials: "same-origin",
                body: body
            });

            const json = await response.json();

            if (!response.ok || !json.success) {
                throw new Error(
                    json.data?.message ||
                    "محصول به پیش‌فاکتور اضافه نشد."
                );
            }

            // Existing popup and counter scripts listen for this event.
            document.dispatchEvent(
                new CustomEvent("hsb_preinvoice_updated", {
                    detail: json.data || {}
                })
            );

        } catch (error) {
            console.error("HSB quote add error:", error);
            alert(error.message);
        } finally {
            delete target.dataset.hsbBusy;
            target.removeAttribute("aria-disabled");
            target.textContent = label;
        }
    }, true);
})();
