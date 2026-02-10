// Switch PDF based on language
function updateResumePDF() {
  const lang = getCurrentLanguage();
  const pdfViewer = document.getElementById('resume-viewer');
  
  if (pdfViewer) {
    if (lang === 'pl') {
      pdfViewer.src = 'AJM_CV_PL.pdf';
    } else {
      pdfViewer.src = 'AJM_CV_EN.pdf';
    }
  }
}

// Update PDF when language changes
document.addEventListener('DOMContentLoaded', () => {
  updateResumePDF();
  
  // Watch for language toggle changes
  const langToggle = document.getElementById('language-toggle');
  if (langToggle) {
    langToggle.addEventListener('change', () => {
      // Small delay to let language update complete
      setTimeout(updateResumePDF, 100);
    });
  }
});