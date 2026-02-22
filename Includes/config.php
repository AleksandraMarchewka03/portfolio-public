<?php
// Site Configuration
define('SITE_NAME', 'Aleksandra Marchewka Portfolio');
define('SITE_URL', 'https://yoursite.com');

// Paths
define('ASSETS_PATH', '/assets');
define('CSS_PATH', ASSETS_PATH . '/css');
define('JS_PATH', ASSETS_PATH . '/js');
define('IMG_PATH', ASSETS_PATH . '/images');
define('DOC_PATH', ASSETS_PATH . '/documents');

// Meta tags
define('META_DESCRIPTION_EN', 'Portfolio of Aleksandra Marchewka - Software Developer specializing in fullstack development and machine learning');
define('META_DESCRIPTION_PL', 'Portfolio Aleksandry Marchewki - Programistki specjalizującej się w programowaniu full-stack i uczeniu maszynowym');

// Helper functions
function asset($path) {
    return ASSETS_PATH . '/' . ltrim($path, '/');
}

function css($file) {
    return CSS_PATH . '/' . ltrim($file, '/');
}

function js($file) {
    return JS_PATH . '/' . ltrim($file, '/');
}
?>