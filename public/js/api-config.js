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

  // Keep /public if it exists, because Laravel needs it to route through index.php
  // when accessed directly without a virtual host (e.g. via XAMPP).

  const isVercel = /\.vercel\.app$/i.test(window.location.hostname);
  const apiSegment = isVercel ? '/backend' : '/api';

  if (basePrefix) {
    window.ADMISSION_API_BASE = basePrefix + apiSegment;
    return;
  }

  // Fallbacks for unknown setups.
  const path = window.location.pathname || '/';
  const publicIdx = path.indexOf('/public/');
  if (publicIdx > 0) {
    window.ADMISSION_API_BASE = path.slice(0, publicIdx) + apiSegment;
  } else {
    window.ADMISSION_API_BASE = window.location.origin + apiSegment;
  }
})();
