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

  toggleButtons.forEach((button) => {
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
});