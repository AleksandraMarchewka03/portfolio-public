document.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector('.carousel-track');
  const cards = Array.from(document.querySelectorAll('.carousel-card'));
  const prevBtn = document.querySelector('.carousel-btn--prev');
  const nextBtn = document.querySelector('.carousel-btn--next');
  const dotsContainer = document.getElementById('carousel-dots');

  if (!track || cards.length === 0) return;

  // Enforce flex via JS as belt-and-suspenders
  track.style.display = 'flex';
  track.style.flexDirection = 'row';
  track.style.flexWrap = 'nowrap';
  track.style.listStyle = 'none';
  track.style.margin = '0';
  track.style.padding = '0';

  const GAP = 16; // 1rem
  let currentIndex = 0;
  let visibleCount = 3;
  let maxIndex = 0;
  let dots = [];

  function buildDots(count) {
    dotsContainer.innerHTML = '';
    dots = [];
    for (let i = 0; i < count; i++) {
      const dot = document.createElement('button');
      dot.className = 'carousel-dot';
      dot.setAttribute('aria-label', `Go to position ${i + 1}`);
      dot.addEventListener('click', () => goTo(i));
      dotsContainer.appendChild(dot);
      dots.push(dot);
    }
  }

  function getVisibleCount(containerWidth) {
    if (containerWidth < 480) return 1;
    if (containerWidth < 768) return 2;
    return Math.min(3, cards.length);
  }

  function layout() {
    const containerWidth = track.parentElement.offsetWidth;
    if (containerWidth === 0) return;

    visibleCount = getVisibleCount(containerWidth);
    maxIndex = Math.max(0, cards.length - visibleCount);

    const cardWidth = (containerWidth - GAP * (visibleCount - 1)) / visibleCount;

    // Reset heights first so we get natural heights
    cards.forEach(card => {
      card.style.height = 'auto';
      card.style.width = cardWidth + 'px';
      card.style.minWidth = cardWidth + 'px';
      card.style.maxWidth = cardWidth + 'px';
      card.style.flexShrink = '0';
      card.style.flexGrow = '0';
      card.style.listStyle = 'none';
    });

    // Equalise heights to tallest card in a rAF so layout has settled
    requestAnimationFrame(() => {
      const maxH = Math.max(...cards.map(c => c.offsetHeight));
      cards.forEach(card => { card.style.height = maxH + 'px'; });
    });

    buildDots(maxIndex + 1);
    currentIndex = Math.min(currentIndex, maxIndex);
    applyPosition(false);
  }

  function applyPosition(animate) {
    const containerWidth = track.parentElement.offsetWidth;
    const cardWidth = (containerWidth - GAP * (visibleCount - 1)) / visibleCount;
    const stepWidth = cardWidth + GAP;
    track.style.transition = animate
      ? 'transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
      : 'none';
    track.style.transform = `translateX(-${currentIndex * stepWidth}px)`;
    dots.forEach((d, i) => d.classList.toggle('carousel-dot--active', i === currentIndex));
    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex >= maxIndex;
  }

  function goTo(index) {
    currentIndex = Math.max(0, Math.min(index, maxIndex));
    applyPosition(true);
  }

  prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
  nextBtn.addEventListener('click', () => goTo(currentIndex + 1));

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(layout, 50);
  });

  layout();
});