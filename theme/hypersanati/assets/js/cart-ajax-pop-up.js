document.addEventListener("DOMContentLoaded", function () {
  const section = document.getElementById("cartAjaxPopupSection");
  const popup = document.getElementById("cartAjaxPopup");
  const closeBtn = document.getElementById("cartAjaxPopupClose");
  const overlay = document.getElementById("cartAjaxPopupOverlay");
  const continueBtn = document.getElementById("cartAjaxPopupContinue");

  const summaryTitle = document.getElementById("cartAjaxPopupSummaryTitle");
  const subtotalEl = document.getElementById("cartAjaxPopupSubtotal");
  const taxEl = document.getElementById("cartAjaxPopupTax");
  const totalEl = document.getElementById("cartAjaxPopupTotal");
  const productNoticeTitle = document.getElementById("cartAjaxPopupProductNoticeTitle");
  const productText = document.getElementById("cartAjaxPopupProductText");
  const productImage = document.getElementById("cartAjaxPopupProductImage");
  const suggestions = document.getElementById("cartAjaxPopupSuggestions");
  const cartUrl = document.getElementById("cartAjaxPopupCartUrl");
  const checkoutUrl = document.getElementById("cartAjaxPopupCheckoutUrl");

  if (!section || !popup || !closeBtn || !overlay) return;

  function openPopup() {
    section.classList.remove("is-hidden");
    section.setAttribute("aria-hidden", "false");
    document.body.classList.add("cart-ajax-popup-open");
  }

  function closePopup() {
    section.classList.add("is-hidden");
    section.setAttribute("aria-hidden", "true");
    document.body.classList.remove("cart-ajax-popup-open");
  }

  function renderProductWords(title) {
    if (!productText) return;

    productText.innerHTML = "";

    const words = title.trim().split(/\s+/).slice(0, 6);

    words.forEach(function (word) {
      const p = document.createElement("p");
      p.textContent = word;
      productText.appendChild(p);
    });
  }

  function updateFragments(fragments) {
    if (!fragments) return;

    Object.keys(fragments).forEach(function (selector) {
      document.querySelectorAll(selector).forEach(function (element) {
        const temp = document.createElement("div");
        temp.innerHTML = fragments[selector];

        const newElement = temp.firstElementChild;

        if (newElement) {
          element.replaceWith(newElement);
        }
      });
    });
  }

  function fillPopup(data) {
    const productTitle = data.product_title || "این محصول";

    if (summaryTitle) {
      summaryTitle.textContent =
        "در سبد خرید شما " + data.cart_count + " آیتم موجود است.";
    }

    if (subtotalEl) subtotalEl.textContent = data.subtotal || "۰ تومان";
    if (taxEl) taxEl.textContent = data.tax || "۰ تومان";
    if (totalEl) totalEl.textContent = data.total || "۰ تومان";

    if (productNoticeTitle) {
      productNoticeTitle.textContent = productTitle;
    }

    if (productImage && data.product_image) {
      productImage.src = data.product_image;
      productImage.alt = productTitle;
    }

    renderProductWords(productTitle);

    if (suggestions) {
      suggestions.innerHTML = data.suggestions_html || "";
    }

    if (cartUrl && data.cart_url) {
      cartUrl.href = data.cart_url;
    }

    if (checkoutUrl && data.checkout_url) {
      checkoutUrl.href = data.checkout_url;
    }
  }

  closeBtn.addEventListener("click", closePopup);
  overlay.addEventListener("click", closePopup);

  if (continueBtn) {
    continueBtn.addEventListener("click", closePopup);
  }

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closePopup();
    }
  });

  document.addEventListener("submit", function (event) {
    const form = event.target;

    if (!form.classList.contains("cart")) return;

    const submitButton =
      event.submitter ||
      form.querySelector(".single_add_to_cart_button.add-to-cart");

    if (!submitButton || !submitButton.classList.contains("add-to-cart")) {
      return;
    }

    event.preventDefault();

    if (submitButton.classList.contains("loading")) return;

    const formData = new FormData(form);

    formData.append("action", "hsb_ajax_add_to_cart");
    formData.append("nonce", hsbCartPopup.nonce);

    if (!formData.get("product_id") && submitButton.value) {
      formData.append("product_id", submitButton.value);
    }

    if (!formData.get("add-to-cart") && submitButton.value) {
      formData.append("add-to-cart", submitButton.value);
    }

    const oldButtonText = submitButton.textContent;

    submitButton.classList.add("loading");
    submitButton.disabled = true;
    submitButton.textContent = "در حال افزودن...";

    fetch(hsbCartPopup.ajax_url, {
      method: "POST",
      credentials: "same-origin",
      body: formData,
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (response) {
        if (!response.success) {
          throw new Error(
            response.data && response.data.message
              ? response.data.message
              : "خطا در افزودن محصول به سبد خرید."
          );
        }

        fillPopup(response.data);
        updateFragments(response.data.fragments);
        openPopup();
      })
      .catch(function (error) {
        alert(error.message);
      })
      .finally(function () {
        submitButton.classList.remove("loading");
        submitButton.disabled = false;
        submitButton.textContent = oldButtonText;
      });
  });
});