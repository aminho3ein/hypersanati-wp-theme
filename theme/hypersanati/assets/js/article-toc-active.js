
document.addEventListener('DOMContentLoaded', function () {
  const toc = document.querySelector('.article-toc-list');
  const links = Array.from(document.querySelectorAll('.article-toc-list a'));

  if (!toc || !links.length) return;

  const sections = links
    .map(link => {
      const id = link.getAttribute('href');
      const section = id ? document.querySelector(id) : null;
      return section ? { link, section } : null;
    })
    .filter(Boolean);

  if (!sections.length) return;

  let currentLink = null;

  function setActive(link) {
    if (!link || link === currentLink) return;

    links.forEach(item => item.classList.remove('is-active'));

    link.classList.add('is-active');
    currentLink = link;

    /*
     * Auto-scroll TOC only on desktop.
     * Never use scrollIntoView here because on mobile
     * it can move the entire page.
     */
    const item = link.closest('li');

    if (item && window.matchMedia('(min-width: 768px)').matches) {

      const itemTop = item.offsetTop;
      const itemBottom = itemTop + item.offsetHeight;

      const visibleTop = toc.scrollTop;
      const visibleBottom = visibleTop + toc.clientHeight;

      const padding = 10;

      if (itemTop < visibleTop + padding) {
        toc.scrollTo({
          top: Math.max(0, itemTop - padding),
          behavior: 'smooth'
        });
      } else if (itemBottom > visibleBottom - padding) {
        toc.scrollTo({
          top: itemBottom - toc.clientHeight + padding,
          behavior: 'smooth'
        });
      }
    }
  }

  function updateActiveSection() {
    const offset = 170;
    let active = sections[0];

    for (const entry of sections) {
      const top = entry.section.getBoundingClientRect().top;

      if (top <= offset) {
        active = entry;
      } else {
        break;
      }
    }

    setActive(active.link);
  }

  window.addEventListener('scroll', updateActiveSection, {
    passive: true
  });

  window.addEventListener('resize', updateActiveSection);

  updateActiveSection();
});
