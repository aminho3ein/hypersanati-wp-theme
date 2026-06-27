document.addEventListener("DOMContentLoaded", function () {
  const cartRows = document.querySelectorAll(".cart-page-items__row");

  cartRows.forEach((row) => {
    const plusBtn = row.querySelector(".cart-page-items__qty-btn--plus");
    const minusBtn = row.querySelector(".cart-page-items__qty-btn--minus");
    const qtyValue = row.querySelector(".cart-page-items__qty-value");
    const removeBtn = row.querySelector(".cart-page-items__remove");

    if (plusBtn && qtyValue) {
      plusBtn.addEventListener("click", function () {
        let currentValue = parseInt(qtyValue.textContent.trim(), 10) || 1;
        qtyValue.textContent = currentValue + 1;
      });
    }

    if (minusBtn && qtyValue) {
      minusBtn.addEventListener("click", function () {
        let currentValue = parseInt(qtyValue.textContent.trim(), 10) || 1;
        if (currentValue > 1) {
          qtyValue.textContent = currentValue - 1;
        }
      });
    }

    if (removeBtn) {
      removeBtn.addEventListener("click", function () {
        row.style.display = "none";
      });
    }
  });
});