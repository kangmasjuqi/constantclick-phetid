// resources/js/app.js
import './bootstrap'; // Ensures Axios is configured

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router'; // We'll create this next
import App from './App.vue'; // Your root Vue component
import { useAuthStore } from './stores/auth'; // We'll create this later

const app = createApp(App); // Create Vue app instance
const pinia = createPinia(); // Create Pinia instance

app.use(pinia); // Integrate Pinia with Vue app
app.use(router); // Integrate Vue Router

// Initialize auth store before mounting the app
// This fetches user data if a token exists from a previous session
const authStore = useAuthStore();
authStore.initialize();

app.mount('#app'); // Mount the Vue app to the #app element in your Blade file