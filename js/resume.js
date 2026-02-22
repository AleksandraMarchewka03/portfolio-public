/* ============================================================
   resume.js — Switch PDF viewer src based on current language
   Depends on: translations.js (getCurrentLanguage)
   ============================================================ */

function updateResumePDF() {
  const lang      = getCurrentLanguage();
  const pdfViewer = document.getElementById('resume-viewer');
  if (!pdfViewer) return;
  pdfViewer.src = lang === 'pl' ? 'uploads/AJM_CV_PL.pdf' : 'uploads/AJM_CV_EN.pdf';
}

document.addEventListener('DOMContentLoaded', () => {
  updateResumePDF();

  const langToggle = document.getElementById('language-toggle');
  if (langToggle) {
    langToggle.addEventListener('change', () => setTimeout(updateResumePDF, 100));
  }
});
