/**
 * API configuration for Admission Office frontend.
 * Detects the correct API base URL for local XAMPP and production deployments.
 */
(function () {
  if (typeof window.ADMISSION_API_BASE !== 'undefined' && window.ADMISSION_API_BASE) return;

  const loc = window.location;
  const isVercel = /\.vercel\.app$/i.test(loc.hostname);
  const apiSegment = isVercel ? '/backend' : '/api';

  // Strategy 1: Derive base from current page path
  // We want to find the "app root" by stripping known page paths and segments
  let path = loc.pathname.replace(/\/+$/, '');
  
  // List of possible route segments to strip to find the actual app root folder
  const pages = [
    '/dashboard', '/apply', '/about', '/news-events', '/news-event-details', 
    '/course-details', '/welcome', '/admin/dashboard', '/admin/login', '/admin'
  ];
  
  let rootPath = path;
  
  // Sort pages by length descending to strip longest segments first
  const sortedPages = [...pages].sort((a, b) => b.length - a.length);
  
  sortedPages.forEach(p => {
    if (rootPath.endsWith(p)) {
      rootPath = rootPath.slice(0, -p.length);
    }
  });

  // Remove /public if present at the end of rootPath (common in XAMPP)
  rootPath = rootPath.replace(/\/public$/, '');
  
  // Final clean up of trailing slashes before appending api segment
  rootPath = rootPath.replace(/\/+$/, '');

  window.ADMISSION_API_BASE = loc.origin + rootPath + apiSegment;
  console.log('[API Config] Base URL:', window.ADMISSION_API_BASE);
})();
