/* HSB EDITORIAL V3 */
document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  const article = document.querySelector(
    "body.single-post .hsb-editorial-body"
  );

  if (!article) return;

  // Auto-detect note paragraphs.
  article.querySelectorAll("p").forEach(function (p) {
    if (/^نکته\s*[:：]/u.test((p.textContent || "").trim())) {
      p.classList.add("hsb-editorial-note");
    }
  });

  // Wrap ordinary tables without changing WordPress content.
  article.querySelectorAll("table").forEach(function (table) {
    if (table.closest(
      ".obt-container, .wp-block-table, .hsb-table-scroll"
    )) return;

    const wrap = document.createElement("div");
    wrap.className = "hsb-table-scroll";
    table.parentNode.insertBefore(wrap, table);
    wrap.appendChild(table);
  });

  // One-open-at-a-time Yoast FAQ accordions.
  article.querySelectorAll(
    ".schema-faq.wp-block-yoast-faq-block"
  ).forEach(function (faq, faqIndex) {

    const items = Array.from(
      faq.querySelectorAll(".schema-faq-section")
    ).map(function (section, index) {
      const question = section.querySelector(".schema-faq-question");
      const answer = section.querySelector(".schema-faq-answer");

      if (!question || !answer) return null;

      if (!answer.id) {
        answer.id = "hsb-faq-" + faqIndex + "-" + index;
      }

      question.setAttribute("role", "button");
      question.setAttribute("tabindex", "0");
      question.setAttribute("aria-expanded", "false");
      question.setAttribute("aria-controls", answer.id);

      section.classList.remove("hsb-faq-open");
      section.classList.add("hsb-faq-enhanced");
      answer.style.maxHeight = "0px";

      return { section, question, answer };
    }).filter(Boolean);

    function update(item, open) {
      item.section.classList.toggle("hsb-faq-open", open);
      item.question.setAttribute("aria-expanded", String(open));

      item.answer.style.maxHeight = open
        ? item.answer.scrollHeight + 50 + "px"
        : "0px";
    }

    items.forEach(function (item) {
      function toggle(event) {
        if (event) event.preventDefault();

        const wasOpen = item.section.classList.contains(
          "hsb-faq-open"
        );

        items.forEach(other => update(other, false));

        if (!wasOpen) update(item, true);
      }

      item.question.addEventListener("click", toggle);

      item.question.addEventListener("keydown", function (event) {
        if (event.key === "Enter" || event.key === " ") {
          toggle(event);
        }
      });
    });

    window.addEventListener("resize", function () {
      items.forEach(function (item) {
        if (item.section.classList.contains("hsb-faq-open")) {
          item.answer.style.maxHeight =
            item.answer.scrollHeight + 50 + "px";
        }
      });
    });
  });
});
