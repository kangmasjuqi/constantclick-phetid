// import 'bootstrap'; // Placeholder for bootstrap CSS/JS if you use it, often empty
import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; // IMPORTANT for Sanctum SPA authentication

// This block automatically adds CSRF token for web routes (handled by Breeze)
// If you're using Sanctum API tokens purely, this might be less critical for /api routes
// but still good for general compatibility.
// window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')
//                                                         ? document.querySelector('meta[name="csrf-token"]').content
//                                                         : '';