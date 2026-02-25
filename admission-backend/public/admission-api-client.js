/**
 * ============================================================
 * admission-api-client.js
 * Drop this into your frontend /src directory
 * Handles JWT tokens, refresh logic, and all API calls
 * ============================================================
 */

const API_BASE = 'http://localhost:8000/api'; // Change for production

// ── Token management (in-memory + sessionStorage fallback) ──
// NOTE: Never store JWTs in localStorage (XSS risk).
// Use sessionStorage or httpOnly cookies for production.
const TokenStore = {
  _access: null,
  _refresh: null,

  setAccess(token)  { this._access  = token; sessionStorage.setItem('_at', token); },
  setRefresh(token) { this._refresh = token; sessionStorage.setItem('_rt', token); },
  getAccess()       { return this._access  || sessionStorage.getItem('_at'); },
  getRefresh()      { return this._refresh || sessionStorage.getItem('_rt'); },
  clear()           {
    this._access = this._refresh = null;
    sessionStorage.removeItem('_at');
    sessionStorage.removeItem('_rt');
  }
};

// ── Core fetch wrapper ────────────────────────────────────────
let isRefreshing = false;
let refreshQueue = [];

async function apiRequest(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`;

  const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    ...options.headers,
  };

  const token = TokenStore.getAccess();
  if (token) headers['Authorization'] = `Bearer ${token}`;

  // Don't set Content-Type for FormData (file uploads)
  if (options.body instanceof FormData) {
    delete headers['Content-Type'];
  }

  const response = await fetch(url, { ...options, headers });

  // Handle 401 – try refresh
  if (response.status === 401 && !options._retry) {
    if (!isRefreshing) {
      isRefreshing = true;
      try {
        await Auth.refresh();
        isRefreshing = false;
        refreshQueue.forEach(cb => cb());
        refreshQueue = [];
        // Retry original request
        return apiRequest(endpoint, { ...options, _retry: true });
      } catch {
        isRefreshing = false;
        TokenStore.clear();
        window.dispatchEvent(new Event('auth:logout'));
        throw new Error('Session expired. Please log in again.');
      }
    }

    // Queue this request while refresh is in progress
    return new Promise((resolve, reject) => {
      refreshQueue.push(() => {
        apiRequest(endpoint, { ...options, _retry: true }).then(resolve).catch(reject);
      });
    });
  }

  const data = await response.json();

  if (!data.success) {
    const err = new Error(data.message || 'Request failed');
    err.status = response.status;
    err.data   = data.data;
    throw err;
  }

  return data;
}

// ── Auth API ──────────────────────────────────────────────────
export const Auth = {
  async register(payload) {
    const res = await apiRequest('/auth/register', {
      method: 'POST',
      body: JSON.stringify(payload),
    });
    TokenStore.setAccess(res.data.access_token);
    TokenStore.setRefresh(res.data.refresh_token);
    return res.data;
  },

  async login(email, password) {
    const res = await apiRequest('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password }),
    });
    TokenStore.setAccess(res.data.access_token);
    TokenStore.setRefresh(res.data.refresh_token);
    return res.data;
  },

  async logout() {
    try {
      await apiRequest('/auth/logout', {
        method: 'POST',
        body: JSON.stringify({ refresh_token: TokenStore.getRefresh() }),
      });
    } finally {
      TokenStore.clear();
    }
  },

  async refresh() {
    const refreshToken = TokenStore.getRefresh();
    if (!refreshToken) throw new Error('No refresh token');
    const res = await apiRequest('/auth/refresh', {
      method: 'POST',
      body: JSON.stringify({ refresh_token: refreshToken }),
    });
    TokenStore.setAccess(res.data.access_token);
    TokenStore.setRefresh(res.data.refresh_token);
    return res.data;
  },

  async me() {
    const res = await apiRequest('/auth/me');
    return res.data;
  },

  isLoggedIn() {
    return !!TokenStore.getAccess();
  },
};

// ── Applicant Profile API ─────────────────────────────────────
export const Profile = {
  async get() {
    const res = await apiRequest('/applicant/profile');
    return res.data;
  },
  async update(profileData) {
    const res = await apiRequest('/applicant/profile', {
      method: 'PUT',
      body: JSON.stringify(profileData),
    });
    return res.data;
  },
};

// ── Programs API ──────────────────────────────────────────────
export const Programs = {
  async list() {
    const res = await apiRequest('/programs');
    return res.data;
  },
};

// ── Applications API ──────────────────────────────────────────
export const Applications = {
  async list(params = {}) {
    const qs  = new URLSearchParams(params).toString();
    const res = await apiRequest(`/applications${qs ? '?' + qs : ''}`);
    return res.data;
  },

  async get(id) {
    const res = await apiRequest(`/applications/${id}`);
    return res.data;
  },

  async submit(payload) {
    const res = await apiRequest('/applications', {
      method: 'POST',
      body: JSON.stringify(payload),
    });
    return res.data;
  },

  async updateStatus(id, status, notes = '') {
    const res = await apiRequest(`/applications/${id}/status`, {
      method: 'PATCH',
      body: JSON.stringify({ status, notes }),
    });
    return res.data;
  },

  async delete(id) {
    await apiRequest(`/applications/${id}`, { method: 'DELETE' });
  },
};

// ── Documents API ─────────────────────────────────────────────
export const Documents = {
  async list(applicationId) {
    const res = await apiRequest(`/applications/${applicationId}/documents`);
    return res.data;
  },

  /**
   * Upload a file document
   * @param {number} applicationId
   * @param {string} documentType  e.g. 'tor', 'birth_certificate'
   * @param {File}   file          File object from <input type="file">
   */
  async upload(applicationId, documentType, file) {
    const form = new FormData();
    form.append('document_type', documentType);
    form.append('file', file);

    const res = await apiRequest(`/applications/${applicationId}/documents`, {
      method: 'POST',
      body: form,
    });
    return res.data;
  },

  downloadUrl(docId) {
    return `${API_BASE}/documents/${docId}/download`;
  },

  async verify(docId) {
    const res = await apiRequest(`/documents/${docId}/verify`, { method: 'PATCH' });
    return res.data;
  },

  async delete(docId) {
    await apiRequest(`/documents/${docId}`, { method: 'DELETE' });
  },
};

// ── Admin API ─────────────────────────────────────────────────
export const Admin = {
  async dashboard() {
    const res = await apiRequest('/admin/dashboard');
    return res.data;
  },

  async users(params = {}) {
    const qs  = new URLSearchParams(params).toString();
    const res = await apiRequest(`/admin/users${qs ? '?' + qs : ''}`);
    return res.data;
  },

  async updateUser(userId, payload) {
    const res = await apiRequest(`/admin/users/${userId}`, {
      method: 'PUT',
      body: JSON.stringify(payload),
    });
    return res.data;
  },

  async deleteUser(userId) {
    await apiRequest(`/admin/users/${userId}`, { method: 'DELETE' });
  },

  async programs() {
    const res = await apiRequest('/admin/programs');
    return res.data;
  },

  async createProgram(payload) {
    const res = await apiRequest('/admin/programs', {
      method: 'POST',
      body: JSON.stringify(payload),
    });
    return res.data;
  },

  async updateProgram(id, payload) {
    const res = await apiRequest(`/admin/programs/${id}`, {
      method: 'PUT',
      body: JSON.stringify(payload),
    });
    return res.data;
  },

  async reportsSummary(academicYear) {
    const res = await apiRequest(`/admin/reports/summary?academic_year=${academicYear}`);
    return res.data;
  },
};

// ── Usage examples ────────────────────────────────────────────
/*

// Login:
const { user, access_token } = await Auth.login('user@email.com', 'password');

// Submit application:
const app = await Applications.submit({
  program_id: 1,
  academic_year: '2024-2025',
  semester: '1st',
  application_type: 'new',
});

// Upload document:
const fileInput = document.getElementById('myFile');
const doc = await Documents.upload(app.id, 'tor', fileInput.files[0]);

// Admin dashboard:
const stats = await Admin.dashboard();

*/
