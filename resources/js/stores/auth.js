// resources/js/stores/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';
import router from '../router'; // Import the router to redirect

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null, // Stores the authenticated user object
        token: localStorage.getItem('authToken') || null, // Get token from localStorage
        isAuthenticated: !!localStorage.getItem('authToken'), // Check if token exists
        authErrors: [], // To store validation/authentication errors
        loading: false, // Loading state for API requests
    }),

    getters: {
        loggedIn: (state) => state.isAuthenticated,
        currentUser: (state) => state.user,
        errors: (state) => state.authErrors,
        isLoading: (state) => state.loading,
    },

    actions: {
        async register(userData) {
            this.loading = true;
            this.authErrors = [];
            try {
                const response = await axios.post('/register', userData);
                this.user = response.data.user; // Assuming register returns user data
                this.token = response.data.token; // From your custom LoginResponse
                this.isAuthenticated = true;
                localStorage.setItem('authToken', this.token);
                router.push('/dashboard');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.authErrors = Object.values(error.response.data.errors).flat();
                } else if (error.response) {
                    this.authErrors = [error.response.data.message || 'Registration failed.'];
                } else {
                    this.authErrors = ['Network error or server unreachable.'];
                }
                this.isAuthenticated = false;
                this.user = null;
                this.token = null;
                localStorage.removeItem('authToken');
            } finally {
                this.loading = false;
            }
        },

        async login(credentials) {
            this.loading = true;
            this.authErrors = [];
            try {
                const response = await axios.post('/login', credentials);
                this.token = response.data.token;
                this.isAuthenticated = true;
                localStorage.setItem('authToken', this.token);
                await this.fetchUser();
                router.push('/dashboard');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.authErrors = Object.values(error.response.data.errors).flat();
                } else if (error.response && error.response.data.message) {
                    this.authErrors = [error.response.data.message];
                } else {
                    this.authErrors = ['Login failed. Please check your credentials or network.'];
                }
                this.isAuthenticated = false;
                this.user = null;
                this.token = null;
                localStorage.removeItem('authToken');
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            this.loading = true;
            try {
                await axios.post('/logout', {}, {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.user = null;
                this.token = null;
                this.isAuthenticated = false;
                this.authErrors = [];
                localStorage.removeItem('authToken');
                this.loading = false;
                router.push('/login');
            }
        },

        async fetchUser() {
            if (!this.token) {
                this.isAuthenticated = false;
                this.user = null;
                return;
            }
            this.loading = true;
            try {
                const response = await axios.get('/api/user', {
                    headers: { Authorization: `Bearer ${this.token}` }
                });
                this.user = response.data;
                this.isAuthenticated = true;
            } catch (error) {
                console.error('Failed to fetch user:', error);
                this.logout(); // Force logout if token is invalid
            } finally {
                this.loading = false;
            }
        },

        initialize() {
            if (this.token && !this.user) {
                this.fetchUser();
            }
        },
    },
});