class CssPropControl {
    constructor(element) {
        this.element = element;
    }
    
    get(varName) {
        return getComputedStyle(this.element).getPropertyValue(varName);
    }
    
    set(varName, val) {
        return this.element.style.setProperty(varName, val);
    }
}

const bodyCssProps = new CssPropControl(document.body);
const toggle = document.querySelector("#dark-mode-toggle");

// Check for saved user preference, if any
const savedMode = localStorage.getItem('colorMode');
if (savedMode) {
    toggle.checked = savedMode === 'dark';
    setColorMode(savedMode);
}

// Set initial mode if no preference saved
if (!savedMode) {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (prefersDark) {
        toggle.checked = true;
        setColorMode('dark');
    }
}

toggle.addEventListener('change', () => {
    const mode = toggle.checked ? 'dark' : 'light';
    setColorMode(mode);
    localStorage.setItem('colorMode', mode);
});

function setColorMode(mode) {
    bodyCssProps.set('--background', bodyCssProps.get(`--${mode}-background`));
    bodyCssProps.set('--primary', bodyCssProps.get(`--${mode}-primary`));
    bodyCssProps.set('--link', bodyCssProps.get(`--${mode}-link`));
    bodyCssProps.set('--button-bg', bodyCssProps.get(`--${mode}-button-bg`));
    bodyCssProps.set('--button-text', bodyCssProps.get(`--${mode}-button-text`));
}