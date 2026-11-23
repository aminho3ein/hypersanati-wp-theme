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

/* HSB LEGAL TOC PINNED FALLBACK V4 - works even with overflow ancestors */
(function () {
  'use strict';
  function boot() {
    var root = document.querySelector('.hsb-legal');
    if (!root) return;
    var sidebar = root.querySelector('.hsb-legal__sidebar');
    var toc = sidebar && sidebar.querySelector('.hsb-legal__toc');
    var article = root.querySelector('.hsb-legal__article');
    if (!sidebar || !toc || !article || toc.dataset.hsbPinnedReady === '1') return;
    toc.dataset.hsbPinnedReady = '1';
    var anchor = document.createElement('div');
    anchor.className = 'hsb-legal__toc-anchor';
    anchor.setAttribute('aria-hidden', 'true');
    sidebar.insertBefore(anchor, toc);
    var pinned = false;
    var pending = false;
    var desktop = window.matchMedia('(min-width: 951px)');
    var offset = 96;
    var props = ['--legal-accent','--legal-accent-dark','--legal-ink','--legal-muted','--legal-surface','--legal-border'];

    function restore() {
      if (pinned) {
        sidebar.insertBefore(toc, anchor.nextSibling);
        pinned = false;
      }
      toc.style.removeProperty('position');
      toc.style.removeProperty('top');
      toc.style.removeProperty('left');
      toc.style.removeProperty('width');
      toc.style.removeProperty('z-index');
      toc.style.removeProperty('direction');
      toc.style.removeProperty('font-family');
      toc.style.removeProperty('box-sizing');
      toc.style.removeProperty('max-height');
      toc.style.removeProperty('overflow-y');
      props.forEach(function (key) { toc.style.removeProperty(key); });
      anchor.style.height = '0px';
      anchor.style.display = 'none';
    }
    function update() {
      pending = false;
      if (!desktop.matches || !root.isConnected || !sidebar.isConnected) {
        restore();
        return;
      }
      var anchorTop = anchor.getBoundingClientRect().top;
      if (anchorTop > offset) {
        if (pinned) restore();
        return;
      }
      if (!pinned) {
        var rootStyles = window.getComputedStyle(root);
        props.forEach(function (key) {
          var value = rootStyles.getPropertyValue(key).trim();
          if (value) toc.style.setProperty(key, value);
        });
        toc.style.fontFamily = rootStyles.fontFamily;
        toc.style.direction = 'rtl';
        toc.style.boxSizing = 'border-box';
        var measuredHeight = toc.getBoundingClientRect().height;
        anchor.style.height = measuredHeight + 'px';
        anchor.style.display = 'block';
        document.body.appendChild(toc);
        pinned = true;
      }
      var sideRect = sidebar.getBoundingClientRect();
      var viewportHeight = window.innerHeight;
      var maxHeight = Math.max(150, viewportHeight - offset - 16);
      var width = sideRect.width;
      toc.style.position = 'fixed';
      toc.style.left = sideRect.left + 'px';
      toc.style.width = width + 'px';
      toc.style.maxHeight = maxHeight + 'px';
      toc.style.overflowY = 'auto';
      toc.style.zIndex = '40';
      var height = toc.getBoundingClientRect().height;
      // Stop at the article/sidebar bottom; do not overlap the help banner or footer.
      var stopBottom = Math.min(sideRect.bottom, article.getBoundingClientRect().bottom);
      toc.style.top = Math.min(offset, stopBottom - height) + 'px';
    }
    function schedule() {
      if (!pending) {
        pending = true;
        window.requestAnimationFrame(update);
      }
    }
    window.addEventListener('scroll', schedule, {passive: true});
    window.addEventListener('resize', schedule, {passive: true});
    window.addEventListener('pageshow', schedule);
    schedule();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
