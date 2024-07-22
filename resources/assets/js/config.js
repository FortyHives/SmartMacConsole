/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

'use strict';

// JS global variables
window.config = {
  colors: {
    primary: '#431407',
    secondary: '#083238',
    success: '#72e128',
    info: '#26c6f9',
    warning: '#fdb528',
    danger: '#ff4d49',
    dark: '#4b4b4b',
    black: '#000',
    white: '#fff',
    cardColor: '#fff',
    bodyBg: '#fff7ed',
    bodyColor: '#676a7b',
    headingColor: '#083238',
    textMuted: '#a8aab4',
    borderColor: '#e5e5e8'
  },
  colors_label: {
    primary: '#431407',
    secondary: '#083238',
    success: '#72e12829',
    info: '#26c6f929',
    warning: '#fdb52829',
    danger: '#ff4d4929',
    dark: '#4b4b4b29'
  },
  colors_dark: {
    cardColor: '#083238',
    bodyBg: '#083238',
    bodyColor: '#b2b3ca',
    headingColor: '#d7d8ee',
    textMuted: '#7b7d95',
    borderColor: '#7c2d12'
  },
  enableMenuLocalStorage: true // Enable menu state with local storage support
};

window.assetsPath = document.documentElement.getAttribute('data-assets-path');
window.baseUrl = document.documentElement.getAttribute('data-base-url') + '/';
window.templateName = document.documentElement.getAttribute('data-template');
window.rtlSupport = true; // set true for rtl support (rtl + ltr), false for ltr only.
