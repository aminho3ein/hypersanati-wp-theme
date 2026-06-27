document.addEventListener("DOMContentLoaded", function () {
  const popup = document.getElementById("cartAjaxPopup");
  const closeBtn = document.getElementById("cartAjaxPopupClose");
  const overlay = document.querySelector(".cart-ajax-popup-overlay");

  if (!popup || !closeBtn || !overlay) return;

  popup.classList.remove("is-hidden");
  overlay.classList.remove("is-hidden");

  closeBtn.addEventListener("click", function () {
    popup.classList.add("is-hidden");
    overlay.classList.add("is-hidden");
  });
});