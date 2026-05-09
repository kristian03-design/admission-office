/**
 * Admission Office â€“ frontend API helper (no modules)
 * Load api-config.js before this. Use: AdmissionAPI.getPrograms(), AdmissionAPI.submitPublic(), etc.
 */
(function () {
  function normalizeApiBase(base) {
    if (base) return base.replace(/\/+$/, '');
    
    const loc = window.location;
    const isVercel = /\.vercel\.app$/i.test(loc.hostname);
    const segment = isVercel ? '/backend' : '/api';
    
    let path = loc.pathname.replace(/\/+$/, '');
    const pages = [
      '/dashboard', '/apply', '/about', '/news-events', '/news-event-details', 
      '/course-details', '/welcome', '/admin/dashboard', '/admin/login', '/admin'
    ];
    
    let rootPath = path;
    const sortedPages = [...pages].sort((a, b) => b.length - a.length);
    sortedPages.forEach(p => {
      if (rootPath.endsWith(p)) {
        rootPath = rootPath.slice(0, -p.length);
      }
    });

    rootPath = rootPath.replace(/\/public$/, '').replace(/\/+$/, '');
    return loc.origin + rootPath + segment;
  }

  const API_BASE = normalizeApiBase(window.ADMISSION_API_BASE);

  function getToken() {
    return sessionStorage.getItem('_at');
  }

  function setToken(access, refresh) {
    sessionStorage.setItem('_at', access || '');
    sessionStorage.setItem('_rt', refresh || '');
  }

  function clearToken() {
    sessionStorage.removeItem('_at');
    sessionStorage.removeItem('_rt');
  }

  async function request(endpoint, options = {}) {
    const url = endpoint.startsWith('http') ? endpoint : API_BASE + endpoint;
    const headers = {
      'Accept': 'application/json',
      ...options.headers,
    };
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken;

    if (options.body && !(options.body instanceof FormData)) {
      headers['Content-Type'] = 'application/json';
    }
    const token = getToken();
    if (token) headers['Authorization'] = 'Bearer ' + token;

    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), 10000); // 10s timeout

    let res;
    try {
      res = await fetch(url, { ...options, headers, signal: controller.signal });
    } catch (e) {
      if (e.name === 'AbortError') throw new Error('Request timed out');
      throw e;
    } finally {
      clearTimeout(id);
    }

    const rawText = await res.text();
    let data = {};
    try {
      data = JSON.parse(rawText.replace(/^\s*\uFEFF/, '')); // strip BOM
    } catch (_) {
      data = {};
    }

    if (!res.ok) {
      const err = new Error(data.message || `Request failed (${res.status})`);
      err.status = res.status;
      err.data = data;
      err.url = url;
      throw err;
    }
    return data;
  }

  async function getJsonProgramsFallback() {
    const res = await fetch('/data/programs.json', {
      headers: { 'Accept': 'application/json' },
      cache: 'no-store',
    });
    if (!res.ok) throw new Error(`Program JSON failed (${res.status})`);
    const data = await res.json();
    const list = Array.isArray(data) ? data : (data.data || data.programs || []);
    return Array.isArray(list) ? list : [];
  }

  window.AdmissionAPI = {
    getBase: () => API_BASE,
    getToken,
    setToken,
    clearToken,
    request,

    /** GET /api/programs â€“ returns array of { id, code, name, department, ... } */
    async getPrograms(options = {}) {
      const allowFallback = options.allowFallback !== false;
      try {
        const data = await request('/programs');
        const list = data.data ?? data.programs ?? (Array.isArray(data) ? data : null);
        if (Array.isArray(list)) {
          return list;
        }
        throw new Error('Unexpected data format');
      } catch (error) {
        if (!allowFallback) {
          throw error;
        }
        return getJsonProgramsFallback();
      }
    },

    /** PATCH /api/programs/:id/slots-left */
    async updateProgramSlotsLeft(id, slotsLeft) {
      let data;
      try {
        data = await request('/programs/' + id + '/slots-left', {
          method: 'PATCH',
          body: JSON.stringify({ slots_left: slotsLeft }),
        });
      } catch (error) {
        if (error && (error.status === 404 || error.status === 405)) {
          data = await request('/programs/' + id + '/slots-left', {
            method: 'POST',
            body: JSON.stringify({ slots_left: slotsLeft }),
          });
        } else {
          throw error;
        }
      }
      return data.data || {};
    },

    /** PATCH /api/programs/:id/status */
    async updateProgramStatus(id, isActive) {
      let data;
      try {
        data = await request('/programs/' + id + '/status', {
          method: 'PATCH',
          body: JSON.stringify({ is_active: !!isActive }),
        });
      } catch (error) {
        if (error && (error.status === 404 || error.status === 405)) {
          data = await request('/programs/' + id + '/status', {
            method: 'POST',
            body: JSON.stringify({ is_active: !!isActive }),
          });
        } else {
          throw error;
        }
      }
      return data.data || {};
    },

    /** GET /api/settings â€“ public settings */
    async getPublicSettings() {
      const data = await request('/settings');
      return data.data || {};
    },

    /** POST /api/applications/submit-public â€“ full form submit (no auth) */
    async submitPublic(payload) {
      const data = await request('/applications/submit-public', {
        method: 'POST',
        body: JSON.stringify(payload),
      });
      return data.data;
    },

    /** Recursively find first string value for given keys in obj (any nesting) */
    _findToken(obj, keys) {
      if (obj === null || obj === undefined) return '';
      if (typeof obj === 'string' && obj.length > 20) return obj;
      if (typeof obj === 'object') {
        for (const k of keys) {
          if (Object.prototype.hasOwnProperty.call(obj, k) && typeof obj[k] === 'string' && obj[k].length > 0)
            return obj[k];
        }
        for (const v of Object.values(obj)) {
          const found = this._findToken(v, keys);
          if (found) return found;
        }
      }
      return '';
    },

    /** POST /api/auth/login â€“ returns { user, access_token?, refresh_token?, _tokenReceived } */
    async login(email, password) {
      const url = (API_BASE.replace(/\/$/, '') + '/auth/login');
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });
      const rawText = await res.text();
      const isHtml = /^\s*</.test(rawText.trim());
      let data = {};
      if (!isHtml) {
        try {
          data = JSON.parse(rawText.replace(/^\s*\uFEFF/, ''));
        } catch (_) {
          data = {};
        }
      }
      if (!res.ok) {
        const err = new Error(data.message || 'Request failed');
        err.status = res.status;
        err.data = data;
        throw err;
      }
      let access = this._findToken(data, ['access_token', 'accessToken', 'token']);
      let refresh = this._findToken(data, ['refresh_token', 'refreshToken']);
      if (!access && rawText) {
        const accessMatch = rawText.match(/"access_token"\s*:\s*"((?:[^"\\]|\\.)*)"/) || rawText.match(/'access_token'\s*:\s*'([^']*)'/);
        const refreshMatch = rawText.match(/"refresh_token"\s*:\s*"((?:[^"\\]|\\.)*)"/) || rawText.match(/'refresh_token'\s*:\s*'([^']*)'/);
        if (accessMatch) access = accessMatch[1].replace(/\\"/g, '"');
        if (refreshMatch) refresh = refreshMatch[1].replace(/\\"/g, '"');
      }
      if (access) {
        try {
          setToken(access, refresh);
        } catch (e) {
          console.warn('setToken failed', e);
        }
      }
      const d = data.data || data || {};
      d._tokenReceived = !!access;
      d._responseSnippet = isHtml ? rawText.trim().slice(0, 120) : (access ? '' : rawText.trim().slice(0, 200));
      return d;
    },

    /** POST /api/applications/:id/documents â€“ upload file (requires auth) */
    async uploadDocument(applicationId, documentType, file, uploadToken) {
      const form = new FormData();
      form.append('document_type', documentType);
      form.append('file', file);
      if (uploadToken) form.append('upload_token', uploadToken);
      const data = await request('/applications/' + applicationId + '/documents', {
        method: 'POST',
        body: form,
      });
      return data.data;
    },

    /** GET /api/admin/dashboard (admin/staff only) */
    async getDashboard() {
      const data = await request('/admin/dashboard');
      return data.data;
    },

    /** GET /api/auth/me â€“ current user (requires auth) */
    async getMe() {
      const data = await request('/auth/me');
      return data.data || data;
    },

    /** POST /api/auth/password - update current admin password */
    async changePassword(payload) {
      return request('/auth/password', {
        method: 'POST',
        body: JSON.stringify(payload),
      });
    },

    /** GET /api/applications (with optional ?status= & page= & per_page=) */
    async getApplications(params = {}) {
      const qs = new URLSearchParams(params).toString();
      const data = await request('/applications' + (qs ? '?' + qs : ''));
      const payload = data.data || data;
      return Array.isArray(payload) ? payload : (payload && Array.isArray(payload.data) ? payload.data : []);
    },

    /** GET /api/applications/:id â€“ full application + applicant details */
    async getApplication(id) {
      const data = await request('/applications/' + id);
      return data.data;
    },

    /** PATCH /api/applications/:id/status */
    async updateApplicationStatus(id, status, notes) {
      const data = await request('/applications/' + id + '/status', {
        method: 'PATCH',
        body: JSON.stringify({ status, notes: notes || '' }),
      });
      return data.data;
    },
    
    /** DELETE /api/applications/:id */
    async deleteApplication(id) {
      return request('/applications/' + id, {
        method: 'DELETE',
      });
    },

    /** GET /api/admin/settings â€“ returns key-value settings object */
    async getSettings() {
      const data = await request('/admin/settings');
      return data.data || {};
    },

    /** PUT /api/admin/settings â€“ saves key-value settings */
    async saveSettings(payload) {
      let data;
      try {
        data = await request('/admin/settings', {
          method: 'PUT',
          body: JSON.stringify(payload),
        });
      } catch (error) {
        if (error && (error.status === 404 || error.status === 405)) {
          data = await request('/admin/settings', {
            method: 'POST',
            body: JSON.stringify(payload),
          });
        } else {
          throw error;
        }
      }
      return data.data || {};
    },

    /** GET /api/admin/dashboard â€“ full analytics (requires auth) */
    async getDashboardStats() {
      const data = await request('/admin/dashboard');
      return data.data || {};
    },

    /** POST /api/admin/clear-cache */
    async clearPublicCache() {
      try {
        return await request('/admin/clear-cache', { method: 'POST' });
      } catch (error) {
        if (error && error.status === 404) {
          const loc = window.location;
          const rootPath = loc.pathname.split('/admin')[0].replace(/\/+$/, '');
          return request(loc.origin + rootPath + '/admin/clear-cache', { method: 'POST' });
        }
        throw error;
      }
    },
  };
})();

