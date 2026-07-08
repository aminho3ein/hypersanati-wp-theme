document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm-password");
  const errorBox = document.getElementById("confirmPasswordError");
  const toggleButtons = document.querySelectorAll("[data-toggle-password]");

  function showPasswordTemporarily(input) {
    if (input) {
      input.type = "text";
    }
  }

  function hidePassword(input) {
    if (input) {
      input.type = "password";
    }
  }

  toggleButtons.forEach(function (button) {
    const inputId = button.getAttribute("data-toggle-password");
    const targetInput = document.getElementById(inputId);

    if (!targetInput) return;

    button.addEventListener("mousedown", function () {
      showPasswordTemporarily(targetInput);
    });

    button.addEventListener("mouseup", function () {
      hidePassword(targetInput);
    });

    button.addEventListener("mouseleave", function () {
      hidePassword(targetInput);
    });

    button.addEventListener("touchstart", function () {
      showPasswordTemporarily(targetInput);
    });

    button.addEventListener("touchend", function () {
      hidePassword(targetInput);
    });

    button.addEventListener("touchcancel", function () {
      hidePassword(targetInput);
    });
  });

  function validatePasswords() {
    if (!passwordInput || !confirmPasswordInput || !errorBox) return true;

    const passwordValue = passwordInput.value.trim();
    const confirmPasswordValue = confirmPasswordInput.value.trim();

    passwordInput.classList.remove("is-error");
    confirmPasswordInput.classList.remove("is-error");
    errorBox.textContent = "";

    if (confirmPasswordValue === "") {
      return true;
    }

    if (passwordValue !== confirmPasswordValue) {
      errorBox.textContent = "رمز عبور و تکرار رمز عبور یکسان نیستند.";
      passwordInput.classList.add("is-error");
      confirmPasswordInput.classList.add("is-error");
      return false;
    }

    return true;
  }

  if (passwordInput && confirmPasswordInput) {
    passwordInput.addEventListener("input", validatePasswords);
    confirmPasswordInput.addEventListener("input", validatePasswords);
  }

  const accordionButton = document.getElementById("orderSummaryToggle");
  const accordionContent = document.getElementById("orderSummaryContent");

  if (accordionButton && accordionContent) {
    accordionButton.addEventListener("click", function () {
      const isExpanded = accordionButton.getAttribute("aria-expanded") === "true";

      accordionButton.setAttribute("aria-expanded", String(!isExpanded));
      accordionContent.classList.toggle("is-open");
    });
  }

  const customPlaceOrderButton = document.getElementById("hsbCustomPlaceOrder");

  if (customPlaceOrderButton) {
    customPlaceOrderButton.addEventListener("click", function () {
      if (!validatePasswords()) return;

      const realPlaceOrderButton = document.getElementById("place_order");

      if (realPlaceOrderButton) {
        realPlaceOrderButton.click();
      }
    });
  }

  const firstNameInput = document.getElementById("billing_first_name");
  const lastNameInput = document.getElementById("billing_last_name");
  const phoneInput = document.getElementById("billing_phone");
  const stateInput = document.getElementById("billing_state");
  const cityInput = document.getElementById("billing_city");
  const addressInput = document.getElementById("billing_address_1");
  const plaqueInput = document.getElementById("billing_plaque");
  const unitInput = document.getElementById("billing_address_2");
  const postcodeInput = document.getElementById("billing_postcode");

  const summaryRecipient = document.querySelector("[data-summary-recipient]");
  const summaryMobile = document.querySelector("[data-summary-mobile]");
  const summaryAddress = document.querySelector("[data-summary-address]");

  function updateCheckoutSummary() {
    const firstName = firstNameInput ? firstNameInput.value.trim() : "";
    const lastName = lastNameInput ? lastNameInput.value.trim() : "";
    const phone = phoneInput ? phoneInput.value.trim() : "";

    const addressParts = [
      stateInput ? stateInput.value.trim() : "",
      cityInput ? cityInput.value.trim() : "",
      addressInput ? addressInput.value.trim() : "",
      plaqueInput ? "پلاک " + plaqueInput.value.trim() : "",
      unitInput ? "واحد " + unitInput.value.trim() : "",
      postcodeInput ? "کدپستی " + postcodeInput.value.trim() : "",
    ].filter(Boolean);

    if (summaryRecipient) {
      summaryRecipient.textContent =
        firstName || lastName ? firstName + " " + lastName : "نام و نام خانوادگی مخاطب";
    }

    if (summaryMobile) {
      summaryMobile.textContent = phone || "۰۹۱۲۰۰۰۰۰۰۰";
    }

    if (summaryAddress) {
      summaryAddress.textContent =
        addressParts.length > 0 ? addressParts.join("، ") : "استان، شهر، خیابان، کوچه، پلاک، واحد، کدپستی";
    }
  }

  [
    firstNameInput,
    lastNameInput,
    phoneInput,
    stateInput,
    cityInput,
    addressInput,
    plaqueInput,
    unitInput,
    postcodeInput,
  ].forEach(function (input) {
    if (input) {
      input.addEventListener("input", updateCheckoutSummary);
      input.addEventListener("change", updateCheckoutSummary);
    }
  });

  updateCheckoutSummary();
});