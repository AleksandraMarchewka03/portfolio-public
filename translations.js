const translations = {
  en: {
    // Navigation
    name: "Aleksandra Marchewka",
    allProjects: "All Projects",
    contact: "Contact",
    blog: "Blog",
    digitalGarden: "Digital Garden",
    settings: "Settings",
    colorMode: "Color Mode",
    darkMode: "Dark Mode",
    lightMode: "Light Mode",
    language: "Language",
    
    // Home page
    greeting: "Hello! I am Aleksandra Marchewka. I am a Software Developer.",
    bio: "I've been coding since I was 13 and recently finished my undergraduate degree in computer science. I have an interest in fullstack development and machine learning.",
    seeMyWork: "See my work",
    myStack: "My Stack",
    recentWork: "Recent Work",
    recentWorkText: "Currently I am refactoring and improving my projects from university. Links to completed work will be added shortly.",
    // Resume page
    cvTitle: "My Resume",
    downloadResume: "Download Resume",
    downloadEnglish: "Download English Version",
    downloadPolish: "Download Polish Version",
    // Footer
    linkedin: "LinkedIn",
    cv: "CV",
    github: "GitHub",
    copyright: "© 2025 Aleksandra Marchewka",
    languageNotice: "🌐 Available in English and Polish",
  },
  pl: {
    // Navigation
    name: "Aleksandra Marchewka",
    allProjects: "Wszystkie Projekty",
    contact: "Kontakt",
    blog: "Blog",
    digitalGarden: "Ogród Cyfrowy",
    settings: "Ustawienia",
    colorMode: "Tryb Kolorów",
    darkMode: "Tryb Ciemny",
    lightMode: "Tryb Jasny",
    language: "Język",
    languageNotice: "🌐 Dostępne po angielsku i po polsku",
    
    // Home page
    greeting: "Cześć! Jestem Aleksandra Marchewka. Jestem Programistką.",
    bio: "Programuję od 13 roku życia i niedawno ukończyłam studia licencjackie z informatyki. Interesuję się programowaniem full-stack i uczeniem maszynowym.",
    seeMyWork: "Zobacz moje prace",
    myStack: "Mój Stack",
    recentWork: "Ostatnie Prace",
    recentWorkText: "Obecnie refaktoryzuję i ulepszam moje projekty z uniwersytetu. Linki do ukończonych prac zostaną dodane wkrótce.",
     // Resume page
    cvTitle: "Moje CV",
    downloadResume: "Pobierz CV",
    downloadEnglish: "Pobierz Wersję Angielską",
    downloadPolish: "Pobierz Wersję Polską",
    // Footer
    linkedin: "LinkedIn",
    cv: "CV",
    github: "GitHub",
    copyright: "© 2025 Aleksandra Marchewka"
  }
};

// Get current language from localStorage or default to English
function getCurrentLanguage() {
  return localStorage.getItem('language') || 'en';
}

// Set language and save to localStorage
function setLanguage(lang) {
  localStorage.setItem('language', lang);
  updatePageLanguage();
}

// Update all translatable elements on the page
function updatePageLanguage() {
  const lang = getCurrentLanguage();
  const t = translations[lang];
  
  // Update all elements with data-translate attribute
  document.querySelectorAll('[data-translate]').forEach(element => {
    const key = element.getAttribute('data-translate');
    if (t[key]) {
      element.textContent = t[key];
    }
  });
  
  // Update language toggle text to show current language (what you're viewing)
  const langText = document.getElementById('lang-text');
  if (langText) {
    // Show current language with emphasis
    if (lang === 'en') {
      langText.innerHTML = '<strong>ENG</strong> / PL';
    } else {
      langText.innerHTML = 'ENG / <strong>PL</strong>';
    }
  }
  
  // Update language toggle checkbox state
  const langToggle = document.getElementById('language-toggle');
  if (langToggle) {
    langToggle.checked = lang === 'pl';
  }
}

// Initialize language on page load
function initializeLanguage() {
  updatePageLanguage();
  
  // Add event listener to language toggle
  const langToggle = document.getElementById('language-toggle');
  if (langToggle) {
    langToggle.addEventListener('change', () => {
      const newLang = langToggle.checked ? 'pl' : 'en';
      setLanguage(newLang);
    });
  }
}

// Update all translatable elements on the page
function updatePageLanguage() {
  const lang = getCurrentLanguage();
  const t = translations[lang];
  
  // Update all elements with data-translate attribute
  document.querySelectorAll('[data-translate]').forEach(element => {
    const key = element.getAttribute('data-translate');
    if (t[key]) {
      element.textContent = t[key];
    }
  });
  
  // Update language indicator
  const currentLangIndicator = document.getElementById('current-lang');
  if (currentLangIndicator) {
    currentLangIndicator.textContent = lang === 'en' ? 'EN' : 'PL';
  }
  
  // Update language toggle text to show current language (what you're viewing)
  const langText = document.getElementById('lang-text');
  if (langText) {
    // Show current language with emphasis
    if (lang === 'en') {
      langText.innerHTML = '<strong>ENG</strong> / PL';
    } else {
      langText.innerHTML = 'ENG / <strong>PL</strong>';
    }
  }
  
  // Update language toggle checkbox state
  const langToggle = document.getElementById('language-toggle');
  if (langToggle) {
    langToggle.checked = lang === 'pl';
  }
}