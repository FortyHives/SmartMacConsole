import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return glob.sync(query);
}
/**
 * Js Files
 */
// Page JS Files
const pageJsFiles = GetFilesArray('resources/assets/js/*.js');

// Processing Vendor JS Files
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js');

// Processing Libs JS Files
const LibsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

/**
 * Scss Files
 */
// Processing Core, Themes & Pages Scss Files
const CoreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');

// Processing Libs Scss & Css Files
const LibsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');

// Processing Fonts Scss Files
const FontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/**/!(_)*.scss');

// Processing Window Assignment for Libs like jKanban, pdfMake
function libsWindowAssignment() {
  return {
    name: 'libsWindowAssignment',

    transform(src, id) {
      if (id.includes('jkanban.js')) {
        return src.replace('this.jKanban', 'window.jKanban');
      } else if (id.includes('vfs_fonts')) {
        return src.replaceAll('this.pdfMake', 'window.pdfMake');
      }
    }
  };
}

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/assets/css/demo.css',
        'resources/js/app.js',
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        'resources/assets/js/agents/agents.js',
        'resources/assets/js/agents/agent-view.js',
        'resources/assets/js/agents/modal-edit-agent.js',
        'resources/assets/js/places/regions.js',
        'resources/assets/js/places/localities.js',
        'resources/assets/js/outlets/active.js',
        'resources/assets/js/outlets/new.js',
        'resources/assets/js/outlets/disabled.js',
        'resources/assets/js/outlets/categories.js',
        'resources/assets/js/outlets/outlet-view.js',
        'resources/assets/js/outlets/modal-edit-outlet.js',
        'resources/assets/js/products/products.js',
        'resources/assets/js/products/product-view.js',
        'resources/assets/js/products/modal-edit-product.js',
        'resources/assets/js/planograms/active.js',
        'resources/assets/js/planograms/disabled.js',
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles,
        'resources/assets/vendor/js/template-customizer.js',
      ],
      refresh: true
    }),
    html(),
    libsWindowAssignment()
  ],
  /* build: {
    outDir: 'public/build', // Ensure this is the correct path
    emptyOutDir: true, // Optional: to clear the output directory before building
    manifest: true, // Ensure the manifest is generated
    rollupOptions: {
      // Customize rollup options here if needed
    },
  }, */
});
