// Language/Translation Management
export class LanguageManager {
    constructor(translations) {
        this.translations = translations;
        this.langToggle = document.getElementById('language-toggle');
        this.langText = document.getElementById('lang-text');
        this.langFlag = document.getElementById('current-lang');
        this.init();
    }

    getCurrentLanguage() {
        return localStorage.getItem('language') || 'en';
    }

    setLanguage(lang) {
        localStorage.setItem('language', lang);
        this.updatePageLanguage();
    }

    updatePageLanguage() {
        const lang = this.getCurrentLanguage();
        const t = this.translations[lang];
        
        // Update all elements with data-translate attribute
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (t[key]) {
                element.textContent = t[key];
            }
        });
        
        // Update language indicator
        if (this.langFlag) {
            this.langFlag.textContent = lang === 'en' ? 'EN' : 'PL';
        }
        
        // Update language toggle text
        if (this.langText) {
            if (lang === 'en') {
                this.langText.innerHTML = '<strong>ENG</strong> / PL';
            } else {
                this.langText.innerHTML = 'ENG / <strong>PL</strong>';
            }
        }
        
        // Update language toggle checkbox state
        if (this.langToggle) {
            this.langToggle.checked = lang === 'pl';
        }

        // Dispatch custom event for other modules to listen
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang } }));
    }

    init() {
        this.updatePageLanguage();
        
        // Add event listener to language toggle
        if (this.langToggle) {
            this.langToggle.addEventListener('change', () => {
                const newLang = this.langToggle.checked ? 'pl' : 'en';
                this.setLanguage(newLang);
            });
        }
    }
}

// Make it globally accessible for backward compatibility
window.getCurrentLanguage = function() {
    return localStorage.getItem('language') || 'en';
};

window.updatePageLanguage = function() {
    if (window.languageManager) {
        window.languageManager.updatePageLanguage();
    }
};