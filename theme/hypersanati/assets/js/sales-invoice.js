document.addEventListener("DOMContentLoaded", function () {
  const downloadBtn = document.getElementById("downloadInvoicePdf");
  const invoiceArea = document.getElementById("invoicePrintArea");

  if (!downloadBtn || !invoiceArea) return;

  downloadBtn.addEventListener("click", function () {
    const opt = {
      margin: [10, 10, 10, 10],
      filename: "sales-invoice.pdf",
      image: { type: "jpeg", quality: 0.98 },
      html2canvas: {
        scale: 2,
        useCORS: true,
        scrollY: 0
      },
      jsPDF: {
        unit: "mm",
        format: "a4",
        orientation: "portrait"
      },
      pagebreak: { mode: ["avoid-all", "css", "legacy"] }
    };

    html2pdf().set(opt).from(invoiceArea).save();
  });
});

document.addEventListener("DOMContentLoaded", function () {
  console.log("sales invoice page loaded");
});