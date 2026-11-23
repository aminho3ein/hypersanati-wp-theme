(function () {
  'use strict';
  function init(hero) {
    const slides = Array.from(hero.querySelectorAll('.hsb-home-hero__slide'));
    if (!slides.length) return;
    const heading = hero.querySelector('#hsb-home-hero-title');
    const dots = Array.from(hero.querySelectorAll('.hsb-home-hero__dot'));
    const prev = hero.querySelector('[data-slide-direction="prev"]');
    const next = hero.querySelector('[data-slide-direction="next"]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const interval = Math.min(15000, Math.max(3000, Number(hero.dataset.hsbSliderInterval) || 6000));
    const autoplay = hero.dataset.hsbSliderAuto === '1';
    const fallbackTitle = heading ? heading.textContent.trim() : '';
    const wordDelay = 110;
    const readingHold = 4000;
    let active = 0;
    let slideTimer = null;
    let typeTimer = null;
    let paused = false;
    let typeToken = 0;
    let typingEnd = 0;

    function stop() {
      if (slideTimer !== null) { clearTimeout(slideTimer); slideTimer = null; }
    }
    function cancelTyping() {
      ++typeToken;
      if (typeTimer !== null) { clearTimeout(typeTimer); typeTimer = null; }
    }
    function start() {
      stop();
      if (!autoplay || paused || document.hidden || reduceMotion.matches || slides.length < 2) return;
      const delay = Math.max(interval, typingEnd - Date.now() + readingHold);
      slideTimer = window.setTimeout(() => show(active + 1), Math.max(100, delay));
    }
    function typeTitle(title) {
      cancelTyping();
      if (!heading) { typingEnd = Date.now(); return; }
      heading.setAttribute('aria-label', title);
      const tokens = title.trim().split(/\s+/u).filter(Boolean);
      if (reduceMotion.matches || tokens.length < 2 || !autoplay) {
        heading.textContent = title;
        typingEnd = Date.now();
        return;
      }
      const current = typeToken;
      let index = 1;
      heading.textContent = tokens[0];
      typingEnd = Date.now() + (tokens.length - 1) * wordDelay;
      function appendWord() {
        if (current !== typeToken) return;
        if (index < tokens.length) {
          heading.textContent += ' ' + tokens[index++];
          if (index < tokens.length) typeTimer = window.setTimeout(appendWord, wordDelay);
          else { typingEnd = Date.now(); start(); }
        }
      }
      typeTimer = window.setTimeout(appendWord, wordDelay);
    }
    function show(index) {
      stop();
      active = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => {
        slide.classList.toggle('is-active', i === active);
        slide.setAttribute('aria-hidden', i === active ? 'false' : 'true');
      });
      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === active);
        dot.setAttribute('aria-pressed', i === active ? 'true' : 'false');
      });
      const title = slides[active].dataset.slideTitle || fallbackTitle;
      typeTitle(title);
      start();
    }
    if (prev) prev.addEventListener('click', () => show(active - 1));
    if (next) next.addEventListener('click', () => show(active + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));
    hero.addEventListener('mouseenter', () => { paused = true; stop(); });
    hero.addEventListener('mouseleave', () => { paused = false; start(); });
    hero.addEventListener('focusin', () => { paused = true; stop(); });
    hero.addEventListener('focusout', (event) => {
      if (hero.contains(event.relatedTarget)) return;
      paused = false; start();
    });
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) { stop(); cancelTyping(); if (heading) heading.textContent = slides[active].dataset.slideTitle || fallbackTitle; }
      else { typingEnd = Date.now(); start(); }
    });
    const motionChange = () => {
      if (reduceMotion.matches) {
        cancelTyping();
        if (heading) heading.textContent = slides[active].dataset.slideTitle || fallbackTitle;
      }
      typingEnd = Date.now(); start();
    };
    if (reduceMotion.addEventListener) reduceMotion.addEventListener('change', motionChange);
    else if (reduceMotion.addListener) reduceMotion.addListener(motionChange);
    show(0);
  }
  function ready() { document.querySelectorAll('.hsb-home-hero').forEach(init); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready);
  else ready();
})();
