(function () {
    "use strict";

    let running = false;

    function paint(counts) {
        const direct = Number(counts.direct) || 0;
        const quote = Number(counts.quote) || 0;

        document.querySelectorAll(".hsb-direct-header-badge").forEach(el => {
            el.textContent = direct.toLocaleString("fa-IR");
            el.classList.toggle("is-empty", direct === 0);
        });

        document.querySelectorAll(".preinvoice-count-badge").forEach(el => {
            el.textContent = quote.toLocaleString("fa-IR");
            el.classList.toggle("is-empty", quote === 0);
        });

        document.querySelectorAll("[data-hsb-count]").forEach(el => {
            const count = el.dataset.hsbCount === "direct" ? direct : quote;
            el.textContent = count.toLocaleString("fa-IR");
        });
    }

    async function refresh() {
        if (running || !window.hsbCountConfig) return;
        running = true;

        try {
            const url = new URL(hsbCountConfig.ajaxUrl);
            url.searchParams.set("action", "hsb_dual_cart_counts");

            const response = await fetch(url, {
                credentials: "same-origin",
                cache: "no-store"
            });

            if (!response.ok) return;

            const json = await response.json();
            if (json.success && json.data) paint(json.data);
        } catch (error) {
            console.warn("Cart count update failed", error);
        } finally {
            running = false;
        }
    }

    document.addEventListener("hsb_preinvoice_updated", refresh);
    document.addEventListener("hsb_quote_quantity_changed", refresh);
    document.addEventListener("hsb_direct_cart_updated", refresh);
    document.addEventListener("hsb_card_direct_added", refresh);

    if (window.jQuery) {
        window.jQuery(document.body).on(
            "added_to_cart removed_from_cart wc_fragments_refreshed",
            refresh
        );
    }

    window.addEventListener("pageshow", refresh);
})();

/* HSB MATCHED HEADER ICONS V1 */
(function () {
    "use strict";

    function syncHeaderIcons() {
        // Direct purchase cart: shopping cart
        document.querySelectorAll(
            ".hsb-direct-header-link"
        ).forEach(function (link) {
            const icon = link.querySelector("i");

            if (icon) {
                icon.className = "fa-solid fa-cart-shopping";
                icon.setAttribute("aria-hidden", "true");
            }
        });

        // Quote cart: invoice document
        document.querySelectorAll(
            ".preinvoice-cart-link:not(.hsb-direct-header-link)"
        ).forEach(function (link) {
            const icon = link.querySelector("i");

            if (icon) {
                icon.className = "fa-solid fa-file-invoice";
                icon.setAttribute("aria-hidden", "true");
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", syncHeaderIcons);
    } else {
        syncHeaderIcons();
    }
})();

/* HSB HEADER POSITION FIX V2 */
(function () {
    "use strict";

    function fixHeaderCartOrder() {
        const directCarts = document.querySelectorAll(
            "header .hsb-direct-header-cart"
        );

        directCarts.forEach(function (direct) {
            const parent = direct.parentElement;
            if (!parent) return;

            const quote = Array.from(parent.children).find(function (el) {
                return el.matches(".cart.preinvoice-cart");
            });

            if (!quote) return;

            const directX = direct.getBoundingClientRect().left;
            const quoteX = quote.getBoundingClientRect().left;

            // Swap only if purchase cart is left of quote cart.
            if (directX < quoteX) {
                const marker = document.createComment("hsb-cart-position");
                direct.replaceWith(marker);
                quote.replaceWith(direct);
                marker.replaceWith(quote);
            }
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", fixHeaderCartOrder);
    } else {
        fixHeaderCartOrder();
    }
})();
