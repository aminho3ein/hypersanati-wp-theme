(function () {
  'use strict';
  function ready() {
    var root = document.querySelector('.hsb-legal');
    if (!root) return;
    var body = root.querySelector('#hsb-legal-body');
    var list = root.querySelector('#hsb-legal-toc');
    if (!body || !list) return;
    // Move WordPress-maintained review metadata into the page hero; keep metadata editable in WP.
    var review = body.querySelector('.hsb-legal-extras-date');
    var reviewSlot = root.querySelector('#hsb-legal-review-slot');
    if (review && reviewSlot) reviewSlot.appendChild(review);
    // Retain the existing account, order and quote links, without duplicating them.
    var shortcuts = body.querySelector('.hsb-legal-extras-shortcuts');
    var article = root.querySelector('.hsb-legal__article');
    if (shortcuts && article) article.appendChild(shortcuts);
    // Existing legal drafts start with their own page title; the visual H1 already provides this.
    var headings = Array.prototype.slice.call(body.querySelectorAll('h2,h3,h4'));
    if (headings.length && headings[0].tagName === 'H2') {
      headings[0].classList.add('hsb-legal__source-title');
      headings.shift();
    }
    list.textContent = '';
    var links = [];
    headings.forEach(function (heading, i) {
      var id = 'hsb-legal-section-' + (i + 1);
      heading.id = id;
      var li = document.createElement('li');
      li.dataset.section = String(i + 1);
      if (heading.tagName === 'H4') li.className = 'hsb-legal__toc-child';
      var a = document.createElement('a');
      a.href = '#' + id;
      a.textContent = heading.textContent.trim();
      li.appendChild(a);
      list.appendChild(li);
      links.push({ heading: heading, link: a });
    });
    if (!links.length) {
      var li = document.createElement('li');
      li.textContent = 'در این صفحه عنوان‌بندی جداگانه‌ای وجود ندارد.';
      list.appendChild(li);
      return;
    }
    function highlight() {
      var current = 0;
      for (var i = 0; i < links.length; i++) {
        if (links[i].heading.getBoundingClientRect().top <= 165) current = i;
      }
      links.forEach(function (item, i) {
        item.link.classList.toggle('is-current', i === current);
        if (i === current) item.link.setAttribute('aria-current', 'location');
        else item.link.removeAttribute('aria-current');
      });
    }
    window.addEventListener('scroll', highlight, { passive: true });
    highlight();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready);
  else ready();
})();
