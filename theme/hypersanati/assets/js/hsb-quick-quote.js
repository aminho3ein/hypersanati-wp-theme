document.addEventListener("click", async function (event) {
    const button = event.target.closest("[data-hsb-quote-id]");
    if (!button || button.disabled) return;

    event.preventDefault();

    const config = window.hsbQuickQuote;
    if (!config) return;

    const original = button.textContent;
    button.disabled = true;
    button.textContent = "در حال افزودن...";

    try {
        const body = new FormData();
        body.append("action", "hsb_preinvoice_add_item");
        body.append("nonce", config.nonce);
        body.append("product_id", button.dataset.hsbQuoteId);
        body.append("quantity", "1");

        const response = await fetch(config.ajaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body
        });

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.data?.message || "ثبت پیش‌فاکتور ناموفق بود.");
        }

        document.dispatchEvent(
            new CustomEvent("hsb_preinvoice_updated", {
                detail: result.data
            })
        );
    } catch (error) {
        alert(error.message);
    } finally {
        button.disabled = false;
        button.textContent = original;
    }
});

/* Direct-purchase card: use the existing WooCommerce popup. */
document.addEventListener("click", function (event) {
    const button = event.target.closest("[data-hsb-direct-id]");
    if (!button) return;

    event.preventDefault();
    event.stopPropagation();

    if (!window.hsbCartPopup) {
        alert("اتصال سبد خرید آماده نیست.");
        return;
    }

    const form = document.createElement("form");
    form.className = "cart";
    form.method = "post";
    form.style.display = "none";

    const id = document.createElement("input");
    id.type = "hidden";
    id.name = "product_id";
    id.value = button.dataset.hsbDirectId;

    const quantity = document.createElement("input");
    quantity.type = "hidden";
    quantity.name = "quantity";
    quantity.value = "1";

    const submit = document.createElement("button");
    submit.type = "submit";
    submit.name = "add-to-cart";
    submit.value = id.value;
    submit.className = "single_add_to_cart_button add-to-cart";

    form.append(id, quantity, submit);
    document.body.appendChild(form);
    form.requestSubmit(submit);
    form.remove();
}, true);

document.addEventListener("keydown", function (event) {
    const button = event.target.closest(
        "[data-hsb-direct-id], [data-hsb-quote-id]"
    );

    if (!button) return;
    if (event.key !== "Enter" && event.key !== " ") return;

    event.preventDefault();
    button.click();
});
