import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

// Create axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('admin_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('admin_token');
      localStorage.removeItem('adminLoggedIn');
      window.location.href = '/admin/login';
    }
    return Promise.reject(error);
  }
);

// API endpoints
export const apiEndpoints = {
  // Student endpoints
  createDemande: (data: any) => api.post('/demandes', data),
  createReclamation: (data: FormData) => api.post('/reclamations', data, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  }),
  suivreDemandes: (data: any) => api.post('/suivi-demandes', data),
  validateStudent: (data: { email: string; apogee: string; cin: string }) =>
    api.post('/validate-student', data),

  // Get professors list
  getProfesseurs: (filiereId?: number) => api.get('/professeurs', { params: filiereId ? { filiere_id: filiereId } : {} }),

  // Admin authentication
  adminLogin: (credentials: { identifiant: string; password: string }) =>
    api.post('/admin/login', credentials),
  adminLogout: () => api.post('/admin/logout'),

  // Admin dashboard
  getDashboard: () => api.get('/admin/dashboard'),

  // Historique page - finalized requests (accepted + refused)
  getHistorique: (params?: any) => api.get('/admin/historique', { params }),
  exportHistoriquePDF: (params?: any) => api.get('/admin/historique/export-pdf', { params, responseType: 'blob' }),
  reverserDemande: (id: string) => api.put(`/admin/historique/${id}/reverser`),

  // Demande page - pending requests only
  getDemandesAttente: (params?: any) => api.get('/admin/demandes-attente', { params }),

  // Legacy routes (keep for backward compatibility)
  getDemandes: (params?: any) => api.get('/admin/demandes', { params }),
  getDemandeDetails: (id: string) => api.get(`/admin/demandes/${id}`),
  getDemandeHistory: (id: string) => api.get(`/admin/demandes/${id}/history`),
  previewPDF: (id: string) => api.get(`/admin/demandes/${id}/preview`),
  validerDemande: (id: string) => api.put(`/admin/demandes/${id}/valider`),
  refuserDemande: (id: string, data: { raison: string }) =>
    api.put(`/admin/demandes/${id}/refuser`, data),

  // Admin reclamations
  getReclamations: (params?: any) => api.get('/admin/reclamations', { params }),
  repondreReclamation: (id: string, data: FormData) =>
    api.post(`/admin/reclamations/${id}/repondre`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    }),
};

export default api;
