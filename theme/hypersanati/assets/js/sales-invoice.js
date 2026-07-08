document.addEventListener("DOMContentLoaded", function () {
  const downloadBtn = document.getElementById("downloadInvoicePdf");
  const invoiceArea = document.getElementById("invoicePrintArea");

  if (!downloadBtn || !invoiceArea) return;

  downloadBtn.addEventListener("click", function () {
    if (typeof html2pdf === "undefined") {
      alert("امکان دانلود PDF در حال حاضر وجود ندارد. لطفاً صفحه را دوباره بارگذاری کنید.");
      return;
    }

    const fileName = downloadBtn.dataset.filename || "sales-invoice.pdf";

    const opt = {
      margin: [10, 10, 10, 10],
      filename: fileName,
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
