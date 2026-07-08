document.addEventListener("DOMContentLoaded", function () {
  const viewInvoiceBtn = document.getElementById("viewSalesInvoice");
  const invoiceSection = document.getElementById("salesInvoiceSection");

  if (!invoiceSection) return;

  function showInvoice() {
    invoiceSection.classList.remove("is-hidden");
    invoiceSection.setAttribute("aria-hidden", "false");

    invoiceSection.scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  }

  if (viewInvoiceBtn) {
    viewInvoiceBtn.addEventListener("click", function () {
      showInvoice();
    });
  }

  if (window.location.hash === "#salesInvoiceSection") {
    setTimeout(showInvoice, 300);
  }
});