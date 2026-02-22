/* ============================================================
   home.js — Home page: carousel language switching + tag clamping
   Depends on: translations.js, Carousel.js
   ============================================================ */

function applyCarouselLanguage() {
  const lang   = localStorage.getItem('language') || 'en';
  const suffix = lang.charAt(0).toUpperCase() + lang.slice(1);
  document.querySelectorAll('.carousel-card[data-id]').forEach(card => {
    const titleEl = card.querySelector('.carousel-card__title');
    const descEl  = card.querySelector('.carousel-card__desc');
    if (titleEl && card.dataset['title' + suffix]) titleEl.textContent = card.dataset['title' + suffix];
    if (descEl  && card.dataset['desc'  + suffix]) descEl.textContent  = card.dataset['desc'  + suffix];
  });
}

function clampTags() {
  document.querySelectorAll('.carousel-card__tags').forEach(container => {
    // Reset
    container.querySelectorAll('.tag-and-more').forEach(el => el.remove());
    container.style.maxHeight = '';
    container.style.overflow  = 'visible';
    const tags = Array.from(container.querySelectorAll('span'));
    tags.forEach(t => t.style.display = '');

    if (tags.length === 0) return;

    const tagH     = tags[0].offsetHeight;
    const gap      = parseFloat(getComputedStyle(container).gap) || 6;
    const twoLineH = tagH * 2 + gap;

    container.style.minHeight = twoLineH + 'px';

    const naturalH = container.scrollHeight;
    container.style.overflow = '';

    if (naturalH <= twoLineH + 1) {
      container.style.maxHeight = twoLineH + 'px';
      return;
    }

    const andMore = document.createElement('span');
    andMore.className   = 'tag-and-more';
    andMore.textContent = 'and more';
    container.appendChild(andMore);

    container.style.overflow = 'visible';
    for (let i = tags.length - 1; i >= 0; i--) {
      if (container.scrollHeight <= twoLineH + 1) break;
      tags[i].style.display = 'none';
    }
    container.style.overflow = '';
    container.style.maxHeight = twoLineH + 'px';
  });
}

function scheduleClamp() {
  setTimeout(clampTags, 100);
  setTimeout(clampTags, 500);
}

document.addEventListener('DOMContentLoaded', () => {
  applyCarouselLanguage();
  scheduleClamp();
});

window.addEventListener('load', applyCarouselLanguage);

window.addEventListener('resize', () => {
  clearTimeout(window._clampTimer);
  window._clampTimer = setTimeout(clampTags, 120);
});

document.getElementById('language-toggle')?.addEventListener('change', () => {
  setTimeout(applyCarouselLanguage, 50);
});
