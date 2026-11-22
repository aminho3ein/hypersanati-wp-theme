(function () {
    "use strict";

    function init() {
        const section = document.querySelector(
            "section.cart-page-items"
        );

        if (!section) return;

        const container =
            section.querySelector(":scope > .container") ||
            section.querySelector(".container") ||
            section;

        const oldTabs = Array.from(
            document.querySelectorAll(".hsb-dual-cart-tabs")
        );

        const tabs = oldTabs[0] || document.createElement("nav");

        oldTabs.slice(1).forEach(el => el.remove());

        tabs.className =
            "hsb-dual-cart-tabs hsb-dual-tabs-unified";

        tabs.setAttribute("aria-label", "انتخاب نوع سبد");

        const current = new URL(window.location.href);
        const direct = current.searchParams.get("hsb_view") === "direct";

        const base = new URL(current.href);
        base.searchParams.delete("hsb_view");
        base.hash = "";

        const quoteUrl = base.href;

        const directUrl = new URL(base.href);
        directUrl.searchParams.set("hsb_view", "direct");

        function tab(label, href, icon, active) {
            const a = document.createElement("a");
            a.href = href;
            a.className = active ? "is-active" : "";

            if (active) a.setAttribute("aria-current", "page");

            const i = document.createElement("i");
            i.className = icon;
            i.setAttribute("aria-hidden", "true");

            const span = document.createElement("span");
            span.textContent = label;

            a.append(i, span);
            return a;
        }

        tabs.replaceChildren(
            tab(
                "سبد خرید",
                directUrl.href,
                "fa-solid fa-cart-shopping",
                direct
            ),
            tab(
                "سبد پیش‌فاکتور",
                quoteUrl,
                "fa-solid fa-file-invoice",
                !direct
            )
        );

        // Same placement in both pages, empty or populated.
        container.prepend(tabs);

        const state = window.hsbDualCartState;

        if (!state) return;

        const directEmpty = !!state.directEmpty;
        const quoteEmpty = !!state.quoteEmpty;

        // Only change empty-state content.
        if (direct ? !directEmpty : !quoteEmpty) return;

        const emptyBox = section.querySelector(
            ".cart-page-items__empty, .preinvoice-empty-state"
        );

        const bothEmpty = directEmpty && quoteEmpty;

        const title = bothEmpty
            ? "سبد خرید و سبد پیش‌فاکتور شما خالی هستند"
            : direct
                ? "سبد خرید شما خالی است"
                : "سبد پیش‌فاکتور شما خالی است";

        const description = bothEmpty
            ? "هنوز محصولی برای خرید مستقیم یا دریافت پیش‌فاکتور انتخاب نکرده‌اید. از فروشگاه محصولات موردنیازتان را انتخاب کنید."
            : direct
                ? "هنوز محصولی برای خرید مستقیم انتخاب نکرده‌اید."
                : "هنوز محصولی به پیش‌فاکتور اضافه نکرده‌اید.";

        if (emptyBox) {
            const heading = emptyBox.querySelector("h1, h2, h3");
            const paragraph = emptyBox.querySelector("p");

            if (heading) {
                heading.textContent = title;
            } else if (paragraph) {
                paragraph.textContent = title;
            }

            if (heading && paragraph) {
                paragraph.textContent = description;
            }
        }

        if (bothEmpty) {
            const pageTitle = section.querySelector(
                ".cart-page-items__title"
            );

            if (pageTitle) pageTitle.textContent = "سبدهای من";

            section.querySelectorAll(
                "h2, h3, .preinvoice-status-badge"
            ).forEach(el => {
                if (el.closest(".cart-page-items__empty")) return;

                if (el.textContent.trim() === "پیش‌فاکتور فعلی") {
                    el.textContent = "سبدهای خالی";
                }
            });
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
