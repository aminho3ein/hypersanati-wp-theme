document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".contact-form");
  const fileInput = document.getElementById("contact-file");
  const fileNameDisplay = document.getElementById("file-name-display");

  if (fileInput && fileNameDisplay) {
    fileInput.addEventListener("change", function () {
      if (fileInput.files && fileInput.files.length > 0) {
        fileNameDisplay.value = fileInput.files[0].name;
      } else {
        fileNameDisplay.value = "";
      }
    });
  }

  if (!form || typeof HypersanatiContactAjax === "undefined") {
    return;
  }

  form.addEventListener("submit", async function (event) {
    event.preventDefault();

    const submitButton = form.querySelector(".contact-submit-btn");
    const originalButtonText = submitButton ? submitButton.textContent : "";

    if (submitButton) {
      submitButton.disabled = true;
      submitButton.classList.add("is-loading");
      submitButton.textContent = HypersanatiContactAjax.sending_text || "در حال ارسال...";
    }

    const formData = new FormData(form);

    try {
      const response = await fetch(HypersanatiContactAjax.ajax_url, {
        method: "POST",
        credentials: "same-origin",
        body: formData,
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        const errorMessage =
          result && result.data && result.data.message
            ? result.data.message
            : "ارسال پیام انجام نشد. لطفاً دوباره تلاش کنید.";

        throw new Error(errorMessage);
      }

      const successMessage =
        result && result.data && result.data.message
          ? result.data.message
          : "پیام شما با موفقیت ثبت شد. همکاران ما در سریع‌ترین زمان ممکن پاسخ می‌دهند.";

      showContactToast(successMessage, "success");

      form.reset();

      if (fileNameDisplay) {
        fileNameDisplay.value = "";
      }
    } catch (error) {
      showContactToast(error.message, "error");
    } finally {
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.classList.remove("is-loading");
        submitButton.textContent = originalButtonText;
      }
    }
  });

  function showContactToast(message, type) {
    const oldToasts = document.querySelectorAll(".hs-contact-toast");
    oldToasts.forEach(function (toast) {
      toast.remove();
    });

    const toast = document.createElement("div");
    toast.className = "hs-contact-toast hs-contact-toast-" + type;
    toast.setAttribute("role", type === "success" ? "status" : "alert");
    toast.setAttribute("aria-live", "polite");

    const icon = type === "success" ? "✓" : "!";

    toast.innerHTML = `
      <div class="hs-contact-toast-card">
        <div class="hs-contact-toast-icon">${icon}</div>
        <div class="hs-contact-toast-content">
          <strong>${type === "success" ? "پیام شما ثبت شد" : "ارسال انجام نشد"}</strong>
          <p>${escapeHtml(message)}</p>
        </div>
      </div>
    `;

    document.body.appendChild(toast);

    window.requestAnimationFrame(function () {
      toast.classList.add("is-visible");
    });

    let timeoutId = window.setTimeout(closeToast, 3000);

    function closeToast() {
      window.clearTimeout(timeoutId);
      toast.classList.remove("is-visible");

      window.setTimeout(function () {
        if (toast && toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 250);

      document.removeEventListener("click", closeToast);
      document.removeEventListener("keydown", handleKeydown);
    }

    function handleKeydown(event) {
      if (event.key === "Escape") {
        closeToast();
      }
    }

    window.setTimeout(function () {
      document.addEventListener("click", closeToast);
      document.addEventListener("keydown", handleKeydown);
    }, 0);
  }

  function escapeHtml(value) {
    const div = document.createElement("div");
    div.textContent = value;
    return div.innerHTML;
  }
});