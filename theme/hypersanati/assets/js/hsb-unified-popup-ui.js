/* HSB UNIFIED POPUP FINAL V1 */
document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  const section = document.getElementById("cartAjaxPopupSection");
  const popup = document.getElementById("cartAjaxPopup");
  if (!section || !popup) return;

  const summary = popup.querySelector(".cart-ajax-popup__summary-box");
  const notice = popup.querySelector(".cart-ajax-popup__notice");
  const productText = popup.querySelector(".cart-ajax-popup__product-text");
  const prices = popup.querySelector(".cart-ajax-popup__prices");
  const suggestions = document.getElementById("cartAjaxPopupSuggestions");
  const relatedTitle = popup.querySelector(".cart-ajax-popup__bottom-title");
  const cartLink = document.getElementById("cartAjaxPopupCartUrl");
  const checkoutLink = document.getElementById("cartAjaxPopupCheckoutUrl");

  // Move textual information to the left summary panel.
  // Keep the actual selected product image in the right panel.
  if (summary && notice) {
    summary.prepend(notice);
  }

  if (summary && productText) {
    if (prices) summary.insertBefore(productText, prices);
    else summary.appendChild(productText);
  }

  function updateRelatedVisibility() {
    const hasItems = Boolean(
      suggestions &&
      suggestions.innerHTML.trim() &&
      suggestions.children.length
    );

    if (relatedTitle) relatedTitle.hidden = !hasItems;
    if (suggestions) suggestions.hidden = !hasItems;

    popup.classList.toggle("hsb-popup-has-related", hasItems);
  }

  if (suggestions) {
    new MutationObserver(updateRelatedVisibility).observe(suggestions, {
      childList: true,
      subtree: true,
      characterData: true
    });
  }

  updateRelatedVisibility();

  function setMode(mode) {
    const quote = mode === "quote";
    section.classList.remove("hsb-inquiry-popup");
    section.classList.toggle("hsb-unified-quote", quote);
    section.classList.toggle("hsb-unified-direct", !quote);

    const oldQty = popup.querySelector(".hsb-quote-popup-qty");
    if (oldQty) oldQty.remove();

    if (prices) prices.hidden = quote;

    if (checkoutLink) {
      checkoutLink.hidden = quote;
      checkoutLink.style.display = quote ? "none" : "";
    }

    if (!quote && cartLink) {
      cartLink.textContent = "ویرایش سبد خرید";
    }
  }

  async function loadQuoteRelated(productId) {
    if (!suggestions) return;

    suggestions.innerHTML = "";
    updateRelatedVisibility();

    const body = new FormData();
    body.set("action", "hsb_popup_related");
    body.set("product_id", String(productId));

    try {
      const response = await fetch(hsbUnifiedPopup.ajaxUrl, {
        method: "POST",
        body,
        credentials: "same-origin"
      });

      const result = await response.json();

      // Ignore a delayed quote response if the popup now
      // displays a direct-purchase confirmation.
      if (!section.classList.contains("hsb-unified-quote")) return;
      if (String(section.dataset.hsbRelatedProduct) !== String(productId)) {
        return;
      }

      suggestions.innerHTML = (
        result.success && result.data &&
        typeof result.data.html === "string"
      ) ? result.data.html : "";

      updateRelatedVisibility();
    } catch (error) {
      suggestions.innerHTML = "";
      updateRelatedVisibility();
      console.warn("Related product request failed:", error);
    }
  }

  document.addEventListener("hsb_preinvoice_updated", function (event) {
    const data = event.detail || {};

    setMode("quote");

    const productId = Number(data.product_id) || 0;
    section.dataset.hsbRelatedProduct = String(productId);

    if (cartLink) {
      const url = window.hsbQuotePopupControls?.quoteUrl;
      if (url) cartLink.href = url;
      cartLink.textContent = "مشاهده سبد پیش‌فاکتور";
    }

    const title = document.getElementById("cartAjaxPopupSummaryTitle");
    if (title) title.textContent = "محصول به پیش‌فاکتور اضافه شد.";

    if (productId) loadQuoteRelated(productId);
    else {
      if (suggestions) suggestions.innerHTML = "";
      updateRelatedVisibility();
    }
  });

  function directMode() {
    setMode("direct");
    delete section.dataset.hsbRelatedProduct;
    updateRelatedVisibility();
  }

  document.addEventListener("hsb_direct_cart_updated", directMode);
  document.addEventListener("hsb_card_direct_added", directMode);
});
