import { ThemeManager } from './modules/theme.js';
import { LanguageManager } from './modules/language.js';
import { BlogManager } from './modules/blog.js';
import { translations } from './data/translations.js';

// Make translations globally accessible
window.translations = translations;

// Initialize managers
document.addEventListener('DOMContentLoaded', () => {
    // Initialize language manager first
    window.languageManager = new LanguageManager(translations);
    
    // Initialize theme manager
    window.themeManager = new ThemeManager();
    
    // Initialize blog manager if on blog page
    if (document.getElementById('column-left')) {
        window.blogManager = new BlogManager();
    }
    
    // Initialize resume PDF switcher if on resume page
    if (document.getElementById('resume-viewer')) {
        initializeResume();
    }
});

// Resume PDF switcher
function initializeResume() {
    const updateResumePDF = () => {
        const lang = window.getCurrentLanguage();
        const pdfViewer = document.getElementById('resume-viewer');
        
        if (pdfViewer) {
            pdfViewer.src = lang === 'pl' ? 'assets/documents/resume-pl.pdf' : 'assets/documents/resume-en.pdf';
        }
    };
    
    updateResumePDF();
    
    window.addEventListener('languageChanged', () => {
        setTimeout(updateResumePDF, 100);
    });
}