/**
 * API configuration for Admission Office frontend.
 * Detects the correct API base URL for local XAMPP and production deployments.
 */
(function () {
  if (typeof window.ADMISSION_API_BASE !== 'undefined' && window.ADMISSION_API_BASE) return;

  const isVercel = /\.vercel\.app$/i.test(window.location.hostname);
  const apiSegment = isVercel ? '/backend' : '/api';

  // Strategy 1: Derive base from the current script URL (works when loaded via <script src>).
  try {
    const script = document.currentScript;
    if (script && script.src) {
      const url = new URL(script.src, window.location.origin);
      const marker = '/js/api-config.js';
      const idx = url.pathname.lastIndexOf(marker);
      if (idx >= 0) {
        // Strip /js/api-config.js to get the folder serving public assets.
        // e.g. /admission-office/public/js/api-config.js → /admission-office/public
        let base = url.pathname.slice(0, idx);
        window.ADMISSION_API_BASE = window.location.origin + base + apiSegment;
        return;
      }
    }
  } catch (_) {}

  // Strategy 2: Derive from the current page URL.
  // If the page is under /public/, use everything up to (and including) /public as the base.
  const path = window.location.pathname || '/';
  const publicIdx = path.indexOf('/public/');
  if (publicIdx >= 0) {
    window.ADMISSION_API_BASE = window.location.origin + path.slice(0, publicIdx + '/public'.length) + apiSegment;
    return;
  }

  // Strategy 3: Assume the origin is the app root (virtual host / production).
  window.ADMISSION_API_BASE = window.location.origin + apiSegment;
})();
