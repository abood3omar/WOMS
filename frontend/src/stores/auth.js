import { defineStore } from 'pinia';
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.withCredentials = true; 

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null, 
    permissions: JSON.parse(localStorage.getItem('permissions')) || [],
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(credentials) {
      try {
    
        await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie');

        const response = await axios.post('/login', credentials);

        this.token = response.data.access_token;
        this.user = response.data.user;
        this.permissions = response.data.user.permissions;

        localStorage.setItem('token', this.token);
        localStorage.setItem('permissions', JSON.stringify(this.permissions));
        
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

        return true;
      } catch (error) {
        console.error('Login Failed:', error);
        throw error;
      }
    },

    logout() {
      this.token = null;
      this.user = null;
      this.permissions = [];
      localStorage.removeItem('token');
      localStorage.removeItem('permissions');
      
      axios.post('/logout').catch(() => {});
      
      window.location.reload();
    },

    can(permissionKey) {
      return this.permissions.includes(permissionKey);
    }
  },
});