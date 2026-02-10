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


// Function to set all color variables at once
const modeText = document.querySelector("#mode-text");
const toggle = document.querySelector("#dark-mode-toggle");

// Function to set all color variables at once
function setColorMode(mode) {
    console.log(`Setting color mode to: ${mode}`);
    
    // Set all CSS variables
    document.documentElement.style.setProperty('--background', `var(--${mode}-background)`);
    document.documentElement.style.setProperty('--primary', `var(--${mode}-primary)`);
    document.documentElement.style.setProperty('--link', `var(--${mode}-link)`);
    document.documentElement.style.setProperty('--button-bg', `var(--${mode}-button-bg)`);
    document.documentElement.style.setProperty('--button-text', `var(--${mode}-button-text)`);
    document.documentElement.style.setProperty('--nav-bg', `var(--${mode}-nav-bg)`);
    document.documentElement.style.setProperty('--section-1', `var(--${mode}-section-1)`);
    document.documentElement.style.setProperty('--section-2', `var(--${mode}-section-2)`);
    document.documentElement.style.setProperty('--border', `var(--${mode}-border)`);
    
    // Update toggle text
    modeText.textContent = mode === 'dark' ? 'Light Mode' : 'Dark Mode';
}

// Initialize color mode
function initializeColorMode() {
    const savedMode = localStorage.getItem('colorMode');
    
    if (savedMode) {
        // Use saved preference
        toggle.checked = savedMode === 'dark';
        setColorMode(savedMode);
    } else {
        // Use system preference or default to light
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const defaultMode = prefersDark ? 'dark' : 'light';
        toggle.checked = prefersDark;
        setColorMode(defaultMode);
        localStorage.setItem('colorMode', defaultMode);
    }
}

// Event listener for toggle
toggle.addEventListener('change', () => {
    const mode = toggle.checked ? 'dark' : 'light';
    setColorMode(mode);
    localStorage.setItem('colorMode', mode);
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', initializeColorMode);

// Also initialize if script loads after DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeColorMode);
} else {
    initializeColorMode();
}

// Listen for system preference changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('colorMode')) {
        const mode = e.matches ? 'dark' : 'light';
        toggle.checked = e.matches;
        setColorMode(mode);
    }
});