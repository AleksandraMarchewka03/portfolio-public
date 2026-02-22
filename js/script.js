/* ============================================================
   script.js — Shared: dark/light mode initialisation
   Depends on: translations.js (loaded first)
   ============================================================ */

const modeText = document.querySelector('#mode-text');
const toggle   = document.querySelector('#dark-mode-toggle');

function setColorMode(mode) {
  const vars = ['background','primary','link','button-bg','button-text','nav-bg','section-1','section-2','border'];
  vars.forEach(v => document.documentElement.style.setProperty(`--${v}`, `var(--${mode}-${v})`));

  const lang = getCurrentLanguage();
  const t    = translations[lang];
  if (modeText) {
    modeText.textContent = mode === 'dark' ? t.lightMode : t.darkMode;
    modeText.setAttribute('data-translate', mode === 'dark' ? 'lightMode' : 'darkMode');
  }
}

function initializeColorMode() {
  const saved = localStorage.getItem('colorMode');
  if (saved) {
    if (toggle) toggle.checked = saved === 'dark';
    setColorMode(saved);
  } else {
    const prefersDark  = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const defaultMode  = prefersDark ? 'dark' : 'light';
    if (toggle) toggle.checked = prefersDark;
    setColorMode(defaultMode);
    localStorage.setItem('colorMode', defaultMode);
  }
}

if (toggle) {
  toggle.addEventListener('change', () => {
    const mode = toggle.checked ? 'dark' : 'light';
    setColorMode(mode);
    localStorage.setItem('colorMode', mode);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initializeLanguage();
  initializeColorMode();
});

if (document.readyState !== 'loading') {
  initializeLanguage();
  initializeColorMode();
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
  if (!localStorage.getItem('colorMode')) {
    const mode = e.matches ? 'dark' : 'light';
    if (toggle) toggle.checked = e.matches;
    setColorMode(mode);
  }
});
