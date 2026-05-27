// Kominhoo Auth — Sanctum Bearer token via Laravel API
const API_BASE = 'http://localhost/kominhoo/backend/public/api/v1';
const AUTH_TOKEN_KEY = 'kominhoo_token';
const AUTH_USER_KEY  = 'kominhoo_user';

// ── Storage helpers ───────────────────────────────────────────────────────────

function authGetToken() {
  return localStorage.getItem(AUTH_TOKEN_KEY) || sessionStorage.getItem(AUTH_TOKEN_KEY);
}

function authGetCurrentUser() {
  try {
    const raw = localStorage.getItem(AUTH_USER_KEY) || sessionStorage.getItem(AUTH_USER_KEY);
    return raw ? JSON.parse(raw) : null;
  } catch { return null; }
}

function authStoreSession(user, token, remember) {
  const store = remember ? localStorage : sessionStorage;
  store.setItem(AUTH_TOKEN_KEY, token);
  store.setItem(AUTH_USER_KEY, JSON.stringify(user));
}

function authSignOut() {
  const token = authGetToken();
  if (token) {
    // Fire-and-forget — invalidate token on server
    fetch(`${API_BASE}/auth/logout`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
    }).catch(() => {});
  }
  [localStorage, sessionStorage].forEach(s => {
    s.removeItem(AUTH_TOKEN_KEY);
    s.removeItem(AUTH_USER_KEY);
    // Legacy key from localStorage-only era
    s.removeItem('kominhoo_session');
    s.removeItem('kominhoo_users');
  });
  localStorage.removeItem('kominhoo_member_id');
}

// ── API fetch wrapper ─────────────────────────────────────────────────────────

async function apiCall(method, endpoint, body = null, token = null) {
  const headers = { 'Content-Type': 'application/json', Accept: 'application/json' };
  if (token) headers.Authorization = `Bearer ${token}`;

  const res = await fetch(`${API_BASE}/${endpoint}`, {
    method,
    headers,
    body: body ? JSON.stringify(body) : undefined,
  });

  const json = await res.json().catch(() => ({}));

  if (!res.ok) {
    const err = new Error(json.message || 'Request failed');
    err.errors  = json.errors  || {};
    err.status  = res.status;
    throw err;
  }
  return json;
}

// ── Auth calls ────────────────────────────────────────────────────────────────

async function authRegister({ name, email, password, password_confirmation, phone, skin_type }) {
  return apiCall('POST', 'auth/register', { name, email, password, password_confirmation, phone, skin_type });
}

async function authLogin({ email, password }) {
  return apiCall('POST', 'auth/login', { email, password });
}

async function authFetchMe() {
  const token = authGetToken();
  if (!token) return null;
  try {
    const res = await apiCall('GET', 'auth/me', null, token);
    return res.data;
  } catch { return null; }
}

// ── Display helpers ───────────────────────────────────────────────────────────

function authFirstName(user) {
  if (!user) return 'Member';
  return (user.name || '').split(' ')[0] || 'Member';
}

function authMemberId(user) {
  if (!user) return '';
  const year = user.created_at ? new Date(user.created_at).getFullYear() : new Date().getFullYear();
  return `KMH-${year}-${String(user.id).padStart(6, '0')}`;
}

// ── Nav update ────────────────────────────────────────────────────────────────

function authUpdateNav() {
  const user = authGetCurrentUser();
  const container = document.getElementById('nav-auth-btns');
  if (!container) return;
  if (user) {
    container.innerHTML = `
      <a href="dashboard.html" class="btn btn-outline btn-sm">Hi, ${authFirstName(user)}</a>
      <button class="btn btn-primary btn-sm" onclick="authSignOut();window.location.href='login.html'">Sign Out</button>`;
  }
}

// ── First-line error helper (used in login/signup pages) ─────────────────────

function authFormatErrors(err) {
  // Laravel 422 errors object: { field: ['msg1'] }
  if (err.errors && Object.keys(err.errors).length) {
    return Object.values(err.errors).flat().join(' ');
  }
  return err.message || 'Something went wrong. Please try again.';
}
