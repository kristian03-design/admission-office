/**
 * API configuration for Admission Office frontend
 * Auto-detects API base for local/XAMPP/Laravel deployments.
 */
(function () {
  if (typeof window.ADMISSION_API_BASE !== 'undefined' && window.ADMISSION_API_BASE) return;

  // Prefer deriving base from where this script is served (most reliable).
  let basePrefix = '';
  try {
    const current = document.currentScript && document.currentScript.src
      ? new URL(document.currentScript.src, window.location.origin)
      : null;
    if (current) {
      const marker = '/js/api-config.js';
      const idx = current.pathname.lastIndexOf(marker);
      if (idx >= 0) {
        basePrefix = current.pathname.slice(0, idx);
      }
    }
  } catch (_) {}

  // If app is served from /public, API is still rooted at app base.
  if (basePrefix.endsWith('/public')) {
    basePrefix = basePrefix.slice(0, -'/public'.length);
  }

  const isVercel = /\.vercel\.app$/i.test(window.location.hostname);

  if (basePrefix) {
    window.ADMISSION_API_BASE = basePrefix + (isVercel ? '/api/index.php/api' : '/api');
    return;
  }

  // Fallbacks for unknown setups.
  const path = window.location.pathname || '/';
  const publicIdx = path.indexOf('/public/');
  if (publicIdx > 0) {
    window.ADMISSION_API_BASE = path.slice(0, publicIdx) + (isVercel ? '/api/index.php/api' : '/api');
  } else {
    window.ADMISSION_API_BASE = window.location.origin + (isVercel ? '/api/index.php/api' : '/api');
  }
})();
