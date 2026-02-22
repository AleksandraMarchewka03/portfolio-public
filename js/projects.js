/* ============================================================
   projects.js — Projects page: filters, search, modal, card language
   Depends on: translations.js
   ============================================================ */

const cards       = Array.from(document.querySelectorAll('.project-card'));
const tagBtns     = Array.from(document.querySelectorAll('.tag-filter'));
const searchInput = document.getElementById('projectSearch');
const clearBtn    = document.getElementById('clearFilters');
const noResults   = document.getElementById('noResults');
const overlay     = document.getElementById('modalOverlay');
let activeTags    = new Set();


/*  Card language  */
function applyCardLanguage() {
  const lang   = localStorage.getItem('language') || 'en';
  const suffix = lang.charAt(0).toUpperCase() + lang.slice(1);
  cards.forEach(card => {
    const titleEl = card.querySelector('.project-card__title');
    const descEl  = card.querySelector('.project-card__desc');
    if (titleEl) titleEl.textContent = card.dataset['title' + suffix] || titleEl.textContent;
    if (descEl)  descEl.textContent  = card.dataset['desc'  + suffix] || descEl.textContent;
  });
}

document.addEventListener('DOMContentLoaded', applyCardLanguage);
window.addEventListener('load', applyCardLanguage);
document.getElementById('language-toggle')?.addEventListener('change', () => setTimeout(applyCardLanguage, 50));


/*  Filters + search  */
function applyFilters() {
  const query = searchInput.value.toLowerCase().trim();
  let visible = 0;

  cards.forEach(card => {
    const tags  = JSON.parse(card.dataset.tags);
    const title = card.querySelector('.project-card__title').textContent.toLowerCase();
    const desc  = card.querySelector('.project-card__desc').textContent.toLowerCase();

    const matchesSearch = !query
      || title.includes(query)
      || desc.includes(query)
      || tags.some(t => t.toLowerCase().includes(query));

    const matchesTags = activeTags.size === 0 || tags.some(t => activeTags.has(t));
    const show = matchesSearch && matchesTags;

    card.classList.toggle('hidden', !show);
    if (show) visible++;

    card.querySelectorAll('.project-card__tags span').forEach(span => {
      span.classList.toggle('tag-match', activeTags.size > 0 && activeTags.has(span.textContent));
    });
  });

  noResults.style.display = visible === 0 ? 'block' : 'none';
}

tagBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const tag = btn.dataset.tag;
    activeTags.has(tag) ? activeTags.delete(tag) : activeTags.add(tag);
    btn.classList.toggle('active', activeTags.has(tag));
    applyFilters();
  });
});

clearBtn.addEventListener('click', () => {
  activeTags.clear();
  tagBtns.forEach(b => b.classList.remove('active'));
  searchInput.value = '';
  applyFilters();
});

searchInput.addEventListener('input', applyFilters);


/*  Modal  */
function openModal(card) {
  const lang   = localStorage.getItem('language') || 'en';
  const suffix = lang.charAt(0).toUpperCase() + lang.slice(1);
  const tags   = JSON.parse(card.dataset.tags);
  const images = JSON.parse(card.dataset.images || '[]');
  const title  = card.dataset['title' + suffix] || card.dataset.titleEn;
  const detail = card.dataset['detail' + suffix] || card.dataset.detailEn;
  const github = card.dataset.github;
  const live   = card.dataset.live;

  document.getElementById('modalIcon').className    = card.dataset.icon;
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalDesc').innerHTML    = detail || '';
  document.getElementById('modalTags').innerHTML    = tags.map(t => `<span>${t}</span>`).join('');

  // Icon links next to title
  const headerLinksEl = document.getElementById('modalHeaderLinks');
  headerLinksEl.innerHTML = '';
  if (github) headerLinksEl.innerHTML += `<a class="modal__header-link" href="${github}" target="_blank" rel="noopener" title="GitHub"><i class="fab fa-github"></i></a>`;
  if (live)   headerLinksEl.innerHTML += `<a class="modal__header-link" href="${live}"   target="_blank" rel="noopener" title="Live Demo"><i class="fas fa-external-link-alt"></i></a>`;

  // Full text buttons below description
  const linksEl = document.getElementById('modalLinks');
  linksEl.innerHTML = '';
  if (github) linksEl.innerHTML += `<a class="modal__link-btn" href="${github}" target="_blank" rel="noopener"><i class="fab fa-github"></i> GitHub</a>`;
  if (live)   linksEl.innerHTML += `<a class="modal__link-btn" href="${live}"   target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> Live Demo</a>`;

  // Images panel
  const imagesEl = document.getElementById('modalImages');
  const detailEl = document.getElementById('modalDetail');
  imagesEl.innerHTML = '';

  if (images.length > 0) {
    detailEl.classList.remove('no-images');
    imagesEl.classList.toggle('multi', images.length > 1);
    images.forEach(src => {
      const img = document.createElement('img');
      img.src = src; img.alt = title;
      imagesEl.appendChild(img);
    });
  } else {
    detailEl.classList.add('no-images');
  }

  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  overlay.classList.remove('open');
  document.body.style.overflow = '';
}

cards.forEach(card => {
  card.querySelector('.view-details-btn').addEventListener('click', e => { e.stopPropagation(); openModal(card); });
  card.querySelectorAll('.card-icon-btn').forEach(btn => btn.addEventListener('click', e => e.stopPropagation()));
  card.addEventListener('click', () => openModal(card));
});

document.getElementById('modalClose').addEventListener('click', closeModal);
overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });


/* URL params (tag filter + direct modal open)  */
const params   = new URLSearchParams(window.location.search);
const tagParam = params.get('tag');
if (tagParam) {
  const matchBtn = tagBtns.find(b => b.dataset.tag.toLowerCase() === tagParam.toLowerCase());
  if (matchBtn) { activeTags.add(matchBtn.dataset.tag); matchBtn.classList.add('active'); applyFilters(); }
}
const openParam = params.get('open');
if (openParam) {
  const target = cards.find(c => c.dataset.id === openParam);
  if (target) openModal(target);
}
