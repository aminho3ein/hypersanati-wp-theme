(function () {
  "use strict";

  // HSB UNIFIED POPUP V1
  // The same HTML/CSS popup is used for both sales flows.
  const config = window.hsbQuotePopupControls;
  if (!config) return;

  let selectedImage = "";

  document.addEventListener("click", function (event) {
    const target = event.target.closest(
      "[data-hsb-quote-id], .product-preinvoice-form button"
    );
    if (!target) return;

    const card = target.closest(
      ".hsb-family-product-card, .product"
    );

    const image = card
      ? card.querySelector("img")
      : document.querySelector(".woocommerce-product-gallery img");

    selectedImage = image
      ? (image.currentSrc || image.src)
      : "";
  }, true);

  document.addEventListener("hsb_preinvoice_updated", function (event) {
    const data = event.detail || {};
    const section = document.getElementById("cartAjaxPopupSection");
    const popup = document.getElementById("cartAjaxPopup");

    if (!section || !popup) return;

    // No separate quote-popup design or quantity controls.
    section.classList.remove("hsb-inquiry-popup");

    const oldQuantity = popup.querySelector(".hsb-quote-popup-qty");
    if (oldQuantity) oldQuantity.remove();

    const summary = document.getElementById("cartAjaxPopupSummaryTitle");
    if (summary) {
      summary.textContent = "محصول به پیش‌فاکتور اضافه شد.";
    }

    const name = data.product_name ||
      data.product_title || "محصول انتخابی";

    const title = document.getElementById(
      "cartAjaxPopupProductNoticeTitle"
    );
    if (title) title.textContent = name;

    const subtitle = popup.querySelector(
      ".cart-ajax-popup__notice-subtitle"
    );
    if (subtitle) {
      subtitle.textContent = "برای استعلام قیمت و موجودی ثبت شد.";
    }

    const image = document.getElementById("cartAjaxPopupProductImage");
    if (image) {
      const imageUrl = data.product_image || selectedImage;
      if (imageUrl) image.src = imageUrl;
      image.alt = name;
    }

    const productWords = document.getElementById("cartAjaxPopupProductText");
    if (productWords) {
      productWords.replaceChildren();
      name.trim().split(/\\s+/).slice(0, 6).forEach(function (word) {
        const item = document.createElement("p");
        item.textContent = word;
        productWords.appendChild(item);
      });
    }

    // Never show misleading zero-price totals for quote-only products.
    const prices = popup.querySelector(".cart-ajax-popup__prices");
    if (prices) prices.style.display = "none";

    const suggestions = document.getElementById(
      "cartAjaxPopupSuggestions"
    );
    if (suggestions) {
      suggestions.innerHTML = typeof data.suggestions_html === "string"
        ? data.suggestions_html
        : "";
    }

    const cartLink = document.getElementById("cartAjaxPopupCartUrl");
    if (cartLink) {
      cartLink.href = config.quoteUrl;
      cartLink.textContent = "مشاهده سبد پیش‌فاکتور";
    }

    const checkoutLink = document.getElementById(
      "cartAjaxPopupCheckoutUrl"
    );
    if (checkoutLink) {
      checkoutLink.hidden = true;
      checkoutLink.style.display = "none";
    }

    section.classList.remove("is-hidden");
    section.setAttribute("aria-hidden", "false");
    document.body.classList.add("cart-ajax-popup-open");
  });
})();
