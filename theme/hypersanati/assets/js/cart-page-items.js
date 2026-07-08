document.addEventListener("DOMContentLoaded", function () {
  const couponToggle = document.querySelector(".cart-sidebar-card__toggle");
  const couponBox = document.querySelector(".cart-sidebar-card__coupon");

  if (couponToggle && couponBox) {
    couponToggle.addEventListener("click", function () {
      couponBox.classList.toggle("is-hidden");
      couponToggle.classList.toggle("is-active");
    });
  }

  document.addEventListener("click", function (event) {
    const plusBtn = event.target.closest(".cart-page-items__qty-btn--plus");
    const minusBtn = event.target.closest(".cart-page-items__qty-btn--minus");

    if (!plusBtn && !minusBtn) return;

    const quantityWrapper = event.target.closest(".quantity");

    if (!quantityWrapper) return;

    const input = quantityWrapper.querySelector(".qty");

    if (!input) return;

    const min = parseFloat(input.getAttribute("min")) || 0;
    const max = parseFloat(input.getAttribute("max")) || 999999;
    const step = parseFloat(input.getAttribute("step")) || 1;
    let value = parseFloat(input.value) || 0;

    if (plusBtn) {
      value += step;
    }

    if (minusBtn) {
      value -= step;
    }

    if (value < min) value = min;
    if (value > max) value = max;

    input.value = value;
    input.dispatchEvent(new Event("change", { bubbles: true }));
  });

  document.addEventListener("change", function (event) {
    if (!event.target.classList.contains("qty")) return;

    const updateButton = document.querySelector(".cart-page-items__update-btn");

    if (updateButton) {
      updateButton.disabled = false;
      updateButton.classList.add("is-active");
    }
  });
});