(function () {
  "use strict";
  function init(hero) {
    const slides = Array.from(hero.querySelectorAll('.hsb-home-hero__slide'));
    if (slides.length < 2) return;
    const heading = hero.querySelector('#hsb-home-hero-title');
    const dots = Array.from(hero.querySelectorAll('.hsb-home-hero__dot'));
    const prev = hero.querySelector('[data-slide-direction="prev"]');
    const next = hero.querySelector('[data-slide-direction="next"]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const interval = Math.min(15000, Math.max(3000, Number(hero.dataset.hsbSliderInterval) || 6000));
    const autoplay = hero.dataset.hsbSliderAuto === '1';
    let active = 0;
    let timer = null;
    let paused = false;
    function show(index) {
      active = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => {
        slide.classList.toggle('is-active', i === active);
        slide.setAttribute('aria-hidden', i === active ? 'false' : 'true');
      });
      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === active);
        dot.setAttribute('aria-pressed', i === active ? 'true' : 'false');
      });
      if (heading) heading.textContent = slides[active].dataset.slideTitle || heading.dataset.hsbFallbackTitle || heading.textContent;
    }
    if (heading) heading.dataset.hsbFallbackTitle = heading.textContent;
    function stop() { if (timer !== null) { window.clearInterval(timer); timer = null; } }
    function start() {
      stop();
      if (autoplay && !paused && !document.hidden && !reduceMotion.matches)
        timer = window.setInterval(() => show(active + 1), interval);
    }
    if (prev) prev.addEventListener('click', () => { show(active - 1); start(); });
    if (next) next.addEventListener('click', () => { show(active + 1); start(); });
    dots.forEach((dot,i) => dot.addEventListener('click', () => { show(i); start(); }));
    hero.addEventListener('mouseenter', () => { paused = true; stop(); });
    hero.addEventListener('mouseleave', () => { paused = false; start(); });
    hero.addEventListener('focusin', () => { paused = true; stop(); });
    hero.addEventListener('focusout', (event) => {
      if (hero.contains(event.relatedTarget)) return;
      paused = false;start();
    });
    document.addEventListener('visibilitychange', start);
    if (reduceMotion.addEventListener) reduceMotion.addEventListener('change', start);
    else if (reduceMotion.addListener) reduceMotion.addListener(start);
    show(0);start();
  }
  function ready() { document.querySelectorAll('.hsb-home-hero').forEach(init); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready);
  else ready();
})();
