// Theme/Color Mode Management
export class ThemeManager {
    constructor() {
        this.toggle = document.querySelector("#dark-mode-toggle");
        this.modeText = document.querySelector("#mode-text");
        this.init();
    }

    setColorMode(mode) {
        console.log(`Setting color mode to: ${mode}`);
        
        const properties = [
            'background', 'primary', 'link', 'button-bg', 
            'button-text', 'nav-bg', 'section-1', 'section-2', 'border'
        ];
        
        properties.forEach(prop => {
            document.documentElement.style.setProperty(
                `--${prop}`, 
                `var(--${mode}-${prop})`
            );
        });
        
        this.updateToggleText(mode);
    }

    updateToggleText(mode) {
        if (!this.modeText) return;
        
        // Get current language from LanguageManager
        const lang = localStorage.getItem('language') || 'en';
        const translations = window.translations || {};
        const t = translations[lang] || {};
        
        this.modeText.textContent = mode === 'dark' ? t.lightMode : t.darkMode;
        this.modeText.setAttribute('data-translate', mode === 'dark' ? 'lightMode' : 'darkMode');
    }

    initializeColorMode() {
        const savedMode = localStorage.getItem('colorMode');
        
        if (savedMode) {
            if (this.toggle) this.toggle.checked = savedMode === 'dark';
            this.setColorMode(savedMode);
        } else {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const defaultMode = prefersDark ? 'dark' : 'light';
            if (this.toggle) this.toggle.checked = prefersDark;
            this.setColorMode(defaultMode);
            localStorage.setItem('colorMode', defaultMode);
        }
    }

    init() {
        // Event listener for toggle
        if (this.toggle) {
            this.toggle.addEventListener('change', () => {
                const mode = this.toggle.checked ? 'dark' : 'light';
                this.setColorMode(mode);
                localStorage.setItem('colorMode', mode);
            });
        }

        // Initialize on load
        this.initializeColorMode();

        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('colorMode')) {
                const mode = e.matches ? 'dark' : 'light';
                if (this.toggle) this.toggle.checked = e.matches;
                this.setColorMode(mode);
            }
        });
    }
}