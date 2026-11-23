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

/* HSB LEGAL SIDEBAR STICKY V5 — pins TOC and help together */
(function () {
  'use strict';
  function boot() {
    var root = document.querySelector('.hsb-legal');
    if (!root) return;
    var layout = root.querySelector('.hsb-legal__layout');
    var sidebar = root.querySelector('.hsb-legal__sidebar');
    var toc = sidebar && sidebar.querySelector('.hsb-legal__toc');
    var help = sidebar && sidebar.querySelector('.hsb-legal__help');
    var article = root.querySelector('.hsb-legal__article');
    if (!layout || !sidebar || !toc || !article || sidebar.dataset.hsbV5 === '1') return;
    sidebar.dataset.hsbV5 = '1';
    var placeholder = document.createElement('div');
    placeholder.className = 'hsb-legal__sidebar-placeholder';
    placeholder.setAttribute('aria-hidden', 'true');
    layout.insertBefore(placeholder, sidebar);
    var desktop = window.matchMedia('(min-width: 951px)');
    var pinned = false;
    var ticking = false;
    var offset = 96;
    var cssVars = ['--legal-accent', '--legal-accent-dark', '--legal-ink', '--legal-muted', '--legal-surface', '--legal-border'];
    function clearStyles(el, keys) {
      keys.forEach(function (name) { el.style.removeProperty(name); });
    }
    function unpin() {
      if (!pinned) return;
      layout.insertBefore(sidebar, placeholder.nextSibling);
      pinned = false;
      placeholder.style.display = 'none';
      clearStyles(sidebar, ['position','top','left','width','max-height','overflow','display','flex-direction','gap','z-index','direction','font-family','box-sizing','margin','align-self']);
      cssVars.forEach(function (name) { sidebar.style.removeProperty(name); });
      clearStyles(toc, ['position','top','max-height','overflow-y','flex','min-height']);
      if (help) clearStyles(help, ['margin-top','flex-shrink']);
    }
    function pin() {
      var original = sidebar.getBoundingClientRect();
      var styles = window.getComputedStyle(root);
      cssVars.forEach(function (name) {
        var value = styles.getPropertyValue(name).trim();
        if (value) sidebar.style.setProperty(name, value);
      });
      sidebar.style.fontFamily = styles.fontFamily;
      sidebar.style.direction = 'rtl';
      sidebar.style.boxSizing = 'border-box';
      placeholder.style.display = 'block';
      placeholder.style.minHeight = '1px';
      document.body.appendChild(sidebar);
      pinned = true;
      sidebar.style.left = original.left + 'px';
      sidebar.style.width = original.width + 'px';
      sidebar.style.margin = '0';
      sidebar.style.alignSelf = 'auto';
      sidebar.style.position = 'fixed';
      sidebar.style.zIndex = '40';
      sidebar.style.display = 'flex';
      sidebar.style.flexDirection = 'column';
      sidebar.style.gap = '12px';
      sidebar.style.overflow = 'visible';
      toc.style.position = 'static';
      toc.style.top = 'auto';
      toc.style.flex = '0 1 auto';
      toc.style.minHeight = '0';
      toc.style.overflowY = 'auto';
      if (help) {
        help.style.marginTop = '0';
        help.style.flexShrink = '0';
      }
    }
    function update() {
      ticking = false;
      if (!desktop.matches || !root.isConnected || !article.isConnected) {
        unpin();
        return;
      }
      var trigger = (pinned ? placeholder : sidebar).getBoundingClientRect().top;
      if (trigger > offset) {
        unpin();
        return;
      }
      if (!pinned) pin();
      var rect = placeholder.getBoundingClientRect();
      var available = Math.max(180, window.innerHeight - offset - 16);
      var helpHeight = help ? help.getBoundingClientRect().height : 0;
      var tocLimit = Math.max(100, available - helpHeight - (help ? 12 : 0));
      sidebar.style.left = rect.left + 'px';
      sidebar.style.width = rect.width + 'px';
      sidebar.style.maxHeight = available + 'px';
      toc.style.maxHeight = tocLimit + 'px';
      var totalHeight = sidebar.getBoundingClientRect().height;
      var stopBottom = article.getBoundingClientRect().bottom;
      sidebar.style.top = Math.min(offset, stopBottom - totalHeight - 8) + 'px';
    }
    function schedule() {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(update);
      }
    }
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule, { passive: true });
    window.addEventListener('pageshow', schedule);
    if ('ResizeObserver' in window) {
      var observer = new ResizeObserver(schedule);
      observer.observe(article);
      observer.observe(toc);
      if (help) observer.observe(help);
    }
    schedule();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
